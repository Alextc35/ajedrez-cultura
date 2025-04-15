<?php
$config = Config::getInstancia();
$liga = $_GET['liga'] ?? 'LIGA LOCAL';

$alumnos = isset($dataToView['data']) ? $dataToView['data'] : [];

$_SESSION['dataToView'] = ['data' => $alumnos, 'liga' => $liga];
?>


<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-3 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorAuth/inicio" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i>
        </a>

        <h2><?= htmlspecialchars($liga) ?></h2>

        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorPDF/generarPDF?liga=<?= urlencode($liga) ?>" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </a>
    </div>
    <?php if (!empty($alumnos)) { ?>
    <div class="text-center p-3 pt-0">
        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorEnfrentamientos/enfrentar?liga=<?= urlencode($liga) ?>" class="btn btn-success d-block">Enfrentar</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered w-100">
            <thead class="table-primary">
                <tr class="text-center">
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
                usort($alumnos, function ($player1, $player2) {
                    return (($player2['victorias'] + $player2['tablas'] * 0.5) <=> ($player1['victorias'] + $player1['tablas'] * 0.5));
                });
                $pos = 1;
                foreach ($alumnos as $alumno) { ?>
                    <tr class="text-center">
                        <td><?= $pos++ . '°'; ?></td>
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
        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorAlumnos/addAlumno?liga=<?= urlencode($liga) ?>" class="btn btn-primary d-block m-2">Añadir alumno</a>
        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorAlumnos/editAlumnos?liga=<?= urlencode($liga) ?>" class="btn btn-primary d-block m-2">Editar alumno</a>
    </div>
<?php } else { ?>
    <p class="text-center">No hay alumnos en esta categoría.</p>
    <div class="text-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorAlumnos/addAlumno?liga=<?= urlencode($liga) ?>" class="btn btn-primary">Añadir alumno</a>
    </div>
<?php } ?>
</div>


