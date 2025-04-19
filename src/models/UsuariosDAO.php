<?php
require_once '../src/core/Conexion.php';

class UsuariosDAO
{
    private $table = 'usuarios';

    public function comprobarUsuario($usuario, $password): bool {
        try {
            $sql = "SELECT * FROM $this->table WHERE usuario = :usuario";
            $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
            $stmt->execute([':usuario' => $usuario]);

            $aux = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($aux && $password == $aux['password'])
                return true;
            else
                return false;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            echo '<div style="text-align: center; margin-top: 100px;">
                    <h1>😓 Ups... algo no va bien</h1>
                    <p>Estamos teniendo problemas técnicos o estamos realizando tareas de mantenimiento.</p>
                    <p>Por favor, vuelve a intentarlo más tarde.</p>
                    <br>
                    <p>Si el problema persiste, puedes contactar con el administrador del sistema.</p>
                  </div>';
            exit;
        }
    }
}