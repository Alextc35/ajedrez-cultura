<?php
require_once '../src/models/AlumnosDAO.php';
class ControladorEnfrentamientos
{
    public string $page_title = "";
    public string $view = "";
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
    }

    public function enfrentar() {
        $liga = $_GET['liga'] ?? 'LIGA LOCAL';
        $this->page_title = "Chess League | Enfrentar";
        $this->view = 'enfrentamientos/enfrentar';
        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }

    public function asignarResultados() {
        $config = Config::getInstancia();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $liga = $_POST['liga'] ?? 'LIGA LOCAL';
            $ids = $_POST['ids'] ?? [];

            if (count($ids) < 2) {
                // $_SESSION['error'] = "Debes seleccionar al menos 2 jugadores.";
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
                exit();
            }

            shuffle($ids);

            $jugadores = [];
            foreach ($ids as $id) {
                $alumno = $this->alumnosObj->getAlumno($id);
                if ($alumno) {
                    $jugadores[$id] = $alumno['nombre'];
                }
            }
            if (empty($jugadores)) {
                // $_SESSION['error'] = "No se encontraron jugadores seleccionados.";
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
                exit();
            }

            $_SESSION['jugadores'] = $jugadores;
            $_SESSION['liga'] = $liga;

            $this->page_title = 'Chess League | Asignar Resultados';
            $this->view = 'enfrentamientos/asignarResultados';

        }
    }

    public function asignarResultadosProcess() {
        $config = Config::getInstancia();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $liga = $_POST['liga'] ?? 'LIGA LOCAL';
            $id1s = $_POST['id1'] ?? [];
            $id2s = $_POST['id2'] ?? [];
            $resultados = $_POST['resultados'] ?? [];
    
            if (empty($id1s) || empty($id2s) || empty($resultados)) {
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorEnfrentamientos/enfrentar?liga=" . urlencode($liga));
                exit();
            }
    
            for ($i = 0, $j = 0; $i < count($id1s); $i++) {
                $rawId1 = $id1s[$i];
                $rawId2 = $id2s[$i];
            
                // Si alguno es BYE → victoria automática
                if ($rawId1 === "bye" || $rawId2 === "bye") {
                    $jugador = $rawId1 === "bye" ? intval($rawId2) : intval($rawId1);
                    if ($jugador > 0) {
                        $this->alumnosObj->updateResultado($jugador, 'victoria');
                    }
                    continue; // no se incrementa $j
                }
            
                // Enfrentamiento real: usamos $resultados[$j]
                $id1 = intval($rawId1);
                $id2 = intval($rawId2);
                $resultado = $resultados[$j]; // 🔁 este índice solo avanza si no hay BYE
                $j++;
            
                switch ($resultado) {
                    case '1-0':
                        $this->alumnosObj->updateResultado($id1, 'victoria');
                        $this->alumnosObj->updateResultado($id2, 'derrota');
                        break;
                    case '0-1':
                        $this->alumnosObj->updateResultado($id1, 'derrota');
                        $this->alumnosObj->updateResultado($id2, 'victoria');
                        break;
                    case '1-1':
                        $this->alumnosObj->updateResultado($id1, 'tablas');
                        $this->alumnosObj->updateResultado($id2, 'tablas');
                        break;
                }
            }            
            header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?liga=" . urlencode($liga));
            exit();
        }
    }    
}