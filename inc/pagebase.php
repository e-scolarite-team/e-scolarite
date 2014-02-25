<?php

require_once("inc/handler.php");

abstract class PageBase extends Handler
{
    public $msgr;
    public $msgi;
    
    public function __construct(){}

    public abstract function titre();
    
	protected abstract function genererContenuBody();

	public function emplacementStyles()
	{
		return array();
	}

    public function emplacementScript()
	{
		return array();
	}

	public function inlineStyle()
	{
		return '';
	}

	public function traiter_requete()
	{
	   $this->verification();
		/**
		 * first process any incoming data.
		 */
		if (isset($_REQUEST) and count($_REQUEST) != 0 )
			$this ->traiter_donnees();

		/**
		 * Now emit page contents.
		 */
		$titre = $this->titre();
		$inlineStyle = $this->inlineStyle();
		$emplacementStyles = $this->emplacementStyles();
        $emplacementScript = $this->emplacementScript();

		$this->emitHeaders($titre, $inlineStyle, $emplacementStyles,$emplacementScript);

		echo "<body>\n";

		$this->genererContenuBody();

		echo "</body>\n";
		echo "</html>\n";
	}

	protected function emitHeaders($titre, $inlineStyle, $emplacementStyles,$emplacementScript)
	{
		echo <<<EOHEADERS
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$titre}</title>
EOHEADERS;
		if (count($emplacementStyles)!=0)
		{
			foreach ($emplacementStyles as $loc)
			{
				echo "\n";
				echo <<< EOHEADERS
		<link rel="stylesheet" media="screen" href="{$loc}" />
EOHEADERS;
			}
		}
        
        if (count($emplacementScript)!=0)
		{
			foreach ($emplacementScript as $loc)
			{
				echo "\n";
				echo <<< EOHEADERS
		<script type="text/javascript" src="{$loc}" ></script>
EOHEADERS;
			}
		}

		echo <<< EOHEADERS
	<style type='text/css' media='all'>
		{$inlineStyle}
	</style>
</head>

EOHEADERS;
	}

	public function traiter_donnees()
	{
	}
    
    public function verification()
	{
	}
}

function redirectToPage($in_url, $in_contentType = 'html/text')
{
	header("Location: $in_url");
	header("Content-Type: $in_contentType; charset=utf-8");
}

function is_logged()
{
    session_start();
    if(isset($_SESSION['cne']) and $_SESSION['cne']!=null)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function is_loggedAdmin()
{
    session_start();
    if(isset($_SESSION['admin']))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function hspc($v)
{
   return  htmlspecialchars($v);
}

?>