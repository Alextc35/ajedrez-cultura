<?php
$liga = $_GET['liga'] ?? 'Local';
$torneoId = $_GET['torneoId'] ?? null;

$alumnos = $dataToView['data']['alumnos'] ?? [];
$torneoNombre = $dataToView['data']['torneo'] ?? '';

$_SESSION['dataToView']['data'] = $dataToView['data'];
?>

<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-3 justify-content-between align-items-center">
        <a href="<?= $index ?>ControladorLigas/seleccionarLiga" class="btn btn-secondary">
            <i class="bi bi-arrow-left-short"></i>
        </a>

        <div class="container d-flex flex-column align-items-center">
            <h2 class="mb-0">LIGA <?= strtoupper(htmlspecialchars($liga)); ?></h2>
            <h5 class="text-muted"><?= htmlspecialchars($torneoNombre) ?></h5>
        </div>

        <a href="<?= $index ?>ControladorPDF/generarPDF?liga=<?= urlencode($liga) ?>" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </a>
    </div>

    <?php if (!empty($alumnos)) { ?>
        <div class="text-center p-3 pt-0">
            <a href="<?= $index ?>ControladorEnfrentamientos/enfrentar?torneoId=<?= urlencode($torneoId) ?>&liga=<?= urlencode($liga) ?>" class="btn btn-success d-block">Enfrentar</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered w-100">
                <thead class="table-primary text-center">
                    <tr>
                        <th class="col-1 col-sm-2">🏆</th>
                        <th class="col-3 col-sm-4">👤</th>
                        <th class="col-2 col-sm-2">✅</th>
                        <th class="col-2 col-sm-2">❌</th>
                        <th class="col-2 col-sm-2">🤝</th>
                        <th class="col-2 col-sm-2">⭐</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    usort($alumnos, function ($a, $b) {
                        $puntajeA = $a['victorias'] + $a['tablas'] * 0.5;
                        $puntajeB = $b['victorias'] + $b['tablas'] * 0.5;
                        return $puntajeB <=> $puntajeA;
                    });

                    $pos = 1;
                    foreach ($alumnos as $alumno): ?>
                        <tr class="text-center">
                            <td><?= $pos++ . '°'; ?></td>
                            <td class="text-start"><?= htmlspecialchars($alumno['nombre']); ?></td>
                            <td><?= $alumno['victorias'] ?></td>
                            <td><?= $alumno['derrotas'] ?></td>
                            <td><?= $alumno['tablas'] ?></td>
                            <td><?= number_format($alumno['victorias'] + $alumno['tablas'] * 0.5, 1) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr class="m-0 p-0">
        </div>
    <?php } else { ?>
        <p class="text-center">No hay alumnos en esta categoría.</p>
        <div class="text-center">
            <a href="<?= $index ?>ControladorAlumnos/addAlumno" class="btn btn-primary">Añadir alumno</a>
        </div>
    <?php } ?>
</div>
