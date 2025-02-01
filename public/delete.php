<?php
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

// 📌 Verificar que la petición es GET y que se envió un ID
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    if ($dataToView['categoria'] === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';
    header("Location: /chess-league/public?controller=ControladorAlumnos&action=$categoria");
}

$alumnosDAO = new AlumnosDAO();
$id = intval($_GET['id']);

// 📌 Verificar que el ID es válido
if ($id <= 0) {
    die("ID no válido.");
}

// 📌 Intentar eliminar al alumno
try {
    $alumnosDAO->deleteAlumno($id);
    header("Location: ?controller=ControladorAlumnos&action=inicio"); // 📌 Redirigir a la lista después de eliminar
    exit();
} catch (Exception $e) {
    die("Error al eliminar el alumno: " . $e->getMessage());
}
