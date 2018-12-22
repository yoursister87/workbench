<?php
/**
 * @file util/Openssl.php
 * @author zhangfumu@zuoyebang.com
 * @date 2018-05-22
 * @version 1.0
 * @brief 使用证书对数据进行签名、验签、加密、解密
 **/

class Hk_Util_Openssl {
    private $padding = OPENSSL_PKCS1_PADDING;
    private $encryptBlockSize;
    private $decryptBlockSize;
    private $privateKey;
    private $publicKey;

    function __construct() {}
    /**
     * 使用数组初始化, privateKey:私钥路径 publicKey:公钥路径 passphrase:私钥密码 bits:位数
     * @param $conf
     * @return bool
     */
    function conf($conf){
        if(!isset($conf['privateKey']) && !isset($conf['publicKey'])){
            return false;
        }
        if(isset($conf['privateKey'])){
            $passphrase  = isset($conf['passphrase']) ? $conf['passphrase'] : '';
            if(false === $this->privateKey(ROOT_PATH.$conf['privateKey'], $passphrase)){
                return false;
            }
        }
        if(isset($conf['publicKey'])){
            if(false === $this->publicKey(ROOT_PATH.$conf['publicKey'])){
                return false;
            }
        }
        $bits = isset($conf['bits']) && in_array($conf['bits'],[512,1024,2048,4096]) ? $conf['bits'] : 1024;
        $this->encryptBlockSize = $bits / 8 - 11;
        $this->decryptBlockSize = $bits / 8;
        return true;
    }
    /**
     * 使用私钥加密
     * @param $plain
     * @return bool|string
     */
    function privateEncrypt($plain){
        $encrypted = '';
        $plain = json_encode($plain);
        $plain = str_split($plain, $this->encryptBlockSize);
        foreach ($plain as $chunk){
            $partial = '';
            if(false === openssl_private_encrypt($chunk, $partial, $this->privateKey, $this->padding)){
                return false;
            }
            $encrypted .= $partial;
        }
        return base64_encode($encrypted);
    }
    /**
     * 使用公钥加密
     * @param $plain
     * @return bool|string
     */
    function publicEncrypt($plain){
        $encrypted = '';
        $plain = json_encode($plain);
        $plain = str_split($plain, $this->encryptBlockSize);
        foreach ($plain as $chunk){
            $partial = '';
            if(false === openssl_public_encrypt($chunk, $partial, $this->publicKey, $this->padding)){
                return false;
            }
            $encrypted .= $partial;
        }
        return base64_encode($encrypted);
    }
    /**
     * 使用私钥解密
     * @param $encrypted
     * @return bool|mixed
     */
    function privateDecrypt($encrypted){
        $plain = '';
        $encrypted = str_split(base64_decode($encrypted), $this->decryptBlockSize);
        foreach ($encrypted as $chunk){
            $partial = '';
            if(false === openssl_private_decrypt($chunk, $partial, $this->privateKey, $this->padding)){
                return false;
            }
            $plain .= $partial;
        }
        return json_decode($plain, true);
    }
    /**
     * 使用公钥解密
     * @param $encrypted
     * @return bool|mixed
     */
    function publicDecrypt($encrypted){
        $plain = '';
        $encrypted = str_split(base64_decode($encrypted), $this->decryptBlockSize);
        foreach ($encrypted as $chunk){
            $partial = '';
            if(false === openssl_public_decrypt($chunk, $partial, $this->publicKey, $this->padding)){
                return false;
            }
            $plain .= $partial;
        }
        return json_decode($plain, true);
    }
    /**
     * 私钥生成签名
     * @param $data
     * @param int $algo
     * @param string $digestAlgo
     * @return bool|string
     */
    function sign($data, $algo = OPENSSL_ALGO_SHA1, $digestAlgo = 'sha512'){
        $digest = openssl_digest($data, $digestAlgo);
        if($digest === false){
            return false;
        }
        $signature = '';
        openssl_sign($digest, $signature, $this->privateKey, $algo);
        return base64_encode($signature);
    }
    /**
     * 公钥验证签名
     * @param $data
     * @param $signature
     * @param int $algo
     * @param string $digestAlgo
     * @return bool
     */
    function verify($data, $signature, $algo = OPENSSL_ALGO_SHA1, $digestAlgo = 'sha512'){
        $digest = openssl_digest($data, $digestAlgo);
        if($digest === false){
            return false;
        }
        $signature = base64_decode($signature);
        $verify = openssl_verify($digest, $signature, $this->publicKey, $algo);
        return $verify == 1 ? true : false;
    }
    /**
     * 初始化私钥
     * @param $privateKeyFilePath
     * @param string $passphrase
     * @return bool
     */
    private function privateKey($privateKeyFilePath, $passphrase = ''){
        $content = file_get_contents($privateKeyFilePath);
        if($content === false){
            return false;
        }
        $privateKey = openssl_pkey_get_private($content, $passphrase);
        if($privateKey === false){
            return false;
        }
        $this->privateKey = $privateKey;
        return true;
    }
    /**
     * 初始化公钥
     * @param $publicKeyFilePath
     * @return bool
     */
    private function publicKey($publicKeyFilePath){
        $content = file_get_contents($publicKeyFilePath);
        if($content === false){
            return false;
        }
        $publicKey = openssl_pkey_get_public($content);
        if($publicKey === false){
            return false;
        }
        $this->publicKey = $publicKey;
        return true;
    }
    function __destruct(){
        if(NULL !== $this->privateKey){
            openssl_free_key($this->privateKey);
        }
        if(NULL !== $this->publicKey){
            openssl_free_key($this->publicKey);
        }
    }
}