
<?php

require_once ("custom-table/fpdf-html-file.php");

if (isset($_GET['picture']) && isset($_GET['position'])) {
    if (!empty($_GET['picture'])) {
        if (filter_var($_GET['picture'], FILTER_VALIDATE_URL)){

            $image = $_GET['picture'];
            $size = array(140,110);
            $orientation = "L";
            $w = 100;
            $h = 70;
            if ($_GET['position'] == "portrait") {
                $size = array(110,140);
                $orientation = "P";
                $w = 70;
                $h = 100;
            }

            $pdf = new PDF_Clipping($orientation, 'cm', $size);
            $pdf->AddPage();
            $pdf->ClippingRect(20,20,$w,$h,true);
            if ($_GET['position'] == "portrait") {
                $pdf->Image($image,0,10,0,120);
            } else {
                $pdf->Image($image,10,15,120,0);
            }
            $pdf->UnsetClipping();
            $pdf->Output('D', 'tabloide-custom-table.pdf');
            
        }
    }
}

