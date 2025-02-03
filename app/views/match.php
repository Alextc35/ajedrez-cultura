<div class="container mt-4">
    <h2 class="text-center text-bg-dark">Selecciona los Jugadores para Enfrentamiento</h2>

    <form action="/chess-league/public/generate_matches.php" method="POST">
        <input type="hidden" name="categoria" value="<?= htmlspecialchars($dataToView['categoria']); ?>">

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataToView['data'] as $alumno) { ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?= $alumno['id']; ?>"></td>
                        <td><?= $alumno['id']; ?></td>
                        <td><?= htmlspecialchars($alumno['nombre']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
            $categoria = $_GET['categoria'] ?? 'LIGA LOCAL';
            if ($categoria === 'LIGA LOCAL')
                $categoria = 'ligaLocal';
            else
                $categoria = 'ligaInfantil';
        ?>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Enfrentar Jugadores</button>
            <a href="?controller=ControladorAlumnos&action=<?= urlencode($categoria) ?>" class="btn btn-secondary">Cancelar</a>
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
