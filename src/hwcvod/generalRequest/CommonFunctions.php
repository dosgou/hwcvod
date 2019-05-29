<?php
namespace hwcvod\generalRequest;

/**
 * 数据操作类
 */
use hwcvod\exception\VodException;

class CommonFunctions{
    /**
     * 公共HTTP请求
     * @param $url
     * @param $params
     * @param string $method
     * @param array $header
     * @param int $timeout
     * @return HttpResponse
     * @throws VodException
     */
    public static function http($url, $params, $method = 'GET', $header = array(), $timeout = 10){
        // POST 提交方式的传入 $set_params 必须是字符串形式
        $opts = array(
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT=>$timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => self::curlHeaders($header)
        );

        // 是否开启代理
        if (ENABLE_HTTP_PROXY) {
            $opts[CURLOPT_PROXYAUTH] = CURLAUTH_BASIC;
            $opts[CURLOPT_PROXY] = HTTP_PROXY_IP;
            $opts[CURLOPT_PROXYPORT] = HTTP_PROXY_PORT;
            $opts[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
        }

        // 关闭https认证
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
            $opts[CURLOPT_SSL_VERIFYHOST] = false;
        }
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $a = array();
                $b = array();
                foreach ($params as $key => $value) {
                    $k = self::escape($key);
                    if (is_array($value)){
                        foreach ($value as $ikey => $ivalue) {
                            $ikv = "$k=" . self::escape($ivalue);
                            array_push($b, $ikv);
                        }
                    }else{
                        $kv = "$k=" . self::escape($value);
                        array_push($a, $kv);
                    }
                }
                if (!empty($b)){
                    $c = array_merge($a,$b);
                    sort($c);
                    $opts[CURLOPT_URL] = $url . '?' . join("&", $c);
                }else{
                    sort($a);
                    $opts[CURLOPT_URL] = $url . '?'. join("&", $a);
                }
                break;
            case 'POST':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            case 'DELETE':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                break;
            case 'PUT':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 0;
                $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new VodException('VOD.100011001','不支持的请求方式！');
        }

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        /**
         * 对响应结果进行封装，包含响应状态码、响应头和响应体
         */
        $httpResponse = new HttpResponse();
        $httpResponse->setStatus(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $httpResponse->setHeader(substr($data, 0, $headerSize));
        $httpResponse->setBody(json_encode(json_decode(substr($data, $headerSize)), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        if (curl_errno($ch)) {
            throw new VodException("ErrorCode: " . curl_errno($ch), "ErrorMessage:" . curl_error($ch));
        }

        curl_close($ch);

        return $httpResponse;
    }

    /**
     * 将请求头key转为小写
     * @param $headers
     * @return array
     */
    public static function curlHeaders($headers){
        $header = array();
        foreach ($headers as $key => $value) {
            array_push($header, strtolower($key) . ':' . trim($value));
        }
        return $header;
    }

    public static function escape($string){
        $entities = array('+', "%7E");
        $replacements = array('%20', "~");
        return str_replace($entities, $replacements, urlencode($string));
    }
}