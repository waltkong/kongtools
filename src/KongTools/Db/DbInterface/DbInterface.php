<?php
namespace KongTools\Db\DbInterface;

interface DbInterface{

    public function connect(array $options);

    public function find(array $where);

    public function select(array $where);

    public function update(array $where,array $data);

    public function insert(array $data);

    public function insertAll(array $data);

    public function delete(array $where);

}