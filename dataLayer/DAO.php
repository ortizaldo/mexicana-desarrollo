<?php

class DAO {
   var $server='localhost';
    var $admin='root';
    var $pass='h4t5un3m1ku86';
    var $base='mexicanaDes';
    function __Construct() {
        
    }
    public function getConnect() {
        $connection = new mysqli($this->server, $this->admin, $this->pass, $this->base);
        $charset = 'utf8';
        $connection->set_charset($charset);
        
        return $connection;
    }
}