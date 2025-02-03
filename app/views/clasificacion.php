<?php
session_start();
// Determinar la categoría actual
if($_GET['action'] === 'list') {
    $categoriaActual = 'LIGA';
}
else {
$categoriaActual = (!empty($dataToView['data']) && $dataToView['data'][array_key_first($dataToView['data'])]['categoria'] === 'LIGA LOCAL')
    ? 'LIGA LOCAL' 
    : 'LIGA INFANTIL';
}
?>
<div>
    <div class="table-container">
        <a href="/chess-league/public/" class="btn btn-secondary float-start">Volver</a>
        <h2 class="text-center"><?= $categoriaActual ?></h2>

        <?php if (count($dataToView['data']) > 0) { ?>
            <div class="table-wrapper table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Posición</th>
                            <th>Nombre</th>
                            <th>Victorias</th>
                            <th>Derrotas</th>
                            <th>Tablas</th>
                            <th>Puntos</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cont = 1;
                            // Calcular puntos y ordenar de mayor a menor
                            usort($dataToView['data'], function ($a, $b) {
                                $puntosA = ($a['victorias'] * 1) + ($a['tablas'] * 0.5);
                                $puntosB = ($b['victorias'] * 1) + ($b['tablas'] * 0.5);
                                return $puntosB <=> $puntosA; // Ordenar de mayor a menor
                            });
                            foreach ($dataToView['data'] as $alumno) {
                        ?>
                            <tr>
                                <td><?php echo $cont++ . '°'; ?></td>
                                <td><?= $alumno['nombre']; ?></td>
                                <td><?= $alumno['victorias']; ?></td>
                                <td><?= $alumno['derrotas']; ?></td>
                                <td><?= $alumno['tablas']; ?></td>
                                <td><?= $alumno['victorias'] + $alumno['tablas'] / 2; ?></td>
                                <td><a href="/chess-league/public/delete.php?id=<?= $alumno['id']; ?>&categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar a <?= htmlspecialchars($alumno['nombre']); ?>?')"><i class="bi bi-person-x-fill"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                    $_SESSION['dataToView'] = $dataToView;
                ?>
            </div>

            <div class="text-center p-2">
                <a href="?controller=ControladorAlumnos&action=add&categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-primary">Añadir alumno</a>
                <a href="?controller=ControladorAlumnos&action=edit&categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-primary">Editar alumno</a>
            </div>
        <?php } else { ?>
            <p class="text-center">No hay alumnos</p>
            <div class="text-center">
                <a href="?controller=ControladorAlumnos&action=add&categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-primary">Añadir alumno</a>
            </div>
        <?php } ?>
    </div>
    <div class="text-center p-2">
        <a href="?controller=ControladorAlumnos&action=match&categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-success">Enfrentar</a>
        <a href="/chess-league/public/generar_pdf.php?categoria=<?= urlencode($categoriaActual) ?>" class="btn btn-danger">Descargar PDF</a>
    </div>
</div>