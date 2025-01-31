<?php
require_once 'model/AlumnosDAO.php';

class ControladorAlumnos
{
    public string $page_title;
    public string $view;
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->view = 'ligaLocal';
        $this->page_title = 'Liga de Ajedrez';
        $this->alumnosObj = new AlumnosDAO();
    }

    // Devuelve todos los alumnos
    public function index() {
        $this->page_title = 'Alumnos';
        return $this->alumnosObj->getAlumnos();
    }
}