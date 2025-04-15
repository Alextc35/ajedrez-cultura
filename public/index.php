<?php
session_start();

require_once '../src/core/Config.php';
require_once '../src/core/Handler.php';
require_once '../src/core/View.php';

$config = Config::getInstancia();
$arrHandler = Handler::getControllerAction();

# echo "<pre style='color:white;'>HANDLER "; print_r($arrHandler); echo "</pre>";

if (!isset($_SESSION['usuario'])) {
    $controller_path = "../src/controllers/" . $config->getParametro("DEFAULT_CONTROLLER_LOGIN") . ".php";
    if (!file_exists($controller_path)) {
        $arrHandler['controller'] = $config->getParametro('DEFAULT_CONTROLLER_LOGIN');
        $arrHandler['action'] = $config->getParametro('DEFAULT_ACTION_LOGIN');
    }
} else {
    $controller_path = '../src/controllers/' . $arrHandler['controller'] . '.php';
    if (!file_exists($controller_path)) {
        $controller_path = '../src/controllers/' . $config->getParametro('DEFAULT_CONTROLLER') . '.php';
        $arrHandler['controller'] = $config->getParametro('DEFAULT_CONTROLLER');
        $arrHandler['action'] = $config->getParametro('DEFAULT_ACTION');
    }
}

require_once $controller_path;

$controllerName = $arrHandler['controller'];
$controller = new $controllerName();
$action = $arrHandler['action'];

$dataToView['data'] = [];
if (method_exists($controller, $action)) {
    $dataToView['data'] = $controller->{$action}();
}
$dataToView['variables'] =  $arrHandler['variables'];
$dataToView['titulo'] = $controller->page_title;
$dataToView['arrHandler'] = $arrHandler;

View::render($controller->view, $dataToView);

# echo "<pre style='color:white;'>SESION "; print_r($_SESSION); echo "</pre>";