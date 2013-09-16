<?
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE);
	exit();
}
$title = "История заказов";
function get_content()
{
	include _DIR."templates/account/account_history.php";
}
?>