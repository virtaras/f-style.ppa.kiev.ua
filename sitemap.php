<?
session_start();
ini_set("display_errors","on");
header('Content-Type: text/xml; charset=UTF-8');
header("Content-Disposition: attachment; filename=sitemap.xml"); 
include("inc/constant.php");
include("inc/connection.php");
include("inc/global.php");
include("inc/emarket.php");
echo "<?xml version='1.0' encoding='UTF-8'?>";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?
$sql = mysql_query("SELECT id,name,url FROM content");
while($r = mysql_fetch_assoc($sql))
{
	?>
	<url>
    <loc><?=_SITE?><?=$r["url"]?></loc>
    <changefreq>daily</changefreq>
   </url>  
	<?
}
$sql = mysql_query("SELECT id FROM catalog WHERE invisible = 0");
while($r = mysql_fetch_assoc($sql))
{
	?>
	<url>
    <loc><?=_SITE.$catalog_array[$r["id"]]["full_url"]?></loc>
    <changefreq>daily</changefreq>
   </url>  
	<?
}
	global $currency_array;
		global $base_currenc;
$sql = mysql_query("SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand ");
while($r = mysql_fetch_assoc($sql))
{
	$url = get_product_url($r);
	?>
	<url>
    <loc><?=$url?></loc>
   </url>  
	<?
}
?>
</urlset>
