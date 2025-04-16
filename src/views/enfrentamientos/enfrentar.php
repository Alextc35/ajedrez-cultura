<?php
    $config = Config::getInstancia();
    $liga = $_GET['liga'] ?? 'LIGA LOCAL';
    $alumnos = $dataToView['data'] ?? [];
?>
<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorLigas/clasificacion?liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left-short ">Volver</i>
        </a>
    </div>
    <h2 class="text-center">Selecciona los Jugadores para enfrentarlos</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>

    <form action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorEnfrentamientos/asignarResultados" method="POST">
        <input type="hidden" name="liga" value="<?= htmlspecialchars($liga); ?>">

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>
                        <div class="form-check form-switch d-flex justify-content-center align-items-center">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">👤</label>
                        </div>
                    </th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno) { ?>
                    <tr class="text-center align-middle">
                        <td>
                            <input type="checkbox" name="ids[]" value="<?= $alumno['id']; ?>" class="form-check-input">
                        </td>
                        <td class="text-start"><?= htmlspecialchars($alumno['nombre']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button type="submit" name="matches" class="btn btn-primary d-block m-auto">Enfrentar Jugadores</button>
    </form>
    <!-- <p class="text-center text-danger fw-semibold m-3">
        ⚠️ Los enfrentamientos están en estado <u>experimental</u>.  
        Pueden surgir fallos no esperados.  
        <strong>Editar enfrentamientos solo si es estrictamente necesario.</strong>
    </p> -->
</div>

<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
