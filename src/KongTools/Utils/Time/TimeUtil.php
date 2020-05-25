<?php
namespace KongTools\Utils\Time;

class TimeUtil{

    /**
     *  今天开始 和 结束时间戳
     * @param string $format  【 date timestamp】
     * @return array
     */
    public static function getTodayBeginAndEndTime($format='date'){
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));

        return [
            'start' => $format=='date' ? date('Y-m-d H:i:s',$start) : $start ,
            'end' => $format=='date' ? date('Y-m-d H:i:s',$start) : $end ,
        ];
    }

    /**
     * date =》 timestamp
     * @param $tr string
     * @return false|int
     */
    public static function convertDateStrToTimestamp($tr){
        $tr = str_replace("：",":",$tr);
        $default = '0000-00-00 00:00:00';
        $ret = '';
        for ($i=0; $i < strlen($default) ; $i++) {
            $ret .= isset($tr[$i]) ? $tr[$i] : $default[$i] ;
        }
        return strtotime($ret);
    }

    /**
     *  utc时间 =》 datetime
     * @param $str
     * @return string
     */
    public static function UTC2Datetime($str){
        $str =  str_replace("T"," ",$str);
        $str =  str_replace("Z","",$str);
        return trim($str);
    }

    /** timestamp =》 utc时间
     * @param $str
     * @return string
     */
    public static function timestamp2UTC($str){
        $date1 = date('Y-m-d',$str);
        $date2 = date('H:i:s',$str);
        return $date1.'T'.$date2.'Z';
    }








}