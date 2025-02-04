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
    public function listPorLiga() {
        // Obtener la categoría de la URL o por defecto "LIGA LOCAL"
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | $liga";
        $this->view = 'clasificacion'; // Usamos la misma vista
    

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }
    
    // TODO: Cambiar nombre a viewAddAlumno
    public function addAlumno() {
        $this->page_title = "Chess League | Añadir alumno";
        $this->view = 'addAlumno';
    }

    public function insertAlumno() { 
        $liga = $_POST['liga'] ?? 'LIGA LOCAL';   
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $victorias = $_POST['victorias'] ?? 0;
            $derrotas = $_POST['derrotas'] ?? 0;
            $tablas = $_POST['tablas'] ?? 0;
    
            // Validación básica
            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre del alumno es obligatorio.";
                header("Location: ?controller=ControladorAlumnos&action=addAlumno&liga=" . urlencode($liga));
                exit();
            }
    
            // Insertar en la base de datos
            $this->alumnosObj->addAlumno($nombre, $liga, (int)$victorias, (int)$derrotas, (int)$tablas);
    
            // Redirigir a la lista de la categoría correspondiente
            $_SESSION['success'] = "Alumno añadido correctamente.";
            header("Location: ?controller=ControladorAlumnos&action=listPorLiga&liga=" . urlencode($liga));
            exit();
        }
    
        // Si no es POST, redirigir
        header("Location: ?controller=ControladorAlumnos&action=addAlumno&Liga=" . urlencode($liga));
        exit();
    }
    
    // TODO: Cambiar nombre a viewEditAlumnos
    public function editAlumnos() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Editar alumno";
        $this->view = 'editAlumnos';

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }

    /**
     * 📌 Procesa la actualización de un alumno
     */
    public function updateAlumnos() {
        $liga = $_POST['liga'] ?? 'LIGA LOCAL';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['id'] ?? [];
            $nombres = $_POST['nombre'] ?? [];
            $victorias = $_POST['victorias'] ?? [];
            $derrotas = $_POST['derrotas'] ?? [];
            $tablas = $_POST['tablas'] ?? [];

            if (empty($ids)) {
                $_SESSION['error'] = "No se enviaron datos para actualizar.";
                header("Location: ?controller=ControladorAlumnos&action=editAlumnos&liga=" . urlencode($liga));
                exit();
            }

            for ($i = 0; $i < count($ids); $i++) {
                $id = intval($ids[$i]);
                $nombre = htmlspecialchars($nombres[$i]);
                $vict = intval($victorias[$i]);
                $derrot = intval($derrotas[$i]);
                $tabl = intval($tablas[$i]);
    
                // Actualizar alumno en la base de datos
                $this->alumnosObj->updateAlumnos($id, $nombre, $vict, $derrot, $tabl);
            }

            $_SESSION['success'] = "Datos actualizados correctamente.";
            header("Location: ?controller=ControladorAlumnos&action=listPorLiga&liga=" . urlencode($liga));
            exit();
        }
        die("Acceso denegado.");
    }

    /**
     * 📌 Procesa la eliminación de un alumno
     */
    public function deleteAlumno() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $liga = $_GET['liga'] ?? 'LIGA LOCAL';
            if ($id > 0) {
                $this->alumnosObj->deleteAlumno($id);
                header("Location: ?controller=ControladorAlumnos&action=listPorLiga&liga=" . urlencode($liga));
                exit();
            }
        }
    }

    // viewMatch
    public function match() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Enfrentar";
        $this->view = 'match';

        $alumnos = $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));

        if (!$alumnos) {
            $_SESSION['error'] = "No hay alumnos en esta liga.";
            header("Location: ?controller=ControladorAlumnos&action=listPorLiga&liga=" . urlencode($liga));
            exit();
        }
        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
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
}
