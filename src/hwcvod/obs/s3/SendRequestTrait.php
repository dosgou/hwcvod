<?php
namespace hwcvod\obs\s3;

use hwcvod\obs\log\S3Log;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use hwcvod\obs\common\Model;
use hwcvod\obs\s3\Resource\RequestResource;
use hwcvod\obs\common\ObsException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Stream;
use hwcvod\obs\common\Constants;
use hwcvod\obs\s3\signature\V2Signature;
use hwcvod\obs\s3\signature\V4Signature;

trait SendRequestTrait{

	protected static $resource = RequestResource::RESOURCE_ARRAY;
	
	protected $ak;
	
	protected $sk;
	
	protected $securityToken = false;
	
	protected $endpoint = '';
	
	protected $pathStyle = false;
	
	protected $region = 'region';
	
	protected $signature = 'v2';
	
	protected $sslVerify = false;
	
	protected $maxRetryCount = 3;
	
	protected $timeout = 0;
	
	protected $socketTimeout = 60; 
	
	protected $connectTimeout = 60;
	
	/** @var Client */
	protected $httpClient;
	
	public function createV2SignedUrl(array $args=[]){
		
		if(!isset($args['Method'])){
			$obsException = new ObsException('Method param must be specified, allowed values: GET | PUT | HEAD | POST | DELETE | OPTIONS');
			$obsException-> setExceptionType('client');
			throw $obsException;
		}
		$method = strval($args['Method']);
		$bucketName = isset($args['Bucket'])? strval($args['Bucket']): null;
		$objectKey =  isset($args['Key'])? strval($args['Key']): null;
		$specialParam = isset($args['SpecialParam'])? strval($args['SpecialParam']): null;
		$expires = isset($args['Expires']) && is_numeric($args['Expires']) ? intval($args['Expires']): 300;
		
		$headers = [];
		if(isset($args['Headers']) && is_array($args['Headers']) ){
			foreach ($args['Headers'] as $key => $val){
				$headers[$key] = $val;
			}
		}
		
		if($this->securityToken && !isset($headers['x-amz-security-token'])){
		    $headers['x-amz-security-token'] = $this->securityToken;
		}
		
		$queryParams = [];
		if(isset($args['QueryParams']) && is_array($args['QueryParams']) ){
			foreach ($args['QueryParams'] as $key => $val){
				$queryParams[$key] = $val;
			}
		}
		$v2 = new V2Signature($this->ak, $this->sk, $this->pathStyle, $this->endpoint, $method);
		
		$url = parse_url($this->endpoint);
		$host = $url['host'];
		
		$result = '';
		
		if($bucketName){
			if($this-> pathStyle){
				$result = '/' . $bucketName;
			}else{
				$host = $bucketName . '.' . $host;
			}
		}
		
		if($objectKey){
			$objectKey = $v2 ->urlencodeWithSafe($objectKey, ',:/+=?&%');
			$result .= '/' . $objectKey;
		}
		
		$result .= '?';
		
		if($specialParam){
			$queryParams[$specialParam] = '';
		}
		
		$queryParams['AWSAccessKeyId'] = $this->ak;
		
		
		if(!is_numeric($expires) || $expires < 0){
			$expires = 300;
		}
		$expires = intval($expires) + intval(microtime(true));
		
		$queryParams['Expires'] = strval($expires);
		
		$_queryParams = [];
		
		foreach ($queryParams as $key => $val){
			$key = $v2 -> urlencodeWithSafe($key);
			$val = $v2 -> urlencodeWithSafe($val);
			$_queryParams[$key] = $val;
			$result .= $key;
			if($val){
				$result .= '=' . $val;
			}
			$result .= '&';
		}
		
		$canonicalstring = $v2 ->makeCanonicalstring($method, $headers, $_queryParams, $bucketName, $objectKey, $expires);
		$signature = base64_encode(hash_hmac('sha1', $canonicalstring, $this->sk, true));
		
		$result .= 'Signature=' . $v2->urlencodeWithSafe($signature);
		
		$model = new Model();
		$model['ActualSignedRequestHeaders'] = $headers;
		$model['SignedUrl'] = $url['scheme'] . '://' . $host . ':' . (isset($url['port']) ? $url['port'] : (strtolower($url['scheme']) === 'https' ? '443' : '80')) . $result;
		return $model;
	}
	
	public function createV4SignedUrl(array $args=[]){
		
		if(!isset($args['Method'])){
			$obsException= new ObsException('Method param must be specified, allowed values: GET | PUT | HEAD | POST | DELETE | OPTIONS');
			$obsException-> setExceptionType('client');
			throw $obsException;
		}
		$method = strval($args['Method']);
		$bucketName = isset($args['Bucket'])? strval($args['Bucket']): null;
		$objectKey =  isset($args['Key'])? strval($args['Key']): null;
		$specialParam = isset($args['SpecialParam'])? strval($args['SpecialParam']): null;
		$expires = isset($args['Expires']) && is_numeric($args['Expires']) ? intval($args['Expires']): 300;
		$headers = [];
		if(isset($args['Headers']) && is_array($args['Headers']) ){
			foreach ($args['Headers'] as $key => $val){
				$headers[$key] = $val;
			}
		}
		
		if($this->securityToken && !isset($headers['x-amz-security-token'])){
		    $headers['x-amz-security-token'] = $this->securityToken;
		}
		
		$queryParams = [];
		if(isset($args['QueryParams']) && is_array($args['QueryParams']) ){
			foreach ($args['QueryParams'] as $key => $val){
				$queryParams[$key] = $val;
			}
		}
		
		$v4 = new V4Signature($this->ak, $this->sk, $this->pathStyle, $this->endpoint, $this->region, $method);
		
		$url = parse_url($this->endpoint);
		$host = $url['host'];
		
		$result = '';
		
		if($bucketName){
			if($this-> pathStyle){
				$result = '/' . $bucketName;
			}else{
				$host = $bucketName . '.' . $host;
			}
		}
		
		if($objectKey){
			$objectKey = $v4 -> urlencodeWithSafe($objectKey);
			$result .= '/' . $objectKey;
		}
		
		$result .= '?';
		
		if($specialParam){
			$queryParams[$specialParam] = '';
		}
		
		if(!is_numeric($expires) || $expires < 0){
			$expires = 300;
		}
		
		$expires = strval($expires);
		
		$date = isset($headers['date']) ? $headers['date'] : (isset($headers['Date']) ? $headers['Date'] : null);
		
		$timestamp = $date ? date_create_from_format('D, d M Y H:i:s \G\M\T', $date, new \DateTimeZone ('UTC')) -> getTimestamp()
			:time();
		
		$longDate = gmdate('Ymd\THis\Z', $timestamp);
		$shortDate = substr($longDate, 0, 8);
		
		$headers['host'] = $host;
		
		$signedHeaders = $v4 -> getSignedHeaders($headers);
		
		$queryParams['X-Amz-Algorithm'] = 'AWS4-HMAC-SHA256';
		$queryParams['X-Amz-Credential'] = $v4 -> getCredential($shortDate);
		$queryParams['X-Amz-Date'] = $longDate;
		$queryParams['X-Amz-Expires'] = $expires;
		$queryParams['X-Amz-SignedHeaders'] = $signedHeaders;
		
		$_queryParams = [];
		
		foreach ($queryParams as $key => $val){
			$key = $v4 -> urlencodeWithSafe($key);
			$val = $v4 -> urlencodeWithSafe($val);
			$_queryParams[$key] = $val;
			$result .= $key;
			if($val){
				$result .= '=' . $val;
			}
			$result .= '&';
		}
		
		$canonicalstring = $v4 -> makeCanonicalstring($method, $headers, $_queryParams, $bucketName, $objectKey, $signedHeaders, 'UNSIGNED-PAYLOAD');
		
		$signature = $v4 -> getSignature($canonicalstring, $longDate, $shortDate);
		
		$result .= 'X-Amz-Signature=' . $v4 -> urlencodeWithSafe($signature);
		
		$model = new Model();
		$model['ActualSignedRequestHeaders'] = $headers;
		$model['SignedUrl'] = $url['scheme'] . '://' . $host . ':' . (isset($url['port']) ? $url['port'] : (strtolower($url['scheme']) === 'https' ? '443' : '80')) . $result;
		return $model;
	}
	
	public function createV4PostSignature(array $args=[]){
		
		$bucketName = isset($args['Bucket'])? strval($args['Bucket']): null;
		$objectKey =  isset($args['Key'])? strval($args['Key']): null;
		$expires = isset($args['Expires']) && is_numeric($args['Expires']) ? intval($args['Expires']): 300;
		
		$formParams = [];
		
		if(isset($args['FormParams']) && is_array($args['FormParams'])){
			foreach ($args['FormParams'] as $key => $val){
				$formParams[$key] = $val;
			}
		}
		
		if($this->securityToken && !isset($formParams['x-amz-security-token'])){
		    $formParams['x-amz-security-token'] = $this->securityToken;
		}
		
		$timestamp = time();
		$longDate = gmdate('Ymd\THis\Z', $timestamp);
		$shortDate = substr($longDate, 0, 8);
		
		$credential = sprintf('%s/%s/%s/s3/aws4_request', $this->ak, $shortDate, $this->region);
		
		$expires = gmdate('Y-m-d\TH:i:s\Z', $timestamp + $expires);
		
		$formParams['X-Amz-Algorithm'] = 'AWS4-HMAC-SHA256';
		$formParams['X-Amz-Date'] = $longDate;
		$formParams['X-Amz-Credential'] = $credential;
		
		if($bucketName){
			$formParams['bucket'] = $bucketName;
		}
		
		if($objectKey){
			$formParams['key'] = $objectKey;
		}
		
		$policy = [];
		
		$policy[] = '{"expiration":"';
		$policy[] = $expires;
		$policy[] = '", "conditions":[';
		
		$matchAnyBucket = true;
		$matchAnyKey = true;
		
		$conditionAllowKeys = ['acl', 'bucket', 'key', 'success_action_redirect', 'redirect', 'success_action_status'];
		
		foreach($formParams as $key => $val){
			if($key){
				$key = strtolower(strval($key));
				
				if($key === 'bucket'){
					$matchAnyBucket = false;
				}else if($key === 'key'){
					$matchAnyKey = false;
				}
				
				if(!in_array($key, Constants::ALLOWED_REQUEST_HTTP_HEADER_METADATA_NAMES) && strpos($key, Constants::AMAZON_HEADER_PREFIX) !== 0 && !in_array($key, $conditionAllowKeys)){
					$key = Constants::METADATA_PREFIX . $key;
				}
				
				$policy[] = '{"';
				$policy[] = $key;
				$policy[] = '":"';
				$policy[] = $val !== null ? strval($val) : '';
				$policy[] = '"},';
			}
		}
		
		if($matchAnyBucket){
			$policy[] = '["starts-with", "$bucket", ""],';
		}
		
		if($matchAnyKey){
			$policy[] = '["starts-with", "$key", ""],';
		}
		
		$policy[] = ']}';
		
		$originPolicy = implode('', $policy);
		
		$policy = base64_encode($originPolicy);
		
		$dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . $this -> sk, true);
		$regionKey = hash_hmac('sha256', $this->region, $dateKey, true);
		$serviceKey = hash_hmac('sha256', 's3', $regionKey, true);
		$signingKey = hash_hmac('sha256', 'aws4_request', $serviceKey, true);
		$signature = hash_hmac('sha256', $policy, $signingKey);
		
		$model = new Model();
		$model['OriginPolicy'] = $originPolicy;
		$model['Policy'] = $policy;
		$model['Algorithm'] = $formParams['X-Amz-Algorithm'];
		$model['Credential'] = $formParams['X-Amz-Credential'];
		$model['Date'] = $formParams['X-Amz-Date'];
		$model['Signature'] = $signature;
		return $model;
	}
	
	public function __call($originMethod, $args)
	{
		$method = $originMethod;
		
		$async = false;
		if(strpos($method, 'Async') === (strlen($method) - 5)){
			$method = substr($method, 0, strlen($method) - 5);
			$async = true;
		}
		
		if(isset(self::$resource['aliases'][$method])){
			$method = self::$resource['aliases'][$method];
		}
		
		$method = lcfirst($method);
		
		
		$operation = isset(self::$resource['operations'][$method]) ? 
			self::$resource['operations'][$method] : null;
		
		if(!$operation){
			S3Log::commonLog(WARNING, 'unknow method ' . $originMethod);
			$obsException= new ObsException('unknow method '. $originMethod);
			$obsException-> setExceptionType('client');
			throw $obsException;
		}
		
		$start = microtime(true);
		if(!$async){
			S3Log::commonLog(INFO, 'enter method '. $originMethod. '...');
			$model = new Model();
			$model['method'] = $method;
			$params = empty($args) ? [] : $args[0];
			$pathArg = empty($args) ? [] : $args[1];
			$this->checkMimeType($method, $params);
			$this->doRequest($model, $operation, $params, $pathArg);
			S3Log::commonLog(INFO, 'obsclient cost ' . round(microtime(true) - $start, 3) * 1000 . ' ms to execute '. $originMethod);
			unset($model['method']);
			return $model;
		}else{
			if(empty($args) || !(is_callable($callback = $args[count($args) -1]))){
				S3Log::commonLog(WARNING, 'async method ' . $originMethod . ' must pass a CallbackInterface as param');
				$obsException= new ObsException('async method ' . $originMethod . ' must pass a CallbackInterface as param');
				$obsException-> setExceptionType('client');
				throw $obsException;
			}
			S3Log::commonLog(INFO, 'enter method '. $originMethod. '...');
			$params = count($args) === 1 ? [] : $args[0];
            $pathArg = empty($args) ? [] : $args[1];
			$this->checkMimeType($method, $params);
			$model = new Model();
			$model['method'] = $method;
			return $this->doRequestAsync($model, $operation, $params, $pathArg, $callback, $start, $originMethod);
		}
	}
	
	private function checkMimeType($method, &$params){
		// fix bug that guzzlehttp lib will add the content-type if not set
		if(($method === 'putObject' || $method === 'initiateMultipartUpload' || $method === 'uploadPart') && (!isset($params['ContentType']) || $params['ContentType'] === null)){
			if(isset($params['Key'])){
				$params['ContentType'] = 'binary/octet-stream';
			}
			
			if((!isset($params['ContentType']) || $params['ContentType'] === null) && isset($params['SourceFile'])){
				$params['ContentType'] = Psr7\mimetype_from_filename($params['SourceFile']);
			}
			
			if(!isset($params['ContentType']) || $params['ContentType'] === null){
				$params['ContentType'] = 'binary/octet-stream';
			}
		}
	}
	
	protected function makeRequest($model, &$operation, $params, $pathArg, $endpoint = null){
		if($endpoint === null){
			$endpoint = $this->endpoint;
		}
		$signatureInterface = strcasecmp($this-> signature, 'v4') === 0 ? new V4Signature($this->ak, $this->sk, $this->pathStyle, $endpoint, $this->region, $model['method'], $this->securityToken) : new V2Signature($this->ak, $this->sk, $this->pathStyle, $endpoint, $model['method'], $this->securityToken);
		$authResult = $signatureInterface -> doAuth($operation, $params, $model, $pathArg);
		$httpMethod = $authResult['method'];
		S3Log::commonLog(DEBUG, 'perform '. strtolower($httpMethod) . ' request with url ' . $authResult['requestUrl']);
		//S3Log::commonLog(DEBUG, 'cannonicalRequest:' . $authResult['cannonicalRequest']);
		S3Log::commonLog(DEBUG, 'request headers ' . var_export($authResult['headers'],true));
		$authResult['headers']['User-Agent'] = self::default_user_agent();
		if($model['method'] === 'putObject'){
			$model['ObjectURL'] = ['value' => $authResult['requestUrl']];
		}
		return new Request($httpMethod, $authResult['requestUrl'], $authResult['headers'], $authResult['body']);
	}
	
	
	protected function doRequest($model, &$operation, $params, $pathArg, $endpoint = null)
	{
		$request = $this -> makeRequest($model, $operation, $params, $pathArg, $endpoint);
		$this->sendRequest($model, $operation, $params, $request);
	}
	
	protected function sendRequest($model, &$operation, $params, $request, $requestCount = 1)
	{
		$start = microtime(true);
		$saveAsStream = false;
		if(isset($operation['stream']) && $operation['stream']){
			$saveAsStream = isset($params['SaveAsStream']) ? $params['SaveAsStream'] : false;
			
			if(isset($params['SaveAsFile'])){
				if($saveAsStream){
					$obsException = new ObsException('SaveAsStream cannot be used with SaveAsFile together');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
				$saveAsStream = true;
			}
			if(isset($params['FilePath'])){
				if($saveAsStream){
					$obsException = new ObsException('SaveAsStream cannot be used with FilePath together');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
				$saveAsStream = true;
			}
			
			if(isset($params['SaveAsFile']) && isset($params['FilePath'])){
				$obsException = new ObsException('SaveAsFile cannot be used with FilePath together');
				$obsException-> setExceptionType('client');
				throw $obsException;
			}
		}
		
		$promise = $this->httpClient->sendAsync($request, ['stream' => $saveAsStream])->then(
				function(Response $response) use ($model, $operation, $params, $request, $start){
					
					S3Log::commonLog(INFO, 'http request cost ' . round(microtime(true) - $start, 3) * 1000 . ' ms');
					
					$statusCode = $response -> getStatusCode();
					$readable = isset($params['Body']) && ($params['Body'] instanceof Stream || is_resource($params['Body']));
					if($statusCode === 307 && !$readable){
						if($location = $response -> getHeaderLine('location')){
							$url = parse_url($this->endpoint);
							$newUrl = parse_url($location);
							$scheme = (isset($newUrl['scheme']) ? $newUrl['scheme'] : $url['scheme']);
							$defaultPort = strtolower($scheme) === 'https' ? '443' : '80';
							$this->doRequest($model, $operation, $params, $scheme. '://' . $newUrl['host'] .
									':' . (isset($newUrl['port']) ? $newUrl['port'] : $defaultPort));
							return;
						}
					}
					$this -> parseResponse($model, $request, $response, $operation);
				},
				function (RequestException $exception) use ($model, $operation, $params, $request, $requestCount, $start) {
					
					S3Log::commonLog(INFO, 'http request cost ' . round(microtime(true) - $start, 3) * 1000 . ' ms');
					$message = null;
					if($exception instanceof ConnectException){
						if($requestCount <= $this->maxRetryCount){
							$this -> sendRequest($model, $operation, $params, $request, $requestCount + 1);
							return;
						}else{
							$message = 'Exceeded retry limitation, max retry count:'. $this->maxRetryCount . ', error message:' . $exception -> getMessage();
						}
					}
					$this -> parseException($model, $request, $exception, $message);
				});
		$promise -> wait();
	}
	
	
	protected function doRequestAsync($model, &$operation, $params, $pathArg, $callback, $startAsync, $originMethod, $endpoint = null){
		$request = $this -> makeRequest($model, $operation, $params, $pathArg, $endpoint);
		return $this->sendRequestAsync($model, $operation, $params, $callback, $startAsync, $originMethod, $request);
	}
	
	protected function sendRequestAsync($model, &$operation, $params, $callback, $startAsync, $originMethod, $request, $requestCount = 1)
	{
		$start = microtime(true);
		
		$saveAsStream = false;
		if(isset($operation['stream']) && $operation['stream']){
			$saveAsStream = isset($params['SaveAsStream']) ? $params['SaveAsStream'] : false;
			
			if($saveAsStream){
				if(isset($params['SaveAsFile'])){
					$obsException = new ObsException('SaveAsStream cannot be used with SaveAsFile together');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
				if(isset($params['FilePath'])){
					$obsException = new ObsException('SaveAsStream cannot be used with FilePath together');
					$obsException-> setExceptionType('client');
					throw $obsException;
				}
			}
			
			if(isset($params['SaveAsFile']) && isset($params['FilePath'])){
				$obsException = new ObsException('SaveAsFile cannot be used with FilePath together');
				$obsException-> setExceptionType('client');
				throw $obsException;
			}
		}
		return $this->httpClient->sendAsync($request, ['stream' => $saveAsStream])->then(
				function(Response $response) use ($model, $operation, $params, $callback, $startAsync, $originMethod, $request, $start){
					S3Log::commonLog(INFO, 'http request cost ' . round(microtime(true) - $start, 3) * 1000 . ' ms');
					$statusCode = $response -> getStatusCode();
					$readable = isset($params['Body']) && ($params['Body'] instanceof Stream || is_resource($params['Body']));
					if($statusCode === 307 && !$readable){
						if($location = $response -> getHeaderLine('location')){
							$url = parse_url($this->endpoint);
							$newUrl = parse_url($location);
							$scheme = (isset($newUrl['scheme']) ? $newUrl['scheme'] : $url['scheme']);
							$defaultPort = strtolower($scheme) === 'https' ? '443' : '80';
							return $this->doRequestAsync($model, $operation, $params, $callback, $startAsync, $originMethod, $scheme. '://' . $newUrl['host'] .
									':' . (isset($newUrl['port']) ? $newUrl['port'] : $defaultPort));
						}
					}
					$this -> parseResponse($model, $request, $response, $operation);
					S3Log::commonLog(INFO, 'obsclient cost ' . round(microtime(true) - $startAsync, 3) * 1000 . ' ms to execute '. $originMethod);
					unset($model['method']);
					$callback(null, $model);
				},
				function (RequestException $exception) use ($model, $operation, $params, $callback, $startAsync, $originMethod, $request, $start, $requestCount){
					S3Log::commonLog(INFO, 'http request cost ' . round(microtime(true) - $start, 3) * 1000 . ' ms');
					$message = null;
					if($exception instanceof ConnectException){
						if($requestCount <= $this->maxRetryCount){
							return $this -> sendRequestAsync($model, $operation, $params, $callback, $startAsync, $originMethod, $request, $requestCount + 1);
						}else{
							$message = 'Exceeded retry limitation, max retry count:'. $this->maxRetryCount . ', error message:' . $exception -> getMessage();
						}
					}
					$obsException = $this -> parseExceptionAsync($request, $exception, $message);
					S3Log::commonLog(INFO, 'obsclient cost ' . round(microtime(true) - $startAsync, 3) * 1000 . ' ms to execute '. $originMethod);
					$callback($obsException, null);
				}
		);
	}
	

}