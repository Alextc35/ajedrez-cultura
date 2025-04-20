<?php
require_once '../src/models/AlumnosDAO.php';
require_once '../src/models/TorneosDAO.php';
require_once '../src/models/EnfrentamientosDAO.php';
class ControladorEnfrentamientos
{
    public string $page_title = "";
    public string $view = "";
    private AlumnosDAO $alumnosObj;
    private TorneosDAO $torneosDAO;
    private EnfrentamientosDAO $enfrentamientosDAO;
    private Config $config;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
        $this->torneosDAO = new TorneosDAO();
        $this->enfrentamientosDAO = new EnfrentamientosDAO();
        $this->config = Config::getInstancia();
    }

    public function enfrentar() {
        $liga = $_GET['liga'] ?? 'Local';

        $this->page_title = "Ajedrez Cultura | Enfrentar";
        $this->view = 'enfrentamientos/enfrentar';

        unset($_SESSION['dataToView'], $dataToView['session']['dataToView']);

        return $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga));
    }

    public function asignarResultados() {
        $config = Config::getInstancia();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $liga = $_POST['liga'] ?? 'Local';
            $torneoId = $_POST['torneoId'] ?? null;
            
            if ($liga === 'Local') {
                $TorneoId = 4;
            } else {
                $TorneoId = 3;
            }
            $ids = $_POST['ids'] ?? [];

            if (count($ids) < 2) {
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/seleccionarTorneo?id=" . $TorneoId . "&liga=" . urlencode($liga));
                exit();
            }

            shuffle($ids);

            $jugadores = [];
            foreach ($ids as $id) {
                $alumno = $this->alumnosObj->getAlumno($id);
                if ($alumno) {
                    $jugadores[] = [ 'id' => $id, 'nombre' => $alumno['nombre'] ];
                }
            }

            if (empty($jugadores)) {
                // $_SESSION['error'] = "No se encontraron jugadores seleccionados.";
                header("Location: " . $config->getParametro('DEFAULT_INDEX') . "ControladorLigas/seleccionarTorneo?id=" . $TorneoId . "&liga=" . urlencode($liga));
                exit();
            }

            $this->page_title = 'Ajedrez Cultura | Asignar Resultados';
            $this->view = 'enfrentamientos/asignarResultados';

            $alumnosLiga = $this->alumnosObj->getAlumnosPorLiga($liga);

            return [
                'jugadores' => $jugadores,
                'liga' => $liga,
                'torneoId' => $torneoId,
                'alumnos' => $alumnosLiga
            ];

        }
    }

    public function asignarResultadosProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Acceso no permitido.");
        }
    
        $id1s = $_POST['id1'] ?? [];
        $id2s = $_POST['id2'] ?? [];
        $resultados = $_POST['resultados'] ?? [];
        $liga = $_POST['liga'] ?? 'Local';
        $torneoId = $_POST['torneoId'] ?? null;
    
        $torneo = $this->torneosDAO->getTorneoPorId($torneoId);
    
        if (!$torneo) {
            die("No se encontró un torneo activo para la liga $liga.");
        }
    
        $torneoId = $torneo['id'];
    
        for ($i = 0; $i < count($resultados); $i++) {
            $id1 = $id1s[$i] === 'bye' ? null : $id1s[$i];
            $id2 = $id2s[$i] === 'bye' ? null : $id2s[$i];
            $resultadoTexto = $resultados[$i];
        
            if (empty($id1) && empty($id2)) continue;

            // Si hay BYE
            if (is_null($id1) || is_null($id2)) {
                $resultado = is_null($id1) ? 'negras' : 'blancas';
                $this->enfrentamientosDAO->crearEnfrentamiento($torneoId, $id1, $id2, $resultado, date('Y-m-d'));
                continue;
            }
    
            // 🧠 Resultado estándar
            if ($resultadoTexto === '1-0') {
                $resultado = 'blancas';
            } elseif ($resultadoTexto === '0-1') {
                $resultado = 'negras';
            } elseif ($resultadoTexto === '1-1') {
                $resultado = 'tablas';
            } else {
                continue; // Formato inválido
            }
    
            // Guardar enfrentamiento
            $this->enfrentamientosDAO->crearEnfrentamiento(
                $torneoId,
                $id1,
                $id2,
                $resultado,
                date('Y-m-d')
            );
        }
    
        // Redirigir a la clasificación de la liga
        header("Location: " . $this->config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?torneoId=" . urlencode($torneoId) . "&liga=" . urlencode($liga));
        exit();
    }       
    
    public function actualizarResultado($enfrentamientoId, $resultado) {
        $sql = "UPDATE enfrentamientos SET resultado = :resultado WHERE id = :id";
        $stmt = Conexion::getInstancia()->getConexion()->prepare($sql);
        return $stmt->execute([
            ':resultado' => $resultado,
            ':id' => $enfrentamientoId
        ]);
    }
    
}