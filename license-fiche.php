<?php

require_once("inc/etudiantbase.php");
//require_once('dompdf/dompdf_config.inc.php');
require_once('MPDF/mpdf.php');

class Acceuil extends BaseEtudiant
{
    public function __construct()
    {
        parent::__construct();
    }

    public function verification()
    {
        parent::verification();
        if(empty($_SESSION['license']))
        {
            redirectToPage('pfe-ins.php');
            exit();
        }

        $html = $_SESSION['license'];
        try
        {
            $mpdf=new mPDF(); 

            $mpdf->useAdobeCJK = true;      // Default setting in config.php
                                    // You can set this to false if you have defined other CJK fonts

            $mpdf->SetAutoFont(AUTOFONT_ALL);   //  AUTOFONT_CJK | AUTOFONT_THAIVIET | AUTOFONT_RTL | AUTOFONT_INDIC    // AUTOFONT_ALL
                                    // () = default ALL, 0 turns OFF (default initially)

            $mpdf->WriteHTML($html);

            $mpdf->Output('License.pdf','D');
            exit;
        }
        catch(Exception $e){}
    }
    
    
}

$page = new Acceuil();
$page->traiter_requete();