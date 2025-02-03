<?php
require_once '../app/models/AlumnosDAO.php';

class ControladorAlumnos {
    public string $page_title;
    public string $view;
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
    }

    public function descripcion() {
        $this->page_title = 'Chess League | Inicio';
        $this->view = 'descripcion';
    }

    /**
     * 📌 Lista todos los alumnos por categoría
     */
    public function list() {
        $this->page_title = 'Clasificación de Alumnos';
        $liga = $_GET['liga'] ?? null;
        return ($liga) ? $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga)) : $this->alumnosObj->getAlumnos();
    }

    /**
     * 📌 Lista a los alumnos por categoría
     */
    public function listPorliga() {
        // Obtener la categoría de la URL o por defecto "LIGA LOCAL"
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | $liga";
        $this->view = 'clasificacion'; // Usamos la misma vista
    

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }
    
    public function addAlumno() {
        $this->page_title = "Chess League | Añadir alumno";
        $this->view = 'addAlumno';
    }

    /**
     * 📌 Procesa la asignación de resultados de los enfrentamientos
     */
    public function assignResults() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Acceso denegado.");
        }

        $id1s = $_POST['id1'] ?? [];
        $id2s = $_POST['id2'] ?? [];
        $resultados = $_POST['resultados'] ?? [];

        for ($i = 0; $i < count($id1s); $i++) {
            if ($resultados[$i] === '1-0') {
                $this->alumnosObj->updateResultado($id1s[$i], 'victoria');
                $this->alumnosObj->updateResultado($id2s[$i], 'derrota');
            } elseif ($resultados[$i] === '0-1') {
                $this->alumnosObj->updateResultado($id1s[$i], 'derrota');
                $this->alumnosObj->updateResultado($id2s[$i], 'victoria');
            } else {
                $this->alumnosObj->updateResultado($id1s[$i], 'tablas');
                $this->alumnosObj->updateResultado($id2s[$i], 'tablas');
            }
        }

        header("Location: ?controller=ControladorAlumnos&action=list");
        exit();
    }

    /**
     * 📌 Procesa la eliminación de un alumno
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                $this->alumnosObj->deleteAlumno($id);
                header("Location: ?controller=ControladorAlumnos&action=list");
                exit();
            }
        }
    }

    /**
     * 📌 Procesa la actualización de un alumno
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $victorias = intval($_POST['victorias']);
            $derrotas = intval($_POST['derrotas']);
            $tablas = intval($_POST['tablas']);

            if ($id > 0) {
                $this->alumnosObj->updateAlumno($id, $nombre, $victorias, $derrotas, $tablas);
                header("Location: ?controller=ControladorAlumnos&action=list");
                exit();
            }
        }
    }

    /**
     * 📌 Genera los enfrentamientos de manera aleatoria
     */
    public function generateMatches() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Acceso denegado.");
        }

        $ids = $_POST['ids'] ?? [];

        if (count($ids) < 2) {
            die("Debes seleccionar al menos 2 jugadores para enfrentarlos.");
        }

        shuffle($ids);
        $_SESSION['selected_players'] = $ids;
        $_SESSION['liga'] = $_POST['liga'] ?? 'LIGA LOCAL';

        header("Location: ?controller=ControladorAlumnos&action=assignResults");
        exit();
    }
}
