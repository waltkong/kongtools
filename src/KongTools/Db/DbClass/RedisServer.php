<?php
namespace KongTools\Db\DbClass;

class RedisServer{

    private static $redis;

    public function __construct($options)
    {

        $default_options = [
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
        foreach ($options as $k => $option){
            $default_options[$k] = $option;
        }

        if(empty(self::$redis) || !self::$redis instanceof \Redis){
            $host = $default_options['host'];
            $port = $default_options['port'];
            $redis = new \Redis();
            $redis->connect($host,$port);
            self::$redis = $redis;
        }
    }

    public function getInstance(){
        return self::$redis;
    }

    public function get($key,$dbindex=1){
        $redis = $this->getInstance();
        $redis->select($dbindex);
        return $redis->get($key);
    }

    public function set($key,$val,$dbindex=1){
        $redis = $this->getInstance();
        $redis->select($dbindex);
        return $redis->set($key,$val);
    }

}