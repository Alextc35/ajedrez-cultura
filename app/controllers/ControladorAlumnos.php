<?php
require_once '../app/models/AlumnosDAO.php';

class ControladorAlumnos
{
    public string $page_title;
    public string $view;
    private AlumnosDAO $alumnosObj;

    public function __construct() {
        $this->view = 'clasificacion';
        $this->page_title = 'Liga de Ajedrez';
        $this->alumnosObj = new AlumnosDAO();
    }

    public function inicio() {
        $this->page_title = 'Inicio';
        $this->view = 'inicio';
    }

    public function ligaLocal() {
        $this->page_title = 'Liga Local';
        $dataToView['data'] = $this->alumnosObj->getAlumnos();

        // Filtrar solo alumnos de LIGA LOCAL
        $dataToView['data'] = array_filter($dataToView['data'], function ($alumno) {
            return $alumno['categoria'] === 'LIGA LOCAL';
        });
        require_once '../app/views/clasificacion.php'; // Vista única
    }

    public function ligaInfantil() {
        $this->page_title = 'Liga Infantil';
        $dataToView['data'] = $this->alumnosObj->getAlumnos();

        // Filtrar solo alumnos de LIGA INFANTIL
        $dataToView['data'] = array_filter($dataToView['data'], function ($alumno) {
            return $alumno['categoria'] === 'LIGA INFANTIL';
        });
        require_once '../app/views/clasificacion.php';
    }
}