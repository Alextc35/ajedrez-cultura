<?php
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

// 📌 Verificar que el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($_POST['categoria'] === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';
    header("Location: /chess-league/public?controller=ControladorAlumnos&action=$categoria");
}

$alumnosDAO = new AlumnosDAO();

// 📌 Obtener datos del formulario
$nombre = trim(htmlspecialchars($_POST['nombre'] ?? ''));
$categoria = $_POST['categoria'] ?? 'LIGA LOCAL';
$victorias = max(0, intval($_POST['victorias'] ?? 0));
$derrotas = max(0, intval($_POST['derrotas'] ?? 0));
$tablas = max(0, intval($_POST['tablas'] ?? 0));

// 📌 Validar que el nombre no esté vacío
if (empty($nombre)) {
    die("El nombre del alumno no puede estar vacío.");
}

// 📌 Insertar en la base de datos
try {
    $alumnosDAO->addAlumno($nombre, $categoria, $victorias, $derrotas, $tablas);
    if ($_POST['categoria'] === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';
    header("Location: /chess-league/public?controller=ControladorAlumnos&action=$categoria"); // 📌 Redirigir a la lista
    exit();
} catch (Exception $e) {
    die("Error al insertar el alumno: " . $e->getMessage());
}
