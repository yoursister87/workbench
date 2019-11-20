<?php


class Hk_Service_Bos {


    protected $arrConf;

    const METHOD_PUT  = 'PUT';
    const METHOD_GET  = 'GET';
    const METHOD_HEAD = 'HEAD';
    const METHOD_POST = "POST";

    const VERSION     = 'v1';

    const CHARGE_VIDEO_HOST = 'charge.zuoyebang.cc';

    public $bukcet;
    public $cdnHost;
    public $uri;
    public $UTCTime;

    public $canonicalURI;
    public $canonicalQueryString;
    public $canonicalHeaders;

    private $signedHeaders;
    private $authorization;
    private $header;
    private $expSeconds = 1800;
    private $ak;
    private $sk;
    private $host;
    private $isPublicRead = false;

    /**
     * @brief 构造函数bos的bucket
     *
     * @return null
     * @param bucket (image|video|charge|skin|...)，无需加zyb或zybqa前缀
     * @see
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:12:35
     **/
    public function __construct($bucket) {
        $this->arrConf = Bd_Conf::getConf('hk/bos');
        $this->ak      = $this->arrConf['service']['ak'];
        $this->sk      = $this->arrConf['service']['sk'];
        $this->host    = $this->arrConf['service']['host'];
        $this->cdnHost = null;
        $this->bucket  = $bucket;

        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->UTCTime   = date("Y-m-d")."T".date("H:i:s")."Z";
        date_default_timezone_set($defaultTimeZone);

        // 检查bucket是否合法
        if (!empty($bucket)) {
            $this->isExists($bucket);
        } else {
            Bd_Log::warning("$bucket is not exist in config file");
        }
    }

    /**
     * @brief 判断bucket是否存在
     *
     * @return  bool 是否存在
     * @param  bucket bucket名称
     * @see
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:13:59
    **/
    public function isExists($bucket) {
        $p = strpos($bucket, "zyb-");
        if ($p !== false) {
            $bucket = substr($bucket, ($p + 4));
        }
        $arrBucket  = $this->arrConf['bucket'];
        if (isset($arrBucket[$bucket])) {
            $this->bucket = isset($arrBucket[$bucket]['name']) ? $arrBucket[$bucket]['name'] : $this->bucket;
            $this->isPublicRead = isset($arrBucket[$bucket]['ispublic']) ? $arrBucket[$bucket]['ispublic'] : false;
            if (isset($arrBucket[$bucket]['host'])) {
                $this->cdnHost  = $arrBucket[$bucket]['host'];
                $this->bucket   = isset($arrBucket[$bucket]['name']) ? $arrBucket[$bucket]['name'] : $this->bucket;
                $this->isPublicRead = isset($arrBucket[$bucket]['ispublic']) ? $arrBucket[$bucket]['ispublic'] : false;
            }
            return true;
        } else {
	        Bd_Log::warning("$bucket not exists");
            return false;
        }
    }

    /**
     * @brief 获取指定对象的cdn加速的uri
     *
     * @return  string url
     * @param  object string 对象名称
     * @author luhaixia
     * @date 2015/09/15 12:14:32
    **/
    private function getCdnUri($object) {
        if (!is_null($this->cdnHost)) {
            return "/{$object}";
        } else {
            return self::getUri($this->bucket, $object);
        }
    }

    /**
     * @brief 获取指定对象的uri
     *
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     *
     * @param  string bucket名称
     * @param  string object对象名称
     * @return string uri
     **/
    static private function getUri($bucket, $object) {
        $version = self::VERSION;
    	return "/{$version}/{$bucket}/{$object}";
    }

    /**
     * @brief 拼接querystring
     *
     * @return  string querystring
     * @param  array arrParams key=>value参数对
     * @see
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     **/
    static private function getQueryString($arrParams) {
        $canonicalQueryArray = array();
        foreach ($arrParams as $key => $value) {
            if (empty($value)) {            # 单key的情况
                $tmp = $key . "=";
            } elseif ($key == 'authorization') {
                $tmp = rawurlencode($key) . "=" . rawurlencode($value);
            } else {
                $tmp = $key . "=" . $value;
            }
            $canonicalQueryArray[] = $tmp;
        }
        return implode('&', $canonicalQueryArray);
    }

    /**
     * @brief http header转换成stirng
     *
     * @return  string httpheader转换成string
     * @param  array arrHeaders key=>value参数对
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     **/
    static private function getCanonicalHeaders($arrHeaders) {
        $signedHeadersArray = array();
        foreach ($arrHeaders as $key => $value) {
            $key   = strtolower($key);
            $value = trim($value);
            if (strlen($value) <= 0) {
                continue;
            }
            $tmp   = rawurlencode($key).":".rawurlencode($value);
            $signedHeadersArray[] = $tmp;
        }
        sort($signedHeadersArray);
        return implode("\n", $signedHeadersArray);
    }

    /**
     * @brief 获取authorization
     *
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     *
     * @param  string host
     * @param  string method
     * @param  string uri
     * @param  array arrQueryString key=>value参数对
     * @param  array arrHeaders key=>value参数对
     * @param  string UTCTime 时间戳
     * @param  string ak
     * @param  string sk
     * @param  int expSecondes 失效时间
     * @return string 返回生成的authorization
     **/
    static private function getAuthorization($host, $method, $uri, $arrQueryString, $arrHeaders, $UTCTime, $ak, $sk, $expSecondes = 1800) {
        $version      = self::VERSION;
        $canonicalURI = str_replace('%2F', '/', rawurlencode($uri));
        $canonicalQueryString = self::getQueryString($arrQueryString);
        $authStringPrefix = "bce-auth-{$version}/{$ak}/{$UTCTime}/{$expSecondes}";
        $signingKey       = hash_hmac('sha256', $authStringPrefix, $sk);
        $canonicalHeaders = self::getCanonicalHeaders($arrHeaders);
        $canonicalRequest = $method."\n".$canonicalURI."\n".$canonicalQueryString."\n".$canonicalHeaders;
        $signature     = hash_hmac('sha256', $canonicalRequest, $signingKey);
        $signedHeaders = strtolower(implode(';', array_keys($arrHeaders)));
        $authorization = $authStringPrefix."/{$signedHeaders}/{$signature}";
        return $authorization;
    }

    /**
     * @brief 上传一个对象文件
     *
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     *
     * @param string $filename 文件名
     * @param array $arrHeaders key=>value header参数对，默认值为array()
     * @param int $timeout 超时控制，大文件上传需要
     * @return boolean
     **/
    private function putObjectByCurl($filename, $arrHeaders = array(), $timeout = 5000) {
    	if (Hk_Util_Tools::isTestRequest()) {
    		return true;
    	}
        $putStart = intval(microtime(true) * 1000000);
        $gmtTime  = gmdate ("D d F Y H:i:s")." GMT";
        $arrPutHeader = array(
            "PUT {$this->uri} HTTP/1.1",
            "Host:{$this->host}",
            "Authorization:{$this->authorization}",
            "Content-Type:application/octet-stream",
            "x-bce-date:{$this->UTCTime}",
            "Date:{$gmtTime}",
        );

        $fp = fopen($filename, 'r');
        $ch = curl_init();
        $arrOpts = array(
            CURLOPT_URL            => "http://".$this->host.$this->uri,
            CURLOPT_HEADER         => 1,
            CURLOPT_HTTPHEADER     => $arrPutHeader,
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_INFILESIZE     => filesize($filename),
            CURLOPT_INFILE         => $fp,
            CURLOPT_UPLOAD         => true,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT_MS     => $timeout,
        );
        curl_setopt_array($ch,$arrOpts);
	    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $ret    = curl_exec($ch);
        $putEnd = intval(microtime(true) * 1000000);
        Bd_Log::addNotice('putObjectByCurl', ($putEnd - $putStart) / 1000);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status != 200 || $ret != '') {
            $error   = curl_errno($ch);
            Bd_Log::warning("Error:[Fail to put object], Detail:[host:{$this->host} uri:{$this->uri} code:$status error:$error ret:{$ret}]");
            curl_close($ch);
            fclose($fp);
            return false;
        }else{
            curl_close($ch);
            fclose($fp);
            return true;
        }
    }

    /**
     * @brief 上传一个字符串
     *
     * added 2016-05-26 添加新参数，能自定义上传meta信息<br>
     * format:
     *  array(
     *      k => v
     *  )
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     * @see
     *
     * @param string content 待上传字符串
     * @param string object 上传后的文件名
     * @param array $bceMeta
     * @return  bool
    **/
    public function putObjectByString($content, $object, array $bceMeta = array()) {
        if (strlen($content) <= 0 || strlen($object) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[content:$content, object:$object]");
            return false;
        }
        if (Hk_Util_Tools::isTestRequest()) {
            return true;
        }
        $this->uri  = self::getUri($this->bucket, $object);
        $arrHeaders = array(
            'Host'         => $this->host,
            'Content-Type' => 'application/octet-stream',
            'x-bce-date'   => $this->UTCTime,
        );
        $this->authorization = self::getAuthorization($this->host, self::METHOD_PUT, $this->uri, array(), $arrHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);
        $gmtTime      = gmdate("D d F Y H:i:s") . " GMT";
        $arrPutHeader = array(
            "PUT {$this->uri} HTTP/1.1",
            "Host:{$this->host}",
            "Authorization:{$this->authorization}",
            "Content-Type:application/octet-stream",
            "x-bce-date:{$this->UTCTime}",
            "Date:{$gmtTime}",
        );

        # add 2016-05-26 添加meta信息存储
        $additionMeta = array();
        if (!empty($bceMeta)) {
            foreach ($bceMeta as $metaName => $metaValue) {
                $additionMeta[] = sprintf("x-bce-meta-%s:%s", $metaName, $metaValue);
            }
        }
        $arrPutHeader = array_merge($arrPutHeader, $additionMeta);

        // 停止重试，防止BOS发生雪崩
        $try    = 0;
        $trymax = 1;
        while ($try < $trymax) {
            $putStart = intval(microtime(true) * 1000000);
            $ch       = curl_init();
            $arrOpts  = array(
                CURLOPT_URL            => "http://".$this->host.$this->uri,
                CURLOPT_HTTPHEADER     => $arrPutHeader,
                CURLOPT_CUSTOMREQUEST  => 'PUT',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => $content,
                CURLOPT_HEADER         => false,
                CURLOPT_TIMEOUT_MS     => 1500,
            );
            curl_setopt_array($ch,$arrOpts);
            if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }
            $ret    = curl_exec($ch);
            $putEnd = intval(microtime(true) * 1000000);
            Bd_Log::addNotice('putObjectByString', ($putEnd - $putStart) / 1000);
            $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($code != 200 || $ret != '') {
                $error = curl_errno($ch);
                Bd_Log::warning("Error:[talk to yun-bos error], Detail:[uri:{$this->uri} code:$code error:$error ret:{$ret} try:$try]");
                curl_close($ch);
                $try++;
                continue;
            }
            curl_close($ch);
            break;
        }
        return $try >= $trymax ? false : true;
    }


    /**
     * 分片上传数据到bos，分为首次和append上传，注意参数传递<br>
     * 成功返回下次append的offset，失败返回false
     *
     * @param string       $content
     * @param string       $object
     * @param boolean      $first       是否首次上传
     * @param int          $offset      上传位移
     * @param array        $bceMeta     首次上传此参数有效
     * @return mixed:int|boolean
     */
    public function putAppendObject($content, $object, $first = true , $offset = 0, array $bceMeta = array()) {
        if (strlen($content) <= 0 || strlen($object) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[content:$content, object:$object]");
            return false;
        }

        $uri = self::getUri($this->bucket, $object);
        if ($first) {           # 第一次上传，可以上传bceMeta
            $uri     = sprintf("%s?append", $uri);
        } else {                # 后续上传需要获知offset
            $uri     = sprintf("%s?append&offset=%d", $uri, $offset);
            $bceMeta = array();
        }
        $httpMethod  = self::METHOD_POST;
        $contentType = "text/plain";
        $authHeaders = array(
            'Host'         => $this->host,
            'Content-Type' => $contentType,
            'x-bce-date'   => $this->UTCTime,
        );
        $authorization = self::getAuthorization($this->host, $httpMethod, $uri, array(), $authHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);
        $reqHeader     = array(
            "$httpMethod {$uri} HTTP/1.1",
            "Host:{$this->host}",
            "Authorization:{$authorization}",
            "Content-Type:{$contentType}",
            "x-bce-date:{$this->UTCTime}",
            "Date:" . gmdate("D d F Y H:i:s") . " GMT",
        );
        if (!empty($bceMeta)) {
            foreach ($bceMeta as $metaName => $metaValue) {
                $reqHeader[] = sprintf("x-bce-meta-%s:%s", $metaName, $metaValue);
            }
        }
        $ret = $this->reqBos($uri, $reqHeader, $content);
        if (false === $ret) {
            return false;
        }
        return isset($ret["header"]["x-bce-next-append-offset"]) ? intval($ret["header"]["x-bce-next-append-offset"]) : 0;
    }

    /**
     * 使用bos抓取指定url数据，并转存到指定bos bucket中<br>
     * 可使用同步/异步模式进行抓取（异步抓取会有延迟）<br>
     * 异步如果成功会返回jobId，可用户查询转存进度
     *
     * @see https://cloud.baidu.com/doc/BOS/API.html#FetchObject.E6.8E.A5.E5.8F.A3
     *
     * @author  tangyang<tangyang@zuoyebang.com>
     * @version 1.0
     * @date    2017-12-21
     *
     * @param string       $sourceUrl   抓取数据源
     * @param string       $targetUri   转存目标uri（例如：a/b/c/d.mp4）
     * @param boolean      $async       是否异步转存，默认同步
     * @param int          $timeout     超时毫秒，默认5000
     * @return mixed:string|boolean
     */
    public function putObjectByUrl($sourceUrl, $targetUri, $async = false, $timeout = 5000) {
        $httpMethod = self::METHOD_POST;
        $reqUri     = self::getUri($this->bucket, $targetUri);
        $fetchMode  = true === $async ? "async" : "sync";

        # 计算签名
        $authHeader = array(
            'Host'       => $this->host,
            'x-bce-date' => $this->UTCTime,
            "x-bce-fetch-source"  => $sourceUrl,
            "x-bce-fetch-mode"    => $fetchMode,
            "x-bce-storage-class" => "STANDARD",
        );
        $queryStr   = array(
            "fetch" => "",
        );
        $this->authorization = self::getAuthorization($this->host, $httpMethod, $reqUri, $queryStr, $authHeader, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);

        $reqUrl     = sprintf("%s?fetch", $reqUri);
        $gmtTime    = gmdate("D d F Y H:i:s") . " GMT";
        $reqHeader  = array(
            "{$httpMethod} {$reqUrl} HTTP/1.1",
            "Host:{$this->host}",
            "Content-Length:0",
            "Date:{$gmtTime}",
            "x-bce-date:{$this->UTCTime}",
            "x-bce-fetch-source:{$sourceUrl}",
            "x-bce-fetch-mode:{$fetchMode}",
            "x-bce-storage-class:STANDARD",
            "Authorization:{$this->authorization}",
        );

        $start = intval(microtime(true) * 1000000);
        $ch    = curl_init();
        $opts  = array(
            CURLOPT_URL            => "http://" . $this->host . $reqUrl,
            CURLOPT_HTTPHEADER     => $reqHeader,
            CURLOPT_CUSTOMREQUEST  => $httpMethod,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT_MS     => $timeout,
        );
        curl_setopt_array($ch, $opts);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $ret   = curl_exec($ch);
        $end   = intval(microtime(true) * 1000000);
        Bd_Log::addNotice('putObjectByUrl', ($end - $start) / 1000);

        $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            $error = curl_errno($ch);
            Bd_Log::warning("Error:[talk to bos error], Detail:[uri:{$this->uri} code:$code error:$error ret:{$ret}]");
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $async ? @json_decode($ret, true)["jobId"] : true;
    }

    /**
     * 获取存储在bos中的自定的x-bce-meta信息<br>
     * 如果返回false, 代表无法获取Meta信息，一般为object不存在<br>
     *
     * @param string         $objectName
     * @return mixed
     */
    public function getObjectMeta($objectName) {
        if (strlen($objectName) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[object: $objectName]");
            return false;
        }

        $httpMethod = self::METHOD_HEAD;
        $uri        = self::getUri($this->bucket, $objectName);
        $arrHeaders = array(
            'Host'         => $this->host,
            'Content-Type' => 'application/octet-stream',
            'x-bce-date'   => $this->UTCTime,
        );
        $this->authorization = self::getAuthorization($this->host, $httpMethod, $uri, array(), $arrHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);

        $gmtTime   = gmdate("D d F Y H:i:s") . " GMT";
        $addHeader = array(
            "{$httpMethod} {$uri} HTTP/1.1",
            "Host:{$this->host}",
            "Authorization:{$this->authorization}",
            "Content-Type:application/octet-stream",
            "x-bce-date:{$this->UTCTime}",
            "Date:{$gmtTime}",
        );

        $url     = sprintf("http://%s%s", $this->host, $uri);
        $start   = intval(microtime(true) * 1000000);
        $ch      = curl_init();
        $arrOpts = array(
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $addHeader,
            CURLOPT_CUSTOMREQUEST  => $httpMethod,
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_TIMEOUT_MS     => 1500,
        );
        curl_setopt_array($ch, $arrOpts);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $ret       = curl_exec($ch);
        $end       = intval(microtime(true) * 1000000);
        Bd_Log::addNotice('getObjectMeta', ($end - $start) / 1000);
        Bd_Log::addNotice('bos_url', $url);

        $code      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200 || $ret == '') {
            $error = curl_errno($ch);
            Bd_Log::warning("Error:[talk to yun-bos error], Detail:[uri:{$uri} code:$code error:$error ret:{$ret}]");
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $this->parseHeaderMeta($ret);
    }

    /**
     * 解析header内容，分离出x-bce-meta信息，得到用户存储的原始的meta信息<br>
     *
     * @param string         $headerStream
     * @return array
     */
    private function parseHeaderMeta($headerStream) {
        $meta    = array();
        $headers = explode("\n", $headerStream);
        if (empty($headers)) {
            return $meta;
        }
        foreach ($headers as $str) {
            $cursor   = stripos($str, ":");
            if (false === $cursor) {
                continue;
            }
            $key        = substr($str, 0, $cursor);
            $value      = substr($str, $cursor + 1);        # strip char :
            if (false !== stripos($key, "x-bce-meta-")) {   # x-bce-meta
                $key        = str_replace("x-bce-meta-", "", trim($key));
                $meta[$key] = trim($value);
            }
        }
        return $meta;
    }

    /**
     * @brief 获取一个对象的url
     *
     * @return string 对象的url地址
     * @param  string object 对象名
     * @param  int expSecondes 地址失效时间
     * @param  bol isIntranet 是否获取内网地址
     * @see
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:14:32
     **/
    public function getObject($object, $expSeconds = 1800, $isIntranet = false) {
    	$this->uri = ($isIntranet) ? self::getUri($this->bucket, $object) : $this->getCdnUri($object);
        $host = ($isIntranet || is_null($this->cdnHost)) ? $this->host : $this->cdnHost;
        if ($this->isPublicRead) {
	        return "https://{$host}{$this->uri}";
	    }
    	$this->expSeconds = $expSeconds;

    	$arrHeaders = array(
	        'Host' => $host,
    	);
        $this->authorization = self::getAuthorization($host, self::METHOD_GET, $this->uri, array(), $arrHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);
    	return "https://{$host}{$this->uri}?authorization=".rawurlencode($this->authorization);
    }

    /**
     * @brief 上传一个对象
     *
     * @return  bool 是否上传成功
     * @param  string $filePath 文件路径
     * @param  string $object 上传后对象的名称
     * @param  int $timeout 上传超时控制，大文件上传需要
     * @see
     * @note
     * @author luhaixia
     * @date 2015/09/15 12:14:32
    **/
    public function putObject($filePath, $object, $timeout = 5000) {
        if (!file_exists($filePath)) {
        	return false;
        }
        $this->uri  = self::getUri($this->bucket, $object);

        $arrHeaders = array(
            'Host'         => $this->host,
            'Content-Type' => 'application/octet-stream',
            'x-bce-date'   => $this->UTCTime,
        );
        $this->authorization = self::getAuthorization($this->host, self::METHOD_PUT, $this->uri, array(), $arrHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);
        return $this->putObjectByCurl($filePath, array(), $timeout);
    }

    /**
     * 让BOS将一个文件从原地址复制到目的地址
     *
     * @author 燕睿涛
     * @param $srcObj string 源文件在BOS中的位置
     * @param $dstObj string 目的文件在BOS中的位置
     * @param $srcBucket string 源文件在BOS中的bucket，为空则表示是源和目标在同一个bucket。
     *
     */
    public function copyObject($srcObj, $dstObj, $srcBucket = "") {
		if(empty($srcBucket)){
			$srcBucket = $this->bucket;
		}else{
			$p = strpos($srcBucket, "zyb-");
			if($p !== false){
				$bucket = substr($srcBucket, ($p+4));
			}else{
				$bucket = $srcBucket;
			}
			$srcBucket = false;
			$arrBucket = $this->arrConf['bucket'];
			if(isset($arrBucket[$bucket])){
				if(isset($arrBucket[$bucket]['host'])){
					$srcBucket = isset($arrBucket[$bucket]['name']) ? $arrBucket[$bucket]['name'] : false;
				}
            }
			if($srcBucket === false){
				Bd_Log::warning("$bucket not exists");
				return false;
			}
		}
        $this->uri = "/{$this->bucket}/{$dstObj}";

        $arrHeaders = array(
            'Host' => $this->host,
            'Content-Type' => 'text/plain',
            'x-bce-date' => $this->UTCTime,
        );
        $this->authorization = self::getAuthorization($this->host, self::METHOD_PUT, $this->uri, array(), $arrHeaders, $this->UTCTime, $this->ak, $this->sk, $this->expSeconds);

        $srcUri = "/{$srcBucket}/{$srcObj}";
        $Date = gmdate ("D d F Y H:i:s")." GMT";
        $headers = [
            "PUT {$this->uri} HTTP/1.1",
            "Host:{$this->host}",
            "Date:{$Date}",
            "Authorization:{$this->authorization}",
            "Content-Type:text/plain",
            "x-bce-copy-source:{$srcUri}",
            "x-bce-date:{$this->UTCTime}",
            "x-bce-metadata-directive:copy",
        ];

        $startTimestamp = intval(microtime(true)*1000000);
        $ch = curl_init();
        $opts = [
            CURLOPT_URL => "http://".$this->host.$this->uri,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT_MS => 1500,
        ];
        curl_setopt_array($ch, $opts);
        $ret = curl_exec($ch);
        $endTimestamp = intval(microtime(true)*1000000);
		Bd_Log::addNotice('copytObject_tim', ($endTimestamp - $startTimestamp)/1000);
        Bd_Log::trace("返回头信息：".var_export($ret, true));

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(!$ret) {
            $error = curl_errno($ch);
            $url = "http://".$this->host.$this->uri;
            Bd_Log::warning("curl $url error:".$error. " status:".$status);
        }
        curl_close($ch);
        if($status != 200) {
            Bd_Log::warning("Fail to copy object. ret:".$ret);
            return  false;
        }
        return true;
    }


    /**
     * 请求bos，并返回请求结果
     *
     * @param string      $uri
     * @param array       $reqHeader
     * @param string      $content
     * @param int         $timeout
     * @return mixed:array|boolean
     */
    private function reqBos($uri, array $reqHeader, $content, $timeout = 1500) {
        $timer = new Bd_Timer(false, Bd_Timer::PRECISION_MS);

        $resp  = array();
        $ch    = curl_init();
        $opts  = array(
            CURLOPT_URL            => "http://{$this->host}{$uri}",
            CURLOPT_HTTPHEADER     => $reqHeader,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $content,
            CURLOPT_HEADER         => true,
            CURLOPT_TIMEOUT_MS     => $timeout,
        );
        curl_setopt_array($ch, $opts);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $timer->start();
        $ret  = curl_exec($ch);
        $cost = $timer->stop();
        Bd_Log::addNotice("bosReqTime", $cost);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $code || false === $ret) {
            Bd_Log::warning("Error:[talk to yun-bos error]", -1, array(
                "uri"       => $uri,
                "errno"     => curl_errno($ch),
                "errmsg"    => curl_error($ch),
                "http_code" => $code,
            ));
            curl_close($ch);
            return false;
        } else {
            $hSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = explode("\r\n", substr($ret, 0, $hSize));
            $resp    = array(
                "header" => $this->parseHeaders($headers),
                "body"   => trim(substr($ret, $hSize)),
            );
            curl_close($ch);
            return $resp;
        }
    }

    private function parseHeaders(array $headers) {
        $ret = array();
        if (empty($headers)) {
            return $ret;
        }
        foreach ($headers as $header) {
            list($k, $v)   = explode(":", $header);
            $ret[trim($k)] = trim($v);
        }
        return $ret;
    }

    /**
     * 获取上传的header
     * @param unknown $bucket
     * @param unknown $object
     * @return multitype:string
     */
    public static function getPutHeader($bucket, $object) {
        $arrConf = Bd_Conf::getConf('hk/bos');
        $host = $arrConf['service']['host'];
        $method = self::METHOD_PUT;
        $uri = self::getUri($bucket, $object);
        $queryString = array();
        $ak = $arrConf['service']['ak'];
        $sk = $arrConf['service']['sk'];
        $UTCTime = date("Y-m-d")."T".date("H:i:s")."Z";
        $expSeconds = 1800;

        $arrHeaders = array(
            'Host' => $host,
            'Content-Type' => 'video/mp4',
            'x-bce-date' => $UTCTime,
        );
        $authorization = self::getAuthorization($host, $method, $uri, $queryString, $arrHeaders, $UTCTime, $ak, $sk, $expSeconds);

        $gmtTime = gmdate ("D d F Y H:i:s")." GMT";
        $arrPutHeader = array(
            "PUT {$uri} HTTP/1.1",
            "Host:{$host}",
            "Authorization:{$authorization}",
            "Content-Type:video/mp4",
            "x-bce-date:{$UTCTime}",
            "Date:{$gmtTime}",
        );
        return $arrPutHeader;
    }
}
