<?php
session_start();
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/AlumnosDAO.php';

if (!isset($_SESSION['selected_players']) || count($_SESSION['selected_players']) < 2) {
    die("No hay suficientes jugadores seleccionados.");
}

$alumnosDAO = new AlumnosDAO();
$ids = $_SESSION['selected_players'];
$categoria = $_SESSION['categoria'] ?? 'LIGA LOCAL';

// 📌 Obtener información de los jugadores
$jugadores = [];
foreach ($ids as $id) {
    $jugadores[] = $alumnosDAO->getAlumno($id);
}

// 📌 Generar emparejamientos (pares de jugadores)
$pairs = [];
for ($i = 0; $i < count($jugadores) - 1; $i += 2) {
    $pairs[] = [$jugadores[$i], $jugadores[$i + 1]];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Resultados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Asignar Resultados - <?= htmlspecialchars($categoria); ?></h2>

    <form action="/chess-league/public/process_results.php" method="POST">
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria); ?>">

        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Jugador 1</th>
                    <th>VS</th>
                    <th>Jugador 2</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pairs as $pair) { ?>
                    <tr>
                        <td><?= htmlspecialchars($pair[0]['nombre']); ?></td>
                        <td>VS</td>
                        <td><?= htmlspecialchars($pair[1]['nombre']); ?></td>
                        <td>
                            <input type="hidden" name="id1[]" value="<?= $pair[0]['id']; ?>">
                            <input type="hidden" name="id2[]" value="<?= $pair[1]['id']; ?>">
                            <select name="resultados[]" class="form-select">
                                <option value="1-0">1-0 (Ganan blancas)</option>
                                <option value="0-1">0-1 (Ganan negras)</option>
                                <option value="1-1">1-1 (Tablas)</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar Resultados</button>
            <a href="/chess-league/public?controller=ControladorAlumnos&action=<?= urlencode($categoria) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
