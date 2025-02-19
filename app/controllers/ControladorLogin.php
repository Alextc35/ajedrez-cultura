<?php
require_once '../app/models/LoginDAO.php';
class ControladorLogin
{
    # 🧬 Atributos
    public string $page_title;
    public string $view;
    private LoginDAO $loginDAO;

    # 👷 Constructor
    public function __construct() {
        $this->loginDAO = new LoginDAO();
    }

    # 🛠️ Métodos
    // Iniciar sesión
    public function login() {
        $this->page_title = 'Chess League | Inicio de sesión';
        $this->view = 'pages/login';

        if (isset($_POST['login'])) {

            // Obtener el usuario y la contraseña
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($usuario && $password) {
                // Llamo al metodo comprobarUsuario
                $verificado = $this->loginDAO->comprobarUsuario($usuario, $password);
                if ($verificado) {
                    // Almacenamos el nombre de la sesión
                    $_SESSION['usuario'] = $usuario;
                    header("Location: " . constant('DEFAULT_INDEX') . "ControladorAlumnos/inicio");
                } else {
                    $this->page_title = 'Chess League | Fallo de sesión';
                    $_SESSION['error'] = "El usuario o la password no coinciden";
                }
            } else {
                $_SESSION['error'] = "Por favor, completa todos los campos";
            }
        }
    }

    // Acción de eliminar la sesión
    public function logout() {
        unset($_SESSION['usuario']); // Eliminar la sesión
        session_destroy();
        header("Location:" . constant('DEFAULT_INDEX'));
    }
}
