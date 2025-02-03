<?php
session_start();

// Determinar la categoría actual
$categoria = (!empty($dataToView['data']) && $dataToView['data'][array_key_first($dataToView['data'])]['categoria'] === 'LIGA LOCAL')
    ? 'LIGA LOCAL' 
    : 'LIGA INFANTIL';

// Asegurar que `$dataToView['data']` esté definido antes de usarlo
$alumnos = isset($dataToView['data']) ? $dataToView['data'] : [];

?>

<div class="container-fluid px-3 mt-5">
    <!-- Botón de Volver alineado correctamente -->
    <div class="d-flex justify-content-start m-3 position-absolute">
        <a href="/chess-league/public/" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <div class="table-container bg-white p-3 rounded shadow">
        <h2 class="text-center">&nbsp;&nbsp;&nbsp;<?= htmlspecialchars($categoria) ?></h2>

        <?php if (!empty($alumnos)) { ?>
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover table-bordered w-100">
                <thead class="table-primary sticky-top">
                    <tr class="text-center">
                        <th>🏆</th> <!-- Posición -->
                        <th>👤</th> <!-- Nombre -->
                        <th>✅</th> <!-- Victorias -->
                        <th>❌</th> <!-- Derrotas -->
                        <th>🤝</th> <!-- Tablas -->
                        <th>⭐</th> <!-- Puntos -->
                    </tr>
                </thead>
                    <tbody>
                        <?php
                        usort($alumnos, function ($a, $b) {
                            return (($b['victorias'] + $b['tablas'] * 0.5) <=> ($a['victorias'] + $a['tablas'] * 0.5));
                        });

                        $cont = 1;
                        foreach ($alumnos as $alumno) { ?>
                            <tr class="text-center">
                                <td><?= $cont++ . '°'; ?></td>
                                <td class="text-start"><?= htmlspecialchars($alumno['nombre']); ?></td>
                                <td><?= $alumno['victorias']; ?></td>
                                <td><?= $alumno['derrotas']; ?></td>
                                <td><?= $alumno['tablas']; ?></td>
                                <td><?= number_format($alumno['victorias'] + $alumno['tablas'] * 0.5, 1); ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-3">
                <a href="?controller=ControladorAlumnos&action=add&categoria=<?= urlencode($categoria) ?>" class="btn btn-primary btn-sm">Añadir alumno</a>
                <a href="?controller=ControladorAlumnos&action=edit&categoria=<?= urlencode($categoria) ?>" class="btn btn-primary btn-sm">Editar alumno</a>
            </div>

            <div class="text-center p-3">
                <a href="?controller=ControladorAlumnos&action=match&categoria=<?= urlencode($categoria) ?>" class="btn btn-success btn-sm">Enfrentar</a>
                <a href="/chess-league/public/generar_pdf.php?categoria=<?= urlencode($categoria) ?>" class="btn btn-danger btn-sm">Descargar PDF</a>
            </div>
        <?php } else { ?>
            <p class="text-center mt-3">No hay alumnos en esta categoría.</p>
            <div class="text-center">
                <a href="?controller=ControladorAlumnos&action=add&categoria=<?= urlencode($categoria) ?>" class="btn btn-primary btn-sm">Añadir alumno</a>
            </div>
        <?php } ?>
    </div>
</div>

