<?php
require_once '../src/core/Config.php';
class Conexion
{
    private static $instancia = null;
    private $conexion;

    private function __construct() {
        $config = Config::getInstancia();

        $host = $config->getParametro('DB_HOST');
        $port = $config->getParametro('DB_PUERTO');
        $dbname = $config->getParametro('DB_NAME');
        $username = $config->getParametro('DB_USER');
        $password = $config->getParametro('DB_PASS');

        try {
            $this->conexion = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstancia() {
        if (Conexion::$instancia === null) {
            Conexion::$instancia = new Conexion();
        }
        return Conexion::$instancia;
    }

    public function getConexion() : PDO {
        return $this->conexion;
    }
}