<?php
    $liga = htmlspecialchars($_SESSION['liga'] ?? 'LIGA LOCAL');
    $jugadores = $_SESSION['jugadores'] ?? [];

    if (empty($jugadores)) {
        echo "<p class='text-danger text-center'>No hay jugadores seleccionados.</p>";
        exit();
    }
?>
<div class="container bg-white p-3 rounded shadow">
    <!-- 📌 Barra de navegación fija dentro del container -->
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <!-- 📌 Botón de Volver -->
        <a href="?action=listPorLiga&liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm"> 
            <i class="bi bi-arrow-left-short ">Volver</i>
        </a>
    </div>
    <h2 class="text-center">Asignar Resultados</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>
    <div class="table-responsive">
        <form action="?action=assignResults" method="POST">
            <input type="hidden" name="categoria" value="<?= $liga; ?>">

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
                    for ($i = 0; $i < count($jugadoresIds) - 1; $i += 2) { ?>
                        <tr class="text-center align-middle">
                            <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i]]); ?></td>
                            <td>vs</td>
                            <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i + 1]]); ?></td>
                            <td>
                                <input type="hidden" name="id1[]" value="<?= $jugadoresIds[$i]; ?>">
                                <input type="hidden" name="id2[]" value="<?= $jugadoresIds[$i + 1]; ?>">
                                <select name="resultados[]" class="form-select">
                                    <option value="1-0">1-0</option>
                                    <option value="0-1">0-1</option>
                                    <option value="1-1">½-½</option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Guardar Resultados</button>
            </div>
        </form>
    </div>
</div>
