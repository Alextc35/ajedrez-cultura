<?php
$config = Config::getInstancia();
?>

<div class="container bg-white p-4 rounded shadow mt-4">
    <h2 class="text-center mb-4">Añadir nuevo alumno</h2>

    <form method="POST" action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAlumnos/insertAlumno">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del alumno</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>

        <div class="mb-3">
            <label for="anio_nacimiento" class="form-label">Año de nacimiento</label>
            <input type="number" class="form-control" name="anio_nacimiento" id="anio_nacimiento" min="2000" max="<?= date('Y') ?>" required>
        </div>

        <div class="mb-3">
            <label for="liga" class="form-label">Liga</label>
            <select name="liga" id="liga" class="form-select" required>
                <option value="Local">Local</option>
                <option value="Infantil">Infantil</option>
            </select>
        </div>

        <!-- Estos campos se pueden ocultar si no los usas desde el formulario -->
        <input type="hidden" name="victorias" value="0">
        <input type="hidden" name="derrotas" value="0">
        <input type="hidden" name="tablas" value="0">

        <div class="d-flex justify-content-between">
            <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAlumnos/listAlumnos" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Guardar alumno
            </button>
        </div>
    </form>
</div>
