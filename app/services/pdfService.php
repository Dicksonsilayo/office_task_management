<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public static function generate($html, $filename = 'report.pdf')
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream($filename, [
            "Attachment" => true
        ]);
    }
}