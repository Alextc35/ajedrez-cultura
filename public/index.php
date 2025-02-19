<?php
# 👨‍💻 Iniciar sesión para el manejo de datos temporales
session_start();

# 🔩 Cargar configuración, handler y la base de datos
require_once '../config/config.php';
require_once '../app/controllers/handler/Handler.php';

//unset($_SESSION['usuario']);
//$_SESSION['usuario'] = "usuario";

# 👉 Usar el comportamiento estático de nuestro Handler (`/app/controllers/handler/Handler.php`)
$arrHandler = Handler::descifrarUriBarras();

//echo "<pre style='color:white;'>HANDLER ";
//print_r($arrHandler);
//echo "</pre>";

if (!isset($_SESSION['usuario'])) {
    // Cargar el controlador y la acción por defecto
    $controller_path = '../app/controllers/' . constant('DEFAULT_CONTROLLER_LOGIN') . '.php';
    $arrHandler['controller'] = constant('DEFAULT_CONTROLLER_LOGIN');
    $arrHandler['action'] = constant('DEFAULT_ACTION_LOGIN');
} else {
    $controller_path = '../app/controllers/' . $arrHandler['controller'] . '.php';
    // Verificar que existe el controlador
    if (!file_exists($controller_path)) {
        // Si no existe, cargar controlador y acción por defecto
        $controller_path = '../app/controllers/' . constant('DEFAULT_CONTROLLER') . '.php';
        $arrHandler['controller'] = constant('DEFAULT_CONTROLLER');
        $arrHandler['action'] = constant('DEFAULT_ACTION');
    }
}


# 📌 Cargar el controlador seleccionado
require_once $controller_path;

$controllerName = $arrHandler['controller'];
$controller = new $controllerName(); // Instanciamos el controlador
$action = $arrHandler['action'];

# 📌 Verificar si el método (acción) existe en el controlador
$dataToView['data'] = []; // Inicializamos la variable de datos
// Si el controlador y la acción existen, ejecutamos el método
if (method_exists($controller, $action))
    $dataToView['data'] = $controller->{$action}();
else
    die("Error: Acción '$action' no encontrada en el controlador '$controllerName'.");

# 📌 Cargar las vistas

require_once '../app/views/templates/header.php'; // Header
require_once "../app/views/{$controller->view}.php";
require_once '../app/views/templates/footer.html'; // Footer

//echo "<pre style='color:white;'>SESION ";
//print_r($_SESSION);
//echo "</pre>";