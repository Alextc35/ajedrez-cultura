<?php

class Handler
{
    public static function descifrarUriBarras()
    {
        // Obtiene solo la ruta sin los parámetros GET
        $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Definir el prefijo base a eliminar (ajusta si es necesario)
        $basePath = "/chess-league/public/";

        // Eliminar el prefijo base de la URI
        if (strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
        }

        // Divide la URI en partes eliminando barras iniciales y finales
        $uriPartida = explode("/", trim($uriPath, "/"));

        // Verifica si "index.php" está en la URL y lo ignora
        if (!empty($uriPartida) && $uriPartida[0] === "index.php") {
            array_shift($uriPartida); // Elimina "index.php" del array
        }

        // Valores por defecto
        if (!isset($_SESSION['usuario'])){
            $defaultController = defined("DEFAULT_CONTROLLER_LOGIN") ? DEFAULT_CONTROLLER_LOGIN : "ControladorLogin";
            $defaultAction = defined("DEFAULT_ACTION_LOGIN") ? DEFAULT_ACTION_LOGIN : "login";
        } else {
            $defaultController = defined("DEFAULT_CONTROLLER") ? DEFAULT_CONTROLLER : "ControladorAlumnos";
            $defaultAction = defined("DEFAULT_ACTION") ? DEFAULT_ACTION : "descripcion";
        }

        // Determinar el controlador y la acción
        $controller = !empty($uriPartida[0]) ? filter_var($uriPartida[0], FILTER_SANITIZE_STRING) : $defaultController;
        $action = !empty($uriPartida[1]) ? filter_var($uriPartida[1], FILTER_SANITIZE_STRING) : $defaultAction;

        // Retornar los valores correctos
        return [
            'controller' => $controller,
            'action' => $action,
            'variables' => $_REQUEST // Parámetros GET/POST
        ];
    }
}
