<?php
// Iniciar sesión para el manejo de datos temporales
session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// Cargar configuración y modelos
require_once '../config/config.php';
require_once '../app/models/Database.php';

// 📌 Establecer valores predeterminados para el controlador y la acción
$controllerName = constant("DEFAULT_CONTROLLER");
$action = $_GET['action'] ?? constant("DEFAULT_ACTION");

// 📌 Construir la ruta del controlador
$controllerPath = '../app/controllers/' . $controllerName . '.php';

// Cargar el controlador seleccionado
require_once $controllerPath;
$controller = new $controllerName();

// 📌 Verificar si el método (acción) existe en el controlador
$dataToView['data'] = [];
if (method_exists($controller, $action)) {
    $dataToView['data'] = $controller->{$action}();
} else {
    die("Error: Acción '$action' no encontrada en el controlador '$controllerName'.");
}

// 📌 Cargar las vistas
require_once '../app/views/templates/header.php';
require_once '../app/views/' . $controller->view . '.php';
require_once '../app/views/templates/footer.php';
