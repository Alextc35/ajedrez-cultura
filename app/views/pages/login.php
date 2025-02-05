<div class="container bg-white p-3 rounded shadow">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>

                    <!-- Mostrar error si las credenciales son incorrectas -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); // Eliminar el mensaje de error después de mostrarlo ?>
                    <?php endif; ?>

                    <form action="?action=signIn" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Acceder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>