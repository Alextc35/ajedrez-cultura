<?php
    $categoria;
    if ($dataToView['categoria'] === 'LIGA LOCAL')
        $categoria = 'ligaLocal';
    else
        $categoria = 'ligaInfantil';
?>
<div class="container mt-4">
    <h2 class="text-center text-bg-dark">Editar Alumnos - <?= htmlspecialchars($dataToView['categoria']); ?></h2>

    <?php if (!empty($dataToView['data'])) { ?>
    <form action="/chess-league/public/update.php" method="POST">
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($dataToView['categoria']); ?>">
        
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Victorias</th>
                    <th>Derrotas</th>
                    <th>Tablas</th>
                    <th>Puntos</th>
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
                    <td><?= number_format(($alumno['victorias'] * 1) + ($alumno['tablas'] * 0.5), 1); ?></td>
                    <td>
                        <a href="/chess-league/public/delete.php?id=<?= $alumno['id']; ?>&categoria=<?= urlencode($categoria) ?>"
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
            <a href="?controller=ControladorAlumnos&action=<?= urlencode($categoria) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <?php } else { ?>
        <p class="text-center">No hay alumnos en <?= htmlspecialchars($dataToView['categoria']); ?> para editar.</p>
    <?php } ?>
</div>