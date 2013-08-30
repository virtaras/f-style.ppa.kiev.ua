<?php
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE."login.html");
	exit();
}
$title = "Персональный аккаунт";
function get_content()
{
?>
	<table>
		<tr>
			<td><a  href="<?=_SITE?>account_registration.html">Сменить пароль</a>
			<div style="color:#000000;font-size:11px;">Редактирование пароля. </div>
			<br /></td>
		</tr>
		<tr>
			<td><a href="<?=_SITE?>account_info.html">Персональная информация</a>
			<div style="color:#000000;font-size:11px;">Данный раздел позволяет редактировать Вашу контактную и персональную информацию. </div><br /></td>
		</tr>
		<tr>	
			<td><a  href="<?=_SITE?>account_history.html">История заказов</a>
			<div style="color:#000000;font-size:11px;">История Ваших заказов. </div></td>
		</tr>
		<tr>	
			<td><a  href="<?=_SITE?>kabinet/izbrannoe/">Избранное</a>
			<div style="color:#000000;font-size:11px;">Избранные товары. </div></td>
		</tr>
	</table>
	<?	
}
?>