<?
$title = "Сравнение товаров";
function get_content()
{
	global $currency_array;
	global $base_currency;
	$garray = array();
	$parents = array();
	$sql = mysql_query("SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
	WHERE goods.id IN (".implode(",",$_SESSION["compare_".$_GET["id"]]).")");
		while($r = mysql_fetch_assoc($sql))
		{
			array_push($garray,$r);
			array_push($parents,$r["parentid"]);
		}
		if(count($garray) == 0)
		{
			return 0;
		}
		include(_DIR._TEMPLATE."compare.html");
}
?>
