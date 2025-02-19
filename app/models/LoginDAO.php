<?php
require_once 'Conexion.php';
/*
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT,
    usuario VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO usuarios (usuario, password)
VALUES ('profesor', MD5('ligamejorada33'));
*/
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
                return true; // Usuario y contraseña válidos
            } else {
                return false; // Credenciales incorrectas
            }
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }
    }
}