<?
function create_cache($id)
{
	$r = db_get_row("SELECT * FROM contentarea WHERE id = $id");
	$filename = "../inc/cache/$r[strongname].inc";
	if(file_exists($filename))
	{
		unlink($filename);
	}
	file_put_contents($filename,htmlspecialchars_decode($r["html"]));
}
function after_insert($id)
{
	create_cache($id);
}
function after_update($id)
{
	create_cache($id);	
}
function before_delete($id)
{
	$strongname = execute_scalar("SELECT strongname FROM contentarea WHERE id = $id");
	if(file_exists("../inc/cache/$strongname.inc"))
	{
		unlink("../inc/cache/$strongname.inc");
	}
}
?>