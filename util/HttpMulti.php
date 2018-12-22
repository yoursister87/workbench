<?php

/**
 * @brief 批量发送http请求的工具类（使用curl_multi）
 * @author guobaoshan@zuoyebang.com
 * @date 2017-03-18
 *
 * 使用方式：
 * 1. new一个类对象
 * 2. 设置连接超时和执行超时，也可不设置，默认都是1s
 * 3. 调用addHttpRequest方法，添加请求url和参数
 * 4. 调用run方法，执行批量请求
 * 5. 调用getResult方法，获取请求结果
 * 返回内容：
 * array——元素个数为addHttpRequest添加的请求个数，一个请求对应一个结果，顺序依次对应
 *
 **/

class Hk_Util_HttpMulti {
    
    // 错误号和错误信息定义
    const ERROR_PARAM            = 1;
    const ERROR_CURLM            = 2;
    private $errMap    = array(
        self::ERROR_PARAM    => "参数错误",
        self::ERROR_CURLM    => "CurlMulti错误",
    );

    // http请求状态定义，在getResult返回结果内的state字段使用
    const HANDLE_STATE_FINISHED    = 1;
    const HANDLE_STATE_DOING    = 2;

    // post请求数据打包格式，1-http_build_query打包，2-json打包，3-json无转义
    const POST_FORMAT_ARRAY         = 1;
    const POST_FORMAT_JSON          = 2;
    const POST_FORMAT_JSON_UNESCAPE = 3;

    // 连接超时，毫秒
    private $_connect_timeout_ms;
    // 执行超时，毫秒
    private $_handle_timeout_ms;

    private $_easy_handlers;
    private $_handle_outputs;
    private $_errno;
    private $_error;

    public function __construct() {
        $this->_connect_timeout_ms = 1000;
        $this->_handle_timeout_ms = 1000;
        $this->_easy_handlers = array();
    }

    // 设置执行超时时间，单位ms
    public function setTimeoutMs($timeout) {
        $this->_handle_timeout_ms = $timeout;
    }

    // 设置连接超时时间，单位ms
    public function setConnectTimeoutMs($timeout) {
        $this->_connect_timeout_ms = $timeout;
    }

    // 获取前次执行失败的错误号
    public function errno() {
        return $this->_errno;
    }

    // 获取前次执行失败的错误信息
    public function error() {
        return $this->_error();
    }

    // 增加http请求，url-请求地址，data-array请求参数，format-请求打包格式
    // 无data，使用get请求；有data，使用post请求
    public function addHttpRequest($url, $data = null, $format = self::POST_FORMAT_ARRAY) {
        $header = array();
        if (is_array($data) && isset($data['httpHeader'])) {
            $header = $data['httpHeader'];
            unset($data['httpHeader']);
        }
        $gzip = 0;
        foreach ($header as $item) {
            if (strpos($item, 'Accept-Encoding') !== false && strpos($item, 'gzip') !== false) {
                $gzip = 1;
            }
        }
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_PROXY, "proxy.zuoyebang.com:80");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->_connect_timeout_ms);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_handle_timeout_ms);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        if ($gzip) {
            curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        }
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            if ($format == self::POST_FORMAT_ARRAY) {
                $postData = http_build_query($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            } else if ($format == self::POST_FORMAT_JSON) {
                $postData = json_encode($data);
                $header[] = 'Content-Type: application/json';
                $header[] = 'Content-Length: ' . strlen($postData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            } else if ($format == self::POST_FORMAT_JSON_UNESCAPE) {
                $postData = json_encode($data, JSON_UNESCAPED_SLASHES);
                $header[] = 'Content-Type: application/json';
                $header[] = 'Content-Length: ' . strlen($postData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            } else {
                $this->errno = self::ERROR_PARAM;
                $this->error = $errMap[self::ERROR_PARAM];
                return false;
            }
        }
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $this->_easy_handlers[] = $ch;
    }

    // 执行http并发请求
    public function run() {
        $mh    = curl_multi_init();
        // 将各个ch添加到mh中
        foreach ($this->_easy_handlers as $ch) {
            $ret    = curl_multi_add_handle($mh, $ch);
            if (0 !== $ret) {
                $this->_handle_outputs[] = array(
                    'state'    => self::HANDLE_STATE_FINISHED,
                    'errno'    => $ret,
                    'error'    => curl_multi_strerror($ret),
                );
            } else {
                $this->_handle_outputs[] = array(
                    'state'    => self::HANDLE_STATE_DOING,
                    'errno'    => 0,
                    'errno'    => '',
                );
            }
        }
        // 开始执行
        $ret        = 0;
        $active        = null;
        do {
            $ret    = curl_multi_exec($mh, $active);
        } while ($ret == CURLM_CALL_MULTI_PERFORM);
        while ($active && $ret == CURLM_OK) {
            // 由于阻塞模式，最小时间为1s，太长，因此这里使用非阻塞模式
            if (curl_multi_select($mh, 0) == -1) {
                usleep(10000);
            }
            do {
                $ret = curl_multi_exec($mh, $active);
            } while ($ret == CURLM_CALL_MULTI_PERFORM);
        }
        if ($ret !== CURLM_OK) {
            $this->_errno    = self::ERROR_CURLM;
            $this->_error    = curl_multi_strerror($ret);
            return false;
        }
        // 执行完成，取结果
        foreach ($this->_easy_handlers as $idx => $ch) {
            if ($this->_handle_outputs[$idx]['state'] == self::HANDLE_STATE_FINISHED) {
                $this->_handle_outputs[$idx]['code']    = 0; //curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->_handle_outputs[$idx]['result']  = '';
                continue;
            }
            $multiInfo    = curl_multi_info_read($mh);
            $this->_handle_outputs[$idx]['state']   = self::HANDLE_STATE_FINISHED;
            $this->_handle_outputs[$idx]['errno']   = $multiInfo['result'];
            $this->_handle_outputs[$idx]['error']   = curl_error($ch);
            $this->_handle_outputs[$idx]['code']    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->_handle_outputs[$idx]['result']  = curl_multi_getcontent($ch);
            // 移除并关闭ch
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        // 关闭mh
        curl_multi_close($mh); 
        return true;
    }

    // 获取并发请求返回的结果数据
    public function getResult() {
        $result    = $this->_handle_outputs;
        $this->clear();
        return $result;
    }

    // 清理数据，如果要连续使用此类的对象，请注意要调用这个方法清理数据，避免爆内存
    public function clear() {
        $this->_errno    = 0;
        $this->_error    = '';
        $this->_easy_handlers   = [];
        $this->_handle_outputs  = [];
    }

}


?>
