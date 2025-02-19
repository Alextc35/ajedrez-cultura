<?php
/**
 * 📌 Configuración Global de la Aplicación
 * Definir constantes para la conexión a la base de datos y rutas de la aplicación.
 */

# 📌 Configuración de la Base de Datos
define("DB_HOST", "localhost");  // Servidor de la base de datos
define("DB_NAME", "ajedrez_clase");  // Nombre de la base de datos
define("DB_USER", "admin");  // Usuario de la base de datos
define("DB_PASS", "ligamejorada33");  // Contraseña de la base de datos (vacía por defecto en XAMPP)

# 📌 Controlador y acción por defecto
define("DEFAULT_CONTROLLER", "ControladorAlumnos"); // Controlador de la Aplicación
define("DEFAULT_ACTION", "inicio"); // Acción por defecto

define("DEFAULT_CONTROLLER_LOGIN", "ControladorLogin");
define("DEFAULT_ACTION_LOGIN", "login");

// ubicación de index
define("DEFAULT_INDEX", "http://localhost/chess-league/public/index.php/");

# 📌 Manejo de errores (Descomentar en desarrollo para depuración)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
