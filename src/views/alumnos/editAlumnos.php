<?php
if (!isset($_SESSION['usuario'])) {
    die("No estás autenticado");
}
?>

<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $index ?>ControladorAlumnos/listAlumnos" class="btn btn-secondary">
            <i class="bi bi-arrow-left-short "></i>
        </a>
    </div>
    <h2 class="text-center">Editar Alumnos</h2>

    <?php if (!empty($dataToView['data'])) { ?>
    <div class="table-responsive">
        <p class="text-center text-warning fw-semibold movil-warning">
            ⚠️ Para una mejor experiencia en dispositivos móviles, se recomienda girar el dispositivo a modo horizontal.
        </p>
        <form action="<?= $index ?>ControladorAlumnos/updateAlumnos" method="POST">

            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                <tr>
                    <th class="col-4 col-md-5">Nombre</th>
                    <th class="col-4 col-md-2">Año</th>
                    <th class="col-4 col-md-3">Liga</th>
                    <th class="col-12 col-md-2">Eliminar</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataToView['data'] as $alumno): ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?= $alumno['id']; ?>">
                        <td><input type="text" name="nombre[]" value="<?= htmlspecialchars($alumno['nombre']); ?>" class="form-control expandable-input"></td>
                        <td><input type="number" name="anio_nacimiento[]" value="<?= $alumno['anio_nacimiento']; ?>" class="form-control expandable-input"></td>
                        <td>
                            <select name="liga[]" class="form-select">
                                <option value="Local" <?= $alumno['liga'] === 'Local' ? 'selected' : '' ?>>Local</option>
                                <option value="Infantil" <?= $alumno['liga'] === 'Infantil' ? 'selected' : '' ?>>Infantil</option>
                            </select>
                        </td>
                        <td class="text-center align-middle">
                            <a href="<?= $index ?>ControladorAlumnos/deleteAlumno?id=<?= $alumno['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Eliminar a <?= htmlspecialchars($alumno['nombre']); ?>?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success d-block m-auto mt-3" onclick="return confirm('¿Confirmar los cambios?')">
                <i class="bi bi-plus-circle"></i> Guardar Cambios
            </button>
        </form>
    </div>
    <?php } else { ?>
        <p class="text-center">No hay alumnos registrados para editar.</p>
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAlumnos/addAlumno" class="btn btn-success d-block mt-3 m-2">Añadir alumno</a>
    <?php } ?>
</div>
