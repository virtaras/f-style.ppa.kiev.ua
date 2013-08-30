<?
function before_update($id)
{
	if(isset($_POST["urlname"]) && trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = create_urlname($_POST["name"]);
	}	
}
function before_insert()
{
	if(trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = create_urlname($_POST["name"]);
	}	
}
function get_tree_url($id,&$url)
{
	$sql = mysql_query("SELECT parentid,urlname FROM content WHERE id = $id");
	while($r = mysql_fetch_assoc($sql))
	{
		array_push($url,$r["urlname"]);
		if($r["parentid"] != "0")
		{
			get_tree_url($r["parentid"],$url);
		}
	}
}
function update($id)
{
		
	db_query("UPDATE content SET create_date = now() WHERE id = '$id'");
	//recreate url version 1 alpha
	$sql = mysql_query("SELECT id,parentid,urlname FROM content ");
	while($r = mysql_fetch_assoc($sql))
	{
		$url = array($r["urlname"]);
		if($r["parentid"] != "0")
		{
			
			get_tree_url($r["parentid"],$url);
		}	
		$url = array_reverse($url);
		mysql_query("UPDATE content  SET url = '".implode("/",$url)."' WHERE id = '$r[id]'");
		mysql_query("DELETE FROM routing WHERE content = 0 AND contentid = $r[id]");
		mysql_query("INSERT INTO routing (urlid,urlname,content,contentid) VALUES ('$r[id]','".implode("/",$url)."','0','$r[id]')");
	}
	
	

}
function after_insert($id)
{
	update($id);
	$max_order = execute_scalar("SELECT count(*) FROM content WHERE parentid = '".execute_scalar("SELECT parentid FROM content WHERE id = $id")."'");
	db_query("UPDATE content SET create_date = now(),showorder=".($max_order)." WHERE id = '$id'");
}
function after_update($id)
{
	update($id);
}
function after_delete($id)
{
	mysql_query("DELETE FROM routing WHERE content = 0 AND contentid = $id");
}
?>