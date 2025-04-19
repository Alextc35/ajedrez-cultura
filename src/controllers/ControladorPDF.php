<?php

class ControladorPDF
{
    public function generarPDF() {
        require_once('libs/tcpdf/tcpdf.php');
        
        if (!isset($_SESSION['dataToView']) || empty($_SESSION['dataToView']['data'])) {
            die("No hay datos para generar el PDF");
        }
        
        $dataToView = $_SESSION['dataToView'];
        $fecha = date('d/m/Y');
        // Obtener la categoría desde la URL
        $liga = isset($_GET['liga']) ? urldecode($_GET['liga']) : 'LIGA LOCAL';
        
        // Filtrar los datos según la categoría
        $dataToView['data'] = array_filter($dataToView['data'], function ($alumno) use ($liga) {
            print_r ($alumno);
            exit;
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
    }

    public function generarPDF2() {
        require_once('libs/tcpdf/tcpdf.php');

        if (!isset($_SESSION['dataToView']) || empty($_SESSION['dataToView']['data'])) {
            die("No hay datos para generar el PDF");
        }

        $dataToView = $_SESSION['dataToView'];
        $fecha = date('d/m/Y');

        // Ordenar alfabéticamente por nombre
        usort($dataToView['data'], fn($a, $b) => $a['nombre'] <=> $b['nombre']);

        // Crear PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Horizontal
        $pdf->SetTitle("Asistencia Escuela Ajedrez - $fecha");
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, "ESCUELA DE AJEDREZ MEJORADA DEL CAMPO 2024 – 2025", 0, 1, 'C');
        $pdf->Ln(3);

        // Tabla de asistencia
        $pdf->SetFont('helvetica', '', 10);
        $html = '<table border="1" cellpadding="4">
                    <thead style="background-color:#f2f2f2;">
                        <tr>
                            <th><b>ALUMNO</b></th>
                            <th><b>AÑO</b></th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dic</th>
                            <th>Ene</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Abr</th>
                            <th>May</th>
                            <th>Jun</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($dataToView['data'] as $alumno) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($alumno['nombre']) . '</td>
                        <td>' . htmlspecialchars($alumno['anio_nacimiento']) . '</td>';
            for ($i = 0; $i < 9; $i++) {
                $html .= '<td></td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, '');

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output("Asistencia_Ajedrez.pdf", 'D'); // Forzar descarga

        exit;
    } 
}