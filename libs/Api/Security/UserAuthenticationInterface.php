<?php

namespace Api\Security;

/**
* @author fayssal tahtoub <faysal.tahtoub@gmail.com>
*/
interface UserAuthenticationInterface
{
	public function getUsername();

	public function getPassword();

	public function getRoles();

	public function setRoles($roles);
}