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
  
  function insert_fields($ri,$name)
  {
	$result = execute_Scalar("SELECT id FROM $ri WHERE name = '$name'");
	if($result == "")
	{
		mysql_query("INSERT INTO $ri (name) VALUES ('$name')");
		$result = mysql_insert_id();
	}
	
	return $result;
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
		$line = $data->sheets[0]['cells'][$i];
		if($line[1] == "" )
		{
			break;
		}
		$brand = convert($line[2]);
		$brandid = execute_scalar("SELECT id FROM brands WHERE name = '$brand'");
		if($brandid == "")
		{
			mysql_query("INSERT INTO brands (name,urlname) VALUES ('$brand','".trim(ruslat(strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim($brand)))))."')");
			$brandid = mysql_insert_id();
		}
		$base_model = convert($line[3]);
		$baseid = execute_scalar("SELECT id FROM goods WHERE name = '$base_model'");
		
		$season = convert($line[6]);
		$seasonid = execute_scalar("SELECT id FROM 	r199 WHERE name = '$season'");
		
		$category = convert($line[7]);
		$categoryid = execute_scalar("SELECT id FROM 	r198 WHERE name = '$category'");
		
		if($categoryid == "")
		{
			mysql_query("INSERT INTO r198 (name) VALUES ('$category')");
			$categoryid = mysql_insert_id();
		}
		//Load base model
		if($baseid == "")
		{
			$urlname = trim(ruslat(mb_strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim($base_model)))));
			$urlname = mb_ereg_replace("-–-","-",$urlname);
			$urlname = mb_ereg_replace("--","-",$urlname);
			
			mysql_query("INSERT INTO goods (parentid,brand,name,urlname,currency,exist_type,r198,r199) VALUES (1,'$brandid','$base_model','$urlname','1','1','$categoryid','$seasonid') ");
			$baseid = mysql_insert_id();
		}
		else
		{
			mysql_query("UPDATE goods SET brand = '$brandid',r198 = '$categoryid', r199 = '$seasonid' WHERE id = $baseid  ");
		}
		
		//Load variant
		$extid = convert($line[1]);
		$id = execute_scalar("SELECT id FROM goods WHERE extid = '$extid'");
		
		$r207 = "";
		if(convert($line[8]) == "да")
		{
			$r207 = "Шип";
		}
		
		$type = explode("R",convert($line[4]));
		
		$r202 = ""; //R
		$r200 = ""; //l
		$r201 = ""; //h
		
		if(count($type) > 1)
		{

			
			$r202 = insert_fields("r202","R".$type[1]);
			
			$hl = explode("/",$type[0]);
			if(isset($hl[0]))
			{
				$r200 = insert_fields("r200",$hl[0]);
			}
			if(isset($hl[1]))
			{
				$r201 = insert_fields("r201",$hl[1]);
			}
		}
		
		$r205 = 0;
		if(convert($line[13]) == "да")
		{
			$r205 = "1";
		}
		else
		{
			
		}
		
		if($id == "")
		{
			mysql_query("INSERT INTO goods (extid,goodsid,parentid,brand,name,urlname,currency,exist_type,r198,r199,r203,r207,r200,r201,r202,price,r205) VALUES ('".convert($line[5])."',$baseid,1,'$brandid','$base_model','$urlname','1','1','$categoryid','$seasonid','".convert($line[5])."','$r207','$r200','$r201','$r202','".str_replace(",",".",convert($line[14]))."','$r205') ");
		}
	
		
	}
	unlink($uploadfile);
	
?>
Загрузка завершена !
<?	
}
}
?>
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
<input type="file" name="load_price" />&nbsp;<input type="submit" class="buttons" value="Загрузить" />
		</td>
	</tr>

</table>
</form>