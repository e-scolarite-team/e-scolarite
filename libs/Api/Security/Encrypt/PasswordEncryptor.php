<?php
namespace Api\Security\Encrypt;

/**
* @author fayssal tahtoub <fayssal.tahtoub@gmail.com>
*
* @version 0.1
*/
class PasswordEncryptor
{

	public static function encodePassword($pass,$method = 'sha512'){
		return hash($method,$pass);
	}
}