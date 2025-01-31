<div>
    <div class="table-container">
        <?php
        $cont = 1;
        if (count($dataToView['data']) > 0) {
        // Calcular puntos y ordenar de mayor a menor
        usort($dataToView['data'], function ($a, $b) {
            $puntosA = ($a['victorias'] * 1) + ($a['tablas'] * 0.5);
            $puntosB = ($b['victorias'] * 1) + ($b['tablas'] * 0.5);
            return $puntosB <=> $puntosA; // Ordenar de mayor a menor
        });
            ?>
            <table>
            <tr>
                <th>Posición</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Victorias</th>
                <th>Derrotas</th>
                <th>Tablas</th>
                <th>Puntos</th>
            </tr>
            <?php
            foreach ($dataToView['data'] as $alumno) {
                ?>
                    <tr>
                        <td><?= $cont++ ?></td>
                        <td><?php echo $alumno['nombre']; ?></td>
                        <td><?php echo $alumno['categoria']; ?></td>
                        <td><?php echo $alumno['victorias']; ?></td>
                        <td><?php echo $alumno['derrotas']; ?></td>
                        <td><?php echo $alumno['tablas']; ?></td>
                        <td><?php echo $alumno['victorias'] + $alumno['tablas'] / 2; ?></td>
                    </tr>
            <?php
            }
        } else {
            echo 'No hay alumnos';
        }
    ?>
        </table>
    </div>
</div>