<?php
session_start();
header('Content-Type: text/javascript; charset=utf-8');
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
require("../function/global.php");

if(isset($_POST["action"]))
{
	switch($_POST["action"])
	{
		case "stone_params":
				function show_cb($t)
				{
					$sql = db_query("SELECT id,name fROM $t");
					while($r = db_fetch_assoc($sql))
					{
						$checked = false;
						if(execute_scalar("SELECT count(*) FROM r166_params WHERE goodsid = $_POST[product] AND parentid = $_POST[id] AND params = '$t' AND paramid = $r[id]") == 1)
						{
							$checked = true;
						}
						?>
						<div><input <? if($checked) { echo "checked='checked'"; }  ?> name="<?=$_POST["product"]?>_<?=$_POST["id"]?>_<?=$t?><?=$r["id"]?>" type="checkbox" value="<?=$r["id"]?>_<?=$_POST["id"]?>" />&nbsp;-&nbsp;<?=$r["name"]?></div>
						<?
					}
				}
				?>
				<input type="hidden" name="stone<?=$_POST["id"]?>" value="<?=$_POST["id"]?>" />
				<table>
					<tr>
						<td><strong>Формы</strong></td>
						<td><strong>Рамзеры</strong></td>
						<td><strong>Цвет</strong></td>
					</tr>
					<tr>
						<td style="vertical-align:top;"><?=show_cb("r167")?></td>
						<td style="vertical-align:top;"><?=show_cb("r168")?></td>
						<td style="vertical-align:top;"><?=show_cb("r169")?></td>
					</tr>
				</table>
				<br /><br /> <?
				break;
		
	} 
}
?>