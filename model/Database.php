<?php

class Database
{
    private $host;
    private $db;
    private $user;
    private $pass;
    public $conection;
    
    public function __construct() {
        $this->host = constant('DB_HOST');
        $this->db = constant('DB_NAME');
        $this->user = constant('DB_USER');
        $this->pass = constant('DB_PASS');

        try {
            $this->conection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass);
            $this->conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error en la base de datos: ' . $e->getMessage();
            exit();
        }
    }
}