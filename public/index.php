<?php
# 📌 Iniciar sesión para el manejo de datos temporales
session_start();

# 📌 Cargar configuración y la base de datos
require_once '../config/config.php';
require_once '../app/models/Database.php';

# 📌 Establecer valores predeterminados para el controlador y la acción
$controllerName = constant("DEFAULT_CONTROLLER"); // Controlador oculto
$action = $_GET['action'] ?? constant("DEFAULT_ACTION"); // `action` se muestra en la petición GET

// Construir la ruta del controlador
$controllerPath = '../app/controllers/' . $controllerName . '.php';

# 📌 Cargar el controlador seleccionado
require_once $controllerPath; // /chess-league/app/controllers/ControladorAlumnos.php
$controller = new $controllerName(); // Instanciamos el controlador

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