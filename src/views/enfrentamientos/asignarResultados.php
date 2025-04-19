<?php
$config = Config::getInstancia();

$liga = htmlspecialchars($dataToView['data']['liga'] ?? 'Local');
$torneoId = htmlspecialchars($dataToView['data']['torneoId'] ?? null);

$jugadoresSeleccionados = $dataToView['data']['jugadores'] ?? [];
$alumnosLiga = $dataToView['data']['alumnos'] ?? [];

$jugadores = [];
foreach ($alumnosLiga as $alumno) {
    $jugadores[$alumno['id']] = $alumno['nombre'];
}

?>


<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-1 justify-content-between align-items-center">
        <a href="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorLigas/clasificacion?torneoId=<?= urlencode($torneoId) ?>&liga=<?= urlencode($liga) ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Si vuelves atrás, los enfrentamientos generados se perderán. ¿Estás seguro de que deseas continuar?')">
            <i class="bi bi-arrow-left-short"> Volver</i>
        </a>
    </div>
    <h2 class="text-center">Asignar Resultados</h2>
    <h5 class="text-center text-muted"><?= htmlspecialchars($liga); ?></h5>

    <div class="table-responsive">
        <form action="<?= $config->getParametro('DEFAULT_INDEX') ?>ControladorEnfrentamientos/asignarResultadosProcess" method="POST">
            <input type="hidden" name="liga" value="<?= $liga; ?>">
            <input type="hidden" name="torneoId" value="<?= $torneoId; ?>">
            <button type="button" class="btn btn-outline-primary d-block mb-2 m-auto" id="btnEditar">
                Editar enfrentamientos
            </button>
            <p id="avisoMovil" class="text-center text-warning fw-semibold movil-warning m-2">
                ⚠️ Para una mejor experiencia en dispositivos móviles, se recomienda girar el dispositivo a modo horizontal.
            </p>
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr class="text-center align-middle">
                        <th>Blancas</th>
                        <th>vs</th>
                        <th>Negras</th>
                        <th>Resultado</th>
                        <th class="modo-edicion d-none">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jugadoresIds = array_column($jugadoresSeleccionados, 'id');
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
                            <td><?php renderJugadorSelect('id1', $id1, $jugadores, true); ?></td>
                            <td>vs</td>
                            <td><?php renderJugadorSelect('id2', $id2, $jugadores, true); ?></td>
                            <td class="resultado-celda">
                                <select name="resultados[]" class="form-select p-1 resultado-select">
                                    <option value="" selected disabled>? - ?</option>
                                    <option value="1-0">1 - 0</option>
                                    <option value="0-1">0 - 1</option>
                                    <option value="1-1">½ - ½</option>
                                </select>
                                <span class="text-success fw-semibold d-none resultado-auto">Victoria automática</span>
                            </td>
                            <td class="modo-edicion d-none text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger eliminar-fila" title="Eliminar enfrentamiento">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php }

                    if ($numJugadores % 2 === 1) {
                        $ultimoId = $jugadoresIds[$numJugadores - 1];
                        ?>
                        <tr class="text-center align-middle">
                            <td><?php renderJugadorSelect('id1', $ultimoId, $jugadores, true); ?></td>
                            <td>vs</td>
                            <td><?php renderJugadorSelect('id2', 'bye', $jugadores, true); ?></td>
                            <td class="resultado-celda">
                                <select name="resultados[]" class="form-select p-1 resultado-select">
                                    <option value="" selected disabled>? - ?</option>
                                    <option value="1-0">1 - 0</option>
                                    <option value="0-1">0 - 1</option>
                                    <option value="1-1">½ - ½</option>
                                </select>
                                <span class="text-success fw-semibold resultado-auto">Victoria automática</span>
                            </td>
                            <td class="modo-edicion d-none text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger eliminar-fila" title="Eliminar enfrentamiento">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="button" id="btnAñadir" class="btn btn-outline-success d-block d-none mb-3 w-100">
                + Añadir enfrentamiento
            </button>
            <button type="submit" class="btn btn-success d-block m-auto" onclick="return confirm('¿Quieres confirmar los resultados?')">
                Guardar Resultados
            </button>
        </form>
    </div>
</div>


<script>
function generarOpcionesJugadores(roleLabel) {
    const jugadores = <?= json_encode($jugadores); ?>;
    let html = `<option value="" selected disabled>${roleLabel}</option>`;

    for (const id in jugadores) {
        html += `<option value="${id}">${jugadores[id]}</option>`;
    }

    html += `<option value="bye">BYE</option>`;
    return html;
}

// Validar resultados antes de enviar
document.querySelector("form").addEventListener("submit", function(event) {
    let selects = document.querySelectorAll("select[name='resultados[]']");
    let selectsJugadores = document.querySelectorAll("select[name='id1[]'], select[name='id2[]']");
    for (let select of selects) {
        if (select.classList.contains("d-none")) continue;
        if (select.value === "") {
            alert("Por favor, selecciona un resultado para todos los enfrentamientos.");
            event.preventDefault();
            return;
        }
    }
    // Validar que no quede ningún 'Jugador X' como opción activa
    for (let select of selectsJugadores) {
        const selectedText = select.options[select.selectedIndex]?.textContent;
        if (selectedText?.includes("Jugador 1") || selectedText?.includes("Jugador 2")) {
            alert("Por favor, selecciona un jugador válido en todos los enfrentamientos.");
            event.preventDefault();
            return;
        }
    }
    // Validar que no haya jugadores repetidos
    const jugadoresUsados = new Set();
    const id1s = document.querySelectorAll("select[name='id1[]']");
    const id2s = document.querySelectorAll("select[name='id2[]']");

    for (let i = 0; i < id1s.length; i++) {
        const jugador1 = id1s[i].value;
        const jugador2 = id2s[i].value;

        // Ignorar si alguno está vacío o aún con texto por defecto
        if (!jugador1 || !jugador2 || jugador1 === "" || jugador2 === "") continue;

        if (jugadoresUsados.has(jugador1) || jugadoresUsados.has(jugador2)) {
            alert("Un mismo jugador no puede participar en más de un enfrentamiento.");
            event.preventDefault();
            return;
        }

        jugadoresUsados.add(jugador1);
        jugadoresUsados.add(jugador2);
    }
});

// Editar/guardar enfrentamientos
const btnEditar = document.getElementById("btnEditar");
const btnAñadir = document.getElementById("btnAñadir");
let modoEdicionActivo = false;

btnEditar.addEventListener("click", function () {
    modoEdicionActivo = !modoEdicionActivo;

    document.querySelectorAll(".modo-edicion").forEach(el =>
        el.classList.toggle("d-none", !modoEdicionActivo)
    );
    document.querySelectorAll(".modo-lectura").forEach((el, i) =>
        el.classList.toggle("d-none", modoEdicionActivo)
    );

    btnEditar.textContent = modoEdicionActivo
        ? "Guardar enfrentamientos"
        : "Editar enfrentamientos";

    btnAñadir.classList.toggle("d-none", !modoEdicionActivo);

    document.getElementById("avisoMovil").classList.toggle("d-none", !modoEdicionActivo);

    // Si se está guardando (saliendo de edición)
    if (!modoEdicionActivo) {
        document.querySelectorAll("select[name='id1[]'], select[name='id2[]']").forEach((select, i) => {
            const selectedText = select.options[select.selectedIndex]?.textContent ?? '';
            const span = select.parentElement.querySelector(".modo-lectura");
            if (span) span.textContent = selectedText;
        });
    }

    setTimeout(() => {
        actualizarResultadoSegunBYE();
        controlarOpcionesBye();
    }, 0);
});

// Mostrar/ocultar victoria automática
function actualizarResultadoSegunBYE() {
    document.querySelectorAll("tbody tr").forEach(fila => {
        const id1 = fila.querySelector("select[name='id1[]']");
        const id2 = fila.querySelector("select[name='id2[]']");
        const celda = fila.querySelector(".resultado-celda");
        const select = celda?.querySelector(".resultado-select");
        const auto = celda?.querySelector(".resultado-auto");

        if (!id1 || !id2 || !celda || !select || !auto) return;

        const valor1 = id1.value;
        const valor2 = id2.value;

        const esBye = valor1 === 'bye' || valor2 === 'bye';

        // Mostrar/ocultar victoria automática
        select.classList.toggle("d-none", esBye);
        auto.classList.toggle("d-none", !esBye);

        // Cambiar texto si hay BYE
        if (esBye) {
            auto.textContent = valor1 === 'bye' ? "0 - 1" : "1 - 0";
        }

        // Añadir o quitar color de fondo
        fila.classList.toggle("table-warning", esBye);
    });
}

// Solo permitir un BYE
function controlarOpcionesBye() {
    const selects = document.querySelectorAll("select[name='id1[]'], select[name='id2[]']");
    let byeSeleccionado = false;

    selects.forEach(s => { if (s.value === 'bye') byeSeleccionado = true; });

    selects.forEach(select => {
        const opciones = select.querySelectorAll("option[value='bye']");
        opciones.forEach(opt => {
            opt.disabled = byeSeleccionado && select.value !== 'bye';
            opt.hidden = byeSeleccionado && select.value !== 'bye';
        });
    });
}

// Eliminar fila
document.addEventListener("click", function (e) {
    if (e.target.closest(".eliminar-fila")) {
        const fila = e.target.closest("tr");

        // Confirmación antes de eliminar
        const confirmar = confirm("¿Estás seguro de que deseas eliminar este enfrentamiento?");
        if (!confirmar) return;

        if (fila) fila.remove();
        actualizarResultadoSegunBYE();
        controlarOpcionesBye();
    }
});

// Añadir nueva fila
btnAñadir.addEventListener("click", function () {
    const confirmar = confirm("¿Estás seguro de que deseas añadir un nuevo enfrentamiento?");
    if (!confirmar) return;

    const tbody = document.querySelector("tbody");

    const fila = document.createElement("tr");
    fila.className = "text-center align-middle";
    fila.innerHTML = `
        <td>
            <select name="id1[]" class="form-select modo-edicion">
                ${generarOpcionesJugadores("Jugador 1")}
            </select>
            <span class="modo-lectura d-none"></span>
        </td>
        <td>vs</td>
        <td>
            <select name="id2[]" class="form-select modo-edicion">
                ${generarOpcionesJugadores("Jugador 2")}
            </select>
            <span class="modo-lectura d-none"></span>
        </td>
        <td class="resultado-celda">
            <select name="resultados[]" class="form-select p-1 resultado-select">
                <option value="" selected disabled>? - ?</option>
                <option value="1-0">1 - 0</option>
                <option value="0-1">0 - 1</option>
                <option value="1-1">\u00bd - \u00bd</option>
            </select>
            <span class="text-success fw-semibold d-none resultado-auto">Victoria automática</span>
        </td>
        <td class="modo-edicion text-center">
            <button type="button" class="btn btn-sm btn-outline-danger eliminar-fila" title="Eliminar enfrentamiento">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(fila);

    actualizarResultadoSegunBYE();
    controlarOpcionesBye();

    fila.querySelectorAll("select[name='id1[]'], select[name='id2[]']").forEach(select => {
        select.addEventListener("change", () => {
            actualizarResultadoSegunBYE();
            controlarOpcionesBye();
        });
    });
});

// Añadir eventos "change" a selects existentes al cargar
document.querySelectorAll("select[name='id1[]'], select[name='id2[]']").forEach(select => {
    select.addEventListener("change", () => {
        actualizarResultadoSegunBYE();
        controlarOpcionesBye();
    });
});

// Inicialización
actualizarResultadoSegunBYE();
controlarOpcionesBye();

let cambiosDetectados = false;

// Detectar cualquier cambio en selects
document.querySelectorAll("select").forEach(select => {
    select.addEventListener("change", () => {
        cambiosDetectados = true;
    });
});
</script>
