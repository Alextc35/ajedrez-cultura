<?php

class AlumnosDAO
{
    private $table = 'alumnos';
    private $db;

    public function __construct() {}

    // Conexión a la base de datos
    public function getConection() {
        $databaseObject = new Database();
        $this->db = $databaseObject->conection;
    }

    // Devuelve todos los alumnos
    public function getAlumnos() {
        $this->getConection();
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateAlumno($id, $nombre, $victorias, $derrotas, $tablas) {
        $this->getConection();
        $sql = "UPDATE alumnos SET nombre = ?, victorias = ?, derrotas = ?, tablas = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre, $victorias, $derrotas, $tablas, $id]);
    }
    
    public function addAlumno($nombre, $categoria, $victorias, $derrotas, $tablas) {
        $this->getConection();
        try {
            $sql = "INSERT INTO alumnos (nombre, categoria, victorias, derrotas, tablas) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nombre, $categoria, $victorias, $derrotas, $tablas]);
        } catch (PDOException $e) {
            die("Error al insertar alumno: " . $e->getMessage());
        }
    }

    public function deleteAlumno($id) {
        $this->getConection();
        try {
            $sql = "DELETE FROM alumnos WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            die("Error al eliminar alumno: " . $e->getMessage());
        }
    }
    
}