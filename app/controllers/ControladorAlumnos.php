<?php
require_once '../app/models/AlumnosDAO.php';
class ControladorAlumnos {
    public string $page_title;
    public string $view;
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
    }

    public function login() {
        $this->page_title = 'Chess League | Login';
        $this->view = 'pages/login';
    }

    public function signIn() {
        // Credenciales de acceso
        $usuario_valido = USUARIO_VALIDO;
        $password_valida = PASSWORD_VALIDA;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validar credenciales
            if ($usuario === $usuario_valido && $password === $password_valida) {
                $_SESSION['usuario'] = $usuario; // Iniciar sesión
                $this->page_title = 'Chess League | Logado';
                $this->view = 'pages/descripcion'; // Redirigir al área protegida
            } else {
                $this->page_title = 'Chess League | Fallo de autenticación';
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            }
        }
    }

    public function logout() {
        unset($_SESSION['usuario']); // Eliminar la sesión
        $this->page_title = 'Chess League | Sesión cerrada';
        $this->view = 'pages/login'; // Redirigir al login
    }

    public function descripcion() {
        $this->page_title = 'Chess League | Inicio';
        $this->view = 'pages/descripcion';
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
        $this->view = 'alumnos/clasificacion'; // Usamos la misma vista
    

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }
    
    // TODO: Cambiar nombre a viewAddAlumno
    public function addAlumno() {
        $this->page_title = "Chess League | Añadir alumno";
        $this->view = 'alumnos/add';
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
                // $_SESSION['error'] = "El nombre del alumno es obligatorio.";
                header("Location: ?action=addAlumno&liga=" . urlencode($liga));
                exit();
            }
    
            // Insertar en la base de datos
            $this->alumnosObj->addAlumno($nombre, $liga, (int)$victorias, (int)$derrotas, (int)$tablas);
    
            // Redirigir a la lista de la categoría correspondiente
            // $_SESSION['success'] = "Alumno añadido correctamente.";
            header("Location: ?action=listPorLiga&liga=" . urlencode($liga));
            exit();
        }
    
        // Si no es POST, redirigir
        header("Location: ?action=addAlumno&Liga=" . urlencode($liga));
        exit();
    }
    
    // TODO: Cambiar nombre a viewEditAlumnos
    public function editAlumnos() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Editar alumno";
        $this->view = 'alumnos/edit';

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
    
                // Actualizar alumno en la base de datos
                $this->alumnosObj->updateAlumnos($id, $nombre, $vict, $derrot, $tabl);
            }

            // $_SESSION['success'] = "Datos actualizados correctamente.";
            header("Location: ?action=listPorLiga&liga=" . urlencode($liga));
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
                header("Location: ?action=listPorLiga&liga=" . urlencode($liga));
                exit();
            }
        }
    }

    // viewMatch
    public function match() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Enfrentar";
        $this->view = 'alumnos/enfrentar';

        $alumnos = $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));

        if (!$alumnos) {
            // $_SESSION['error'] = "No hay alumnos en esta liga.";
            header("Location: ?action=listPorLiga&liga=" . urlencode($liga));
            exit();
        }
        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }

/**
 * 📌 Genera los enfrentamientos de manera aleatoria y los envía a la vista assign.php
 */
public function generateMatches() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("Acceso denegado.");
    }

    $liga = $_POST['liga'] ?? 'LIGA LOCAL';
    $ids = $_POST['ids'] ?? [];

    if (count($ids) < 2) {
        // $_SESSION['error'] = "Debes seleccionar al menos 2 jugadores.";
        header("Location: ?action=match&liga=" . urlencode($liga));
        exit();
    }

    shuffle($ids); // Mezclar los jugadores aleatoriamente

    // 📌 Guardar solo ID y Nombre en $_SESSION
    $jugadores = [];
    foreach ($ids as $id) {
        $alumno = $this->alumnosObj->getAlumno($id); // Obtener alumno por ID
        if ($alumno) {
            $jugadores[$id] = $alumno['nombre']; // Guardar solo el nombre con su ID
        }
    }

    if (empty($jugadores)) {
        // $_SESSION['error'] = "No se encontraron jugadores seleccionados.";
        header("Location: ?action=match&liga=" . urlencode($liga));
        exit();
    }

    // 📌 Guardar en $_SESSION solo ID y nombre
    $_SESSION['jugadores'] = $jugadores;
    $_SESSION['liga'] = $liga;

    // 📌 Enviar los datos a la vista assign.php
    $this->page_title = 'Asignar Enfrentamientos';
    $this->view = 'alumnos/assign';

    return ['jugadores' => $jugadores, 'liga' => $liga];
}
    /**
     * 📌 Procesa la asignación de resultados de los enfrentamientos
     */
    public function assignResults() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Acceso denegado.");
        }

        $liga = $_POST['liga'] ?? 'LIGA LOCAL';
        $id1s = $_POST['id1'] ?? [];
        $id2s = $_POST['id2'] ?? [];
        $resultados = $_POST['resultados'] ?? [];

        if (empty($id1s) || empty($id2s) || empty($resultados)) {
            // $_SESSION['error'] = "No se recibieron datos válidos.";
            header("Location: ?action=match&liga=" . urlencode($liga));
            exit();
        }

        // 📌 Recorrer todos los enfrentamientos y actualizar la base de datos
        for ($i = 0; $i < count($id1s); $i++) {
            $id1 = intval($id1s[$i]);
            $id2 = intval($id2s[$i]);
            $resultado = $resultados[$i];

            if ($resultado === '1-0') { // Ganan blancas
                $this->alumnosObj->updateResultado($id1, 'victoria');
                $this->alumnosObj->updateResultado($id2, 'derrota');
            } elseif ($resultado === '0-1') { // Ganan negras
                $this->alumnosObj->updateResultado($id1, 'derrota');
                $this->alumnosObj->updateResultado($id2, 'victoria');
            } elseif ($resultado === '1-1') { // Tablas
                $this->alumnosObj->updateResultado($id1, 'tablas');
                $this->alumnosObj->updateResultado($id2, 'tablas');
            }
        }

        // $_SESSION['success'] = "Resultados guardados correctamente.";
        header("Location: ?action=listPorLiga&liga=" . urlencode($liga));
        exit();
    }
}
