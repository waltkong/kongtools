<?php
namespace KongTools\Db\DbClass;

use KongTools\Exceptions\DbException;
class WorkermanMysql
{
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
            self::$db =  new \Workerman\MySQL\Connection($default_options['server'],
                $default_options['port'],
                $default_options['username'],
                $default_options['password'],
                $default_options['database_name']);
        }catch (\Exception $e){
            throw new DbException('connect error');
        }

        return self::$db;
    }


    public function getInstance(){
        return self::$db;
    }

}