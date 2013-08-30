<?php
	function get_seo_catalog_name($id)
	{
		global $catalog_array;
		global $chainlet;
		$result = "";
		if(isset($chainlet[$id]))
		{
			$result = $catalog_array[$chainlet[$id]]['name'];
		}
		return $result;
	}
	$where = "WHERE goods.id = '$_GET[id]'";
	/*if(!is_numeric($_GET["id"]))
	{
		
		$where = "WHERE goods.urlname = '$_GET[id]' AND goods.parentid = '$productparent'";
	}*/
	
	$tovar = execute_row_assoc("SELECT goods.*,exist_type.name as extype,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname, brands.urlname as brandurl 
	FROM goods
	LEFT JOIN exist_type ON exist_type.id = goods.exist_type
	INNER JOIN catalog ON catalog.id = goods.parentid
	LEFT JOIN brands ON brands.id = goods.brand
	$where");
	
	if($tovar["id"] == "")
	{
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		die(); 
	}
		
	
	$title = $tovar["title"];
	$pattern = "/\[p(\d+)\]/e";
	if($title == "")
	{
		$title = setting("seo_goods_title");
		$title = str_replace("[t]",$tovar["name"],$title);
		$title = str_replace("[c]",$tovar["cname"],$title);
		$title = str_replace("[b]",$tovar["brandname"],$title);
		$title = preg_replace($pattern, "get_seo_catalog_name(\$1)", $title);
		
	}

	//$description = $tovar["meta_description"] != "" ? $tovar["meta_description"] : substr(strip_tags(trim($tovar["description"])),0,255);

	$keywords = $tovar["keywords"];
	if($keywords == "")
	{
		$keywords = setting("seo_goods_keywords");
		$keywords = str_replace("[t]",$tovar["name"],$keywords);
		$keywords = str_replace("[c]",$tovar["cname"],$keywords);
		$keywords = str_replace("[b]",$tovar["brandname"],$keywords);
		$keywords = preg_replace($pattern, "get_seo_catalog_name(\$1)", $keywords);
	}
		$description = $tovar["meta_description"];
		if($description == "")
		{
			$description = setting("seo_goods_description");
			$description = str_replace("[t]",$tovar["name"],$description);
			$description = str_replace("[c]",$tovar["cname"],$description);
			$description = str_replace("[b]",$tovar["brandname"],$description);
			$description = preg_replace($pattern, "get_seo_catalog_name(\$1)", $description);
		}
	$seo_text = setting("seo_text_goods");
	if($seo_text != "")
	{
		$seo_text = str_replace("[t]",$tovar["name"],$seo_text);
		$seo_text = str_replace("[c]",$tovar["cname"],$seo_text);
		$seo_text = str_replace("[b]",$tovar["brandname"],$seo_text);
		$seo_text = preg_replace($pattern, "get_seo_catalog_name(\$1)", $seo_text);
	}
	

	function get_content()
	{
		
		global $tovar;
		global $currency_array;
		global $title;
		global $keywords;
		global $description;
		global $seo_text;
		global $db;
		$images = dbQuery("SELECT * FROM images WHERE source = 3 AND parentid = $tovar[id] ORDER BY is_main DESC LIMIT 1,100",$db);
		$mainImage = dbGetRow("SELECT * FROM images WHERE source = 3 AND parentid = $tovar[id] AND is_main = 1 ORDER BY is_main DESC LIMIT 0,1",$db);
		$price = get_price($tovar["price"],_DISPLAY_CURRENCY,$tovar["id"],$tovar["currency"]);
		$price_action = get_price($tovar["price_action"],_DISPLAY_CURRENCY,$tovar["id"],$tovar["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		
		if(!isset($_SESSION["viewed"]))
		{
			$_SESSION["viewed"] = array();
		}
		if(!in_array($tovar["id"],$_SESSION["viewed"]))
		{
			$_SESSION["viewed"][] = $tovar["id"];
		}
		
		include(_TEMPLATE."tovar.php");
		
		
	}
?>
