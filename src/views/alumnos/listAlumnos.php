<?php
$config = Config::getInstancia();
$alumnos = isset($dataToView['data']) ? $dataToView['data'] : [];
$desactivado = empty($dataToView['data']) ? 'disabled' : '';
?>

<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-3 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAuth/inicio" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i>
        </a>
        <h3 class="text-center">Mensualidad</h3>
        <div>
            <button id="toggleEdit" class="btn btn-primary btn-sm <?= $desactivado ?>">Editar</button>
            <!-- <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorPDF/generarPDF2" class="btn btn-danger disabled">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </a> -->
        </div>
    </div>

    <?php if (!empty($alumnos)) { ?>
        <div class="table-responsive table-responsive-sticky mt-3">
            <p class="text-center text-warning fw-semibold movil-warning">
                ⚠️ Para una mejor experiencia en dispositivos móviles, se recomienda girar el dispositivo a modo horizontal.
            </p>
            <table class="table table-bordered table-hover text-center align-middle" id="asistenciaTable">
                <thead class="table-primary">
                    <tr>
                        <th>ALUMNO</th>
                        <th>AÑO</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dic</th>
                        <th>Ene</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Abr</th>
                        <th>May</th>
                        <th>Jun</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $index => $alumno): ?>
                        <tr data-index="<?= $index ?>">
                            <td class="text-start"><?= htmlspecialchars($alumno['nombre']) ?></td>
                            <td>
                                <?= 
                                    $alumno['anio_nacimiento'] === null || $alumno['anio_nacimiento'] == 0
                                    ? '–' 
                                    : htmlspecialchars($alumno['anio_nacimiento']) 
                                ?>
                            </td>
                            <?php 
                                $mesMap = ['Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
                                $monthNumMap = [10, 11, 12, 1, 2, 3, 4, 5, 6];

                                foreach ($mesMap as $i => $mes) {
                                    $pagado = array_key_exists($mes, $alumno['pagos']) ? $alumno['pagos'][$mes] : null;
                                
                                    if ($pagado === true) {
                                        echo "<td class='asistencia' data-month='{$i}'>✅</td>";
                                    } else {
                                        echo "<td class='asistencia' data-month='{$i}'></td>";
                                    }
                                }
                                
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr class="m-0 p-0">
        </div>
    <?php } else { ?>
        <p class="text-center">No hay alumnos apuntados...</p>
    <?php } ?>

    <a id="addAlumno" href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAlumnos/addAlumno" class="btn btn-success d-block mt-3 m-2">Añadir alumno</a>
    <a id="editAlumnos" href="<?= $config->getParametro('DEFAULT_INDEX')?>ControladorAlumnos/editAlumnos" class="btn btn-primary d-block m-2 <?= $desactivado ?>">Editar alumnos</a>

    <!-- 🧾 Formulario oculto para envío clásico -->
    <form id="formPagos" method="POST" action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorAlumnos/guardarPagos">
        <input type="hidden" name="pagos_data" id="pagos_data">
    </form>
</div>

<script>
const alumnoInfo = <?= json_encode(array_map(function ($a) {
    return [ 'id' => $a['id'] ];
}, $alumnos)) ?>;

let editMode = false;

document.getElementById('toggleEdit').addEventListener('click', function () {
    const rows = document.querySelectorAll('#asistenciaTable tbody tr');
    editMode = !editMode;
    this.textContent = editMode ? 'Guardar' : 'Editar';

    const mesMap = ['Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
    const monthMap = [10, 11, 12, 1, 2, 3, 4, 5, 6];
    const pagosPayload = [];

    document.getElementById('addAlumno').classList.toggle('disabled', editMode);
    document.getElementById('editAlumnos').classList.toggle('disabled', editMode);

    rows.forEach((row, rowIndex) => {
        const alumnoId = alumnoInfo[rowIndex].id;
        const celdas = row.querySelectorAll('.asistencia');

        celdas.forEach((celda, i) => {
            const mes = mesMap[i];
            const anio = ['Octubre','Noviembre','Diciembre'].includes(mes) ? 2024 : 2025;

            if (editMode) {
                const isTicked = celda.textContent.trim() === '✅';
                celda.innerHTML = `<input type="checkbox" ${isTicked ? 'checked' : ''}>`;
            } else {
                const input = celda.querySelector('input');
                const checked = input && input.checked;

                celda.innerHTML = checked ? '✅' : '';

                pagosPayload.push({
                    alumno_id: alumnoId,
                    mes: mes,
                    anio: anio,
                    pagado: checked
                });
            }
        });
    });

    // Guardar por formulario oculto al salir del modo edición
    if (!editMode) {
        document.getElementById('pagos_data').value = JSON.stringify(pagosPayload);
        document.getElementById('formPagos').submit();
    }
});

</script>

