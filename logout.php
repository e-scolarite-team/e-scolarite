<?php
require_once("inc/pagebase.php");
session_start();
session_destroy();
    redirectToPage("index.php");
    exit();


?>