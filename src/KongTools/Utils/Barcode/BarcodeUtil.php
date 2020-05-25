<?php
namespace KongTools\Utils\Barcode;

use Picqer\Barcode\BarcodeGeneratorPNG;

/**
 *  条形码
 * Class BarcodeUtil
 * vendor picqer/php-barcode-generator  条形码
 * @package KongTools\Utils\Barcode
 */
class BarcodeUtil{

    /**
     * 获取条码的前台url
     * @param $data  //条码号 唯一标示
     */
    public function getSrc( $data ){
        $generator = new BarcodeGeneratorPNG();
        $src = 'data:image/png;base64,'.base64_encode($generator->getBarcode((string)$data, $generator::TYPE_CODE_128));
        return $src;
    }


}