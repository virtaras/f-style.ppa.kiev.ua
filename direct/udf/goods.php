<?php
function get_catalog($parentid,$level,$value)
{
	$top_level = mysql_query("SELECT id,name,parentid FROM catalog WHERE parentid = $parentid ORDER BY showorder ");
	while($row = mysql_fetch_assoc($top_level))
	{
		$blank ="";
		for($i = 0;$i < 100 - $level;$i++)
		{
			$blank .= "&nbsp;&nbsp;";
		}
		echo "<option ".($value == $row["id"] ? "SELECTED" : "")." value=\"$row[id]\" >$blank $row[name]</option>";
		get_catalog($row["id"],$level-1,$value);
			
	}
}
function get_catalog_list($parentid,$level,$control)
{
	echo "<select ".$control->js." class='select_box' id='".$control->id."' name='".$control->name."'><option value='-1'>Не выбран</option>";  
	if($_GET["id"] == "-1")
	{
		get_catalog($parentid,$level,isset($_GET["parent"]) ? $_GET["parent"] : 0);
	}
	else
	{
	get_catalog($parentid,$level,$control->value);
	}
	echo "</select>"; 
}
function save_sr($id)
{
	$tab_sql = db_query("SELECT name,table_name,field_type,rname 
	FROM `fields` f 
	LEFT JOIN categoryfields ON  categoryfields.fieldid = f.id
	WHERE source = '0' AND f.field_type = 5 AND
    (categoryfields.categoryid = '".execute_scalar("SELECT parentid FROM goods WHERE id = '$id'")."' OR f.isgeneral = 1 )
    ORDER BY categoryfields.showorder");
	
	while($r = mysql_fetch_assoc($tab_sql))
	{
		db_query("DELETE FROM s".$r["table_name"]." WHERE goodsid = $id");
		$rsql = db_query("SELECT * FROM ".$r["table_name"]);
		if(isset($_POST[$r["table_name"]]))
		{
			foreach($_POST[$r["table_name"]] as $current)
			{
				db_query("INSERT INTO s".$r["table_name"]." (goodsid,valueid) VALUES ($id,$current)");
			}
		}
	}
	
	mysql_query("DELETE FROM search_goods WHERE goodsid = '$id' ");
	$grow = db_get_row("SELECT id,name,urlname FROM goods WHERE id = ".$id);
	mysql_query("INSERT INTO search_goods(name,urlname,goodsid) VALUES ('$grow[name]','$grow[urlname]',$id)");
	
}
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
function after_update($id)
{
	save_sr($id);
	//remove cache
	if(file_exists("../inc/cache/pages/tovar".$id.".inc"))
	{
		unlink("../inc/cache/pages/tovar".$id.".inc");
	}
	
	mysql_query("UPDATE goods SET brand = $_POST[brand] WHERE goodsid = $id AND brand <= 0");
}
function after_insert($id)
{
	save_sr($id);
}
function after_delete($id)
{
	mysql_query("DELETE FROM images WHERE parentid = '$id' AND source = 3");
	mysql_query("DELETE FROM goods WHERE goodsid = '$id' ");
	mysql_query("DELETE FROM search_goods WHERE goodsid = '$id' ");
}
function after_copy($oldid,$newid)
{
	$variants = db_query("SELECT * FROM goods WHERE goodsid = '$oldid' ORDER BY id");
	$res_array = array();
	while($r = db_fetch_assoc($variants))
	{
		array_push($res_array,$r);
	}
	foreach($res_array as $current)
	{
		unset($current["id"]);
		unset($current["goodsid"]);
		foreach($current as $key=>$value)
		{
			$current[$key] = "'$value'";
		}
		$sql_text = ("INSERT INTO goods (goodsid,".implode(",",array_keys($current)).") VALUES ($newid,".implode(",",$current).")");
		db_query($sql_text);

	}

	$sql = db_query("SELECT * FROM images WHERE parentid = $oldid AND source = 3");
	$i = 0;
	while($r = db_fetch_assoc($sql))
	{
		$new_image = "3_".date("dmYHis")."_".$i.".".$r["format"];
		db_query("INSERT INTO images(parentid,is_main,image,title,source,showorder,width,height ,format) VALUES('$newid',$r[is_main],'$new_image','$r[title]','$r[source]','$r[showorder]','$r[width]','$r[height]','$r[format]')");
		copy("../images/files/$r[image]","../images/files/$new_image");
		$i++;
	}

}
?>