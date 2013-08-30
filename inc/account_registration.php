<?php
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE."login.html");
	exit();
}
$title = "Регистрационные данные";
function get_content()
{
	$row = execute_row_assoc("SELECT * FROM clients WHERE id = '".$_SESSION["login_user"]["id"]."'");
	?>
	<iframe style="display:none;"  name="save_account"></iframe>
	<form method="post" action="<?=_SITE?>account.php?type=registration" target="save_account">
	<table cellpadding="2" class="rtable">
		<tr>
			<td class="title2">E-Mail</td>
			<td><?=$row["email"]?></td>
		</tr>
		<tr>
			<td class="title2">Новый пароль</td>
			<td><input class="ibox" name="newpassw" type="password" />
			<div style="font-size:10px;">Длина минимум 5 символов.</div>
			</td>
		</tr>
		<tr>
			<td  class="title2">Повторите пароль</td>
			<td><input class="ibox" name="renewpassw" type="password" /></td>
		</tr>
		<tr>
			<td class="title2">Старый пароль</td>
			<td><input class="ibox" name="oldpassw" type="password" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="buttons" value="Сохранить" /></td>
		</tr>
	</table>
	</form>
	<?
}	
?>