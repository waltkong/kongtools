<?php
namespace KongTools\Api;

use KongTools\Exceptions\JwtException;
use KongTools\Utils\JsonWebToken\Jwt\JWT;

/**
 * jwt工具
 * Class JwtTool
 * @package KongTools\Api
 */
class JwtTool{

    public static $refreshToken;
    public $key;
    public $time;
    public $iss = '1148568473@qq.com';   //签发者 可选
    public $aud = '1148568473@qq.com';   //接受者 可选
    public $expire_time;
    public $refresh_time;

    public function __construct ($key='abc123456',$expire_time=7200){   //$expire_time=86400
        $this->key = $key;
        $this->time = time();
        $this->expire_time = $expire_time; //过期时间,这里默认设置一天吧 1xiaoshi
        $this->refresh_time = 3600;   //刷新时间 比如 1小时
    }

    /**
     *签发token
     *
     */
    public function issue(array $data=[]){
        try{
            $data['refresh_time'] = $this->time + $this->refresh_time;
            $token = [
                'iss' => $this->iss, //签发者 可选
                'aud' => $this->aud, //接收该JWT的一方，可选  'http://www.helloweba.net'
                'iat' => $this->time, //签发时间
                'nbf' => $this->time + 1 , //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
                'exp' => $this->time+$this->expire_time,
                'data' => $data,
            ];
            $jwt = JWT::encode($token,$this->key,$alg = 'HS256');
            return $jwt;
        }catch (\Exception $e){
            throw new JwtException($e->getMessage());
        }
    }

    /**
     *验证token
     * 返回数据
     */
    public function verify($jwt){
        try {

            JWT::$leeway = 1;//当前时间减去1，把时间留点余地
            $decoded = JWT::decode($jwt, $this->key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            $data = $arr['data'];

            if($arr['exp'] < time()){  //过期时间过了
                throw new JwtException("token过期");
            }

            if(!empty($data) && !is_array($data)){
                if(is_object($data)){
                    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                }
                $data = json_decode($data,1);
                if($data['refresh_time'] < time()){  //就必须得刷新了
                    self::$refreshToken = $this->issue($data);
                }
            }
            return $data;
        } catch(\Exception $e) {  //其他错误
            throw new JwtException($e->getMessage());
        }
    }

    /**
     *获取刷新后的token
     * 接收原token
     * 结果返回给前台
     */
    public function getRefreshToken($jwt){
        if(!empty(self::$refreshToken)){
            return self::$refreshToken;
        }else{
            return $jwt;
        }
    }


}