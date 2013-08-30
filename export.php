<?

function yandex()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.xml");
	echo "<?xml version='1.0' encoding='windows-1251' ?>";

?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=date("Y-m-d H:i")?>">
<shop>
  <name><?=setting("price_title")?></name>
  <company><?=setting("price_description")?></company>
  <url><?=_SITE?></url>
  <currencies>
	<currency id="UAH" rate="1"/>
	<currency id="USD" rate="<?=execute_scalar("SELECT course  FROM currency  WHERE id = '3'")?>"/>
  </currencies>
  <categories>
  <?
  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
  while($r = mysql_fetch_assoc($sql))
  {
	?>
	 <category id="<?=$r["id"]?>"  ><?=str_replace('&','-',iconv("utf8","cp1251",$r["name"]))?></category>

	<?
  }
  ?>
  </categories>
  
  <offers>
	<?
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT image FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],1,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],1,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[1]["shortname"];
		$tovar_url = get_product_url($r);
		?>
		<offer id="<?=$r["id"]?>" type="vendor.model" available="false" >

	  <url><?=$tovar_url?></url>
	  <price><?=($price_action > 0 ? $price_action : $price)?></price>
	  <currencyId>UAH</currencyId>
	  <categoryId><?=$r["parentid"]?></categoryId >
	  <picture><?if ($r["imid"] != "") {?><?=_SITE?>images/files/<?=$r["imid"]?><?} else {}?></picture>

	<delivery> true </delivery>
	<local_delivery_cost>0</local_delivery_cost>
	  <typePrefix></typePrefix>
	  <vendor><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["brandname"]))?></vendor>
	  <vendorCode><?=$r["code"]?></vendorCode>
	  <model><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["name"]))?></model>
	  <description><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"])) ?></description>
	  <manufacturer_warranty>true</manufacturer_warranty>

	</offer>

		<?
	}
	?>
  </offers>

</shop>

</yml_catalog>
	<?
}
function hotline()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.xml");
	echo "<?xml version='1.0' encoding='windows-1251' ?>";

?>
<price>
    <date><?=date("Y-m-d H:i")?></date>
    <firmName><?=setting("price_title")?></firmName>
    <firmId><?=setting("price_description")?></firmId>
    <rate><?=execute_scalar("SELECT course  FROM currency  WHERE id = '3'")?></rate>
    <categories>
        <category>
  <?
  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
  while($r = mysql_fetch_assoc($sql))
  {
	?>
            <id><?=$r["id"]?></id>
            <parentId><?=$r["parentid"]?></parentId>
            <name><?=str_replace('&','-',iconv("utf8","cp1251",$r["name"]))?></name>
	<?
  }
	?>
        </category>
    </categories>
    <items>       
	<?
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$tovar_url = get_product_url($r);
		?>
	<item>
            <categoryId><?=$r["id"]?></categoryId>
            <code><?=$r["id"]?></code>
            <vendor><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["brandname"]))?></vendor>
            <name><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["name"]))?></name>
            <description><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"])) ?></description>
            <url><?=$tovar_url?></url>
            <image><?if ($r["imid"] != "") {?><?=_SITE?>images/1000/<?=$r["imid"]?>.jpg<?} else {}?></image>
            <priceRUAH><?=($price_action > 0 ? $price_action : $price)?></priceRUAH>
            <priceRUSD></priceRUSD>
            <priceOUSD></priceOUSD>
            <stock>На складе</stock>
            <guarantee>12</guarantee>
		 </item>
		<?
	}
	?>
        

    </items>
</price> 
	<?
}
function price()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.xml");
	echo "<?xml version='1.0' encoding='windows-1251' ?>";
?>

<price date="<?=date("Y-m-d H:i")?>">
<name><?=setting("price_title")?></name>
<currency code="USD" rate="<?=execute_scalar("SELECT course  FROM currency  WHERE id = '3'")?>"/>
	<catalog>
	<?
		  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
  while($r = mysql_fetch_assoc($sql))
  {
	?>
		<category id="<?=$r["id"]?>"><?=str_replace('&','-',iconv("utf8","cp1251",$r["name"]))?></category>
	<?
  }
  ?>
</catalog>
<items>
	<?
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$tovar_url = get_product_url($r);
		?>
<item id="<?=$r["id"]?>">
	<name><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["name"]))?></name>
	<categoryId><?=$r["parentid"]?></categoryId>
	<price><?=($price_action > 0 ? $price_action : $price)?></price>
	<bnprice></bnprice>
	<url><?=$tovar_url?></url>
	<image><?if ($r["imid"] != "") {?><?=_SITE?>images/1000/<?=$r["imid"]?>.jpg<?} else {}?></image>
	<vendor><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["brandname"]))?></vendor>
	<description><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"]))?>
</description>
	<warranty>12</warranty>
</item>
		<?
	}
	?>
</items>
</price>
<?
}
function nadavi()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.csv");
	echo "Раздел;бренд;модель / размер шины / ИН/ИС;Коментарий;сезон;группа;Цена, грн./ед.;Линк на модель;Линк на изображение \n";
	
  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
	while($r2 = mysql_fetch_assoc($sql))
	{
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$tovar_url = get_product_url($r);
?>
<?=str_replace('&','-',iconv("utf8","cp1251",$r2["name"]))?>;<?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["brandname"]))?>;<?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["name"]))?>;<?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"])) ?>;<?=($price_action > 0 ? $price_action : $price)?>;<?=$tovar_url?>;<?if ($r["imid"] != "") {?><?=_SITE?>images/1000/<?=$r["imid"]?>.jpg<?} else {}?>;
<?
	}
	}
}
function vcene()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.xml");
	echo "<?xml version='1.0' encoding='windows-1251' ?>";

?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=date("Y-m-d H:i")?>">
<shop>
  <name><?=setting("price_title")?></name>
  <company><?=setting("price_description")?></company>
  <url><?=_SITE?></url>
  <currencies>
	<currency id="UAH" rate="1"/>
	<currency id="USD" rate="<?=execute_scalar("SELECT course  FROM currency  WHERE id = '3'")?>"/>
  </currencies>
  <categories>
  <?
  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
  while($r = mysql_fetch_assoc($sql))
  {
	?>
	<category id="<?=$r["id"]?>"  ><?=str_replace('&','-',iconv("utf8","cp1251",$r["name"]))?></category>
	<?
  }
  ?>
  </categories>
  
	<offers>
	<?
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$tovar_url = get_product_url($r);
		?>
	<offer id="<?=$r["id"]?>" type="vendor.model" available="false" >
		<url><?=$tovar_url?></url>
		<price><?=($price_action > 0 ? $price_action : $price)?></price>
		<currencyId>UAH</currencyId>
		<categoryId><?=$r["parentid"]?></categoryId >
		<image><?if ($r["imid"] != "") {?><?=_SITE?>images/1000/<?=$r["imid"]?>.jpg<?} else {}?></image>
		<description><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"])) ?></description>
	</offer>
		<?
	}
	?>
  </offers>
</shop>
</yml_catalog>
	<?
}
function ava()
{
	include("inc/cache/catalog_array.php");
	include("inc/cache/settings.php");
	header ("content-type: text/xml; charset=windows-1251");
	header("Content-Disposition: attachment; filename=price.xml");
	echo "<?xml version='1.0' encoding='windows-1251' ?>";
?>
<price date="<?=date("Y-m-d H:i")?>">
	<name><?=setting("price_title")?></name>
	<url><?=_SITE?></url>
	<currency code='UAH'/>
	<currency code="USD" rate="<?=execute_scalar("SELECT course  FROM currency  WHERE id = '3'")?>"/>
<catalog>
  <?
  $sql = mysql_query("SELECT id,name,parentid FROM catalog   ORDER BY parentid");
  while($r = mysql_fetch_assoc($sql))
  {
	?>
	 <category id="<?=$r["id"]?>"  ><?=str_replace('&','-',iconv("utf8","cp1251",$r["name"]))?></category>
	<?
  }
  ?>
</catalog>
	<items>
	<?
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,

			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
	   FROM goods
        INNER JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
        WHERE  goods.exist_type != 2";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_assoc($sql))
	{
		$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$tovar_url = get_product_url($r);
		?>
		<item id="<?=$r["id"]?>">
			<name><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["name"]))?></name>
			<url><?=$tovar_url?></url>
			<price><?=($price_action > 0 ? $price_action : $price)?></price>
			<categoryId><?=$r["parentid"]?></categoryId>
			<vendor><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["brandname"]))?></vendor>
			<image><?if ($r["imid"] != "") {?><?=_SITE?>images/1000/<?=$r["imid"]?>.jpg<?} else {}?></image>
			<description><?=iconv("utf8","cp1251",str_replace("&","&amp;",$r["description"])) ?></description>
			<vendorCode><?=$r["code"]?></vendorCode>
		</item>
		<?
	}
	?>
	</items>
</price>
<?
}
if(isset($_GET["source"]))
{
	include("inc/constant.php");
	include("inc/connection.php");
	include("inc/global.php");
	include("inc/emarket.php");
	include("inc/engine.php");
	
	
	switch($_GET["source"])
	{
	
		case "yandex":
				yandex();
			break;
		case "hotline":
				hotline();
			break;
		case "price":
				price();
			break;
		case "nadavi":
				nadavi();
			break;
		case "vcene":
				vcene();
			break;
		case "ava":
				ava();
			break;
	}
		
}
?>