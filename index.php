<?php
include '/model/Database.php';
include '/controller/ControladorAlumnos.php';

//
$_GET['controller'] = 'ControladorAlumnos';
//

$controller_path = 'controller/' . $_GET['controller'] . '.php';

// Cargamos el controlador
require_once $controller_path;
$controllerName = $_GET['controller'];
$controller = new $controllerName();

// Cargamos la vista
require_once '/view/templates/header.php';
require_once 'view/' . $controller->view . '.php';
require_once '/view/templates/footer.php';
?>