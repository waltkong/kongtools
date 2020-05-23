<?php
namespace KongTools\Utils\Encrypts;

/**
 * 3des加密算法，可以和java通用
 * Class ThreeDes
 * @package KongTools\Utils\Encrypts
 */
class ThreeDes{

    /**
     * Encrypt
     * @param $data string
     * @param $key string
     * @return string
     */
    public function encrypt($data, $key)
    {
        $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        $encData = base64_encode($encData);
        return $encData;
    }

    /**
     * Decrypt
     * @param $data string
     * @param $key string
     * @return string
     */
    public function decrypt($data, $key)
    {
        $data    = base64_decode($data);
        $decData = openssl_decrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return $decData;
    }




}