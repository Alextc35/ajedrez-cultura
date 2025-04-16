<?php
$config = Config::getInstancia();
$liga = htmlspecialchars($_SESSION['liga'] ?? 'LIGA LOCAL');

// Obtener TODOS los alumnos de la liga actual
$jugadoresData = $_SESSION['dataToView']['data'] ?? [];
$jugadores = [];
foreach ($jugadoresData as $alumno) {
    $jugadores[$alumno['id']] = $alumno['nombre'];
}

$jugadoresSeleccionados = $_SESSION['jugadores'] ?? [];

if (empty($jugadoresSeleccionados)) {
    echo "<p class='text-danger text-center'>No hay jugadores seleccionados.</p>";
    exit();
}
?>

<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorLigas/clasificacion?liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Si vuelves atrás, los enfrentamientos generados se perderán. ¿Estás seguro de que deseas continuar?')">
            <i class="bi bi-arrow-left-short"> Volver</i>
        </a>
    </div>
    <h2 class="text-center">Asignar Resultados</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>

    <div class="table-responsive">
        <form action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorEnfrentamientos/asignarResultadosProcess" method="POST">
            <input type="hidden" name="liga" value="<?= $liga; ?>">
            <button type="button" class="btn btn-outline-primary d-block mb-2 m-auto" id="btnEditar">
                Editar enfrentamientos
            </button>
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th>Blancas</th>
                        <th>vs</th>
                        <th>Negras</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jugadoresIds = array_keys($jugadoresSeleccionados);
                    $numJugadores = count($jugadoresIds);

                    function renderJugadorSelect($name, $selectedId, $jugadores, $includeBye = false) {
                        echo "<select name='{$name}[]' class='form-select w-100 d-none modo-edicion'>";
                        foreach ($jugadores as $id => $nombre) {
                            $selected = ($id == $selectedId) ? "selected" : "";
                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                        }
                        if ($includeBye) {
                            $selected = ($selectedId === 'bye') ? "selected" : "";
                            echo "<option value='bye' {$selected}>BYE</option>";
                        }
                        echo "</select>";

                        $nombreVisible = $selectedId === 'bye' ? 'BYE' : $jugadores[$selectedId] ?? '';
                        echo "<span class='modo-lectura'>{$nombreVisible}</span>";
                    }

                    for ($i = 0; $i < $numJugadores - 1; $i += 2) {
                        $id1 = $jugadoresIds[$i];
                        $id2 = $jugadoresIds[$i + 1];
                        ?>
                        <tr class="text-center align-middle">
                            <td>
                                <?php renderJugadorSelect('id1', $id1, $jugadores, true); ?>
                            </td>
                            <td>vs</td>
                            <td>
                                <?php renderJugadorSelect('id2', $id2, $jugadores, true); ?>
                            </td>
                            <td class="resultado-celda">
                                <select name="resultados[]" class="form-select p-1 resultado-select">
                                    <option value="" selected disabled>? - ?</option>
                                    <option value="1-0">1 - 0</option>
                                    <option value="0-1">0 - 1</option>
                                    <option value="1-1">½ - ½</option>
                                </select>
                                <span class="text-success fw-semibold d-none resultado-auto">Victoria automática</span>
                            </td>
                        </tr>
                    <?php }

                    if ($numJugadores % 2 === 1) {
                        $ultimoId = $jugadoresIds[$numJugadores - 1];
                        ?>
                        <tr class="text-center align-middle table-warning">
                            <td>
                                <?php renderJugadorSelect('id1', $ultimoId, $jugadores, true); ?>
                            </td>
                            <td>vs</td>
                            <td>
                                <?php renderJugadorSelect('id2', 'bye', $jugadores, true); ?>
                            </td>
                            <td class="resultado-celda">
                                <select name="resultados[]" class="form-select p-1 resultado-select">
                                    <option value="" selected disabled>? - ?</option>
                                    <option value="1-0">1 - 0</option>
                                    <option value="0-1">0 - 1</option>
                                    <option value="1-1">½ - ½</option>
                                </select>
                                <span class="text-success fw-semibold resultado-auto">Victoria automática</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success d-block m-auto" onclick="return confirm('¿Quieres confirmar los resultados?')">
                Guardar Resultados
            </button>
        </form>
    </div>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let selects = document.querySelectorAll("select[name='resultados[]']");
    for (let select of selects) {
        // Ignorar si el <select> está oculto (porque hay BYE)
        if (select.classList.contains("d-none")) continue;

        if (select.value === "") {
            alert("Por favor, selecciona un resultado para todos los enfrentamientos.");
            event.preventDefault();
            return;
        }
    }
});

const btnEditar = document.getElementById("btnEditar");
let modoEdicionActivo = false;

btnEditar.addEventListener("click", function () {
    if (!modoEdicionActivo) {
        // Activar modo edición
        modoEdicionActivo = true;
        btnEditar.textContent = "Guardar enfrentamientos";

        document.querySelectorAll(".modo-edicion").forEach(el => el.classList.remove("d-none"));
        document.querySelectorAll(".modo-lectura").forEach(el => el.classList.add("d-none"));
    } else {
        // Guardar cambios y salir de edición
        modoEdicionActivo = false;
        btnEditar.textContent = "Editar enfrentamientos";

        document.querySelectorAll(".modo-edicion").forEach(el => el.classList.add("d-none"));
        document.querySelectorAll(".modo-lectura").forEach((el, i) => {
            const select = document.querySelectorAll(".modo-edicion")[i];
            if (select && select.tagName === "SELECT") {
                const selectedText = select.options[select.selectedIndex]?.textContent ?? '';
                el.textContent = selectedText;
            }
            el.classList.remove("d-none");
        });

        actualizarResultadoSegunBYE();  // Reforzamos el resultado visual
    }
});


// 🟨 Mostrar/ocultar "Victoria automática"
function actualizarResultadoSegunBYE() {
    const filas = document.querySelectorAll("tr");

    filas.forEach(fila => {
        const select1 = fila.querySelector("select[name='id1[]']");
        const select2 = fila.querySelector("select[name='id2[]']");
        const celdaResultado = fila.querySelector(".resultado-celda");

        if (select1 && select2 && celdaResultado) {
            const esBye = select1.value === "bye" || select2.value === "bye";
            const selectResultado = celdaResultado.querySelector(".resultado-select");
            const textoAuto = celdaResultado.querySelector(".resultado-auto");

            if (esBye) {
                selectResultado.classList.add("d-none");
                textoAuto.classList.remove("d-none");
            } else {
                selectResultado.classList.remove("d-none");
                textoAuto.classList.add("d-none");
            }
        }
    });
}

// 🛑 Limitar a un solo BYE seleccionado por ronda
function controlarOpcionesBye() {
    const selects = document.querySelectorAll("select[name='id1[]'], select[name='id2[]']");
    let byeSeleccionado = false;

    // Detectar si ya hay algún "bye" seleccionado
    selects.forEach(select => {
        if (select.value === 'bye') {
            byeSeleccionado = true;
        }
    });

    // Activar o desactivar opción BYE
    selects.forEach(select => {
        const opciones = select.querySelectorAll("option");

        opciones.forEach(opt => {
            if (opt.value === 'bye') {
                if (byeSeleccionado && select.value !== 'bye') {
                    opt.disabled = true;
                    opt.hidden = true;
                } else {
                    opt.disabled = false;
                    opt.hidden = false;
                }
            }
        });
    });
}

// Ejecutar validaciones al cargar y al cambiar selects
document.querySelectorAll("select[name='id1[]'], select[name='id2[]']").forEach(select => {
    select.addEventListener("change", () => {
        actualizarResultadoSegunBYE();
        controlarOpcionesBye();
    });
});

// Ejecutar al cargar
actualizarResultadoSegunBYE();
controlarOpcionesBye();
</script>