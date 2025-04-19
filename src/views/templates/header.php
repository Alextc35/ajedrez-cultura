<?php
    $titulo = $dataToView['controlador']->page_title ?? '';
    $index = Config::getInstancia()->getParametro('DEFAULT_INDEX');
    $deshabilitado = ($dataToView['handler']['action'] ?? '') === 'asignarResultados' || !isset($_SESSION['usuario']) ? 'disabled' : '';
    $gestionAlumnos = ($dataToView['handler']['action'] ?? '') === 'listAlumnos' || !isset($_SESSION['usuario']) ? 'disabled' : '';
    $ligas = ($dataToView['handler']['action'] ?? '') === 'seleccionarLiga' || !isset($_SESSION['usuario']) ? 'disabled' : '';
    $clasificacion = ($dataToView['handler']['action'] ?? '') === 'clasificacion' || !isset($_SESSION['usuario']) ? 'disabled' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link rel="icon" type="image/x-icon" href="/ajedrez-cultura/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous">
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"
      integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD"
      crossorigin="anonymous">
    <link rel="stylesheet" href="/ajedrez-cultura/public/css/style.css">
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-items-center w-auto m-3">
        <div class="container text-center rounded shadow bg-light p-2 m-2">
            <h1>Ajedrez Cultura</h1>
            <p>Elige la opción deseada:</p>
            <a href="<?= $index ?>ControladorAlumnos/listAlumnos" class="btn btn-primary d-block m-2 <?= $deshabilitado ?> <?= $gestionAlumnos ?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>GESTIÓN DE ALUMNOS</a>
            <a href="<?= $index ?>ControladorLigas/seleccionarLiga" class="btn btn-success d-block m-2 <?= $deshabilitado ?> <?= $ligas ?> <?= $clasificacion?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>LIGAS</a>
            <p class="text-muted pt-2 ">Desarrollado por <a href="https://www.linkedin.com/in/alejandrotellezcorona/" target="_blank" class="text-decoration-none text-muted fw-bold">Alejandro Téllez Corona</a></p>
        </div>