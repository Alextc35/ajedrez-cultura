<?php
require_once '../config/config.php';
class Conexion
{
    private static $instancia = null;
    private $conexion;

    private function __construct() {
        // parámetros de la BD
        $host = constant('DB_HOST');
        $dbname = constant('DB_NAME');
        $username = constant('DB_USER');
        $password = constant('DB_PASS');

        try {
            $this->conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit("Error al conectar a la base de datos: " . $e->getMessage()); // si no me puedo conectar salgo del programa
        }
    }

    // si no existe la instancia se crea y se referencia por la clase
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