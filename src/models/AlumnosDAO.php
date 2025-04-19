<?php
require_once '../src/core/Conexion.php';

class AlumnosDAO
{
    private $table = 'alumnos';

    public function __construct() {}

    public function getAlumnos() {
        $sql = "SELECT * FROM $this->table";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlumnosConPagos() {
        $conexion = Conexion::getInstancia()->getConexion();
    
        $sql = "SELECT id, nombre, anio_nacimiento, liga FROM $this->table";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $meses = ['Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
    
        foreach ($alumnos as &$alumno) {
            $sqlPagos = "SELECT mes, anio, pagado FROM pagos_mensuales WHERE alumno_id = ?";
            $stmtPagos = $conexion->prepare($sqlPagos);
            $stmtPagos->execute([$alumno['id']]);
    
            $pagos = array_fill_keys($meses, null); // Iniciar todos los meses con null
    
            foreach ($stmtPagos->fetchAll(PDO::FETCH_ASSOC) as $pago) {
                $pagos[$pago['mes']] = (bool) $pago['pagado'];
            }
    
            $alumno['pagos'] = $pagos;
        }
    
        return $alumnos;
    }    

    public function getAlumnosPorLiga($liga) {
        $sql = "SELECT id, nombre FROM alumnos WHERE liga = ?";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->execute([$liga]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAlumno($id) {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAlumno($nombre, $anio = null, $liga, $victorias = 0, $derrotas = 0, $tablas = 0) {
        $sql = "INSERT INTO $this->table (nombre, anio_nacimiento, liga)
                VALUES (:nombre, :anio, :liga)";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':anio' => $anio,
            ':liga' => $liga
        ]);
    }

    public function updateAlumno($id, $nombre, $anio_nacimiento, $liga) {
        $sql = "UPDATE $this->table 
                SET nombre = :nombre, anio_nacimiento = :anio_nacimiento, liga = :liga 
                WHERE id = :id";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':anio_nacimiento' => $anio_nacimiento,
            ':liga' => $liga
        ]);
    }    

    public function deleteAlumno($id) {
        $conexion = Conexion::getInstancia()->getConexion();
    
        try {
            $conexion->beginTransaction();
    
            // 1. Eliminar pagos del alumno
            $sqlPagos = "DELETE FROM pagos_mensuales WHERE alumno_id = :id";
            $stmtPagos = $conexion->prepare($sqlPagos);
            $stmtPagos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPagos->execute();

            // Eliminar enfrentamientos donde participe como alumno1 o alumno2
            $sqlEnfrentamientos = "DELETE FROM enfrentamientos WHERE alumno1_id = :id OR alumno2_id = :id";
            $stmtEnfrentamientos = $conexion->prepare($sqlEnfrentamientos);
            $stmtEnfrentamientos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtEnfrentamientos->execute();

    
            // 2. Eliminar al alumno
            $sqlAlumno = "DELETE FROM $this->table WHERE id = :id";
            $stmtAlumno = $conexion->prepare($sqlAlumno);
            $stmtAlumno->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtAlumno->execute();
    
            $conexion->commit();
            return true;
        } catch (PDOException $e) {
            $conexion->rollBack();
            throw $e;
        }
    }

    /**
     * ✅ Guarda o actualiza el pago mensual de un alumno
     */
    public function guardarPagoMensual($alumnoId, $mes, $anio, $pagado) {
        $conexion = Conexion::getInstancia()->getConexion();
        // Comprobar si existe el registro
        $checkSql = "SELECT COUNT(*) FROM pagos_mensuales WHERE alumno_id = ? AND mes = ? AND anio = ?";
        $check = $conexion->prepare($checkSql);
        $check->execute([$alumnoId, $mes, $anio]);
        $existe = $check->fetchColumn();

        if ($existe) {
            // Update
            $sql = "UPDATE pagos_mensuales SET pagado = :pagado WHERE alumno_id = :alumno_id AND mes = :mes AND anio = :anio";
        } else {
            // Insert
            $sql = "INSERT INTO pagos_mensuales (alumno_id, mes, anio, pagado) VALUES (:alumno_id, :mes, :anio, :pagado)";
        }

        $stmt = $conexion->prepare($sql);
        return $stmt->execute([
            ':alumno_id' => $alumnoId,
            ':mes' => $mes,
            ':anio' => $anio,
            ':pagado' => $pagado ? 1 : 0
        ]);
    }
}