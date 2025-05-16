<?php

require_once '../src/core/Conexion.php';

class EnfrentamientosDAO
{
    private $db;
    private $table = 'enfrentamientos';

    public function __construct()
    {
        $this->db = Conexion::getInstancia()->getConexion();
    }

    /**
     * Obtiene todos los enfrentamientos de un torneo específico
     * @param int $torneo_id
     * @return array
     */
    public function getEnfrentamientosPorTorneo($torneo_id)
    {
        $sql = "SELECT * FROM $this->table WHERE torneo_id = :torneo_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':torneo_id', $torneo_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo enfrentamiento
     */
    public function crearEnfrentamiento($torneo_id, $alumno1_id, $alumno2_id, $resultado, $fecha) {
        $sql = "INSERT INTO enfrentamientos (torneo_id, alumno1_id, alumno2_id, resultado, fecha)
                VALUES (:torneo_id, :alumno1_id, :alumno2_id, :resultado, :fecha)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':torneo_id' => $torneo_id,
            ':alumno1_id' => $alumno1_id,  // puede ser null
            ':alumno2_id' => $alumno2_id,  // puede ser null
            ':resultado' => $resultado,
            ':fecha' => $fecha
        ]);
    }
    
    

    /**
     * Elimina todos los enfrentamientos de un torneo
     */
    public function eliminarEnfrentamientosPorTorneo($torneo_id)
    {
        $sql = "DELETE FROM $this->table WHERE torneo_id = :torneo_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':torneo_id', $torneo_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Elimina los enfrentamientos donde participa un alumno (como jugador 1 o 2)
     */
    public function eliminarEnfrentamientosPorAlumno($alumno_id)
    {
        $sql = "DELETE FROM $this->table WHERE alumno1_id = :id OR alumno2_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $alumno_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getHistorialPorTorneo($torneoId) {
        $sql = "SELECT alumno1_id, alumno2_id, resultado, fecha 
                FROM $this->table 
                WHERE torneo_id = :torneo_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':torneo_id', $torneoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
