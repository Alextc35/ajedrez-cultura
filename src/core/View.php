<?php
require_once '../src/core/Config.php';
/**
 * Clase View
 * ------------------------
 * Encargada de renderizar una vista PHP utilizando un layout básico
 * compuesto por:
 * - Header: /views/templates/header.php
 * - Vista específica: /views/{nombreVista}.php
 * - Footer: /views/templates/footer.html
 *
 * Parámetros:
 * - $nombreVista: nombre del archivo de vista (sin ruta)
 * - $dataToView: array asociativo con datos que se expondrán en la vista
 */
class View
{
    public static function render(string $nombreVista, array $dataToView) {
        $config = Config::getInstancia();
        $index = $config->getParametro('DEFAULT_INDEX');
        extract($dataToView);

        include("../src/views/templates/header.php");

        $vistaPath = "../src/views/$nombreVista.php";
        if (file_exists($vistaPath)) {
            include($vistaPath);
        } else {
            echo "<p class='text-danger text-center'>ERROR<br>Vista no encontrada: $nombreVista.php</p>";
        }

        include("../src/views/templates/footer.html");
    }
}