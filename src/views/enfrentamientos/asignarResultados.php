<?php
    $config = Config::getInstancia();
    $liga = htmlspecialchars($_SESSION['liga'] ?? 'LIGA LOCAL');
    $jugadores = $_SESSION['jugadores'] ?? [];

    if (empty($jugadores)) {
        echo "<p class='text-danger text-center'>No hay jugadores seleccionados.</p>";
        exit();
    }
?>
<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorLigas/clasificacion?liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Si vuelves atrás, los enfrentamientos generados se perderán. ¿Estás seguro de que deseas continuar?')">
            <i class="bi bi-arrow-left-short ">Volver</i>
        </a>
    </div>
    <h2 class="text-center">Asignar Resultados</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>
    <div class="table-responsive">
        <form action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorEnfrentamientos/asignarResultadosProcess" method="POST">
            <input type="hidden" name="liga" value="<?= $liga; ?>">

            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th class="col-3 col-sm-4">Blancas</th>
                        <th class="col-2 col-sm-2">vs</th>
                        <th class="col-2 col-sm-4">Negras</th>
                        <th class="col-2 col-sm-6">Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jugadoresIds = array_keys($jugadores);
                    $numJugadores = count($jugadoresIds);
                    for ($i = 0; $i < $numJugadores - 1; $i += 2) { ?>
                        <tr class="text-center align-middle">
                            <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i]]); ?></td>
                            <td>vs</td>
                            <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i + 1]]); ?></td>
                            <td>
                                <input type="hidden" name="id1[]" value="<?= $jugadoresIds[$i]; ?>">
                                <input type="hidden" name="id2[]" value="<?= $jugadoresIds[$i + 1]; ?>">
                                <select name="resultados[]" class="form-select p-1">
                                    <option value="" selected disabled>? - ?</option>
                                    <option value="1-0">1 - 0</option>
                                    <option value="0-1">0 - 1</option>
                                    <option value="1-1">½ - ½</option>
                                </select>
                            </td>
                        </tr>
                    <?php } 
                    if ($numJugadores % 2 === 1) {
                        $ultimoId = $jugadoresIds[$numJugadores - 1]; ?>
                        <tr class="text-center align-middle table-warning">
                            <td><?= htmlspecialchars($jugadores[$ultimoId]); ?></td>
                            <td>vs</td>
                            <td><strong>BYE</strong></td>
                            <td>
                                <input type="hidden" name="id1[]" value="<?= $ultimoId; ?>">
                                <input type="hidden" name="id2[]" value="bye">
                                <select name="resultados[]" class="form-select p-1">
                                        <option value="" selected disabled>? - ?</option>
                                        <option value="1-0">1 - 0</option>
                                        <option value="0-1">0 - 1</option>
                                        <option value="1-1">½ - ½</option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success d-block m-auto" onclick="return confirm('¿Quieres confirmar los resultados?')">Guardar Resultados</button>
        </form>
    </div>
</div>
<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let selects = document.querySelectorAll("select[name='resultados[]']");
    for (let select of selects) {
        if (select.value === "") {
            alert("Por favor, selecciona un resultado para todos los enfrentamientos.");
            event.preventDefault();
            return;
        }
    }
});
</script>
