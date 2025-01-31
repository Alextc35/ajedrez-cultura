<div>
    <div>
        <a href="index.php?controller=ControladorAlumnos&action=index">Ejemplo</a>
        <hr/>
    </div>
    <div>
        <?php
        $cont = 1;
        if (count($dataToView['data']) > 0) {
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