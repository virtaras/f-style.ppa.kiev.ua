<?php
function after_update($id)
{
	if(isset($_POST["publish"]))
	{
		$is_exists = execute_scalar("SELECT id FROM  ratings WHERE goodsid = '".$_POST["goodsid"]."' AND commentid = '$id'");
		if($is_exists != "")
		{
			mysql_query("UPDATE ratings SET rating = '".$_POST["rating"]."' WHERE id = '$is_exists'");
		}
		else
		{
			mysql_query("INSERT INTO ratings (goodsid,rating,create_date,commentid) VALUES ('".$_POST["goodsid"]."','".$_POST["rating"]."',now(),$id)");
		}
		
		$current = db_get_row("SELECT sum(rating)/count(id)  as percent, count(id) as votes FROM ratings WHERE goodsid = '".intval($_POST["goodsid"])."'");
		
		$current["percent"]  = round($current["percent"]*2) / 2;
							
		mysql_query("UPDATE goods SET r233 = '" . $current["percent"] . "', r243 = '" . $current["votes"] . "' WHERE id = '".intval($_POST["goodsid"])."'");
		
	}
}

function before_delete($id)
{
	mysql_query("DELETE FROM ratings WHERE commentid = '$id'");
	
	$comment = db_get_row("SELECT * FROM comments WHERE id = '$id'");
	
	$current = db_get_row("SELECT sum(rating)/count(id)  as percent, count(id) as votes FROM ratings WHERE goodsid = '".intval($comment["goodsid"])."'");
				
		$current["percent"]  = round($current["percent"]*2) / 2;
							
		mysql_query("UPDATE goods SET r233 = '" . $current["percent"] . "', r243 = '" . $current["votes"] . "' WHERE id = '".intval($comment["goodsid"])."'");
}
?>