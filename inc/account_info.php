<?php
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE);
	exit();
}
$title = "Контактная информация";
function get_content()
{
	include _DIR."templates/account/account_info.php";
}
?>