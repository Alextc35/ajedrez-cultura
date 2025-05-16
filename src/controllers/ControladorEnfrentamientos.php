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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $liga = $_POST['liga'] ?? 'Local';
            $torneoId = $_POST['torneoId'] ?? null;

            $ids = $_POST['ids'] ?? [];

            if (count($ids) < 2) {
                header("Location: " . $this->config->getParametro('DEFAULT_INDEX') . "ControladorLigas/seleccionarTorneo?id=" . $torneoId . "&liga=" . urlencode($liga));
                exit();
            }
            
            $jugadores = [];
            foreach ($ids as $id) {
                $alumno = $this->alumnosObj->getAlumno($id);
                if ($alumno) {
                    $jugadores[] = [ 'id' => $id, 'nombre' => $alumno['nombre'] ];
                }
            }

            if (empty($jugadores)) {
                // $_SESSION['error'] = "No se encontraron jugadores seleccionados.";
                header("Location: " . $this->config->getParametro('DEFAULT_INDEX') . "ControladorLigas/seleccionarTorneo?id=" . $torneoId . "&liga=" . urlencode($liga));
                exit();
            }

            // Obtener historial de enfrentamientos del torneo
            $historial = $this->enfrentamientosDAO->getHistorialPorTorneo($torneoId);
            // echo "<pre>";
            // var_dump($historial); exit();
            // echo "</pre>";

            // Determinar el último color jugado por cada jugador
            $ultimoColor = [];
            foreach($historial as $partida) {
                $fecha = strtotime($partida['fecha']);
                $alumno1 = $partida['alumno1_id'];
                $alumno2 = $partida['alumno2_id'];

                if (!isset($ultimoColor[$alumno1]) || $fecha > $ultimoColor[$alumno1]['fecha']) {
                    $ultimoColor[$alumno1] = ['color' => 'blancas', 'fecha' => $fecha];
                }
                if (!isset($ultimoColor[$alumno2]) || $fecha > $ultimoColor[$alumno2]['fecha']) {
                    $ultimoColor[$alumno2] = ['color' => 'negras', 'fecha' => $fecha];
                }
            }

            // Aleatorizar orden de jugadores
            shuffle($jugadores);
            
            // Emparejamientos inteligentes
            $jugadoresNoEmparejados = $jugadores;
            $emparejamientos = [];

            $maxIntentos = count($jugadoresNoEmparejados) * 2;
            $intentos = 0;

            while (count($jugadoresNoEmparejados) >= 2 && $intentos < $maxIntentos) {
                $jugador1 = array_shift($jugadoresNoEmparejados);

                $encontrado = false;
                foreach ($jugadoresNoEmparejados as $i => $jugador2) {
                    // Alternancia de colores
                    $color1 = $ultimoColor[$jugador1['id']]['color'] ?? null;
                    $color2 = $ultimoColor[$jugador2['id']]['color'] ?? null;

                    if ($color1 === 'negras' || ($color2 === 'blancas' && $color1 !== 'blancas')) {
                        $blancas = $jugador1;
                        $negras = $jugador2;
                    } else {
                        $blancas = $jugador2;
                        $negras = $jugador1;
                    }

                    // Bloquea si ya jugaron con esa misma combinación de colores
                    if ($this->yaJugaronMismaCombinacion($blancas['id'], $negras['id'], $historial)) {
                        continue;
                    }

                    $emparejamientos[] = [
                        'id1' => $blancas['id'],
                        'id2' => $negras['id'],
                        'nombre1' => $blancas['nombre'],
                        'nombre2' => $negras['nombre'],
                    ];

                    unset($jugadoresNoEmparejados[$i]);
                    $jugadoresNoEmparejados = array_values($jugadoresNoEmparejados);
                    $encontrado = true;
                    break;
                }

                // Si no encontró rival válida, lo deja para el fiinal
                if (!$encontrado) {
                    array_push($jugadoresNoEmparejados, $jugador1);
                }

                $intentos++;
            }

            // Si queda un jugador solo => BYE
            if (count($jugadoresNoEmparejados) === 1) {
                $solo = $jugadoresNoEmparejados[0];
                $emparejamientos[] = [
                    'id1' => $solo['id'],
                    'id2' => 'bye',
                    'nombre1' => $solo['nombre'],
                    'nombre2' => 'BYE'
                ];
            }

            $this->page_title = 'Ajedrez Cultura | Asignar Resultados';
            $this->view = 'enfrentamientos/asignarResultados';
            $alumnosLiga = $this->alumnosObj->getAlumnosPorLiga($liga);

            // echo "<pre>";
            // var_dump($emparejamientos); exit();
            // echo "</pre>";
            
            return [
                'jugadores' => $emparejamientos,
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
    
    private function yaJugaronMismaCombinacion($blancasId, $negrasId, $historial) {
        foreach ($historial as $partida) {
            if ($partida['alumno1_id'] == $blancasId && $partida['alumno2_id'] == $negrasId) {
                return true;
            }
        }
        return false;
    }

}