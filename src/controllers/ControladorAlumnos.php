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

    public function addAlumno() {
        $this->page_title = "Chess League | Añadir alumno";
        $this->view = 'alumnos/addAlumno';
    }

    public function insertAlumno() {
        $config = Config::getInstancia();
        $liga = $_POST['liga'] ?? 'LIGA LOCAL';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $victorias = $_POST['victorias'] ?? 0;
            $derrotas = $_POST['derrotas'] ?? 0;
            $tablas = $_POST['tablas'] ?? 0;

            if (empty($nombre)) {
                // $_SESSION['error'] = "El nombre del alumno es obligatorio.";
                header("Location: ?action=addAlumno&liga=" . urlencode($liga));
                exit();
            }

            $this->alumnosObj->addAlumno($nombre, $liga, (int)$victorias, (int)$derrotas, (int)$tablas);

            // $_SESSION['success'] = "Alumno añadido correctamente.";
            header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
            exit();
        }

        header("Location: ?action=addAlumno&Liga=" . urlencode($liga));
        exit();
    }

    public function editAlumnos() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Editar alumno";
        $this->view = 'alumnos/editAlumnos';

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }

    public function updateAlumnos() {
        $config = Config::getInstancia();
        $liga = $_POST['liga'] ?? 'LIGA LOCAL';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['id'] ?? [];
            $nombres = $_POST['nombre'] ?? [];
            $victorias = $_POST['victorias'] ?? [];
            $derrotas = $_POST['derrotas'] ?? [];
            $tablas = $_POST['tablas'] ?? [];

            if (empty($ids)) {
                // $_SESSION['error'] = "No se enviaron datos para actualizar.";
                header("Location: ?action=editAlumnos&liga=" . urlencode($liga));
                exit();
            }

            for ($i = 0; $i < count($ids); $i++) {
                $id = intval($ids[$i]);
                $nombre = htmlspecialchars($nombres[$i]);
                $vict = intval($victorias[$i]);
                $derrot = intval($derrotas[$i]);
                $tabl = intval($tablas[$i]);

                $this->alumnosObj->updateAlumnos($id, $nombre, $vict, $derrot, $tabl);
            }

            // $_SESSION['success'] = "Datos actualizados correctamente.";
            header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
            exit();
        }
        die("Acceso denegado.");
    }

    public function deleteAlumno() {
        $config = Config::getInstancia();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $liga = $_GET['liga'] ?? 'LIGA LOCAL';
            if ($id > 0) {
                $this->alumnosObj->deleteAlumno($id);
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
                exit();
            }
        }
    }
}