<?php
require_once '../src/models/UsuariosDAO.php';
require_once '../src/models/LogsLoginDAO.php';
class ControladorAuth
{
    public string $page_title = "";
    public string $view = "";
    public Config $config;
    private UsuariosDAO $usuariosDAO;
    private LogsLoginDAO $logsLoginDAO;

    public function __construct() {
        $this->config = Config::getInstancia();
        $this->usuariosDAO = new UsuariosDAO();
        $this->logsLoginDAO = new LogsLoginDAO();
    }

    public function login() {
        $this->page_title = 'Ajedrez Cultura | Inicio de sesión';
        $this->view = 'auth/login';

        if (isset($_POST['login'])) {
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            if ($usuario && $password) {
                $verificado = $this->usuariosDAO->comprobarUsuario($usuario, $password);
                if ($verificado) {
                    $_SESSION['usuario'] = $usuario;
                    $this->registrarLogin($usuario);
                    header("Location:" .  $this->config->getParametro('DEFAULT_INDEX') . "ControladorAuth/inicio");
                } else {
                    $this->page_title = 'Ajedrez Cultura | Fallo de sesión';
                    $_SESSION['error'] = "El usuario o la password no coinciden";
                }
            }
        }
    }

    public function logout() {
        unset($_SESSION['usuario']);
        session_destroy();
        
        header("Location:" . $this->config->getParametro('DEFAULT_INDEX'));
    }

    public function inicio() {
        $this->page_title = 'Ajedrez Cultura | Inicio';
        $this->view = 'auth/inicio';

        unset($_SESSION['dataToView'], $_SESSION['jugadores'], $_SESSION['liga'],  $_SESSION['torneos'], $_SESSION['torneo_id']);
    }

    private function registrarLogin(string $usuario): void {
        $this->logsLoginDAO->registrarLogin($usuario);
    }
}
