<?php
session_start();
header('Content-Type: text/javascript; charset=utf-8');
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
require("../function/global.php");
function strtolower_utf8($string){
  $convert_to = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
    "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
    "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
    "ь", "э", "ю", "я"
  );
  $convert_from = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
    "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
    "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы",
    "Ь", "Э", "Ю", "Я"
  );
    return str_replace($convert_from, $convert_to, $string);
  }
function show_menu_item($parentid,$level = 100)
{
	$sql = db_query("SELECT id,title,parentid FROM menuitem WHERE  parentid = '$parentid' ORDER BY showorder");
	while($r = db_fetch_assoc($sql))
	{
		$blank ="";
		for($i = 0;$i < 100 - $level;$i++)
		{
			$blank .= "&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		?>
		<div style="vertical-align:center;margin-top:3px;" ><?=$blank?>
		<img onclick="up_menu(<?=$r["id"]?>,-1)" src="images/rasc.gif" />&nbsp;
		<img onclick="up_menu(<?=$r["id"]?>,1)" src="images/rdesc.gif" />&nbsp;
		<img src="images/ac.gif" onclick="$('#item<?=$r["id"]?>').load('udf/ajax.php',{action:'menuitem',id:-1,parentid:<?=$r["id"]?>,divid:'item<?=$r["id"]?>',menuid:<?=$_POST["menuid"]?>})" />&nbsp;
		<img onclick="rm_menuitem('<?=$r["id"]?>')" src="images/dc.gif" />&nbsp;
		<a onclick="$('#item<?=$r["id"]?>').load('udf/ajax.php',{action:'menuitem',id:<?=$r["id"]?>,parentid:<?=$r["parentid"]?>,divid:'item<?=$r["id"]?>'})" href="#"><?=$r["title"]?></a>
				<div id="item<?=$r["id"]?>"></div></div>
		<?
		show_menu_item($r["id"],$level-1);
		
	}
		
}
if(isset($_POST["action"]))
{
switch($_POST["action"])
{
	case "menuitem":
		$r = db_get_row("SELECT * FROM menuitem WHERE id = '$_POST[id]'");
		?>
		<input type="hidden" id="parentid<?=$_POST["id"]?>" value="<?=$_POST["parentid"]?>" />
		<table>
			<tr>
				<td class='title'>Заголовок</td>
				<td><input type="text" value="<?=$r["title"]?>" class='input_box' id="title<?=$_POST["id"]?>" /></td>
			</tr>
			<tr>
				<td class='title'>Ссылка</td>
				<td><input type="text" value="<?=$r["link"]?>" class='input_box' id="link<?=$_POST["id"]?>" /></td>
			</tr>
			<tr>
				<td class='title'>SQL для субменю</td>
				<td><textarea rows="9" class='input_box' id="sql<?=$_POST["id"]?>"><?=$r["childsql"]?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'>Шаблон ссылки</td>
				<td><input type="text" value="<?=$r["linktemplate"]?>" class='input_box' id="linktemplate<?=$_POST["id"]?>" /></td>
			</tr>
			
			<tr>
				<td></td>
				<td><input type="button" onclick="save_menuitem('<?=$_POST["id"]?>')" class="buttons" value="Сохранить" />
				&nbsp;
				<input onclick="$('#<?=$_POST["divid"]?>').empty()"  type="button" class="buttons" value="Закрыть" />
				</td>
			</tr>
		</table>
		<?
		break;
		case "showmenu":
			$sql = db_query("SELECT id,title,parentid FROM menuitem WHERE menuid = '$_POST[menuid]' AND parentid = '-1' ORDER BY showorder");
			while($r = db_fetch_assoc($sql))
			{
				?>
				<div style="vertical-align:center;margin-top:3px;" ><img onclick="up_menu(<?=$r["id"]?>,-1)" src="images/rasc.gif" />&nbsp;<img onclick="up_menu(<?=$r["id"]?>,1)" src="images/rdesc.gif" />&nbsp;<img onclick="$('#item<?=$r["id"]?>').load('udf/ajax.php',{action:'menuitem',id:-1,parentid:<?=$r["id"]?>,divid:'item<?=$r["id"]?>',menuid:<?=$_POST["menuid"]?>})" src="images/ac.gif" />&nbsp;<img onclick="rm_menuitem('<?=$r["id"]?>')" src="images/dc.gif" />&nbsp;<a onclick="$('#item<?=$r["id"]?>').load('udf/ajax.php',{action:'menuitem',id:<?=$r["id"]?>,parentid:<?=$r["parentid"]?>,divid:'item<?=$r["id"]?>'})" href="#"><?=$r["title"]?></a>
				<div id="item<?=$r["id"]?>"></div></div>
				<?
				show_menu_item($r["id"],99);
			
			}
			break;
			case "save_menu":
			
			if($_POST["id"] == "-1")
			{
				
				$max_order = execute_scalar("SELECT max(showorder) FROM menuitem WHERE parentid = '$_POST[parentid]'");
				db_query("INSERT INTO menuitem (menuid,parentid,title,link,childsql,showorder,linktemplate) VALUES ('$_POST[menuid]','$_POST[parentid]','$_POST[title]','$_POST[link]','$_POST[sql]','".($max_order + 1)."','$_POST[linktemplate]')");
			}
			else
			{
				db_query("UPDATE menuitem SET parentid = '$_POST[parentid]',title = '$_POST[title]',link = '$_POST[link]',childsql = '$_POST[sql]',linktemplate='$_POST[linktemplate]' WHERE id = '$_POST[id]'");
			}
				break;
			case "rm_menu":
				function rm_menu($parentid)
				{
					$sql = db_query("SELECT id FROM menuitem WHERE parentid = '$parentid'");
					while($r = db_fetch_assoc($sql))
					{
						db_query("DELETE FROM menuitem WHERE id = '$r[id]'");
						rm_menu($r["id"]);
					}
					db_query("DELETE FROM menuitem WHERE id = '$parentid'");
				}
				rm_menu($_POST["id"]);
				break;
			case "up_menu":
				$parent = execute_scalar("SELECT parentid FROM menuitem WHERE id = '$_POST[up]'");
	$count_item = execute_scalar("SELECT count(*)  FROM menuitem WHERE parentid = '$parent'");
	$currentsql = mysql_query("SELECT id,showorder FROM menuitem WHERE id = '$_POST[up]'");
	$current = mysql_fetch_assoc($currentsql);
	if(($current["showorder"] == 1 && $_POST["position"] == "-1") || ($current["showorder"] == $count_item && $_POST["position"] == "1"))
	{
		
	}
	else
		{
			$old_position = $current["showorder"];
			$new_position = $current["showorder"] + $_POST["position"];
			
			//echo $old_position." ".$new_position;
			
			$old_id_sql = mysql_query("SELECT id FROM menuitem WHERE parentid = '$parent' AND showorder = '$new_position'");
			$old_id = mysql_fetch_assoc($old_id_sql);
			$old = $old_id["id"];
			
			mysql_query("UPDATE menuitem SET showorder = '$old_position' WHERE id = '$old'");
			mysql_query("UPDATE menuitem SET showorder = '$new_position' WHERE id = '$_POST[up]'");
		}
				break;	
				case "show_editor":
				?>
				<textarea id="<?=$_POST["name"]?>" name="<?=$_POST["name"]?>"><?=htmlspecialchars_decode(execute_scalar($_POST["sql"]))?></textarea>
				<?
				break;
				case "show_goods_list":
		function get_child_id($parentid,&$child_array)
		{
			global $catalog_array;
			if(is_array($catalog_array))
			{
				foreach(array_keys($catalog_array) as $current)
				{
					if($catalog_array[$current]["parent"] == $parentid)
					{
						array_push(&$child_array,$current);
						get_child_id($current,&$child_array);
					}
				}
			}
		}
			include("../../inc/cache/catalog_array.php");
			
			$child_array = array($_POST["parentid"]);
			get_child_id($_POST["parentid"],&$child_array);
			?>
			<select size="7" id="goods_from_list"			class="select_box">
				<?
					$where = "";
					if($_POST["findtext"] != "")
					{
						$where = " AND name LIKE '".trim($_POST["findtext"])."%'";
					}
					$sql = db_query("SELECT id,name FROM goods WHERE parentid IN (".implode(",",$child_array).") $where  ORDER BY name ");
					while($r = db_fetch_assoc($sql))
					{
						?>
						<option value="<?=$r["id"]?>"><?=$r["name"]?></option>
						<?
					}
				?>
			</select>
			<?
			break;
			case "show_goods_satellites":
				?>
				<select size="12" id="goods_to_list" class="select_box">
					<?
					$sql = db_query("SELECT satellites.id,goods.name FROM satellites
					INNER JOIN goods ON goods.id = satellites.goodsid AND satellites.parentid = $_POST[parent]
					ORDER BY goods.name
					");
					while($r = db_fetch_assoc($sql))
					{
						?>
						<option value="<?=$r["id"]?>"><?=$r["name"]?></option>
						<?
					}
					?>
				</select>
				<?
			break;
		case "add_satellites":
			if(execute_scalar("SELECT count(*) FROM satellites WHERE parentid = $_POST[parent] AND goodsid = $_POST[id] ") == 0)
			{
			db_query("INSERT INTO satellites(parentid,goodsid) VALUES ($_POST[parent],$_POST[id])");	
			}
			break;
		case "rm_satellites":
			db_query("DELETE FROM satellites WHERE id = $_POST[id]");
			break;
		case "show_all":
                   $_SESSION["pagesize_".$_POST["t"]."_all"] = true;
                break;
				 case "show_pager":
                   unset($_SESSION["pagesize_".$_POST["t"]."_all"]);
				      break; 
		case "clear_exist":
			db_query("UPDATE goods SET exist_type = 2,last_update = now(),is_export = 0 WHERE exist_type != 5  ");
			break;
	
} }

if(isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "fast_search":
		
		header('Content-Type: text/html; charset=utf-8');
		$sql = mysql_query("SELECT id,name FROM goods WHERE CONCAT(LCASE(name),LCASE(IF(code IS NULL,'',code))) RLIKE '".strtolower_utf8($_GET["term"])."' LIMIT 0,20");
		echo "[";
		$ind = 0;
		$arr = array();
		while($r = mysql_fetch_assoc($sql))
		{
			$ind++;
			array_push($arr,'{ "id": "'.$r["id"].'", "label": "'.$r["name"].'", "value": "'.$r["name"].'" }');
			//echo "\"$r[name]\"";
			//if($ind < mysql_num_rows($sql))
			//{
			//	echo ",";
			//}
		}
		echo implode(",",$arr)."]";
			break;
	}
}	

?>