<?
function set_password($id)
{
	if(!empty($_POST["passw"]))
	{
		$passw = md5($_POST["passw"]);
		db_query("UPDATE clients SET passw = '$passw' WHERE id = $id");
	}
	
}
function after_update($id)
{
	set_password($id);
}
function after_insert($id)
{
	set_password($id);
}
?>