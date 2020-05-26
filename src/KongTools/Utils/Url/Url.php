<?php
namespace KongTools\Utils\Url;

class Url{

    /**
     * url_encode
     * @param $str
     * @return mixed
     */
    public static function urlEncode($str)
    {
        $new_str = urlencode($str);
        return str_replace('%2F','/',$new_str);
    }

    /**
     * 获取当前host域名
     * @return string
     */
    public static function getHost(){

        $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        $url = $scheme.$_SERVER['HTTP_HOST'];
        return $url;
    }


    /**
     *  验证是不是url
     * @param $v
     * @return bool
     */
    public static function isUrl($v)
    {
        $pattern = "#(http|https)://(.*\.)?.*\..*#i";
        if (preg_match($pattern, $v)) {
            return true;
        } else {
            return false;
        }
    }


}