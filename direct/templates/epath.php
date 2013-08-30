<?
//change position
if(isset($_GET["up"]))
{
	$parent = execute_scalar("SELECT parentid FROM catalog WHERE id = '$_GET[up]'");
	$count_item = execute_scalar("SELECT count(*) AS count_item FROM catalog WHERE parentid = '$parent'");
	$currentsql = mysql_query("SELECT id,showorder FROM catalog WHERE id = '$_GET[up]'");
	$current = mysql_fetch_assoc($currentsql);
	if(($current["showorder"] == 1 && $_GET["position"] == "-1") || ($current["showorder"] == $count_item && $_GET["position"] == "1"))
	{
		
	}
	else
		{
			$old_position = $current["showorder"];
			$new_position = $current["showorder"] + $_GET["position"];
			
			//echo $old_position." ".$new_position;
			
			$old_id_sql = mysql_query("SELECT id FROM catalog WHERE parentid = '$parent' AND showorder = '$new_position'");
			$old_id = mysql_fetch_assoc($old_id_sql);
			$old = $old_id["id"];
			
			mysql_query("UPDATE catalog SET showorder = '$old_position' WHERE id = '$old'");
			mysql_query("UPDATE catalog SET showorder = '$new_position' WHERE id = '$_GET[up]'");
		}
        create_cache();
?>
<script language="JavaScript">
document.location.href  = "http://<?=get_url(0,$this->config)?>&sort=showorder+ASC";
</script>
<?
exit();
}

//change position

if(isset($_GET["parent"]))
{
	$path_array = array();
	function get_path_array($id,&$arr = array())
	{
		$parent = execute_scalar("SELECT parentid FROM catalog WHERE id = '$id'");
		
		if($parent != "0")
		{
			array_push($arr,$parent);
			get_path_array($parent,&$arr);
		}
		
	}
	get_path_array($_GET["parent"],&$path_array);
	asort($path_array);
	reset($path_array);
	?>
	<p>
	<a href='index.php?t=catalog&sort=showorder+asc'>Верхний уровень</a> 
	<?
	if(count($path_array) == 0)
	{
		$path_array = array($_GET["parent"]);
	}
	else
	{
		array_push($path_array,$_GET["parent"]);
	}
	foreach($path_array as $key)
		{
		$style  = "";
		if($key == $_GET["parent"] )
		{
			$style = "style='font-weight:bold;'";
		}
		echo  " / <a $style class='path' href='index.php?t=catalog&parent=$key&sort=showorder+ASC'>".execute_scalar("SELECT name FROM catalog WHERE id = '$key'")."</a>&nbsp;";
		}
	?>
	
	</p>
	<?
}
?>