<?php
namespace hwcvod\obs\s3\signature;

use hwcvod\obs\log\S3Log;
use hwcvod\obs\common\ObsException;
use hwcvod\obs\common\SchemaFormatter;
use GuzzleHttp\Psr7\Stream;
use hwcvod\obs\common\Model;

abstract class AbstractSignature implements SignatureInterface
{
	
	protected $ak;
	
	protected $sk;
	
	protected $pathStyle;
	
	protected $endpoint;
	
	protected $methodName;
	
	protected $securityToken;
	
	public static function urlencodeWithSafe($val, $safe=',:?&%'){
		if(($len = strlen($val)) === 0){
			return '';
		}
		$buffer = [];
		for ($index=0;$index<$len;$index++){
			$str = $val[$index];
			
			$buffer[] = !($pos = strpos($safe, $str)) && $pos !== 0 ? urlencode($str) : $str;
		}
		return implode('', $buffer);
	}
	
	protected function __construct($ak, $sk, $pathStyle, $endpoint, $methodName, $securityToken=false)
	{
		$this -> ak = $ak;
		$this -> sk = $sk;
		$this -> pathStyle = $pathStyle;
		$this -> endpoint = $endpoint;
		$this -> methodName = $methodName;
		$this -> securityToken = $securityToken;
	}
	
	
	protected function transXmlByType($key, &$value, &$subParams)
	{
		$xml = [];
		$treatAsString = false;
		if(isset($value['type'])){
			$type = $value['type'];
			if($type === 'array'){
				$name = isset($value['sentAs']) ? $value['sentAs'] : $key;
				$subXml = [];
				foreach($subParams as $item){
					$temp = $this->transXmlByType($key, $value['items'], $item);
					if($temp !== ''){
						$subXml[] = $temp;
					}
				}
				if(!empty($subXml)){
					if(!isset($value['data']) || !isset($value['data']['xmlFlattened']) || !$value['data']['xmlFlattened']){
						$xml[] = '<' . $name . '>';
						$xml[] = implode('', $subXml);
						$xml[] = '</' . $name . '>';
					}else{
						$xml[] = implode('', $subXml);
					}
				}
			}else if($type === 'object'){
				$name = isset($value['sentAs']) ? $value['sentAs'] : (isset($value['name']) ? $value['name'] : $key);
				$properties = $value['properties'];
				$subXml = [];
				$attr = [];
				foreach ($properties as $pkey => $pvalue){
					if(isset($pvalue['required']) && $pvalue['required'] && !isset($subParams[$pkey])){
						$obsException= new ObsException('param:' .$pkey. ' is required');
						$obsException-> setExceptionType('client');
						throw $obsException;
					}
					if(isset($subParams[$pkey])){
						if(isset($pvalue['data']) && isset($pvalue['data']['xmlAttribute']) && $pvalue['data']['xmlAttribute']){
							$attr[$pvalue['sentAs']] = '"' . trim(strval($subParams[$pkey])) . '"';
							if(isset($pvalue['data']['xmlNamespace'])){
								$ns = substr($pvalue['sentAs'], 0, strpos($pvalue['sentAs'], ':'));
								$attr['xmlns:' . $ns] = '"' . $pvalue['data']['xmlNamespace'] . '"';
							}
						}else{
							$subXml[] = $this -> transXmlByType($pkey, $pvalue, $subParams[$pkey]);
						}
					}
				}
				$val = implode('', $subXml);
				if($val !== ''){
					$_name = $name;
					if(!empty($attr)){
						foreach ($attr as $akey => $avalue){
							$_name .= ' ' . $akey . '=' . $avalue;
						}
					}
					$xml[] = '<' . $_name . '>';
					$xml[] = $val;
					$xml[] = '</' . $name . '>';
				}
			}else{
				$treatAsString = true;
			}
		}else{
			$treatAsString = true;
			$type = null;
		}
		
		if($treatAsString){
			if($type === 'boolean'){
				if(!is_bool($subParams) && strval($subParams) !== 'false' && strval($subParams) !== 'true'){
					$obsException= new ObsException('param:' .$key. ' is not a boolean value');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
			}else if($type === 'numeric'){
				if(!is_numeric($subParams)){
					$obsException= new ObsException('param:' .$key. ' is not a numeric value');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
			}else if($type === 'float'){
				if(!is_float($subParams)){
					$obsException= new ObsException('param:' .$key. ' is not a float value');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
			}else if($type === 'int' || $type === 'integer'){
				if(!is_int($subParams)){
					$obsException= new ObsException('param:' .$key. ' is not a int value');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
			}
			
			$name = isset($value['sentAs']) ? $value['sentAs'] : $key;
			if(is_bool($subParams)){
				$val = 	$subParams ? 'true' : 'false';
			}else{
				$val = strval($subParams);
			}
			if(isset($value['format'])){
				$val = SchemaFormatter::format($value['format'], $val);
			}
			if($val !== ''){
				$xml[] = '<' . $name . '>';
				$xml[] = $val;
				$xml[] = '</' . $name . '>';
			}else if(isset($value['canEmpty']) && $value['canEmpty']){
				$xml[] = '<' . $name . '>';
				$xml[] = $val;
				$xml[] = '</' . $name . '>';
			}
		}
		$ret = implode('', $xml);
		
		if(isset($value['wrapper'])){
			$ret = '<'. $value['wrapper'] . '>' . $ret . '</'. $value['wrapper'] . '>';
		}
		
		return $ret;
	}
	
	protected function prepareAuth(array &$requestConfig, array &$params, Model $model,array &$pathArg)
	{
		$method = $requestConfig['httpMethod'];
		$requestUrl = $this->endpoint;
		$headers = [];
		$pathArgs = $pathArg;
		$dnsParam = null;
		$uriParam = null;
		$body = [];
		$xml = [];
		
		if(isset($requestConfig['specialParam'])){
			$pathArgs[$requestConfig['specialParam']] = '';
		}
		
		$result = ['body' => null];
		$url = parse_url($requestUrl);
		$host = $url['host'];
		
		$fileFlag = false;
		
		if(isset($requestConfig['requestParameters'])){
			$paramsMetadata = $requestConfig['requestParameters'];
			foreach ($paramsMetadata as $key => $value){
				if(isset($value['required']) && $value['required'] && !isset($params[$key])){
					$obsException= new ObsException('param:' .$key. ' is required');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
				if(isset($params[$key]) && isset($value['location'])){
					$location = $value['location'];
					$val = $params[$key];
					$type = 'string';
					if($val !== '' && isset($value['type'])){
						$type = $value['type'];
						if($type === 'boolean'){
							if(!is_bool($val) && strval($val) !== 'false' && strval($val) !== 'true'){
								$obsException= new ObsException('param:' .$key. ' is not a boolean value');
								$obsException-> setExceptionType('client');
								throw $obsException;
							}
						}else if($type === 'numeric'){
							if(!is_numeric($val)){
								$obsException= new ObsException('param:' .$key. ' is not a numeric value');
								$obsException-> setExceptionType('client');
								throw $obsException;
							}
						}else if($type === 'float'){
							if(!is_float($val)){
								$obsException= new ObsException('param:' .$key. ' is not a float value');
								$obsException-> setExceptionType('client');
								throw $obsException;
							}
						}else if($type === 'int' || $type === 'integer'){
							if(!is_int($val)){
								$obsException= new ObsException('param:' .$key. ' is not a int value');
								$obsException-> setExceptionType('client');
								throw $obsException;
							}
						}
					}
					
					if($location === 'header'){
						if($type === 'object'){
							if(is_array($val)){
								$sentAs = strtolower($value['sentAs']);
								foreach ($val as $k => $v){
									$k = strtolower($k);
									$name = strpos($k, $sentAs) === 0 ? $k : $sentAs . $k;
									$headers[$name] = self::urlencodeWithSafe($v, ',:/+=?&%');
								}
							}
						}else if($type === 'array'){
							if(is_array($val)){
								$name = isset($value['sentAs']) ? $value['sentAs'] : (isset($value['items']['sentAs']) ? $value['items']['sentAs'] : $key);
								$temp = [];
								foreach ($val as $v){
									if(($v = strval($v)) !== ''){
										$temp[] =  self::urlencodeWithSafe($v, ',:/+=?&%');
									}
								}
								$headers[$name] = $temp;
							}
						}else if($type === 'password'){
							if(($val = strval($val)) !== ''){
								$name = isset($value['sentAs']) ? $value['sentAs'] : $key;
								$pwdName = isset($value['pwdSentAs']) ? $value['pwdSentAs'] : $name . '-MD5';
								$val1 = base64_encode($val);
								$val2 = base64_encode(md5($val, true));
								$headers[$name] = $val1;
								$headers[$pwdName] = $val2;
							}
						}else{
							if(($val = strval($val)) !== ''){
								$name = isset($value['sentAs']) ? $value['sentAs'] : $key;
								if(isset($value['format'])){
									$val = SchemaFormatter::format($value['format'], $val);
								}
								$headers[$name] = self::urlencodeWithSafe($val, ',:/+=?&%');;
							}
						}
					}else if($location === 'uri' && $uriParam === null){
						$uriParam = self::urlencodeWithSafe($val, ',:/+=?&%');
					}else if($location === 'dns' && $dnsParam === null){
						$dnsParam = $val;
					}else if($location === 'query'){
						$name = isset($value['sentAs']) ? $value['sentAs'] : $key;
						if(strval($val) !== ''){
							$pathArgs[self::urlencodeWithSafe($name)] = self::urlencodeWithSafe(strval($val));
						}
					}else if($location === 'xml'){
						$val = $this->transXmlByType($key, $value, $val);
						if($val !== ''){
							$xml[] = $val;
						}
					}else if($location === 'body'){
						
						if(isset($result['body'])){
							$obsException= new ObsException('duplicated body provided');
							$obsException-> setExceptionType('client');
							throw $obsException;
						}
						
						if($type === 'file'){
							if(!file_exists($val)){
								$obsException= new ObsException('file[' .$val. '] does not exist');
								$obsException-> setExceptionType('client');
								throw $obsException;
							}
							$result['body'] = new Stream(fopen($val, 'r'));
							$fileFlag = true;
						}else if($type === 'stream'){
							$result['body'] = $val;
						}else{
							$result['body'] = strval($val);
						}
					}else if($location === 'response'){
						$model[$key] = ['value' => $val, 'type' => $type];
					}
				}
			}
			
			
			if($dnsParam){
				if($this -> pathStyle){
					$requestUrl = $requestUrl . '/' .  $dnsParam;
				}else{
					$defaultPort = strtolower($url['scheme']) === 'https' ? '443' : '80';
					if(!is_int(strpos($url['host'], $dnsParam))){
						$host = $dnsParam. '.' . $host;
					}
					$requestUrl = $url['scheme'] . '://' . $host . ':' . (isset($url['port']) ? $url['port'] : $defaultPort);
				}
			}
			if($uriParam){
				$requestUrl = $requestUrl . '/' . $uriParam;
			}
			
			if(!empty($pathArgs)){
				$requestUrl .= '?';
				$_pathArgs = [];
				foreach ($pathArgs as $key => $value){
					$_pathArgs[] = $value === null || $value === '' ? $key : $key . '=' . $value;
				}
				$requestUrl .= implode('&', $_pathArgs);
			}
		}
		
		if($xml || (isset($requestConfig['data']['xmlAllowEmpty']) && $requestConfig['data']['xmlAllowEmpty'])){
			$body[] = '<';
			$xmlRoot = $requestConfig['data']['xmlRoot']['name'];
			$body[] = $xmlRoot;
			if(isset($requestConfig['data']['xmlRoot']['namespaces'])){
				$body[] = ' xmlns="' . $requestConfig['data']['xmlRoot']['namespaces'] . '"';
			}
			$body[] = '>';
			$body[] = implode('', $xml);
			$body[] = '</';
			$body[] = $xmlRoot;
			$body[] = '>';
			$headers['Content-Type'] = 'application/xml';
			$result['body'] = implode('', $body);
			
			S3Log::commonLog(DEBUG, 'request content ' . $result['body']);
			
			if(isset($requestConfig['data']['contentMd5']) && $requestConfig['data']['contentMd5']){
				$headers['Content-MD5'] = base64_encode(md5($result['body'],true));
			}
		}
        if(isset($params['Content-Md5']) && $params['Content-Md5']){
            $headers['Content-MD5'] = $params['Content-Md5'];
        }
		
		if($fileFlag && ($result['body'] instanceof Stream)){
			if($this->methodName === 'uploadPart' && (isset($model['Offset']) || isset($model['PartSize']))){
				$bodySize = $result['body'] ->getSize();
				if(isset($model['Offset'])){
					$offset = intval($model['Offset']['value']);
					$offset = $offset >= 0 && $offset < $bodySize ? $offset : 0;
				}else{
					$offset = 0;
				}
				
				if(isset($model['PartSize'])){
					$partSize = intval($model['PartSize']['value']);
					$partSize = $partSize > 0 && $partSize  <= ($bodySize - $offset) ? $partSize : $bodySize - $offset;
				}else{
					$partSize = $bodySize - $offset;
				}
				$result['body'] -> rewind();
				$result['body'] -> seek($offset);
				$headers['Content-Length'] = $partSize;
			}else if(isset($headers['Content-Length'])){
				$bodySize = $result['body'] -> getSize();
				if(intval($headers['Content-Length']) > $bodySize){
					$headers['Content-Length'] =  $bodySize;
				}
			}
		}
		
		if($this->securityToken){
		    $headers['x-amz-security-token'] = $this->securityToken;
		}

        if(isset($params['Content-Type']) && $params['Content-Type']){
            $headers['Content-Type'] = $params['Content-Type'];
        }
		
		$result['host'] = $host;
		$result['method'] = $method;
		$result['headers'] = $headers;
		$result['pathArgs'] = $pathArgs;
		$result['dnsParam'] = $dnsParam;
		$result['uriParam'] = $uriParam;
		$result['requestUrl'] = $requestUrl;
		
		return $result;
	}
}