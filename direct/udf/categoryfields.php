<?
function update_subcatalog_categoryfields($parentid,$field)
{
	$sql = mysql_query("SELECT id FROM catalog WHERE parentid = '$parentid'");
	while($row = mysql_fetch_assoc($sql))
	{
		mysql_query("UPDATE categoryfields SET
			showorder = '$field[showorder]',
			inlist = '$field[inlist]',
			hide_default = '$field[hide_default]'
			WHERE categoryid = '$row[id]' AND fieldid = '$field[fieldid]'");
		update_subcatalog_categoryfields($row["id"],$field);
	}
}
function insert_subcatalog_categoryfields($parentid,$field)
{
	$sql = db_query("SELECT id FROM catalog WHERE parentid = '$parentid'");
	while($r = db_fetch_assoc($sql))
	{
		if(execute_scalar("SELECT count(*) FROM categoryfields WHERE fieldid = '$field[fieldid]' AND categoryid = '$r[id]'") == 0) {
		db_query("INSERT INTO categoryfields (categoryid,fieldid,showorder,inlist,hide_default) VALUES ('$r[id]','$field[fieldid]','$field[showorder]','$field[inlist]','$field[hide_default]')");}
		insert_subcatalog_categoryfields($r["id"],$field); 
	}
	
}
function delete_subcatalog_categoryfields($parentid,$fieldid)
{
	$sql = db_query("SELECT id FROM catalog WHERE parentid = '$parentid'");
	while($r = db_fetch_assoc($sql))
	{
		db_query("DELETE FROM categoryfields WHERE fieldid = '$fieldid' AND categoryid = '$r[id]'");
		delete_subcatalog_categoryfields($r["id"],$fieldid);
	}
	
}
function after_insert($id)
{
	$field = db_get_row("SELECT * FROM categoryfields WHERE id = '$id'");
	insert_subcatalog_categoryfields($field["categoryid"],$field);
}
function before_delete($id)
{
	delete_subcatalog_categoryfields(execute_scalar("SELECT categoryid FROM categoryfields WHERE id = '$id'"),execute_scalar("SELECT fieldid FROM categoryfields WHERE id = '$id'"));
}
function after_update($id)
{
	$field = db_get_row("SELECT * FROM categoryfields WHERE id = '$id'");
	update_subcatalog_categoryfields($field["categoryid"],$field);
}
?>