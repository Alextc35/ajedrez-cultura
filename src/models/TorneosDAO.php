<?php
require_once '../src/core/Conexion.php';

class TorneosDAO
{
    private string $tabla = 'torneos';

    // Obtener todos los torneos
    public function getTodos(): array {
        try {
            $sql = "SELECT * FROM $this->tabla ORDER BY fecha_inicio DESC";
            $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener torneos: " . $e->getMessage());
            return [];
        }
    }

    public function getTorneoPorId($id) {
        $sql = "SELECT * FROM $this->tabla WHERE id = :id LIMIT 1";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener torneo por nombre + liga
    public function getTorneoPorNombreYLiga(string $nombre, string $liga): ?array {
        try {
            $sql = "SELECT * FROM $this->tabla WHERE nombre = :nombre AND liga = :liga LIMIT 1";
            $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':liga' => $liga
            ]);
            $torneo = $stmt->fetch(PDO::FETCH_ASSOC);
            return $torneo ?: null;
        } catch (PDOException $e) {
            error_log("Error al buscar torneo: " . $e->getMessage());
            return null;
        }
    }

    public function getLigaPorTorneoID($id) {
        $sql = "SELECT * FROM torneos WHERE id = :id LIMIT 1";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['liga'];
    }
    
}
