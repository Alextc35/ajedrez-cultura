<?php
session_start();
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso denegado.");
}

$ids = $_POST['ids'] ?? [];

// 📌 Verificar que haya al menos 2 jugadores seleccionados
if (count($ids) < 2) {
    die("Debes seleccionar al menos 2 jugadores para enfrentarlos.");
}

// 📌 Mezclar aleatoriamente los jugadores seleccionados
shuffle($ids);

// 📌 Guardar los jugadores seleccionados en sesión para la siguiente pantalla
$_SESSION['selected_players'] = $ids;
$_SESSION['categoria'] = $_POST['categoria'] ?? 'LIGA LOCAL';

// 📌 Redirigir a la pantalla de asignación de resultados
header("Location: assign_results.php");
exit();
