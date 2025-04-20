<?php

class ControladorPDF
{
    public function generarPDF() {
        require_once('libs/tcpdf/tcpdf.php');
    
        if (!isset($_SESSION['dataToView']) || empty($_SESSION['dataToView']['data']['alumnos'])) {
            die("No hay datos para generar el PDF");
        }
    
        $dataToView = $_SESSION['dataToView'];
        $fecha = date('d/m/Y');
        $liga = isset($_GET['liga']) ? urldecode($_GET['liga']) : 'Local';
    
        // Filtrar alumnos por liga
        $dataToView['data']['alumnos'] = array_filter($dataToView['data']['alumnos'], function ($alumno) use ($liga) {
            return isset($alumno['liga']) && $alumno['liga'] === $liga;
        });
    
        if (empty($dataToView['data']['alumnos'])) {
            die("No hay datos filtrados por liga: $liga");
        }
    
        // Ordenar por puntuación
        usort($dataToView['data']['alumnos'], function ($a, $b) {
            $puntosA = $a['victorias'] + $a['tablas'] * 0.5;
            $puntosB = $b['victorias'] + $b['tablas'] * 0.5;
            return $puntosB <=> $puntosA;
        });
    
        // Crear PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle("Clasificación - Liga $liga");
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
    
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, "Clasificación - Liga $liga ($fecha)", 0, 1, 'C');
        $pdf->Ln(5);
    
        $pdf->SetFont('helvetica', '', 11);
    
        $html = '
        <table border="1" cellpadding="5">
            <thead>
                <tr style="background-color:#007BFF; color:white;">
                    <th>Posición</th>
                    <th>Nombre</th>
                    <th>Victorias</th>
                    <th>Derrotas</th>
                    <th>Tablas</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody>
        ';
    
        $pos = 1;
        foreach ($dataToView['data']['alumnos'] as $alumno) {
            $puntos = $alumno['victorias'] + $alumno['tablas'] * 0.5;
    
            $html .= '
            <tr>
                <td>' . $pos++ . '°</td>
                <td>' . htmlspecialchars($alumno['nombre']) . '</td>
                <td>' . $alumno['victorias'] . '</td>
                <td>' . $alumno['derrotas'] . '</td>
                <td>' . $alumno['tablas'] . '</td>
                <td>' . number_format($puntos, 1) . '</td>
            </tr>';
        }
    
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
    
        if (ob_get_length()) {
            ob_end_clean(); // Previene errores de salida
        }
    
        $pdf->Output("Clasificacion_{$liga}.pdf", 'D'); // Forzar descarga
        exit;
    }    
}