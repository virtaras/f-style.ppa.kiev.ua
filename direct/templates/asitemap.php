<?
session_start();
require("../../inc/cache/catalog_array.php");
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
require("../../inc/cache/catalog_array.php");
header("Content-type: text/csv; charset=utf-8; header=present");
header("Content-Disposition: attachment; filename=export.csv");
?>
<? $zagolovok = "Название группы товаров;Название производителя;Модель товара;Цена (в долларах);ссылка уникального перехода на страницу описания товара;Текствовое описание";
$zagolovok = mb_convert_encoding($zagolovok, "CP1251", "UTF-8");
echo $zagolovok;?>;<?//Заголовки?>
<?echo "\r\n";?>
<?
$sql = db_query("SELECT * FROM goods ORDER BY parentid");
while($r = db_fetch_assoc($sql))
{?><? if ($r["r405"] == 1 and $r["exist_type"] == 1) {?>
<? $encoder406 = (execute_Scalar("SELECT name FROM r406 WHERE id = $r[r406]"));
$encoder406 = mb_convert_encoding($encoder406, "CP1251", "UTF-8");
echo $encoder406;?>;<?//Название категории Nadavi?>
<?=execute_Scalar("SELECT name FROM brands WHERE id = $r[brand]")?>;<?//Бренд?>
<? $data = $r["name"];
$data = mb_convert_encoding($data, "CP1251", "UTF-8");
echo $data;?>;<?//Название товара?>
<? $currency = $r["currency"];
$courseUSD = (execute_Scalar("SELECT course FROM currency WHERE id = 3"));
$courseEUR = (execute_Scalar("SELECT course FROM currency WHERE id = 7"));
$price_action = (str_replace(",",".",$r["price_action"]));
$price = (str_replace(",",".",$r["price"]));
if ($currency == 3) 
{
	if ($price_action == 0)	
	{
	echo round($price);
	} else {
	echo round($price_action);
	}
}	
if ($currency == 1) 
{
	if ($price_action == 0)	
	{
	echo round($price/$courseUSD);
	} else {
	echo round($price_action/$courseUSD);
	}
}
if ($currency == 7) 
{
	if ($price_action == 0)	
	{
	echo round($price*$courseEUR/$courseUSD);
	} else {
	echo round($price_action*$courseEUR/$courseUSD);
	}
}?>;<?//Цена?>
<?$url = 'http://'.$_SERVER['HTTP_HOST'].'/tovar/'.$r["id"].'.html';
echo $url;?>;<?//URL?>
<? $str1 = $r["description"];
$str = str_replace("\r\n",'',html_entity_decode(strip_tags(trim(htmlspecialchars_decode(mb_convert_encoding($str1, "CP1251", "UTF-8"))))));
$str1 = str_replace("&bull;","",$str);
$str2 = str_replace("&bdquo;","",$str1);
echo str_replace(";",",",$str2);
?>;<?//Краткое описание товара?>
<?echo "\r\n";
}?><?}?>