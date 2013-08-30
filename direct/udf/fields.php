<?php
function create_cache($id)
{
	$file = "../inc/cache/fields_array.php";
	$sql = db_query("SELECT * FROM `fields`");
		$str = "<? \$fields_array = array();";
	while($r = db_fetch_assoc($sql))
	{
		$str .= "\$fields_array[$r[id]][\"rname\"] = '$r[rname]';";
		$str .= "\$fields_array[$r[id]][\"field_type\"] = '$r[field_type]';";
		$str .= "\$fields_array[$r[id]][\"title\"] = '$r[title]';";
		$str .= "\$fields_array[$r[id]][\"sort\"] = '$r[issorting]';";
	}
	$str .= " ?>";
	unlink($file);
	file_put_contents($file,$str);
}
function after_insert($id)
{
	$current_id_sql = mysql_query("SELECT val FROM states WHERE  id = 'fields_id'"); 
	$currentid = mysql_fetch_assoc($current_id_sql);
	mysql_query("UPDATE states SET val = '".($currentid["val"]+1)."' WHERE id = 'fields_id'");
	if($_POST["field_type"] == "2" || $_POST["field_type"] == "5")
	{
		$sql ="
		CREATE TABLE `r".($currentid["val"]+1)."` (
		`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 300 ) NOT NULL,
		`showorder` INT NOT NULL DEFAULT  '0'
		) ENGINE = MYISAM ;
		";
		mysql_query($sql);
		
		mysql_query("UPDATE `fields` SET table_name = 'r".($currentid["val"]+1)."',rname = 'r".($currentid["val"]+1)."' WHERE id = '$id'");
		if($_POST["field_type"] == "2")
		{
			mysql_query("ALTER TABLE `goods` ADD `r".($currentid["val"]+1)."` BIGINT NULL DEFAULT '0';");
		}
		$tablename = "r".($currentid["val"]+1);
		if($_POST["field_type"] == "5")
		{
			$sql ="
		CREATE TABLE `sr".($currentid["val"]+1)."` (
		`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`goodsid` BIGINT NOT NULL,
		 `valueid` BIGINT NOT NULL 
		) ENGINE = MYISAM ;
		";
		mysql_query($sql);
		}
	}
	else
	{
		switch($_POST["field_type"])
		{
			case "1":
				mysql_query("ALTER TABLE `goods` ADD `r".($currentid["val"]+1)."` VARCHAR(200) NULL DEFAULT '';");
				break;
			case "3";
				mysql_query("ALTER TABLE `goods` ADD `r".($currentid["val"]+1)."` DECIMAL(10,2) NULL DEFAULT '0';");
				break;
				case "4";
				mysql_query("ALTER TABLE `goods` ADD `r".($currentid["val"]+1)."` INT NULL DEFAULT '0';");
				break;
		}
		mysql_query("UPDATE `fields` SET rname = 'r".($currentid["val"]+1)."' WHERE id = '$id'");
	}
	create_cache($id);
}
function after_update($id)
{
	create_cache($id);
}
function before_delete($id)
{
	$current_sql = mysql_query("SELECT * FROM `fields` WHERE id = '$id'");
	$current = mysql_fetch_assoc($current_sql);
	if($current["field_type"] != "5")
	{
		mysql_query("ALTER TABLE `goods` DROP `$current[rname]`");
	}
	if($current["table_name"] != "")
	{
		mysql_query("DROP TABLE `$current[table_name]`");
	}
	if($current["field_type"] == "5")
	{
		mysql_query("DROP TABLE `s$current[table_name]`");
	}
}
?>