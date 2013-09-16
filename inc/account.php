<?php
global $page;
$page=prepare($_GET["page"]);
if(!checkLogged())
{
	header("Location: "._SITE."login.html");
	exit();
}
if(!file_exists(_DIR."templates/account/".$page.".php")){
	?><script>document.location.href='/';</script><?
}
$title = "Персональный аккаунт";
function get_content()
{
	global $page;
	include _DIR."templates/account/".$page.".php";
}
?>