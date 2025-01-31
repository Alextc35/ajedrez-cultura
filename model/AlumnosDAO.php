<?php
class AlumnosDAO
{
    private $table = 'alumnos';
    private $conection;

    public function __construct() {}

    // Conexión a la base de datos
    public function getConection() {
        $databaseObject = new Database();
        $this->conection = $databaseObject->conection;
    }

    // Devuelve todos los alumnos
    public function getAlumnos() {
        $this->getConection();
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAlumno($id) {
        if(is_null($id)) return false;
        $this->getConection();
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}