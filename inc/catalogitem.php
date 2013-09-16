<?
if(!isset($result_sql))
{
	$result_sql = mysql_query($sql_text);
	
}
$gcount = mysql_num_rows($result_sql);
$ind = 0;
$template_name = "catalogitem.html";
if(isset($_SESSION["mode_"]) && $_SESSION["mode_"] == "2")
{
	$template_name = "priceitem.html";
}

while($r = mysql_fetch_assoc($result_sql))
{
	$ind++;
	$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
	$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
	$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
	$tovar_url = get_product_url($r);
	include(_TEMPLATE.$template_name);
}

?>
