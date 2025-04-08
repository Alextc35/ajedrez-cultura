<?php
session_start();

require_once('libs/tcpdf/tcpdf.php'); // Si descargaste TCPDF manualmente

if (!isset($_SESSION['dataToView']) || empty($_SESSION['dataToView']['data'])) {
    die("No hay datos para generar el PDF");
}

$dataToView = $_SESSION['dataToView'];
$fecha = date('d/m/Y');
// Obtener la categoría desde la URL
$liga = isset($_GET['liga']) ? urldecode($_GET['liga']) : 'LIGA LOCAL';

// Filtrar los datos según la categoría
$dataToView['data'] = array_filter($dataToView['data'], function ($alumno) use ($liga) {
    return $alumno['liga'] === $liga;
});

if (empty($dataToView['data'])) {
    die("No hay datos filtrados por liga: $liga");
}

// Crear nuevo documento PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle("Clasificación - $liga");
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "$liga - $fecha", 0, 1, 'C');

// Espacio antes de la tabla
$pdf->Ln(5);

// Cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$html = '<table border="1" cellpadding="5">
            <thead>
                <tr style="background-color:#007BFF;color:#ffffff;">
                    <th>Posición</th>
                    <th>Nombre</th>
                    <th>Victorias</th>
                    <th>Derrotas</th>
                    <th>Tablas</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody>';

// Ordenar los datos antes de generar el PDF
usort($dataToView['data'], function ($player1, $player2) {
    $puntosPlayer1 = ($player1['victorias'] * 1) + ($player1['tablas'] * 0.5);
    $puntosPlayer2 = ($player2['victorias'] * 1) + ($player2['tablas'] * 0.5);
    return $puntosPlayer2 <=> $puntosPlayer1; // Ordenar de mayor a menor
});

// Agregar los datos al PDF
$cont = 1;
foreach ($dataToView['data'] as $alumno) {
    $puntos = ($alumno['victorias'] * 1) + ($alumno['tablas'] * 0.5);
    $html .= '<tr>
                <td>' . $cont++ . '°</td>
                <td>' . $alumno['nombre'] . '</td>
                <td>' . $alumno['victorias'] . '</td>
                <td>' . $alumno['derrotas'] . '</td>
                <td>' . $alumno['tablas'] . '</td>
                <td>' . number_format($puntos, 1) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Agregar tabla al PDF
$pdf->writeHTML($html, true, false, false, false, '');

if (ob_get_length()) {
    ob_end_clean(); // Limpia cualquier salida previa
}

// Salida del archivo
$pdf->Output("$liga.pdf", 'D'); // 'D' fuerza la descarga

exit;
//
//unset($_SESSION['dataToView']);
?>