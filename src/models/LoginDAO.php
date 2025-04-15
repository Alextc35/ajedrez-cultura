<?php
require_once '../src/core/Conexion.php';

class LoginDAO
{
    private $table = 'usuarios';

    public function __construct(){}

    public function comprobarUsuario($usuario, $password) : bool {
        try {
            $sql = "SELECT * FROM $this->table WHERE usuario = :usuario";
            $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
            $stmt->execute([':usuario' => $usuario]);

            $aux = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($aux && $password == $aux['password']) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }
    }
}