<?php
namespace KongTools\ThirdParty\Sms;

/**
 *  短信宝
 */
class Smsbao{

    static $baseurl = 'http://api.smsbao.com/';
    static $options;

    public function __construct($options)
    {
        $default_option = [
            'user' => '',    //短信平台帐号
            'password_raw' => '',  //短信平台密码(原始)
        ];
        foreach ($options as $k => $option){
            $default_option[$k] = $option;
        }
        $default_option['password_encrypt'] = md5($default_option['password_raw']);
        self::$options = $default_option;
    }


    /**
     *  发送短信
     * @param $content string 短信内容 text文本
     * @param $phone  string 手机号
     * @return array
     */
    public function send($content, $phone){

//     demo:   $content="您的验证码是".mt_rand(1000,9999).",5分钟内有效,若非本人操作请忽略此消息";//要发送的短信内容

        $statusStrMap = [
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        ];

        $sendurl = self::$baseurl."sms?u=".self::$options['user']."&p=".self::$options['password_encrypt']."&m=".$phone."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;

        return [
            'status' => $result,
            'msg' => $statusStrMap[$result] ?? '',
        ];
    }


}