<?
function after_insert($id)
{
	$current_id_sql = db_query("SELECT val FROM states WHERE  id = 'fields_id'"); 
	$currentid = db_fetch_assoc($current_id_sql);
	db_query("UPDATE states SET val = '".($currentid["val"]+1)."' WHERE id = 'fields_id'");
	db_query("UPDATE order_fields SET code = 'r".($currentid["val"]+1)."' WHERE id = '$id'");
	mysql_query("ALTER TABLE `orders` ADD `r".($currentid["val"]+1)."` VARCHAR( 500 ) NULL;");
}
function before_delete($id)
{
	$current_sql = db_query("SELECT * FROM order_fields WHERE id = '$id'");
	$current = db_fetch_assoc($current_sql);
	db_query("ALTER TABLE `orders` DROP `$current[code]`");
	
}
?>