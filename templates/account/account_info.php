<?
include _DIR."templates/account/menu.php";
?>
<div style="display:inline-block;">
	<?$row = execute_row_assoc("SELECT * FROM clients WHERE id = '".$_SESSION["login_user"]["id"]."'");?>
	<iframe style="display:none;"  name="save_account_info"></iframe>
	<form method="POST" action="<?=_SITE?>account.php?type=info" target="save_account_info">
		<p class="pass_change">Личные данные</p>
	<table cellspacing="0" class="rtable inf" cellpadding="2"  border="0">
		<tr>
			<td class="title2">E-Mail:</td>
			<td style="font-family: 'officinasansmediumcregular'; padding-top: 8px; font-size: 13px; color: #595d60; height: 35px;"><?=$row["email"]?></td>
		</tr>
				
				<?
			$fsql = mysql_query("SELECT * FROM clients_fields ORDER BY showorder");
			while($f = mysql_fetch_assoc($fsql))
			{
				echo "<tr>";
				if($f["fieldtype"] != "4")
				{
					?>
					<td  class="title2"><?=$f["title"]?>:</td>
					<td><input class="ibox" id="<?=$f["code"]?>" name="<?=$f["code"]?>" value="<?=$row[$f["code"]]?>" style="width:300px;" type="text" /></td>
					<?
				}
				else
				{
				?>
				<td><?=$f["title"]?></td>
				<td >
				<textarea class="ibox" rows="5" style="width:300px;margin-top:3px;" id="<?=$f["code"]?>" name="<?=$f["code"]?>" ><?=$row[$f["code"]]?></textarea></td>
					<?
					} 
				?>
				<td>&nbsp;<? if($f["isrequired"] == "1") { ?><sup style="color:red;">*</sup><? } ?></td> <?
				echo "</tr>";
			}
			?>
			<tr>
				<td><input type="submit" class="buttons" value="Сохранить" /></td>
			</tr>	
			</table>
	</form>
	
	<iframe style="display:none;"  name="save_account_pass"></iframe>
	<form method="POST" action="<?=_SITE?>account.php?type=registration" target="save_account_pass">
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