<?php
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE."login.html");
	exit();
}
$title = "Регистрационные данные";
function get_content()
{
	include _DIR."templates/account/account_registration.php";
}	
?>