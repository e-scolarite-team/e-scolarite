<?php 
namespace Api\Utils;

/**
* @author fayssal tahtoyb <fayssal.tahtoub@gmail.com>
* @version 0.1
*/
class ArrayYtils
{
	
	function __construct()
	{
		
	}

	public static function getLastElement($stack){
		return end($stack);
	}

	public static function getLastKey($stack){
		end($stack);
		return key($stack);
	}
}