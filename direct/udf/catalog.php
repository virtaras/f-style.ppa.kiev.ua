<?
ini_set("display_errors","On");
function delete($id)
{
	mysql_query("DELETE FROM catalog WHERE id  = '$id'");
	mysql_query("DELETE FROM goods WHERE parentid  = '$id'");
	mysql_query("DELETE FROM images WHERE parentid  = '$id' AND source = '4'");
	mysql_query("DELETE FROM categoryfields WHERE categoryid  = '$id'");
	$result = mysql_query("SELECT id FROM catalog WHERE parentid = '$id'");
	$del_array = array();
	while($row = mysql_fetch_assoc($result))
	{
		array_push($del_array,$row["id"]);
	}
	foreach($del_array as $current)
	{
		delete($current);
	}
}
function before_insert()
{
    if(trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = create_urlname($_POST["name"]);
	}	
	$max = execute_scalar("SELECT count(*) FROM catalog WHERE parentid = '$_POST[parentid]'");
	$_POST["showorder"] = $max+1;
}
function after_delete($id)
{
	delete($id);
	create_cache($id);
	//mysql_query("DELETE FROM routing WHERE content = 1 AND contentid = $id");
}
function create_cache($id = 0)
{
	
	
	$file = $_SERVER["DOCUMENT_ROOT"]."/inc/cache/catalog_array.php";
	$str = "<? \$ca = array();";
	$all_id_sql = mysql_query("SELECT catalog.id,parentid,in_list,one_name,name,urlname FROM catalog  
	WHERE invisible = 0 ORDER BY showorder,name ");
	//mysql_query("DELETE FROM routing WHERE content = 1");
	while($row = mysql_fetch_assoc($all_id_sql))
	{
		
		//$arr = array($row["id"]);
		//$url = array($row["urlname"]);
		//get_tree_id($row["id"],&$arr,&$url);
		
		//$url = array_reverse($url);
		//reset($url);
		
		
		//mysql_query("INSERT INTO routing (urlid,urlname,content,contentid) VALUES ('$row[id]','".implode("/",$url)."','1','$row[id]')");
		//mysql_query("INSERT INTO routing (urlid,urlname,content,contentid) VALUES ('$row[id]','".$row["urlname"]."','1','$row[id]')");

		
		$str .= "\$ca[$row[id]][\"parent\"] = '$row[parentid]';";
		$str .= "\$ca[$row[id]][\"url\"] = '".$row["urlname"]."-c" . $row["id"]. "';";
		//$str .= "\$ca[$row[id]][\"full_url\"] = '".implode("/",$url)."';";
		$str .= "\$ca[$row[id]][\"name\"] = '".$row["name"]."';";
		
		//$str .= "\$urls['".implode("/",$url)."'] = '".$row["id"]."';";
		
		
		
		
		
		//$str .= "\$ca[$row[id]][\"inlist\"] = '".$row["in_list"]."';";
		//$str .= "\$ca[$row[id]][\"onename\"] = '".$row["one_name"]."';";
		$str .= "\$ca[$row[id]][\"scount\"] = '".execute_scalar("SELECT count(*) FROM catalog WHERE parentid = '$row[id]'")."';";
			
	}
	$str .= " ?>";
	if(file_exists($file))
	{
		unlink($file);
	}
	file_put_contents($file,$str);

	
}
function before_update($id)
{
	if(isset($_POST["urlname"]) && trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = create_urlname($_POST["name"]);
	}	
}
function after_update($id)
{
	create_cache($id);
}
function after_insert($id)
{

    create_cache($id);
    $parentid = execute_scalar("SELECT parentid FROM catalog WHERE id = '$id'");
    if($parentid != 0)
    {
        $sql = db_query("SELECT * FROM categoryfields WHERE categoryid = '$parentid' ");
        while($field = db_fetch_assoc($sql))
        {
           	db_query("INSERT INTO categoryfields (categoryid,fieldid,showorder,inlist) VALUES ('$id','$field[fieldid]','$field[showorder]','$field[inlist]')");
        }
    }
	
    
}
?>