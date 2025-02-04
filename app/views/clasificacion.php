<?php

// Determinar la categoría actual
$liga = $_GET['liga'] ?? 'LIGA LOCAL';

// Asegurar que `$dataToView['data']` esté definido antes de usarlo
$alumnos = isset($dataToView['data']) ? $dataToView['data'] : [];

$_SESSION['dataToView'] = ['data' => $alumnos, 'liga' => $liga]; // PDF
?>


<div class="container bg-white p-3 rounded shadow">
    <!-- 📌 Barra de navegación fija dentro del container -->
    <div class="container d-flex p-0 pb-3 justify-content-between align-items-center">
        <!-- 📌 Botón de Volver -->
        <a href="/chess-league/public/" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i>
        </a>

        <!-- 📌 Título centrado -->
        <h2><?= htmlspecialchars($liga) ?></h2>

        <!-- 📌 Botón para descargar PDF -->
        <a href="/chess-league/public/generar_pdf.php?liga=<?= urlencode($liga) ?>" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf-fill"></i>
        </a>
    </div>

    <?php if (!empty($alumnos)) { ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered w-100">
                <thead class="table-primary position-sticky top-0">
                    <tr class="text-center">
                        <th class="col-1 col-sm-2">🏆</th> <!-- Posición -->
                        <th class="col-3 col-sm-4">👤</th> <!-- Nombre -->
                        <th class="col-2 col-sm-2">✅</th> <!-- Victorias -->
                        <th class="col-2 col-sm-2">❌</th> <!-- Derrotas -->
                        <th class="col-2 col-sm-2">🤝</th> <!-- Tablas -->
                        <th class="col-2 col-sm-2">⭐</th> <!-- Puntos -->
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
            <hr class="m-0 p-0">
        </div>

        <div class="text-center mt-3">
            <a href="?controller=ControladorAlumnos&action=addAlumno&liga=<?= urlencode($liga) ?>" class="btn btn-primary d-block m-2">Añadir alumno</a>
            <a href="?controller=ControladorAlumnos&action=editAlumnos&liga=<?= urlencode($liga) ?>" class="btn btn-primary d-block m-2">Editar alumno</a>
        </div>
        <hr class="m-0 w-75 mx-auto">
        <div class="text-center p-3">
            <a href="?controller=ControladorAlumnos&action=match&liga=<?= urlencode($liga) ?>" class="btn btn-success d-block">Enfrentar</a>
        </div>
    <?php } else { ?>
        <p class="text-center mt-3">No hay alumnos en esta categoría.</p>
        <div class="text-center">
            <a href="?controller=ControladorAlumnos&action=add&liga=<?= urlencode($liga) ?>" class="btn btn-primary">Añadir alumno</a>
        </div>
    <?php } ?>
</div>


