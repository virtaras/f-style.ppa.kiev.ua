<?php

if(!isset($_SESSION["user"]))
{
	$_SESSION["login_admin_back"] = $_SERVER["REQUEST_URI"];
	header("Location: login.php");
}

?>