<?php

class AlumnosDAO
{
    private $table = 'alumnos';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * 📌 Obtiene todos los alumnos de la base de datos
     * @return array
     */
    public function getAlumnos() {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->conection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 📌 Obtiene los alumnos por categoría (LIGA LOCAL o LIGA INFANTIL)
     * @param string $liga
     * @return array
     */
    public function getAlumnosPorLiga($liga) {
        $sql = "SELECT * FROM $this->table WHERE liga = :liga";
        $stmt = $this->db->conection->prepare($sql);
        $stmt->bindParam(':liga', $liga, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 📌 Obtiene los datos de un alumno por su ID
     * @param int $id
     * @return array|false
     */
    public function getAlumno($id) {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->db->conection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 📌 Inserta un nuevo alumno en la base de datos
     * @param string $nombre
     * @param string $liga
     * @param int $victorias
     * @param int $derrotas
     * @param int $tablas
     * @return bool
     */
    public function insertAlumno($nombre, $liga, $victorias = 0, $derrotas = 0, $tablas = 0) {
        $sql = "INSERT INTO $this->table (nombre, liga, victorias, derrotas, tablas)
                VALUES (:nombre, :liga, :victorias, :derrotas, :tablas)";
        $stmt = $this->db->conection->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':liga' => $liga,
            ':victorias' => $victorias,
            ':derrotas' => $derrotas,
            ':tablas' => $tablas
        ]);
    }

    /**
     * 📌 Actualiza los datos de un alumno
     * @param int $id
     * @param string $nombre
     * @param int $victorias
     * @param int $derrotas
     * @param int $tablas
     * @return bool
     */
    public function updateAlumno($id, $nombre, $victorias, $derrotas, $tablas) {
        $sql = "UPDATE $this->table 
                SET nombre = :nombre, victorias = :victorias, derrotas = :derrotas, tablas = :tablas 
                WHERE id = :id";
        $stmt = $this->db->conection->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':victorias' => $victorias,
            ':derrotas' => $derrotas,
            ':tablas' => $tablas
        ]);
    }

    /**
     * 📌 Actualiza los resultados de un alumno según el resultado de un enfrentamiento
     * @param int $id
     * @param string $resultado ('victoria', 'derrota', 'tablas')
     * @return bool
     */
    public function updateResultado($id, $resultado) {
        $campo = ($resultado === 'victoria') ? 'victorias' : (($resultado === 'derrota') ? 'derrotas' : 'tablas');
        $sql = "UPDATE $this->table SET $campo = $campo + 1 WHERE id = :id";
        $stmt = $this->db->conection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * 📌 Elimina un alumno por su ID
     * @param int $id
     * @return bool
     */
    public function deleteAlumno($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->db->conection->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
