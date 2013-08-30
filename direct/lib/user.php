<?php

class User
{
	var $ID = "";
	var $Login = "";
	var $passw = "";
	var $DataBase = "";
	var $Host = "";
	var $DataBaseTitle = "";
	var $Name = "";
	var $Group = "";
	var $Menu = "";

    function User($login,$passw,$db,$host,$dbtitle,$username = "",$group = "",$id = "")
	{
		$this->Login = $login;
		$this->Passw = $passw;
		$this->DataBase = $db;
		$this->Host = $host;
		$this->DataBaseTitle = $dbtitle;
		$this->Name = $username;
		$this->Group = $group;
		$this->ID = $id;
	}
}

?>