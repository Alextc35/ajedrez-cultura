<?php
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
?>
<div class="container mt-4">
    <h2 class="text-center text-bg-dark">Editar Alumnos - <?= htmlspecialchars($liga); ?></h2>

    <?php if (!empty($dataToView['data'])) { ?>
    <form action="?controller=ControladorAlumnos&action=updateAlumnos" method="POST">
        <input type="hidden" name="liga" value="<?= htmlspecialchars($liga); ?>">
        
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Victorias</th>
                    <th>Derrotas</th>
                    <th>Tablas</th>
                    <th>🗑️</th> <!-- Eliminar -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataToView['data'] as $alumno) { ?>
                <tr>
                    <td>
                        <?= $alumno['id']; ?>
                        <input type="hidden" name="id[]" value="<?= $alumno['id']; ?>">
                    </td>
                    <td><input type="text" name="nombre[]" value="<?= htmlspecialchars($alumno['nombre']); ?>" class="form-control"></td>
                    <td><input type="number" name="victorias[]" value="<?= $alumno['victorias']; ?>" class="form-control"></td>
                    <td><input type="number" name="derrotas[]" value="<?= $alumno['derrotas']; ?>" class="form-control"></td>
                    <td><input type="number" name="tablas[]" value="<?= $alumno['tablas']; ?>" class="form-control"></td>
                    <td>
                        <a href="?controller=ControladorAlumnos&action=deleteAlumno&id=<?= $alumno['id']; ?>&liga=<?= urlencode($liga) ?>"
                            class="btn btn-danger btn-sm d-flex justify-content-center align-items-center"
                            onclick="return confirm('¿Estás seguro de que quieres eliminar a <?= htmlspecialchars($alumno['nombre']); ?>?')">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="?controller=ControladorAlumnos&action=listPorLiga&liga=<?= urlencode($liga) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <?php } else { ?>
        <p class="text-center">No hay alumnos en <?= htmlspecialchars($liga); ?> para editar.</p>
    <?php } ?>
</div>