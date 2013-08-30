<?
ini_set("display_errors","On");
function convert($val)
{
	return trim(str_replace("'","''",str_replace("\\","\\\\",$val)));
}
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
if(isset($_FILES["load_price"]) ) {
if($_FILES["load_price"]['name'] != "")
{
	require('excel/reader.php');
	$uploaddir = "../temp/";
	$uploadfile = $uploaddir.basename($_FILES["load_price"]['name']);	
	move_uploaded_file($_FILES["load_price"]["tmp_name"], $uploadfile);
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($uploadfile);
	error_reporting(E_ALL ^ E_NOTICE);
	for ($i = 2; $i <= 10000; $i++) 
	{
		

		
	
		if($data->sheets[0]['cells'][$i][5] == "" && $data->sheets[0]['cells'][$i][6] == "")
		{
			break;
		}
		$arr = array();
		if(convert($data->sheets[0]['cells'][$i][1]) != "")
		{
			$arr["parentid"] = execute_scalar("SELECT id FROM catalog WHERE extid ='".convert($data->sheets[0]['cells'][$i][1])."'");
		}
		else
		{
			$arr["parentid"] = execute_scalar("SELECT id FROM catalog WHERE name ='".convert($data->sheets[0]['cells'][$i][2])."'");
		}
		if($arr["parentid"] == "")
		{
			unset($arr["parentid"]);
		}
		if(convert($data->sheets[0]['cells'][$i][3]) != "")
		{
			$arr["brand"] = "'".convert($data->sheets[0]['cells'][$i][3])."'";
			
			$brandid = execute_scalar("SELECT id FROM brands WHERE name = ".$arr["brand"]."");
			if($brandid == "" && trim($arr["brand"]) != "''")
			{
				mysql_query("INSERT INTO brands (name) VALUES (".$arr["brand"].")");
				$arr["brand"] = get_last_id("brands");
			} 
			else if($brandid != "")
			{
				$arr["brand"] = $brandid;
			}
			else
			{
				$arr["brand"] = 0;
			}
		}
		if(convert($data->sheets[0]['cells'][$i][4]))
		{
			$arr["exist_type"] = "'".convert($data->sheets[0]['cells'][$i][4])."'";
		}
		$arr["extid"] = "'".htmlspecialchars(convert($data->sheets[0]['cells'][$i][5]))."'";
		
		if(convert($data->sheets[0]['cells'][$i][6]) !=  "")
		{
			$arr["name"] = "'".htmlspecialchars(convert($data->sheets[0]['cells'][$i][6]))."'";
		}
		if(convert($data->sheets[0]['cells'][$i][7]) != "")
		{
			$arr["price"] = "'".str_replace(",",".",convert($data->sheets[0]['cells'][$i][7]))."'";
		}
		if(convert($data->sheets[0]['cells'][$i][8]) != "")
		{
			$arr["price_action"] = "'".str_replace(",",".",convert($data->sheets[0]['cells'][$i][8]))."'";
		}
		if(convert($data->sheets[0]['cells'][$i][9]) != "")
		{
			$arr["currency"] = "'".str_replace(",",".",convert($data->sheets[0]['cells'][$i][9]))."'";
		}
		if(convert($data->sheets[0]['cells'][$i][10]) != "")
		{
			$arr["description"] = "'".htmlspecialchars(convert($data->sheets[0]['cells'][$i][10]))."'";
		}
		
		if(convert($data->sheets[0]['cells'][$i][11]) != "")
		{
			$goodsid = execute_scalar("SELECT id FROM goods WHERE extid = '".htmlspecialchars(convert($data->sheets[0]['cells'][$i][11]))."'");
			if($goodsid != "")
			{
				$arr["goodsid"] = "'$goodsid'";
			}
		}
		
		
		if(convert($data->sheets[0]['cells'][$i][5]) != "")
		{
			$gid = execute_scalar("SELECT id FROM goods WHERE extid = ".$arr["extid"]);
		}
		else
		{
			$gid = execute_scalar("SELECT id FROM goods WHERE name = ".$arr["name"]);
		}
		
		if($gid == "")
		{
			
			if(convert($data->sheets[0]['cells'][$i][6]) !=  "" && convert($data->sheets[0]['cells'][$i][9]) !=  "" && convert($data->sheets[0]['cells'][$i][7]) !=  "")
			{
				$arr["urlname"] = "'".trim(ruslat(mb_strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim(convert($data->sheets[0]['cells'][$i][6]))))))."'";
				$arr["urlname"] = mb_ereg_replace("-–-","-",$arr["urlname"]);
				$arr["urlname"] = mb_ereg_replace("--","-",$arr["urlname"]);
			$sql_text = "INSERT INTO goods (".implode(",",array_keys($arr)).") VALUES (".implode(",",$arr).")";
			//echo $sql_text."<br />";
			mysql_query($sql_text);
			$gid = get_last_id("goods");
			}
		}
		else
		{
			$description = execute_scalar("SELECT description FROM goods WHERE id = '$gid'");
			$name = execute_scalar("SELECT name FROM goods WHERE id = '$gid'");
			$arr["urlname"] = "'".trim(ruslat(mb_strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim($name)))))."'";
			$arr["urlname"] = mb_ereg_replace("-–-","-",$arr["urlname"]);
			$arr["urlname"] = mb_ereg_replace("--","-",$arr["urlname"]);
			$sql_text = "UPDATE goods SET 
			";
			foreach(array_keys($arr) as $key)
			{
				if($key == "description" && !empty($description))
				{
					continue;
				}
				if(convert($data->sheets[0]['cells'][$i][4]) == "")
				{
					$arr["exist_type"] = 1;
				}
				
				$sql_text .= $key." = ".$arr[$key].",";
			}
			$sql_text = substr($sql_text,0,strlen($sql_text) - 1);
			$sql_text .= " WHERE id = '$gid'";
			//echo $sql_text."<br />"; 
			mysql_query($sql_text);
			
		}
		

		
	}
	unlink($uploadfile);
?>
Загрузка завершена !
<?	
}
}
?>
<script language="JavaScript">
	function clear_exist()
	{
		if(confirm("Очистить наличие товаров ?"))
		{
			$.post("udf/ajax.php",{action:'clear_exist'},function() { alert("Процедура выполнена.");} );
		}
	}
</script>
<input type="button" onclick="clear_exist()" value="Очистить наличие" class="buttons" /><br /><br />
<table class="tool_bar" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="tb"></td>
<td class="tbr">Загрузка из Excel</td>
		</tr>
		</table>	
		<br />
	<script language="JavaScript">
function set_template(isdata)
{
	document.getElementById("template").src = "templates/excel.php?id="+get_select_value("parentid")+'&isdata='+isdata.toString();
}

</script>	
<form method="post" enctype="multipart/form-data">
<table>
	<tr>
		<td>
			<? /* ?>
			<iframe name="template" style="display:none;"  id="template" ></iframe>
			Группа товаров для загрузки :&nbsp;
			<select name="parentid" id="parentid">
<option value="0">...</option>
<?	get_tree(0,100,&$catalog_array,0); ?>
</select>&nbsp;&nbsp;&nbsp;<a href="#" onclick="set_template(false)">Показать шаблон</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="set_template(true)">Выгрузить данные</a>
<br />
<div style="font-size:10px;">(Если используется общий шаблон, группу выбирать не надо)</div>
<br /><br />
<? */ ?>
<input type="file" name="load_price" />&nbsp;<input type="submit" class="buttons" value="Загрузить" />

		</td>
	</tr>

</table>
</form>