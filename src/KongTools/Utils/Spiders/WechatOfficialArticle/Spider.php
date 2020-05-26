<?php
namespace KongTools\Utils\Spiders\WechatOfficialArticle;

/**
 * 抓取微信公众号文章
 * Class Spider
 * @package KongTools\Utils\Spiders\WechatOfficialArticle
 */
class Spider{


    /**
     *      * 进入url手动采集文章逻辑
     *      *
     *      
     */

    public function exec($centent_url)
    {
        $request = trim($centent_url);

        //地址验证(不完整的验证,后补)
        if (empty($request)) {

            $resdata["r"] = 3;
            return $resdata;
            exit;
        }
        //抓取文章内容
        $html = $this->getUrlContent($request);
        $result = array();
//抓取文章主要内容
        preg_match_all("/id=\"js_content\">(.*)<script/iUs", $html, $content, PREG_PATTERN_ORDER);
//dump($html);exit;

        $content = "<div id='js_content'>" . $content[1][0];

//$content变量的值是前面获取到的文章内容html
        $content = str_replace("data-src", "src", $content);
//$content变量的值是前面获取到的文章内容html
        $content = str_replace("preview.html", "player.html", $content);
//将视频地址中的&amp全部替换成&
//        $content = str_replace("&amp","&",$content);
//$html变量的值是前面获取到的文章全部html
        preg_match_all('/var msg_title = \"(.*?)\";/si', $html, $m);
        $msg_title = $m[1][0];//文章标题

        preg_match_all('/var msg_desc = \"(.*?)\";/si', $html, $m);
        $msg_desc = $m[1][0];   //文章标题

        //preg_match_all('/var round_head_img = \"(.*?)\";/si',$html,$m);
        //视频地址

//https://v.qq.com/x/page/*****.html 获取到的vid参数,将星号替换

        preg_match_all('/src=\"(.*?)\"/si', $content, $m);//获取src集合

        $imgUrl = $m[1];

        preg_match_all('/var msg_cdn_url = \"(.*?)\";/si',$html,$m);
        $msg_cdn_url = $m[1][0];//首张图片

        // $imagearr = $this->crabImage($msg_cdn_url);

        //处理图片

//将图片下载到本地文件夹中

        $savepath = array();

        foreach ($imgUrl as $key => $value) {

            //这里的crabImage图片下载本地函数可以再前面的几篇文章中找一下

            $res = $this->crabImage($value);

            //保存后的地址存入$savepath中

            $savepath[$key] = $res['save_path'];

        }

//将$content中的图片地址全部替换成$save_path
        $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        $domain = $scheme.$_SERVER['HTTP_HOST'];

        foreach ($savepath as $k => $val) {

            //todo
            $val = $domain.'/'.$val;    //路径品全
            $content = str_replace($imgUrl[$k], $val, $content);

        }


//将替换过的内容部分再替换出原$html里的内容

        $result['content'] = $content;

        $result['title'] = $msg_title;

        $result['description'] = $msg_desc;

        $result['search_url'] = $request;


        return $result;

    }

    /**
     *      * 爬虫程序
     *      * 从给定的url获取html内容
     *      * @param string $url
     *      * @return string
     *
     *      */

    public function getUrlContent($request)
    {

        $handle = fopen($request, "r");

        if ($handle) {

            $content = stream_get_contents($handle, -1);

//读取资源流到一个字符串,第二个参数需要读取的最大的字节数。默认是-1（读取全部的缓冲数据）

// $content = file_get_contents($url, 1024 * 1024);

//这里也可以用php的curl爬取网页内容，可将curl爬取方式封装在这里

            return $content;
        } else {
            return false;
        }
    }


    /**
     * PHP将网页上的图片攫取到本地存储
     * @param $imgUrl  图片url地址
     * @param string $saveDir 本地存储路径 默认存储在当前路径
     * @param null $fileName 图片存储到本地的文件名
     * @return mixed
     */
    function crabImage($imgUrl)
    {

        //路径拼全一点
        $time = time();
        $saveDir = './uploads/gongzhonghao_images/'.date('Y-m',$time).'/'.date('d',$time).'/';
        $saveDirNoDot = '/uploads/gongzhonghao_images/'.date('Y-m',$time).'/'.date('d',$time).'/';


        if (empty($imgUrl)) {
            return false;
        }
        //获取图片信息大小
        $imgSize = getImageSize($imgUrl);
        if (!in_array($imgSize['mime'], array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'), true)) {
            return false;
        }

        //获取后缀名
        $_mime = explode('/', $imgSize['mime']);
        $_ext = '.' . end($_mime);

        if (empty($fileName)) {  //生成唯一的文件名
            $fileName = uniqid(time(), true) . $_ext;
        }

        //开始攫取
        ob_start();
        readfile($imgUrl);
        $imgInfo = ob_get_contents();
        ob_end_clean();

        if (!file_exists($saveDir)) {
            mkdir($saveDir, 0777, true);
        }
        $fp = fopen($saveDir . $fileName, 'a');
        $imgLen = strlen($imgInfo);    //计算图片源码大小
        $_inx = 2048;   //每次写入2k
        $_time = ceil($imgLen / $_inx);
        for ($i = 0; $i < $_time; $i++) {
            fwrite($fp, substr($imgInfo, $i * $_inx, $_inx));
        }
        fclose($fp);
        // return array('file_name' => $fileName, 'save_path' => $saveDir . $fileName);
        $save_path = $saveDirNoDot . $fileName;
        return array('file_name' => $fileName, 'save_path' =>$save_path);


    }



}