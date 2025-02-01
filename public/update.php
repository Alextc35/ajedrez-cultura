<?php
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

// 📌 Verificar que la petición sea POST antes de continuar
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso denegado.");
}

// 📌 Instanciar el modelo
$alumnosDAO = new AlumnosDAO();

// 📌 Obtener la categoría desde el formulario
$categoria = $_POST['categoria'] ?? 'LIGA LOCAL';

// 📌 Obtener los datos del formulario (con validación básica)
$ids = $_POST['id'] ?? [];
$nombres = $_POST['nombre'] ?? [];
$victorias = $_POST['victorias'] ?? [];
$derrotas = $_POST['derrotas'] ?? [];
$tablas = $_POST['tablas'] ?? [];

// 📌 Verificar que haya datos para actualizar
if (empty($ids)) {
    die("No se recibieron datos para actualizar.");
}

// 📌 Recorrer los datos y actualizar en la BD
try {
    for ($i = 0; $i < count($ids); $i++) {
        $id = intval($ids[$i]);
        $nombre = trim(htmlspecialchars($nombres[$i]));
        $vict = max(0, intval($victorias[$i]));
        $derrot = max(0, intval($derrotas[$i]));
        $tabl = max(0, intval($tablas[$i]));

        // 📌 Solo actualizar si el ID es válido
        if ($id > 0) {
            $alumnosDAO->updateAlumno($id, $nombre, $vict, $derrot, $tabl);
        }
    }

    if ($categoria === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';

    // 📌 Redirigir correctamente a la edición sin que se ejecute "Acceso denegado"
    header("Location: ../public/?controller=ControladorAlumnos&action=" . urlencode($categoria));
    exit();

} catch (Exception $e) {
    die("Error al actualizar los datos: " . $e->getMessage());
}
