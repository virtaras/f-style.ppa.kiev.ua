<?
function create_cache()
{
	$file = "../inc/cache/menu.php";
	$str = "<? \$menuarray = array();";
	$sql = db_query("SELECT * FROM menulinks WHERE invisible = 0 ORDER BY showorder");
	while($r = db_fetch_assoc($sql))
	{
		$str .= " \$menuarray['".$r["parentid"]."']['".$r["id"]."']['name'] = '".$r["title"]."';";
		$str .= " \$menuarray['".$r["parentid"]."']['".$r["id"]."']['nofollow'] = ".$r["nofollow"].";";
		$str .= " \$menuarray['".$r["parentid"]."']['".$r["id"]."']['link'] = '".$r["link"]."';";
	}
	$str .= " ?>";
	if(file_exists($file))
	{
		unlink($file);
	}
	file_put_contents($file,$str);
}
function after_insert($id)
{
	create_cache();
}
function after_update($id)
{
	create_cache();
}
?>
