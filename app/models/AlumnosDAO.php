<?php
require_once 'Conexion.php';
class AlumnosDAO
{
    private $table = 'alumnos';

    public function __construct() {}

    /**
     * 📌 Obtiene todos los alumnos de la base de datos
     * @return array
     */
    public function getAlumnos() {
        $sql = "SELECT * FROM $this->table";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
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
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
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
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
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
    public function addAlumno($nombre, $liga, $victorias = 0, $derrotas = 0, $tablas = 0) {
        $sql = "INSERT INTO $this->table (nombre, liga, victorias, derrotas, tablas)
                VALUES (:nombre, :liga, :victorias, :derrotas, :tablas)";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
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
    public function updateAlumnos($id, $nombre, $victorias, $derrotas, $tablas) {
        $sql = "UPDATE $this->table 
                SET nombre = :nombre, victorias = :victorias, derrotas = :derrotas, tablas = :tablas 
                WHERE id = :id";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':victorias' => $victorias,
            ':derrotas' => $derrotas,
            ':tablas' => $tablas
        ]);
    }

    /**
     * 📌 Elimina un alumno por su ID
     * @param int $id
     * @return bool
     */
    public function deleteAlumno($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * 📌 Actualiza los resultados de un alumno según el resultado de un enfrentamiento
     * @param int $id
     * @param string $resultado ('victoria', 'derrota', 'tablas')
     * @return bool
     */
    public function updateResultado($id, $resultado) {
        if ($resultado === 'victoria') {
            $sql = "UPDATE alumnos SET victorias = victorias + 1 WHERE id = ?";
        } elseif ($resultado === 'derrota') {
            $sql = "UPDATE alumnos SET derrotas = derrotas + 1 WHERE id = ?";
        } elseif ($resultado === 'tablas') {
            $sql = "UPDATE alumnos SET tablas = tablas + 1 WHERE id = ?";
        } else {
            return; // No hacer nada si el resultado es inválido
        }

        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->execute([$id]);
    }

}
