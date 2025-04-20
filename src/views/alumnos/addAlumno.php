<?php
$config = Config::getInstancia();
?>

<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $index ?>ControladorAlumnos/listAlumnos" class="btn btn-secondary">
            <i class="bi bi-arrow-left-short "></i>
        </a>
    </div>
    <h2 class="text-center mb-4">Añadir nuevo alumno</h2>

    <form method="POST" action="<?= $index ?>ControladorAlumnos/insertAlumno">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del alumno</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>

        <div class="mb-3">
            <label for="anio_nacimiento" class="form-label">Año de nacimiento</label>
            <input type="number" class="form-control" name="anio_nacimiento" id="anio_nacimiento" min="1935" max="<?= date('Y') ?>">
        </div>

        <div class="mb-3">
            <label for="liga" class="form-label">Liga</label>
            <select name="liga" id="liga" class="form-select" required>
                <option value="Local">Local</option>
                <option value="Infantil">Infantil</option>
            </select>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Guardar alumno
            </button>
        </div>
    </form>
</div>
