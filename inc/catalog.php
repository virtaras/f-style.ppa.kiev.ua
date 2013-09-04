<?
function get_seo_catalog_name($id)
{
	global $catalog_array;
	global $chainlet; 
	$result = "";
	if(isset($chainlet[$id]))
	{
		$result = $catalog_array[$chainlet[$id]]['name'];
	}
}

$head = execute_row_assoc("SELECT * FROM catalog WHERE ID = '$_GET[id]'");

if($head["id"] == "")
	{
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		die(); 
	}

$title =  $head["title"];
$description = $head["description"];
$keywords = $head["keywords"];
$pattern = "/\[p(\d+)\]/e";
if($title == "")
{
	$title = setting("seo_category_title");
	$title = str_replace("[c]",$head["name"],$title);
	$title = preg_replace($pattern, "get_seo_catalog_name(\$1)", $title);
	
}
if($keywords == "")
{
	$keywords = setting("seo_category_keywords");
	$keywords = str_replace("[c]",$head["name"],$keywords);
	$keywords = preg_replace($pattern, "get_seo_catalog_name(\$1)",$keywords);
	
}
if($description == "")
{
	$description = setting("seo_category_description");
	$description = str_replace("[c]",$head["name"],$description);
	$description = preg_replace($pattern, "get_seo_catalog_name(\$1)",$description);
}
if($title == "")
{
	$title = $head["name"];
}
$seo_text = setting("seo_text_category");
if($seo_text != "")
{
	$seo_text = str_replace("[c]",$head["name"],$seo_text);
	$seo_text = preg_replace($pattern, "get_seo_catalog_name(\$1)", $seo_text);
}
function get_goods($parent,$child_array = array())
{
		global $head;
		global $catalog_array;
		global $currency_array;
		global $base_currency;
		$current_category = $_GET["id"]; 
		if(isset($_COOKIE["page_size_"]))
        {
			$pagesize = $_COOKIE["page_size_"];
        }
		else
		{
			$pagesize = setting("default_page_size");
		}
		if(isset($_SESSION["mode_".$_GET["id"]]) && $_SESSION["mode_".$_GET["id"]] == "2")
		{
			$pagesize = 100000;
		}
		//require("filters.php");

		//require("sort.php");
	
		if(setting("use_variants") == "1")
		{
		$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF((SELECT count(*) FROM goods WHERE goodsid = gid) = 0,IF(goods.price_action > 0,goods.price_action,goods.price),(SELECT min(price) FROM goods WHERE goodsid = gid))*(SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
        
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imformat
		FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		INNER JOIN brands ON brands.id = goods.brand
        WHERE goods.parentid IN (".$parent.") AND goods.goodsid = 0  ORDER BY "._SORT_NAME;
		}
		else
		{
			$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE goods.parentid IN (".$parent.") AND goods.goodsid = 0 AND goods.price > 0 ORDER BY  "._SORT_NAME;
		}
		$count_sql_str = "SELECT count(goods.id)
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE goods.goodsid = 0 AND goods.parentid IN (".$parent.")  AND goods.price > 0";
//		 echo $sql_text;
		require("paging.php");

		include(_TEMPLATE."catalog_header.html");
        $sql_text = $sql_text.$limit;
		
        require("catalogitem.php");

        include(_TEMPLATE."catalog_footer.html");
		
}
global $cur_goods_groups;
$cur_goods_groups=array();
$goods_groups=mysql_query("SELECT goods_groups.* FROM goods_groups");
while($goods_group=mysql_fetch_assoc($goods_groups)){
	if(isset($_GET[$goods_group["code"]]))$cur_goods_groups[$goods_group["code"]]=prepare($_GET[$goods_group["code"]]);
}
global $sales;
if(isset($_GET["sales"]) && $_GET["sales"]>0) $sales=prepare($_GET["sales"]);
function get_content()
{
	global $head;
	global $catalog_array;
	global $currency_array;
	global $cur_goods_groups;
	global $sales;
	if($catalog_array[$_GET["id"]]["scount"] > 0)
	{
		$is_catalog_list = 1;
		
		$child_array = array($head["id"]);
		get_child_id($_GET["id"],&$child_array);
		get_goods(implode(",",$child_array),$child_array);
		
		/*
		$ind = 0;
		$carray = get_catalog_items_all($_GET["id"]);
		$gcount = count($carray);
		foreach($carray as $key=>$value)
		{
			$ind++;
			global $db;
			$img = dbGetRow("SELECT id,format FROM images WHERE source = 2 AND parentid = $key ORDER BY is_main DESC LIMIT 0,1",$db);
			if(count($img) > 0)
			{
				$value["imid"] = $img["id"];
				$value["imformat"] = $img["format"];
			}
			else
			{
				$value["imid"] = 0;
				$value["imformat"] = "";
			}
			include(_TEMPLATE."listitem.html");
		}
		*/
				
	}
	else
	{
		get_goods($_GET["id"]);
	}
}

?>
