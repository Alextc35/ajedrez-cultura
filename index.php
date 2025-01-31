<?php
include 'config/config.php';
include 'model/Database.php';

if(!isset($_GET['controller']))
    $_GET['controller'] = constant("DEFAULT_CONTROLLER");
if(!isset($_GET['action']))
    $_GET['action'] = constant("DEFAULT_ACTION");

$controller_path = 'controller/' . $_GET['controller'] . '.php';

// Comprobamos que el controlador exista
if(!file_exists($controller_path)) {
    $controller_path = 'controller/' . constant("DEFAULT_CONTROLLER") . '.php';
    $_GET['controller'] = constant("DEFAULT_CONTROLLER");
    $_GET['action'] = constant("DEFAULT_ACTION");
}

// Cargamos el controlador
require_once $controller_path;
$controllerName = $_GET['controller'];
$controller = new $controllerName();

// Check if method is defined
$dataToView['data'] = array();

if (method_exists($controller, $_GET['action']))
    $dataToView['data'] = $controller->{$_GET['action']}();

// Cargamos la vista
require_once 'view/templates/header.php';
require_once 'view/' . $controller->view . '.php';
require_once 'view/templates/footer.php';
?>