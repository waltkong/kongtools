<?php
namespace KongTools\Exceptions;

use Throwable;

/**
 * 数据库 过程中抛出异常
 * @package KongTools\Exceptions
 */
class DbException extends \Exception{

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