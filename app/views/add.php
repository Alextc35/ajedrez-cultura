<?php
    $categoria;
    if ($_GET['categoria'] === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';
?>
<div class="container mt-4 bg-light rounded">
    <h2 class="text-center">Añadir Nuevo Alumno</h2>

    <form action="/chess-league/public/insert.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Alumno:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría:</label>
            <select name="categoria" id="categoria" class="form-select" required>
                <option value="LIGA LOCAL">LIGA LOCAL</option>
                <option value="LIGA INFANTIL">LIGA INFANTIL</option>
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
            <a href="?controller=ControladorAlumnos&action=<?= urlencode($categoria) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
