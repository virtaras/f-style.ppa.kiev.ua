<?
$title = "Результаты поиска";
$_GET["stext"] = stripcslashes($_GET["stext"]);
$sarr = explode(":",$_GET["stext"]);
	$stext = "";
	$scategory = "";
	if(count($sarr) == 1)
	{
		$stext = urldecode($sarr[0]);
		$scategory = "0";
	}
	else
	{
		$stext = urldecode($sarr[1]);
		$scategory = $sarr[0];
	}

	$stext = substr($stext, 0, 200);
    $title = $stext;
	
	$stext = str_replace(array("+","-","_","/","\\","(",")"),"",$stext);
	$stext = str_replace("'","''",$stext);
	$stext_array = explode(" ",$stext);
    $where = " WHERE goods.exist_type != 2 AND goods.goodsid = 0 ";
	
	foreach($stext_array as $current)
	{
		if($current == "")
		{
			continue;
		}
		if(strlen($current) < 3)
		{
			//continue;
		}
        if(strlen($current) >= 3 && in_array( substr($current, strlen($current) - 1, 1), array("и","ы")))
        {
            $current = substr($current, 0, strlen($current) - 1);
        }
		$where .= " AND LCASE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT(goods.name,catalog.name,catalog.one_name,IF(brands.name is NULL,'',brands.name ),LCASE(IF(code IS NULL,'',code))),'-',''),'-',''),'.',''),'/',''),'\\\\',''),' ','')) RLIKE '".mb_strtolower($current, 'UTF-8')."'"; 
	}
	if($scategory != "0")
	{
		//echo $scategory;
		$childarray = array($scategory);
		get_child($scategory,&$childarray);
		$where .= " AND goods.parentid IN (".implode(",",$childarray).")";
	}
	if(isset($_COOKIE["page_size_"]))
	{
		$pagesize = $_COOKIE["page_size_"];
	}
	else
	{
		$pagesize = setting("default_page_size");
	}
	$limit = "LIMIT 0,$pagesize ";
	$sql_text = "SELECT 
				goods.*,
				IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,
				brands.name as brandname,
				brands.urlname as brandurl,
				goods.id as gid,
				goods.currency as gcurrency,
				IF((SELECT count(*) FROM goods WHERE goodsid = gid) = 0,IF(goods.price_action > 0,goods.price_action,goods.price),(SELECT min(price) FROM goods WHERE goodsid = gid))*(SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
				(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
				(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
				FROM goods
				INNER JOIN catalog ON catalog.id = goods.parentid
				LEFT JOIN brands ON brands.id = goods.brand
				LEFT JOIN exist_type ON exist_type.id = goods.exist_type
				$where ORDER BY "._SORT_NAME;
				//echo $sql_text;
				$count_sql_str = "SELECT count(*) FROM goods
				INNER JOIN catalog ON catalog.id = goods.parentid
				LEFT JOIN brands ON brands.id = goods.brand
				$where
				";
				require("paging.php");
				$sql_text = $sql_text.$limit; 
				$result_sql = mysql_query($sql_text);
				$gcount = mysql_num_rows($result_sql);
				
				if($gcount == "1")
				{
					$r = mysql_fetch_assoc($result_sql);
					header("Location: ".get_product_url($r));
					exit();
				}
				
				
	
function get_content()
{

	global $stext;
	global $scategory;
    global $currency_array;
	global $base_currency;
	global $result_sql;
	global $pagesize;
	global $pagecount;
	global $pager;
	global $count;
	include(_TEMPLATE."catalog_header.html");
    require("catalogitem.php");
    include(_TEMPLATE."catalog_footer.html");

if($gcount == 0)
{
	?>
	<br />
	<p>По Вашему запросу ничего не найдено. Сформулируйте запрос иначе !<p>
		<br />
	<?
}
}

?>
