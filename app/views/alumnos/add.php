<?php
    if (!isset($_SESSION['usuario'])) {
        die("No estás autenticado");
    }
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
    $otraLiga = $liga === 'LIGA LOCAL' ? 'LIGA INFANTIL' : 'LIGA LOCAL';
?>
<div class="container bg-white p-3 rounded shadow">
    <!-- 📌 Barra de navegación fija dentro del container -->
    <div class="container d-flex p-0 pb-2 m-0 justify-content-between align-items-center">
        <!-- 📌 Botón de Volver -->
        <a href="<?= constant('DEFAULT_INDEX')?>ControladorAlumnos/list?liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left-short">Volver</i>
        </a>
        <h2 class="text-center">Añadir Alumnos</h2>
    </div>

    <form action="<?= constant('DEFAULT_INDEX')?>ControladorAlumnos/insertAlumno" method="POST">
        <input type="hidden" name="liga" value="<?= htmlspecialchars($liga) ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Alumno:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Introduce el nombre del alumno..." required>
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

        <button type="submit" class="btn btn-success d-block m-auto" onclick="return confirm('¿Quieres confirmar los ajustes?')">Añadir Alumno</button>
    </form>
</div>
