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
    
}