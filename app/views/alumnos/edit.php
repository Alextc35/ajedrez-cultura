<?php
    if (!isset($_SESSION['usuario'])) {
        die("No estás autenticado");
    }
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
?>
<div class="container bg-white p-3 rounded shadow">
    <!-- 📌 Barra de navegación fija dentro del container -->
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <!-- 📌 Botón de Volver -->
        <a href="?action=listPorLiga&liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm"> 
            <i class="bi bi-arrow-left-short ">Volver</i>
        </a>
    </div>
    <!-- 📌 Título centrado -->
    <h2 class="text-center">Editar Alumnos</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>

    <?php if (!empty($dataToView['data'])) { ?>
    <div class="table-responsive">
        <form action="?action=updateAlumnos" method="POST">
            <input type="hidden" name="liga" value="<?= htmlspecialchars($liga); ?>">
            
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th class="col-3 col-sm-4">👤</th>
                        <th class="col-2 col-sm-2">✅</th>
                        <th class="col-2 col-sm-2">❌</th>
                        <th class="col-2 col-sm-2">🤝</th>
                        <th class="col-2 col-sm-2">🗑️</th> <!-- Eliminar -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataToView['data'] as $alumno) { ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?= $alumno['id']; ?>">
                        <td><input type="text" name="nombre[]" value="<?= htmlspecialchars($alumno['nombre']); ?>" class="form-control p-1 expandable-input"></td>
                        <td><input type="number" name="victorias[]" value="<?= $alumno['victorias']; ?>" class="form-control p-1"></td>
                        <td><input type="number" name="derrotas[]" value="<?= $alumno['derrotas']; ?>" class="form-control p-1"></td>
                        <td><input type="number" name="tablas[]" value="<?= $alumno['tablas']; ?>" class="form-control p-1"></td>
                        <td>
                            <a href="?action=deleteAlumno&id=<?= $alumno['id']; ?>&liga=<?= urlencode($liga) ?>"
                                class="btn btn-danger btn-sm d-flex justify-content-center align-items-center"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar a <?= htmlspecialchars($alumno['nombre']); ?>?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success d-block m-auto" onclick="return confirm('¿Quieres confirmar los ajustes?')">Guardar Cambios</button>
        </form>
    </div>
    <?php } else { ?>
        <p class="text-center">No hay alumnos en <?= htmlspecialchars($liga); ?> para editar.</p>
    <?php } ?>
</div>