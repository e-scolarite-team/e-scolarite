<?php

require_once("inc/etudiantbase.php");
require_once('dompdf/dompdf_config.inc.php');

class Acceuil extends BaseEtudiant
{
    public function __construct()
    {
        parent::__construct();
    }

    public function verification()
    {
        parent::verification();
        if(empty($_SESSION['pfe']))
        {
            redirectToPage('pfe-ins.php');
            exit();
        }

        $html = $_SESSION['pfe'];
        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("pfe.pdf");
    }
    
    
}

$page = new Acceuil();
$page->traiter_requete();