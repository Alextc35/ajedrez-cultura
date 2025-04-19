<?php
require_once '../src/core/Conexion.php';

class LogsLoginDAO
{
    private $table = 'logs_login';

    public function registrarLogin(string $usuario): void {
        try {
            $sql = "INSERT INTO $this->table (usuario, ip, user_agent) VALUES (:usuario, :ip, :user_agent)";
            $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
            $stmt->execute([
                ':usuario' => $usuario,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'N/A'
            ]);
        } catch (PDOException $e) {
            error_log("Error al registrar login: " . $e->getMessage());
        }
    }
}