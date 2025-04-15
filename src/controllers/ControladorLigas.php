<?php
require_once '../src/models/AlumnosDAO.php';
class ControladorLigas
{
    public string $page_title = "";
    public string $view = "";
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->alumnosObj = new AlumnosDAO();
    }

    public function clasificacion() {
        $liga = $_GET['liga'] ?? null;
        $this->page_title = "Chess League | $liga";
        $this->view = 'alumnos/clasificacion';

        return ($liga) ? $this->alumnosObj->getAlumnosPorLiga(htmlspecialchars($liga)) : $this->alumnosObj->getAlumnos();
    }
}