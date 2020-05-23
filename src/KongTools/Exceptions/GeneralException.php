<?php
namespace KongTools\Exceptions;

use Throwable;

/**
 * 如果不知道返回什么报错，就抛出这个类
 * @package KongTools\Exceptions
 */
class GeneralException extends \Exception{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function  __toString()
    {
        return $this->getMessage();
    }

    /**
     * Report the exception.

     */
//    public function render(){
//
//    }


}