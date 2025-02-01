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

    public function edit() {
        $this->page_title = 'Editar Alumnos';
        $dataToView['data'] = $this->alumnosObj->getAlumnos();
    
        // 📌 Determinar la categoría desde la URL (Si no está, usa LIGA LOCAL por defecto)
        $categoria = isset($_GET['categoria']) && $_GET['categoria'] === 'LIGA INFANTIL' ? 'LIGA INFANTIL' : 'LIGA LOCAL';
    
        // 📌 Filtrar alumnos según la categoría seleccionada
        $dataToView['data'] = array_filter($dataToView['data'], function ($alumno) use ($categoria) {
            return $alumno['categoria'] === $categoria;
        });
    
        // Pasar la categoría a la vista
        $dataToView['categoria'] = $categoria;
        $this->view = 'edit';
        require_once '../app/views/edit.php';
    }
    
    public function add() {
        $this->page_title = 'Añadir Alumno';
        $this->view = 'add';
        require_once '../app/views/add.php'; // Cargar la vista del formulario
    }
    
    public function delete() {
        $this->page_title = 'Eliminar Alumno';
        $this->view = 'delete';
        require_once '../app/views/delete.php'; // Cargar la vista de eliminación
    }
    
}