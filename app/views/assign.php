<?php
    $liga = htmlspecialchars($_SESSION['liga'] ?? 'LIGA LOCAL');
    $jugadores = $_SESSION['jugadores'] ?? [];

    if (empty($jugadores)) {
        echo "<p class='text-danger text-center'>No hay jugadores seleccionados.</p>";
        exit();
    }
?>
<div class="container mt-4">
    <h2 class="text-center">Asignar Resultados - <?= $liga; ?></h2>

    <form action="?action=assignResults" method="POST">
        <input type="hidden" name="categoria" value="<?= $liga; ?>">

        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Blancas</th>
                    <th>VS</th>
                    <th>Negras</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $jugadoresIds = array_keys($jugadores);
                for ($i = 0; $i < count($jugadoresIds) - 1; $i += 2) { ?>
                    <tr>
                        <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i]]); ?></td>
                        <td>VS</td>
                        <td><?= htmlspecialchars($jugadores[$jugadoresIds[$i + 1]]); ?></td>
                        <td>
                            <input type="hidden" name="id1[]" value="<?= $jugadoresIds[$i]; ?>">
                            <input type="hidden" name="id2[]" value="<?= $jugadoresIds[$i + 1]; ?>">
                            <select name="resultados[]" class="form-select">
                                <option value="1-0">1-0</option>
                                <option value="0-1">0-1</option>
                                <option value="1-1">½ - ½</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar Resultados</button>
            <a href="?action=listPorLiga&liga=<?= urlencode($_SESSION['liga']) ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
