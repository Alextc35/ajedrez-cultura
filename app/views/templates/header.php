<?php
    $paginaActual = $arrHandler['action'] ?? ''; // Obtener la acción actual
    $index = constant('DEFAULT_INDEX');
    // Si el usuario no está autenticado o está en 'assign', deshabilitamos los enlaces
    $deshabilitado = $paginaActual === 'generateMatches' || !isset($_SESSION['usuario']) ? 'disabled' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $controller->page_title ?></title>
    <link rel="stylesheet" href="/ajedrez-cultura/public/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ajedrez-cultura/public/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/ajedrez-cultura/public/assets/css/style.min.css">
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-items-center w-auto m-3">
        <div class="container text-center rounded shadow bg-light p-2 m-2">
            <h1>Selecciona la Liga</h1>
            <p>Elige la liga a la que deseas acceder:</p>
            <a href="<?= $index ?>ControladorAlumnos/list?liga=LIGA LOCAL" class="btn btn-primary <?= $deshabilitado ?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>LIGA LOCAL</a>
            <a href="<?= $index ?>ControladorAlumnos/list?liga=LIGA INFANTIL" class="btn btn-success <?= $deshabilitado ?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>LIGA INFANTIL</a>
            <p class="text-muted pt-2 ">Desarrollado por <a href="https://www.linkedin.com/in/alejandrotellezcorona/" target="_blank" class="text-decoration-none text-muted fw-bold">Alejandro Téllez Corona</a></p>
        </div>