<?php
namespace KongTools\Db\DbClass;

use KongTools\Db\DbInterface\DbInterface;
use KongTools\Exceptions\DbException;
use Medoo\Medoo;

/**
 *  使用 meedo可以轻便操作数据库，具体操作见文档 "https://medoo.lvtao.net/1.2/doc.php"
 * Class Mysql
 * @package KongTools\Db\DbClass
 */
class MedooMysql{

    static $db;

    public function __construct()
    {

    }

    public function connect(array $options)
    {
        $default_options = [
            'database_type' => 'mysql',
            'database_name' => 'default',
            'server' => '127.0.0.1',
            'port' => '3306',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'prefix' => '',
        ];

        foreach ($options as $k => $option){
            $default_options[$k] = $option;
        }

        try{
            self::$db =  new Medoo($default_options);
        }catch (\Exception $e){
            throw new DbException('connect error');
        }

        return self::$db;
    }



}