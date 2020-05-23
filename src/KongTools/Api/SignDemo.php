<?php
namespace KongTools\Api;
use KongTools\Exceptions\SignException;

/**
 *  接口数据交互中，通常会使用到签名，这里提供一个签名示例
 * Class SignDemo
 * @package KongTools\Api
 */
class SignDemo{

    /**
     *  进行加密
     * @param $api_key string 秘钥
     * @param array $verifyData
     * @return string
     * @throws \Exception
     */
    public static function setSign($api_key, $verifyData=[]){

        if(empty($verifyData)) throw new SignException('no sign data');

        ksort($verifyData);
        $strarr = [];
        foreach ($verifyData as $k => $item){
            $strarr[] = "{$k}={$item}";
        }

        $str = implode('&',$strarr);

        //拼接key
        $str .= $api_key;

        //加密
        $ret =  strtolower(md5($str));
        $ret = strtolower(sha1($ret));

        return $ret;
    }


    /**
     * 校验签名
     * @param $api_key
     * @param array $verifyData
     * @param $sign
     * @throws SignException
     */
    public static function checkSign($api_key, $verifyData=[] , $sign){

        if(empty($verifyData)) throw new SignException('no sign data');

        if($sign != self::setSign($api_key, $verifyData)){
            throw new SignException('sign error');
        }

    }



}