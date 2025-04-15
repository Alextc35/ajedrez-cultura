<?php
require_once '../src/models/LoginDAO.php';
class ControladorAuth
{
    public string $page_title = "";
    public string $view = "";
    private LoginDAO $loginDAO;

    public function __construct() {
        $this->loginDAO = new LoginDAO();
    }

    public function login() {
        $config = Config::getInstancia();
        $this->page_title = 'Chess League | Inicio de sesión';
        $this->view = 'auth/login';

        if (isset($_POST['login'])) {
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($usuario && $password) {
                $verificado = $this->loginDAO->comprobarUsuario($usuario, $password);
                if ($verificado) {
                    $_SESSION['usuario'] = $usuario;
                    header("Location:" .  $config->getParametro('DEFAULT_INDEX') . "ControladorAuth/inicio");
                } else {
                    $this->page_title = 'Chess League | Fallo de sesión';
                    $_SESSION['error'] = "El usuario o la password no coinciden";
                }
            } else {
                $_SESSION['error'] = "Por favor, completa todos los campos";
            }
        }
    }

    public function inicio() {
        unset($_SESSION['dataToView'], $_SESSION['jugadores'], $_SESSION['liga']);
        $this->page_title = 'Chess League | Inicio';
        $this->view = 'auth/inicio';
    }

    public function logout() {
        $config = Config::getInstancia();
        unset($_SESSION['usuario']);
        session_destroy();
        header("Location:" . $config->getParametro('DEFAULT_INDEX'));
    }
}
