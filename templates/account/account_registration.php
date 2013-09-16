<?
include _DIR."templates/account/menu.php";
?>
<div class="mr_bar-right">
	<?$row = execute_row_assoc("SELECT * FROM clients WHERE id = '".$_SESSION["login_user"]["id"]."'");?>
	<iframe style="display:none;"  name="save_account"></iframe>
	<form method="post" action="<?=_SITE?>account.php?type=registration" target="save_account">
	<p class="pass_change">Изменить пароль</p>
	<table cellpadding="2" class="rtable">
		<tr>

			<td><input class="ibox" name="oldpassw" type="password" placeholder="Старый пароль" />

			</td>
		</tr>
		<tr>
			<td><input class="ibox" name="newpassw" type="password" placeholder="Новый пароль (Длина минимум 5 символов)"/></td>
		</tr>
		<tr>
			<td><input class="ibox" name="renewpassw" type="password" placeholder="Новый пароль еще раз"/></td>
		</tr>
		<tr>
			
			<td><input type="submit" class="buttons" value="Сохранить" /></td>
		</tr>
	</table>
	</form>
</div>
<?
include _DIR."templates/account/footer.php";
?>