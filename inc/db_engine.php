<?php

define("SERVER","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB","fpscolaritev1");

class db_engine
{
    private static $con;
    
    function __construct($server,$user,$pass,$db)
    {
        $this->connecter($server,$user,$pass,$db);
    }
    
    public static function connecter($server,$user,$pass,$db)
    {
        if(is_null(self::$con))
        {
            @self::$con = new mysqli($server,$user,$pass,$db);
            if(mysqli_connect_errno())
            {
                echo "Un problème est survenue veuillez ressayer plus tard.";
                exit();
            }
            if(!@self::$con->query('SET NAMES UTF8'))
            {
                echo "Un problème est survenue veuillez ressayer plus tard.";
                exit();
            }
        }
    }
    
    public static function executer_r($requete)
    {
        if(!is_null(self::$con))
        {
            $resultat = self::$con->query($requete);
            return $resultat;
        }
        else
        {
            return false;
        }
    }
    
    public static function executer($requete)
    {
        if(!is_null(self::$con))
        {
            self::$con->query($requete);
        }
    }
    
    public static function echapper($valeur)
    {
        if(!is_null(self::$con))
        {
            $s_valeur = $this->con->real_escape_string($valeur);
            return $s_valeur;
        }
    }
    
    public static function preparer($req)
    {
        /* Create a prepared statement */
        $st = null;
        if($st = self::$con -> prepare($req))
        {
            return $st;
        }
        return null;
    }
    
    public static function fermer()
    {
        @self::$con->close();
        self::$con = null;
    }
    
}



?>
