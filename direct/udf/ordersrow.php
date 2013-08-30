<?php 

function set_gname($id)
{
	$gname = execute_scalar("SELECT name FROM goods WHERE id = ".execute_scalar("SELECT goodsid FROM ordersrow WHERE id = $id"));
	db_query("UPDATE ordersrow SET goodsname = '$gname' WHERE id = $id");
}
function after_insert($id)
{
	set_gname($id);
}

function after_update($id)
{
	set_gname($id);
}

?>