<div class="container bg-white p-3 rounded shadow">
    <div class="container d-flex p-0 pb-3 justify-content-between align-items-center">
        <a href="<?= $index ?>ControladorAuth/inicio" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i>
        </a>
        <h2 class="text-center">Seleccionar Liga</h2>
    </div>

    <div class="d-flex flex-column gap-2 text-center">
        <a href="<?= $index ?>ControladorLigas/seleccionarTorneo?id=1&liga=Local" class="btn btn-secondary">LIGA LOCAL</a>
        <a href="<?= $index ?>ControladorLigas/seleccionarTorneo?id=2&liga=Infantil" class="btn btn-secondary">LIGA INFANTIL</a>
    </div>
</div>