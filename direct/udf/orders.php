<?
function after_delete($id)
{
	db_query("DELETE FROM ordersrow WHERE parentid = $id");
}

?>