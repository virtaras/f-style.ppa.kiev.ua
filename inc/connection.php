<?php
	$db = mysql_connect(_HOST,_DBUSER,_DBPASSWORD);
	mysql_select_db(_DB);
	mysql_query("set names utf8");
?>