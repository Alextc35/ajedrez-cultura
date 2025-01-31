<?php
session_start();

require_once('tcpdf/tcpdf.php'); // Si descargaste TCPDF manualmente

if (!isset($_SESSION['dataToView'])) {
    die("No hay datos para generar el PDF");
}

$dataToView = $_SESSION['dataToView'];
$fecha = date('d/m/Y');

// Crear nuevo documento PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('LIGA LOCAL');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "LIGA LOCAL - $fecha", 0, 1, 'C');

// Espacio antes de la tabla
$pdf->Ln(5);

// Cabecera de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$html = '<table border="1" cellpadding="5">
            <thead>
                <tr style="background-color:#007BFF;color:#ffffff;">
                    <th>Posición</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Victorias</th>
                    <th>Derrotas</th>
                    <th>Tablas</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody>';

// Ordenar los datos antes de generar el PDF
usort($dataToView['data'], function ($a, $b) {
    $puntosA = ($a['victorias'] * 1) + ($a['tablas'] * 0.5);
    $puntosB = ($b['victorias'] * 1) + ($b['tablas'] * 0.5);
    return $puntosB <=> $puntosA; // Ordenar de mayor a menor
});

// Agregar los datos al PDF
$cont = 1;
foreach ($dataToView['data'] as $alumno) {
    $puntos = ($alumno['victorias'] * 1) + ($alumno['tablas'] * 0.5);
    $html .= '<tr>
                <td>' . $cont++ . '</td>
                <td>' . htmlspecialchars($alumno['nombre']) . '</td>
                <td>' . htmlspecialchars($alumno['categoria']) . '</td>
                <td>' . $alumno['victorias'] . '</td>
                <td>' . $alumno['derrotas'] . '</td>
                <td>' . $alumno['tablas'] . '</td>
                <td>' . number_format($puntos, 1) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Agregar tabla al PDF
$pdf->writeHTML($html, true, false, false, false, '');

// Salida del archivo
$pdf->Output('clasificacion_alumnos.pdf', 'D'); // 'D' fuerza la descarga
?>