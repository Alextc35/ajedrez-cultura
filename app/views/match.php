<?php
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
    $alumnos = $dataToView['data'] ?? [];
?>
<div class="container bg-white p-3 rounded shadow">
    <!-- 📌 Barra de navegación fija dentro del container -->
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <!-- 📌 Botón de Volver -->
        <a href="?action=listPorLiga&liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm"> 
            <i class="bi bi-arrow-left-short ">Volver</i>
        </a>
    </div>
    <h2 class="text-center">Selecciona los Jugadores para enfrentarlos</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>

    <form action="?action=generateMatches" method="POST">
        <input type="hidden" name="liga" value="<?= htmlspecialchars($liga); ?>">

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th class="text-center"><input type="checkbox" id="selectAll"></th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno) { ?>
                    <tr class="text-center align-middle">
                        <td>
                            <input type="checkbox" id="check<?= $alumno['id']; ?>" name="ids[]" value="<?= $alumno['id']; ?>" class="custom-checkbox">
                            <label for="check<?= $alumno['id']; ?>" class="custom-checkbox-label"></label>
                        </td>
                        <td class="text-start"><?= htmlspecialchars($alumno['nombre']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Enfrentar Jugadores</button>
        </div>
    </form>
</div>

<script>
    // Seleccionar/Deseleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
