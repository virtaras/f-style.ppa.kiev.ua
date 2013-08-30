<?php
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE);
	exit();
}
$title = "Контактная информация";
function get_content()
{
	$row = execute_row_assoc("SELECT * FROM clients WHERE id = '".$_SESSION["login_user"]["id"]."'");
	?>
	<iframe style="display:none;"  name="save_account"></iframe>
	<form method="post" action="<?=_SITE?>account.php?type=info" target="save_account">
	<table cellspacing="0" class="rtable" cellpadding="2"  border="0">
		<tr>
			<td class="title2">E-Mail</td>
			<td><strong><?=$row["email"]?></strong></td>
		</tr>
				
				<?
			$fsql = mysql_query("SELECT * FROM clients_fields ORDER BY showorder");
			while($f = mysql_fetch_assoc($fsql))
			{
				echo "<tr>";
				if($f["fieldtype"] != "4")
				{
					?>
					<td><?=$f["title"]?></td>
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
			<td></td>
			<td><input type="submit" class="buttons" value="Сохранить" /></td>
		</tr>	
			</table>
	</form>
	<?
}
?>