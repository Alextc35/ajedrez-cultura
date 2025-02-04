<?php
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

// 📌 Depuración: Mostrar método recibido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso denegado. Método recibido: " . $_SERVER['REQUEST_METHOD']);
}

$alumnosDAO = new AlumnosDAO();

$categoria = $_POST['categoria'] ?? 'LIGA LOCAL'; // Valor por defecto si no está definida
$id1s = $_POST['id1'];
$id2s = $_POST['id2'];
$resultados = $_POST['resultados'];

for ($i = 0; $i < count($id1s); $i++) {
    if ($resultados[$i] === '1-0') {
        $alumnosDAO->updateResultado($id1s[$i], 'victoria');
        $alumnosDAO->updateResultado($id2s[$i], 'derrota');
    } elseif ($resultados[$i] === '0-1') {
        $alumnosDAO->updateResultado($id1s[$i], 'derrota');
        $alumnosDAO->updateResultado($id2s[$i], 'victoria');
    } else {
        $alumnosDAO->updateResultado($id1s[$i], 'tablas');
        $alumnosDAO->updateResultado($id2s[$i], 'tablas');
    }
}

if ($categoria === 'LIGA LOCAL')
    $categoria = 'ligaLocal';
else
    $categoria = 'ligaInfantil';

header("Location: /chess-league/public/?controller=ControladorAlumnos&action=$categoria");
exit();

