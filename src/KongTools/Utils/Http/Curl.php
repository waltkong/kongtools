<?php
namespace KongTools\Utils\Http;

use EasyWeChat\Kernel\Exceptions\Exception;
use GuzzleHttp\Client;
use KongTools\Exceptions\HttpException;

class Curl{

    static $timeout = 10.0;

    /**
     *  post数据
     * @param $url
     * @param array $data
     * @return mixed
     * @throws HttpException
     */
    public static function post($url, array $data){
        $client = new Client([
            'timeout'  => self::$timeout,
        ]);
        try{
            $response = $client->request('POST', $url, [
                'form_params' => $data
            ]);
            if($response){
                $contents = $response->getBody()->getContents();
                $ret = json_decode($contents,true);
                return $ret;
            }else{
                throw new \Exception('request error');
            }
        }catch (\Exception $e){
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * get数据
     * @param $url
     * @param array $data
     * @return mixed
     * @throws HttpException
     */
    public static function get($url, array $data=[]){

        if(!empty($data)){
            $url_params = self::array_2_http_query($data);
            $url = $url . "?".$url_params;
        }

        $client = new Client([
            'timeout'  => self::$timeout,
        ]);

        try{
            $response =  $client->get($url);
            if($response){
                $contents = $response->getBody()->getContents();
                $ret = json_decode($contents,true);
                return $ret;
            }
            else{
                throw new \Exception('request error');
            }
        }catch (\Exception $e){
            throw new HttpException($e->getMessage());
        }
    }


    /**
     * 将数组转化成 http参数
     * @param $arr
     * @return string
     * @throws Exception
     */
    public static  function array_2_http_query($arr){
        $res = [];
        foreach ($arr as $k => $v){
            if(!empty($v)){
                if(is_object($v) || is_array($v)){
                    throw new Exception('request data not formatted');
                }
            }
            if($v!==''){
                $res[] = $k.'='.$v;
            }
        }
        return implode('&',$res);
    }



}