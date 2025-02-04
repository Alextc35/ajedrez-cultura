<?php
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
    $otraLiga = $liga === 'LIGA LOCAL' ? 'LIGA INFANTIL' : 'LIGA LOCAL';
?>
<div class="container-fluid bg-light rounded py-3">
    <h2 class="text-center">Añadir Nuevo Alumno</h2>

    <form action="?controller=ControladorAlumnos&action=insertAlumno" method="POST">
        <input type="hidden" name="liga" value="<?= htmlspecialchars($liga) ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Alumno:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="liga" class="form-label">Liga:</label>
            <select name="liga" id="liga" class="form-select" required>
                <option value="<?= $liga ?>"><?= $liga ?></option>
                <option value="<?= $otraLiga ?>"><?= $otraLiga ?></option>
            </select>
        </div>

        <div class="mb-3">
            <label for="victorias" class="form-label">Victorias:</label>
            <input type="number" name="victorias" id="victorias" class="form-control" value="0" min="0" required>
        </div>

        <div class="mb-3">
            <label for="derrotas" class="form-label">Derrotas:</label>
            <input type="number" name="derrotas" id="derrotas" class="form-control" value="0" min="0" required>
        </div>

        <div class="mb-3">
            <label for="tablas" class="form-label">Tablas:</label>
            <input type="number" name="tablas" id="tablas" class="form-control" value="0" min="0" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Añadir Alumno</button>
            <a href="?controller=ControladorAlumnos&action=listPorLiga&liga=<?= urlencode($liga) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
