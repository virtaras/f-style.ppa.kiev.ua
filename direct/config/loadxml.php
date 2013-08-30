<?
$title = "Загрузка товаров из XML";
$table_page = "get_loader_html";
$ind = 0;
if($ind == 0 && $_FILES["load_xml"]['name'] != "")
{
	$ind++;
	$uploaddir = "../temp/";
	$uploadfile = $uploaddir.basename($_FILES["load_xml"]['name']);	
	move_uploaded_file($_FILES["load_xml"]["tmp_name"], $uploadfile);
	$g_array = array("extid"=>"code","name"=>"name","price"=>"price1","description"=>"notes","brand"=>"publisher","r35"=>"format","r36"=>"rubric","r38"=>"year","r39"=>"isbn","r42"=>"binding","r34"=>"pages");
	$res = file_get_contents($uploadfile); 
	$xml = new SimpleXMLElement($res);
	
	foreach ($xml->price as $price) 
	{
		if(trim($price->name) == "")
		{
			continue;
		}
		$row_array = array();
		foreach($g_array as $key=>$value)
		{
			$xml_item = "";
			eval("\$xml_item = trim(\$price->".$value.");");	
			$row_array[$key] = "'".str_replace("'","''",$xml_item)."'";
		}
		$parent = explode("/",$price->parentcode);
		if(trim($price->publisher) != "")
		{
		$publisher = execute_scalar("SELECT id FROM brands WHERE name = '$price->publisher'");
		if($publisher == "")
		{
			db_query("INSERT INTO brands(name) VALUES ('".trim($price->publisher)."')");
			$publisher = mysql_insert_id();
		}
		}
		else
		{
			$publisher = 0;
		}
		$row_array["brand"] = $publisher;
		$row_array["parentid"] = execute_scalar("SELECT id FROM catalog WHERE extid = '".$parent[0]."'");
		$row_array["currency"] = 1;
		$tovarid = execute_scalar("SELECT id FROM goods WHERE extid = ".$row_array["extid"]);
		if($tovarid == "")
		{
			db_query("INSERT INTO goods (".implode(",",array_keys($row_array)).") VALUES (".implode(",",$row_array).")");
			//echo "INSERT INTO goods (".implode(",",array_keys($row_array)).") VALUES (".implode(",",$row_array).")"."<br />";
		}
		else
		{
			$sql_text = "UPDATE goods SET ";
			foreach($row_array as $key=>$value)
			{
				$sql_text .= $key." = ".$value.",";
			}
			$sql_text .= " goodsid = 0 WHERE id = $tovarid ";
			db_query($sql_text);
		}
	}
	
	unlink($uploadfile);
	unset($_FILES["load_xml"]);
	
}
if(!function_exists("get_loader_html"))
{
	function get_loader_html()
	{
		?>
		<table class="tool_bar" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="tb"></td>
<td class="tbr">Загрузка из XML</td>
		</tr>
		</table>
		<br />
		<form method="post" enctype="multipart/form-data">
		<input type="file" name="load_xml" />&nbsp;<input type="submit" class="buttons" value="Загрузить" />
		</form>
		<?
	}
}
?>