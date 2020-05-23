<?php
namespace KongTools\Utils\Qrcode;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeUtil{

    /**
     *获取二维码的图片src
     * $data
     */
    public function getSrc($data,$size=300,$logoUrl=''){
        $qrCode = self::generate($data,$size,$logoUrl);
        $src =  "data:image/png;base64,".base64_encode($qrCode);
        return $src;
    }

    /**
     *生成二维码的二进制数据
     */
    protected function generate($data,$size=300,$logoUrl='',$errorCorrection='Q'){
        if(is_array($data)){
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        $qrCode = QrCode::format('png')->   //Will return a PNG image
        size($size)->             ////设置像素尺寸
        errorCorrection($errorCorrection)->   //容错级别提高  L(7% 的字节码恢复率) M(15%) Q(25%) H (30% 的字节码恢复率)
        encoding('UTF-8');     //encode
        if(!empty($logoUrl)){
            $qrCode = $qrCode->merge($logoUrl, .1, true);  //生成一个中间有LOGO图片的二维码,且LOGO图片占整个二维码图片的10%. 绝对路径
        }
        return $qrCode->generate($data);   //二进制数据
    }




}