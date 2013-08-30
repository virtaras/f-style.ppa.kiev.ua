<?
function create_cache()
{
	$file = "../inc/cache/settings.php";
	$str = "<? ";
	$sql = db_query("SELECT * FROM settings");
	while($r = db_fetch_assoc($sql))
	{
		$str .= "define('_".trim($r["name"])."','".trim($r["val"])."');";
	}
	$str .= " ?>";
	unlink($file);
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