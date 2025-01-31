<div>
    <div class="table-container">
        <h2 class="text-center">LIGA LOCAL</h2>
        <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Posición</th>
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <th>Victorias</th>
                        <th>Derrotas</th>
                        <th>Tablas</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cont = 1;
                        if (count($dataToView['data']) > 0) {
                        // Calcular puntos y ordenar de mayor a menor
                        usort($dataToView['data'], function ($a, $b) {
                            $puntosA = ($a['victorias'] * 1) + ($a['tablas'] * 0.5);
                            $puntosB = ($b['victorias'] * 1) + ($b['tablas'] * 0.5);
                            return $puntosB <=> $puntosA; // Ordenar de mayor a menor
                        });
                        foreach ($dataToView['data'] as $alumno) {
                    ?>
                        <tr>
                                <td><?= $cont++ ?></td>
                                <td><?= $alumno['nombre']; ?></td>
                                <td><?= $alumno['categoria']; ?></td>
                                <td><?= $alumno['victorias']; ?></td>
                                <td><?= $alumno['derrotas']; ?></td>
                                <td><?= $alumno['tablas']; ?></td>
                                <td><?= $alumno['victorias'] + $alumno['tablas'] / 2; ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo 'No hay alumnos';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>