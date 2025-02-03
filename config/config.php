<?php
/**
 * 📌 Configuración Global de la Aplicación
 * Definir constantes para la conexión a la base de datos y rutas de la aplicación.
 */

// 📌 Configuración de la Base de Datos
define("DB_HOST", "localhost");  // Servidor de la base de datos
define("DB_NAME", "ajedrez_clase");  // Nombre de la base de datos
define("DB_USER", "root");  // Usuario de la base de datos
define("DB_PASS", "");  // Contraseña de la base de datos (vacía por defecto en XAMPP)

// 📌 Controlador y acción por defecto
define("DEFAULT_CONTROLLER", "ControladorAlumnos");
define("DEFAULT_ACTION", "descripcion");

// 📌 Manejo de errores (Descomentar en desarrollo para depuración)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * 📌 Verificación de Configuración
 * Si falta alguna constante importante, detener la ejecución con un mensaje de error.
 */
$requiredConstants = ["DB_HOST", "DB_NAME", "DB_USER", "DB_PASS", "DEFAULT_CONTROLLER", "DEFAULT_ACTION"];
foreach ($requiredConstants as $constant) {
    if (!defined($constant)) {
        die("Error: La constante '$constant' no está definida en config.php.");
    }
}
