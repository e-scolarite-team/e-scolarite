<?php

require_once("inc/pagebase.php");
//require_once('dompdf/dompdf_config.inc.php');
require_once('MPDF/mpdf.php');

class Acceuil extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre(){}
    public function genererContenuBody(){}
    

    public function verification()
    {
        parent::verification();
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
        if(!isset($_SESSION['pcne']) or !isset($_SESSION['pr']) or !isset($_SESSION['html']))
        {
            redirectToPage('preinscription.php');
            exit();
        }

        $html = $_SESSION['html'];
/*        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("preinscription.pdf");
*/
        $mpdf=new mPDF(); 

        $mpdf->useAdobeCJK = true;      // Default setting in config.php
                                // You can set this to false if you have defined other CJK fonts

        $mpdf->SetAutoFont(AUTOFONT_ALL);   //  AUTOFONT_CJK | AUTOFONT_THAIVIET | AUTOFONT_RTL | AUTOFONT_INDIC    // AUTOFONT_ALL
                                // () = default ALL, 0 turns OFF (default initially)

        $mpdf->WriteHTML($html);

        $mpdf->Output('Preinscription.pdf','D');
        exit;
    }
    
    
}

$page = new Acceuil();
$page->traiter_requete();