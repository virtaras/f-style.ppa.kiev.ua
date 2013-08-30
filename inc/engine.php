<?php
include_once("image.php");
//DB functions ************************************************************************
function open_connection()
	{
		$db = mysql_connect(_HOST,_DBUSER,_DBPASSWORD);
		mysql_select_db(_DB);
		mysql_query("set names utf8");
		return $db;
	}
	
	function close_connection($db)
	{
		mysql_close($db);
	}
	
	function dbQuery($query,&$db = null,$count_all = false,&$all_count = 0) {
		
		$already_open = false;
		
		if($db == null) { 
			$db = open_connection();
		} else {
			$already_open = true;
		}
		
		$result = array();
		
		if($count_all){
		
			$sql_query = explode("SELECT",$query,2);
			$sql_query = "SELECT SQL_CALC_FOUND_ROWS" . $sql_query[1];
		}
		
		$sql = mysql_query($query);
		while($r = mysql_fetch_assoc($sql))
		{
			array_push($result,$r);
		}
		
		if($count_all){
			$all_count = dbGetOne("SELECT FOUND_ROWS();",$db);
		}
		
		if(!$already_open) {
			close_connection($db);
		}
		
		return $result;
	}
	
	function dbNonQuery($query,&$db = null,$is_insert = false) {
		
		$result = "";
		
		$already_open = false;
		
		if($db == null) { 
			$db = open_connection();
		} else {
			$already_open = true;
		}
		
		$result = array();
		
		$sql = mysql_query($query);
		
		if($is_insert)
		{
			$result = mysql_insert_id();	
		}
		
		if(!$already_open) {
			close_connection($db);
		}

		return $result;
	}
	
	function dbGetOne($query,&$db = null) {
		
		$already_open = false;
		
		if($db == null) { 
			$db = open_connection();
		} else {
			$already_open = true;
		}
		$sql = mysql_query($query,$db);
		$row =  mysql_fetch_array($sql);
		
		if(!$already_open) {
			close_connection($db);
		}
		return $row[0];
	}	
	
	function dbGetRow($query,&$db = null) {
		
		$already_open = false;
		
		if($db == null) { 
			$db = open_connection();
		} else {
			$already_open = true;
		}
		$sql = mysql_query($query,$db);
		$row =  mysql_fetch_assoc($sql);
		if(!$already_open) {
			close_connection($db);
		}
		return $row;
	}	
//End DB functions ************************************************************************


//Images functions ************************************************************************
function getTovarImage($tovar,$source=3)
{
	global $db;
	$image = dbGetRow("SELECT * FROM images WHERE parentid = '".$tovar["id"]."' AND source = '$source' ORDER BY is_main DESC LIMIT 0,1",$db);
	if((!isset($image["image"]) || !$image["image"]) && ($tovar["goodsid"]!=0)){
		$image = dbGetRow("SELECT * FROM images WHERE parentid = '".$tovar["goodsid"]."' AND source = '$source' ORDER BY is_main DESC LIMIT 0,1",$db);
	}
	return $image;
}
function getImageById($id)
{
	$sql_text = "SELECT * FROM images WHERE id = '$id'";
	global $db;
	return dbGetRow($sql_text,$db);
}
function getImages($id,$source)
{	
	$sql_text = "SELECT * FROM images WHERE parentid = '$id' AND source = '$source' ORDER BY showorder ASC";
	global $db;
	return dbQuery($sql_text,$db);
}
function getRandomImage($id,$source)
{
	$sql_text = "SELECT * FROM images WHERE parentid = '$id' AND source = '$source' ORDER BY rand() LIMIT 0,1";
	global $db;
	return dbGetRow($sql_text,$db);
}
function getMainImage($id,$source)
{
	$sql_text = "SELECT * FROM images WHERE parentid = '$id' AND source = '$source' ORDER BY is_main DESC LIMIT 0,1";
	global $db;
	return dbGetRow($sql_text,$db);
}
function getResizeImageById($id,$resizeFormat/* w,h,wh,k  */,$settings,$format = "",$img = array())
{
	if(intval($id) > 0)
	{
		switch($resizeFormat)
		{
			case "h":
				$thumb =  $id . "-0x" . $settings["height"] . "." . $format;
				break;
			case "w":
				$thumb =  $id . "-" . $settings["width"] . "x0." . $format;
				break;
			case "wh":
				$thumb =  $img["id"] . "-" . $settings["width"] . "x" . $settings["height"] . "." . $format;	
			break;	
			case "k":
				$thumb =  $id . "k" . $settings["k"] . "." . $format;
				break;
			case "l":
				$thumb =  $id . "l" . $settings["width"] . "x" . $settings["height"] . "." . $format;
			break;	
		}
		if(file_exists(_DIR . "images/thumbs/" . $thumb))
		{
			return "/images/thumbs/" . $thumb;
		}
		else
		{
			if(count($img) == 0)
			{
				$img = getImageById($id);
			}
			$path = _DIR . "images/files/" . $img["image"];
			if(file_exists($path))
			{
				$image = new SimpleImage();
				$image->load($path);
				switch($resizeFormat)
				{
					case "h":
						$image->resizeToHeight($settings["height"]);
						$thumb =  $img["id"] . "-0x" . $settings["height"] . "." . $img["format"];
						break;
					case "w":
						$image->resizeToWidth($settings["width"]);
						$thumb =  $img["id"] . "-" . $settings["width"] . "x0." . $img["format"];
						break;
					case "wh":
						$image->resize($settings["width"],$settings["height"]);
						$thumb =  $img["id"] . "-" . $settings["width"] . "x" . $settings["height"] . "." . $img["format"];
						break;
					case "k":
						
						if($image->getWidth() > $image->getHeight())
						{
							$image->resizeToWidth($settings["k"]);
						}
						else if($image->getWidth() < $image->getHeight())
						{
							$image->resizeToHeight($settings["k"]);
						}
						else
						{
							$image->resize($settings["k"],$settings["k"]);
						}
						$thumb =  $id . "k" . $settings["k"] . "." . $format;
						break;
					case "l":
						if($image->getWidth() > $image->getHeight())
						{
							$image->resizeToWidth($settings["width"]);
						}
						else if($image->getWidth() < $image->getHeight())
						{
							$image->resizeToHeight($settings["height"]);
						}
						else
						{
							$image->resize($settings["width"],$settings["width"]);
						}
						$thumb =  $id . "l" . $settings["width"] . "x" . $settings["height"] . "." . $format;
					break;	
				}
				
				if(isset($settings["crop"]))
				{
					$image->crop($settings["crop"]);
				}
				
				$image->save(_DIR . "images/thumbs/" . $thumb);
				return "/images/thumbs/" . $thumb;
				
			}
			else
			{
				return "/templates/images/nofoto.jpg";
			}
		}
		
	}
	else
	{
		return "/templates/images/nofoto.jpg";
	}
}


//End Images functions ************************************************************************

//Content functions  ************************************************************************
function getContentItems($select,$where,$orderby,$limit,$with_image = false)
{
	$where_array = array("content.ispublish = 1");
	
	foreach($where as $key=>$value)
	{
		if($value != "")
		{
			if(is_array($value))
			{
				array_push($where_array,$key . " IN " . "(" . implode(",",$value) . ")");
			}
			else
			{
				array_push($where_array,$key . "=" . "'" . $value . "'");
			}	
		}
		else
		{
			array_push($where_array,$key);
		}
	}	
	if($with_image)
	{
		$select[] = "(SELECT id FROM images WHERE source = 1 AND parentid = content.id AND is_main = 1  LIMIT 0,1) as imid";
		$select[] = "(SELECT format FROM images WHERE source = 1 AND parentid = content.id AND is_main = 1 LIMIT 0,1) as imformat";
	}
	
	$sql_text = "SELECT " . implode(",",$select) . " FROM content WHERE " . implode(" AND ",$where_array) . "  ORDER BY " . implode(",",$orderby) . " LIMIT 0," . $limit;
	global $db;
	return dbQuery($sql_text,$db);
} 
//End Content functions ************************************************************************

//Brands functions ************************************************************************
function getBrandsListAll($only_width_products = false)
{
	$sql_text = "SELECT id,name,urlname FROM brands ORDER BY name";
	if($only_width_products)
	{
		$sql_text = "SELECT brands.id,brands.name,brands.urlname FROM brands 
		INNER JOIN goods ON goods.brand = brands.id
		GROUP BY brands.id,brands.name,brands.urlname
		ORDER BY brands.name
		";
	}
	global $db;
	return dbQuery($sql_text,$db);;
}
//End Brands functions ************************************************************************
?>