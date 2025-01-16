<?php

namespace App\Libraries;

class PdfGenerator
{
    public function generate($html, $file_name, $paper = 'A4', $orientation = 'portrait')
    {
        // Logic untuk menghasilkan PDF (contohnya menggunakan dompdf atau tcpdf)
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        $dompdf->stream($file_name . '.pdf');
    }
}
