<?php
	$currentuser = $_SESSION["user"];
	$currentuser = unserialize($currentuser);
	switch($engine_db)
	{
		case "mysql":
			$db = mysql_connect($currentuser->Host,$currentuser->Login,$currentuser->Passw);
			mysql_select_db($currentuser->DataBase);
			mysql_query("set names utf8");
			break;
		case "fb":
			$db = ibase_connect($currentuser->Host.":".$currentuser->DataBase,$currentuser->Login,$currentuser->Passw,"WIN1251");
	}	
	
?>