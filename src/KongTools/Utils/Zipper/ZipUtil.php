<?php
namespace KongTools\Utils\Zipper;

use Chumper\Zipper\Facades\Zipper;

/**
 *  压缩工具
 *  vendor "chumper/zipper": "1.0.x"
 * */
class ZipUtil
{
    /**
     * 将 $folder 目录下的文件全部打包压缩
     *
     * @param $folder string 需要压缩的目录  ‘/’结尾
     * @param $output_folder  string 输出目录   ‘/’结尾
     * @param $filename  string 文件名称
     * @return mixed 返回打包后的文件全路径
     */
    public function zip( $folder ,$output_folder , $filename=''){
        $files = glob($folder.'*');    //$folder='public/files/'

        if(!is_dir($output_folder)){
            mkdir($output_folder,0755,1);
        }

        $fname = empty($filename) ? date('YmdHis').rand(1000,9999) : '';

        $zipFullName = $output_folder.$fname.'.zip';

        Zipper::make($zipFullName)->add($files)->close();

        return $zipFullName;
    }

}