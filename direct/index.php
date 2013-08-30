<?php 
	ini_set("display_errors","Off");
	session_start();
	include("config/global.php");
	include("function/auth.php");
	include("language/$language/$language.php");
	include("lib/user.php");
	include("function/connection.php");
    include("function/db.php");
	
	
	include("function/global.php");
	include("lib/engine.php");
	
	if(isset($_GET["t"]))
	{
		$config = new Config("config/$_GET[t].php");
	}
	else
	{
		$config = new Config();
	}
	
	db_action($config);//insert,update,delete
	
	get_content($config);//get content html
	
	if($engine_db == "fb")
	{
		ibase_close($db);
	}
?>
