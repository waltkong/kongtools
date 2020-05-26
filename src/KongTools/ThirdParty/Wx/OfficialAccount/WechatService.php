<?php
namespace KongTools\ThirdParty\Wx\OfficialAccount;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;

/**
 *  微信公众号
 * Class WechatService
 * @package KongTools\ThirdParty\Wx\OfficialAccount
 */
class WechatService{

    public static $wechat_user_key = 'wk_wechat_user';

    public static $constuct_options;

    public function __construct($options=[])
    {
        $options_default = [
            'wechat_user_key' => 'wk_wechat_user',
            'wechat_target_url_key' => 'wk_wechat_target_url',
            'wechat_target_url_val' => '/wechat/h5',
            'get_session_func' => 'session_get',  // 需要传递一个获取session的方法,如 session_set($key)
            'set_session_func' => 'session_set',  // 需要传递一个设置session的方法,如 session_set($key,$value)
        ];
        foreach ($options as $k => $option){
            $options_default[$k] = $option;
        }

        self::$wechat_user_key = $options_default['wechat_user_key'];
        self::$constuct_options = $options_default;
    }

    private function sessionGet($key){
        $func = self::$constuct_options['get_session_func'];
        return $func($key);
    }

    private function sessionSet($key,$val){
        $func = self::$constuct_options['set_session_func'];
        return $func($key,$val);
    }


    /**
     *  公众号入口page调用 如果授权返回用户信息 没有则会去授权页面
     * @param $official_config
     * @return array
     */
    public function check_oauth($official_config){

        $official_config_default = [  //    //公众号配置
            'app_id' => '',
            'secret' => '',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];
        foreach ($official_config as $k => $item){
            $official_config_default[$k] = $item;
        }

        $app = Factory::officialAccount($official_config_default);

        $oauth = $app->oauth;

        // 未登录
        if (empty($this->sessionGet(self::$wechat_user_key))) {

            $this->sessionSet(self::$constuct_options['wechat_target_url_key'],self::$constuct_options['wechat_target_url_val']);

            $oauth->redirect()->send();
        }
        // 已经登录过
        $user = $this->sessionGet(self::$wechat_user_key);

        return [
            'openid' => $user['id'] ??'', // 对应微信的 OPENID
            'nickname' => $user['nickname'] ??'',  // 对应微信的 nickname
            'name' => $user['name'] ??'', // 对应微信的 nickname
            'avatar' => $user['avatar'] ??'',  // 头像网址
            'original' => $user['original'] ??'', // 原始API返回的结果
            'access_token' => $user['token'] ??'',  // access_token， 比如用于地址共享时使用
        ];

    }

    /**
     * 授权回调逻辑
     * @param $official_config
     * @return string 跳转地址
     */
    public function oauth_callback($official_config){

        $official_config_default = [  //    //公众号配置
            'app_id' => '',
            'secret' => '',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];
        foreach ($official_config as $k => $item){
            $official_config_default[$k] = $item;
        }

        $app = Factory::officialAccount($official_config_default);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        $this->sessionSet(self::$wechat_user_key,$user);
        $route = $this->sessionGet(self::$constuct_options['wechat_target_url_key']);

        header("Location: $route");die;
    }


    /**
     * 向指定用户发消息
     * @param $official_config
     * @param $openid
     * @param $msg
     */
    public function pushMessageToUser($official_config, $openid, $msg){

        $app = Factory::officialAccount($official_config);

        $message = new Text($msg);

        $result = $app->customer_service->message($message)->to($openid)->send();

        return $result;

    }


}

