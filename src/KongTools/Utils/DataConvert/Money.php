<?php
namespace KongTools\Utils\DataConvert;

class Money{

    /** 分 =》元
     * @param $fen
     * @return string
     */
    public static function fenToYuan($fen){
        $yuan = ((int)$fen/100);
        return sprintf("%01.2f", $yuan);
    }

    /**
     *  元 =》 分
     * @param $yuan
     * @return int
     */
    public static function yuanToFen($yuan){
        $yuan = number_format($yuan,2,".","");
        return (int)($yuan*100);
    }


}

