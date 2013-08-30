<?
function after_insert($id)
{
	$current_id_sql = mysql_query("SELECT val FROM states WHERE  id = 'fields_id'"); 
	$currentid = mysql_fetch_assoc($current_id_sql);
	mysql_query("UPDATE states SET val = '".($currentid["val"]+1)."' WHERE id = 'fields_id'");
	mysql_query("UPDATE goods_groups SET code = 'r".($currentid["val"]+1)."' WHERE id = '$id'");
	mysql_query("ALTER TABLE `goods` ADD `r".($currentid["val"]+1)."` BIGINT NULL DEFAULT '0';");
}
function before_delete($id)
{
	$current_sql = mysql_query("SELECT * FROM goods_groups WHERE id = '$id'");
	$current = mysql_fetch_assoc($current_sql);
	mysql_query("ALTER TABLE `goods` DROP `$current[code]`");
	
}
?>