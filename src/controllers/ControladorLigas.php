<?php
require_once '../src/models/AlumnosDAO.php';
require_once '../src/models/TorneosDAO.php';
require_once '../src/models/EnfrentamientosDAO.php';

class ControladorLigas
{
    public string $page_title = "";
    public string $view = "";
    private AlumnosDAO $alumnosDAO;
    private TorneosDAO $torneosDAO;
    private EnfrentamientosDAO $enfrentamientosDAO;
    private Config $config;

    public function __construct() {
        $this->alumnosDAO = new AlumnosDAO();
        $this->torneosDAO = new TorneosDAO();
        $this->enfrentamientosDAO = new EnfrentamientosDAO();
        $this->config = Config::getInstancia();
    }

    public function seleccionarLiga() {
        $this->page_title = "Ajedrez Cultura | Elegir Liga";
        $this->view = 'ligas/seleccionarLiga';

        unset($_SESSION['dataToView'], $_SESSION['jugadores']);
    }

    public function seleccionarTorneo() {
        $id = $_GET['id'] ?? null;
        $liga = $_GET['liga'] ?? null;
    
        if ($id && $liga) {
            $dataToView['torneo']['torneoId'] = $id;
            $dataToView['torneo']['liga'] = $liga;
    
            // Redirigir a la clasificación directamente
            header("Location: " . $this->config->getParametro('DEFAULT_INDEX') . "ControladorLigas/clasificacion?torneoId=" . urlencode($id) . "&liga=" . urlencode($liga));
        } else {
            $_SESSION['error'] = "Torneo o liga no válida.";
            // Puedes redirigir de nuevo a la vista de selección
            header("Location: " . $this->config->getParametro('DEFAULT_INDEX') . "ControladorLigas/seleccionarLiga");
            exit;
        }
    }    
    
    public function clasificacion() {
        $liga = $_GET['liga'] ?? 'Local';
        $torneoId = $_GET['torneoId'] ?? null;
    
        $this->page_title = "Ajedrez Cultura | $liga";
        $this->view = 'ligas/clasificacion';
    
        $torneo = $this->torneosDAO->getTorneoPorId($torneoId);
    
        if (!$torneo) {
            die("No se encontró un torneo activo para la liga $liga.");
        }
    
        $alumnos = $this->alumnosDAO->getAlumnosPorLiga($liga);
        $enfrentamientos = $this->enfrentamientosDAO->getEnfrentamientosPorTorneo($torneoId);
    
        $estadisticas = [];
        foreach ($alumnos as $alumno) {
            $estadisticas[$alumno['id']] = [
                'id' => $alumno['id'],
                'nombre' => $alumno['nombre'],
                'liga' => $liga,
                'victorias' => 0,
                'derrotas' => 0,
                'tablas' => 0
            ];
        }
    
        foreach ($enfrentamientos as $e) {
            $a1 = $e['alumno1_id'];
            $a2 = $e['alumno2_id'];
            $res = $e['resultado'];
        
            // 🟨 BYE: si uno es null, el otro gana automáticamente
            if (is_null($a1) && isset($estadisticas[$a2])) {
                $estadisticas[$a2]['victorias']++;
                continue;
            }
        
            if (is_null($a2) && isset($estadisticas[$a1])) {
                $estadisticas[$a1]['victorias']++;
                continue;
            }
        
            // 🧠 Casos normales
            if ($res === 'tablas') {
                if (isset($estadisticas[$a1])) $estadisticas[$a1]['tablas']++;
                if (isset($estadisticas[$a2])) $estadisticas[$a2]['tablas']++;
            } elseif ($res === 'blancas') {
                if (isset($estadisticas[$a1])) $estadisticas[$a1]['victorias']++;
                if (isset($estadisticas[$a2])) $estadisticas[$a2]['derrotas']++;
            } elseif ($res === 'negras') {
                if (isset($estadisticas[$a2])) $estadisticas[$a2]['victorias']++;
                if (isset($estadisticas[$a1])) $estadisticas[$a1]['derrotas']++;
            }
        }
                
        return [
            'alumnos' => array_values($estadisticas),
            'torneo' => $torneo['nombre']
        ];
    }
}