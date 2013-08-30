<?
$brandrow = array();
 
if(is_numeric($_GET["id"]))
{
	$brandrow = execute_row_assoc("SELECT * FROM brands WHERE id = $_GET[id]");
}
else
{
	$brandrow = execute_row_assoc("SELECT * FROM brands WHERE urlname = '".urldecode($_GET["id"])."'");
}
if($brandrow["id"] == "")
{
	header("HTTP/1.0 404 Not Found");
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	die(); 
}

$title = setting("seo_brand_title");
$title = str_replace("[b]",$brandrow["name"],$title);


$keywords = setting("seo_brand_keywords");
$keywords = str_replace("[b]",$brandrow["name"],$keywords);

$description = setting("seo_brand_description");
$description = str_replace("[b]",$brandrow["name"],$description);

$seo_text  = setting("seo_text_brand");
$seo_text = str_replace("[b]",$brandrow["name"],$seo_text);
	
if(isset($_GET["parent"]))
{
	$title = str_replace("[c]",$catalog_array[$_GET["parent"]]["name"],$title);
	$keywords = str_replace("[c]",$catalog_array[$_GET["parent"]]["name"],$keywords);
	$description = str_replace("[c]",$catalog_array[$_GET["parent"]]["name"],$description);
	$seo_text = str_replace("[c]",$catalog_array[$_GET["parent"]]["name"],$seo_text);
}
else
{
	$title = str_replace("[c]","",$title);
	$keywords = str_replace("[c]","",$keywords);
	$description = str_replace("[c]","",$description);
	$seo_text = str_replace("[c]","",$seo_text);
}

if($title == "")
{
	$title = $brandrow["name"];
}

function get_content()
{
	global $brandrow;
	include(_TEMPLATE."brands.html");
}

?>