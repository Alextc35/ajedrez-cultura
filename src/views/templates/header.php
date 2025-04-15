<?php
    $titulo = $dataToView['titulo'] ?? '';
    $index = Config::getInstancia()->getParametro('DEFAULT_INDEX');
    $deshabilitado = ($arrHandler['action'] ?? '') === 'asignarResultados' || !isset($_SESSION['usuario']) ? 'disabled' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
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
            <h1>Selecciona la Liga</h1>
            <p>Elige la liga a la que deseas acceder:</p>
            <a href="<?= $index ?>ControladorLigas/clasificacion?liga=LIGA LOCAL" class="btn btn-primary <?= $deshabilitado ?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>LIGA LOCAL</a>
            <a href="<?= $index ?>ControladorLigas/clasificacion?liga=LIGA INFANTIL" class="btn btn-success <?= $deshabilitado ?>" <?= $deshabilitado ? 'tabindex="-1" aria-disabled="true"' : '' ?>>LIGA INFANTIL</a>
            <p class="text-muted pt-2 ">Desarrollado por <a href="https://www.linkedin.com/in/alejandrotellezcorona/" target="_blank" class="text-decoration-none text-muted fw-bold">Alejandro Téllez Corona</a></p>
        </div>