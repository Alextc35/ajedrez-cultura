<?php
/**
 * Clase Handler
 * ------------------------
 * Encargada de interpretar la URI actual para determinar:
 * - El controlador a ejecutar
 * - La acción (método) a invocar
 * - Y las variables de entrada (GET/POST combinadas)
 *
 * - Elimina el path base del proyecto (/ajedrez-cultura/public/)
 * - Soporta rutas del tipo /controlador/accion
 * - Aplica control de acceso según la sesión del usuario
 * - Retorna un array con: controller, action y variables
 */
class Handler
{
    public static function getControllerAction()
    {
        $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = "/ajedrez-cultura/public/";

        if (strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
        }

        $uriPartida = explode("/", trim($uriPath, "/"));

        if (!empty($uriPartida) && $uriPartida[0] === "index.php") {
            array_shift($uriPartida);
        }

        $config = Config::getInstancia();

        if (!isset($_SESSION['usuario'])) {
            $defaultController = $config->getParametro("DEFAULT_CONTROLLER_LOGIN") ?: "ControladorLogin";
            $defaultAction = $config->getParametro("DEFAULT_ACTION_LOGIN") ?: "login";
        } else {
            $defaultController = $config->getParametro("DEFAULT_CONTROLLER") ?: "ControladorAlumnos";
            $defaultAction = $config->getParametro("DEFAULT_ACTION") ?: "inicio";
        }        

        $controller = !empty($uriPartida[0]) ? filter_var($uriPartida[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : $defaultController;
        $action = !empty($uriPartida[1]) ? filter_var($uriPartida[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) : $defaultAction;        

        return [
            'controller' => $controller,
            'action' => $action,
            'variables' => $_REQUEST
        ];
    }
}