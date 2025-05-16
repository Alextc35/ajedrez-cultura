<?php
require_once '../src/models/AlumnosDAO.php';
class ControladorAlumnos
{
    public string $page_title = "";
    public string $view = "";
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
    }

    public function listAlumnos() {
        $this->page_title = "Ajedrez Cultura | Alumnos";
        $this->view = 'alumnos/listAlumnos';

        return $this->alumnosObj->getAlumnosConPagos() ?? [];
    }

    public function addAlumno() {
        $this->page_title = "Ajedrez Cultura | Añadir alumno";
        $this->view = 'alumnos/addAlumno';
    }

    public function insertAlumno() {
        $config = Config::getInstancia();
        $liga = $_POST['liga'] ?? 'Local';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $anio = $_POST['anio_nacimiento'] ?? null;
            $anio = ($anio === '' || $anio === null) ? null : (int)$anio;            
    
            if (empty($nombre)) {
                header("Location: ?action=addAlumno&liga=" . urlencode($liga));
                exit();
            }
    
            $ok = $this->alumnosObj->addAlumno($nombre, (int)$anio, $liga);
    
            if ($ok) {
                $conexion = Conexion::getInstancia()->getConexion();
                $alumnoId = $conexion->lastInsertId();
    
                $meses = [
                    'Octubre' => 2024, 'Noviembre' => 2024, 'Diciembre' => 2024,
                    'Enero' => 2025, 'Febrero' => 2025, 'Marzo' => 2025,
                    'Abril' => 2025, 'Mayo' => 2025, 'Junio' => 2025
                ];
    
                foreach ($meses as $mes => $anioMes) {
                    $this->alumnosObj->guardarPagoMensual($alumnoId, $mes, $anioMes, false);
                }
            }
    
            header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos");
            exit();
        }
    
        header("Location: ?action=addAlumno&liga=" . urlencode($liga));
        exit();
    }
       
    
    public function editAlumnos() {
        $this->page_title = "Ajedrez Cultura | Editar alumno";
        $this->view = 'alumnos/editAlumnos';

        return $this->alumnosObj->getAlumnos();
    }

    public function updateAlumnos() {
        $config = Config::getInstancia();
        $liga = $_POST['liga'] ?? 'Local';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['id'] ?? [];
            $nombres = $_POST['nombre'] ?? [];
            $anios = $_POST['anio_nacimiento'] ?? [];
            $ligas = $_POST['liga'] ?? [];
    
            if (empty($ids)) {
                header("Location: ?action=editAlumnos&liga=" . urlencode($liga));
                exit();
            }
    
            for ($i = 0; $i < count($ids); $i++) {
                $id = intval($ids[$i]);
                $nombre = htmlspecialchars(trim($nombres[$i]));
                $anio = intval($anios[$i]);
                $ligaAlumno = in_array($ligas[$i], ['Local', 'Infantil']) ? $ligas[$i] : 'Local'; // validación básica
    
                $this->alumnosObj->updateAlumno($id, $nombre, $anio, $ligaAlumno);
            }
    
            header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos");
            exit();
        }
    
        die("Acceso denegado.");
    }    

    public function deleteAlumno() {
        $config = Config::getInstancia();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                $this->alumnosObj->deleteAlumno($id);
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos");
                exit();
            }
        }
    }

    public function guardarPagos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = $_POST['pagos_data'] ?? '';
    
            $data = json_decode($json, true);
    
            // Validación básica del JSON
            if (!is_array($data)) {
                // Redirigir con error si el JSON no es válido
                header("Location: " . Config::getInstancia()->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos&error=json");
                exit();
            }

            foreach ($data as $pago) {
                $alumnoId = intval($pago['alumno_id']);
                $mes = $pago['mes'];
                $anio = intval($pago['anio']);
                $pagado = boolval($pago['pagado']);
    
                $this->alumnosObj->guardarPagoMensual($alumnoId, $mes, $anio, $pagado);
            }
    
            // Redirigir con mensaje de éxito
            header("Location: " . Config::getInstancia()->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos");
            exit();
        }
    
        // Si se intenta acceder por GET, redirigir
        header("Location: " . Config::getInstancia()->getParametro('DEFAULT_INDEX') . "ControladorAlumnos/listAlumnos&error=method");
        exit();
    }
}