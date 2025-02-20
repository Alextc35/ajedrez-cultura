<?php
# 👨‍💻 Iniciar sesión para el manejo de datos temporales

session_start();

# 🔩 Cargar configuración y el handler

require_once '../config/config.php';
require_once '../app/controllers/handler/Handler.php';

# 👉 Usar el comportamiento estático de nuestro Handler (`/app/controllers/handler/Handler.php`)

$arrHandler = Handler::descifrarUriBarras(); // Obtiene el controlador y la acción de la URI

//echo "<pre style='color:white;'>HANDLER ";
//print_r($arrHandler);
//echo "</pre>";

# 🚹 Comprobamos si está logado

if (!isset($_SESSION['usuario'])) { // Si no está logado
    // Cargar el controlador y la acción por defecto (Variables finales en el archivo de config.php)
    $controller_path = '../app/controllers/' . constant('DEFAULT_CONTROLLER_LOGIN') . '.php';
    $arrHandler['controller'] = constant('DEFAULT_CONTROLLER_LOGIN'); // ControladorLogin
    $arrHandler['action'] = constant('DEFAULT_ACTION_LOGIN'); // login
} else { // Si está logado
    // Cargar el controlador del handler
    $controller_path = '../app/controllers/' . $arrHandler['controller'] . '.php';
    // Verificar que existe el controlador
    if (!file_exists($controller_path)) {
        // Si no existe, cargar controlador y acción por defecto
        $controller_path = '../app/controllers/' . constant('DEFAULT_CONTROLLER') . '.php';
        $arrHandler['controller'] = constant('DEFAULT_CONTROLLER'); // ControladorAlumnos
        $arrHandler['action'] = constant('DEFAULT_ACTION'); // inicio
    }
}

# 📌 Cargar el controlador seleccionado

require_once $controller_path;

$controllerName = $arrHandler['controller']; // Obtenemos el controlador del handler
$controller = new $controllerName(); // Instanciamos el controlador
$action = $arrHandler['action']; // Capturamos la acción del handler

# 📌 Verificar si la acción existe en el controlador

$dataToView['data'] = []; // Inicializamos la variable de datos

// Si el controlador y la acción existen, ejecutamos la acción
if (method_exists($controller, $action))
    $dataToView['data'] = $controller->{$action}(); // Obtenemos posibles datos de la acción

# 📌 Cargar las vistas

require_once '../app/views/templates/header.php'; // Header
require_once "../app/views/{$controller->view}.php"; // Vista dinámica dada por el handler
require_once '../app/views/templates/footer.html'; // Footer

//echo "<pre style='color:white;'>SESION ";
//print_r($_SESSION);
//echo "</pre>";