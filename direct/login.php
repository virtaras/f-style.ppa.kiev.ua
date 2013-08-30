<?php
session_start();
require("config/global.php");
require("function/db.php");
require("config/db.php");
if(isset($_GET["logout"]))
{
	session_destroy();
}
function check_user(&$currentuser)
{
	$count = execute_scalar("SELECT count(*) FROM users WHERE login = '".trim($_POST["login"])."' AND passw = '".trim($_POST["passw"])."'");
	

	
	if($count == 1)
	{
		$user = db_get_row("SELECT * FROM users WHERE login = '".trim($_POST["login"])."' AND passw = '".trim($_POST["passw"])."' ");
		$currentuser->ID = row("id",$user);
		$currentuser->Name = row("name",$user);
		$currentuser->Group = row("groupid",$user);
		$currentuser->Menu = row("menuid",$user);
		db_query("UPDATE users SET last_login = now(),last_ip = '$_SERVER[REMOTE_ADDR]' WHERE id = '".row("id",$user)."'");
		return true;
	}
	else
	{
		return false;
	}
}
if(isset($_GET["login"]))
{
	
	$success = false;
	require("lib/user.php");
	$user = new User($db_array[$_POST["db"]][3],$db_array[$_POST["db"]][4],$db_array[$_POST["db"]][1],$db_array[$_POST["db"]][0],$db_array[$_POST["db"]][2]);
	
	
	switch($engine_db)
	{
		case "mysql":
		if(!mysql_connect($user->Host,$user->Login,$user->Passw))
		{
			$_SESSION["login_admin_error"] = true;
		}
		else
		{
			if(!mysql_select_db(trim($user->DataBase)))
			{
				$_SESSION["login_admin_error"] = true;
			}
			else
			{
				if(check_user($user))
				{
					$success = true;
				}
				else
				{
					$_SESSION["login_admin_error"] = true;
				}	
			}
		
		}
		break;
		case "fb":
			$db = ibase_connect($user->Host.":".$user->DataBase,$user->Login,$user->Passw,$ibase_connect_charset);
			if($db)
			{
				if(check_user($user))
				{
					$success = true;
				}
				else
				{
					$_SESSION["login_admin_error"] = true;
				}	 
			}
			else
			{
				$_SESSION["login_admin_error"] = true;
			}
			break;
	}
	
	
	if($success)
	{
	
			$_SESSION["user"] = serialize($user);
			
			if(!empty($_SESSION["login_admin_back"]))
			{
				header("Location: $_SESSION[login_admin_back]");
				exit();
			}
			else
			{	
				header("Location: index.php");
				exit();
			}
	}
	

}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/admin.css" />
<script language="JavaScript" type="text/javascript" src="js/const.php"></script>	
<script language="JavaScript" type="text/javascript" src="js/global_function.js"></script>
</head><body >
<form method="post" action="login.php?login">
<center>
<?php

include("language/$language/$language.php");
if(isset($_SESSION["login_admin_error"]))
{
	?>
	<div class="error"><?=_MESSAGE_ADMIN_DENIED?></div>
	<?
	unset($_SESSION["login_admin_error"]);
}
?>
<table class="">
<tr>
	<td colspan="2" style="text-align:center;" class="tt"><?=_TITLE_ADMINISTRATOR?></td>
<tr>
<tr>
	<td class="title"><?=_USERNAME?></td>
	<td><input type="text" style="width:200px" name="login" class='input_box' /></td>
</tr>
<tr>
	<td class="title"><?=_PASSWORD?></td>
	<td><input type="password" style="width:200px" name="passw" class='input_box' /></td>
</tr>
<tr>
	<td class="title"><?=_DATABASE?></td>
	<td><select style="width:200px"  name="db">
	<?php
	
	foreach(array_keys($db_array) as $current)
	{
		?>
		<option value="<?=$current?>"><?=$db_array[$current][2]?></option>
		<?
	}
	?>
	</select></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td  style="text-align:left;"><input type="submit" class="buttons" value="<?=_BUTTON_ENTER?>" /></td>
</tr>
</table>
</center>
</form>
</body>
</html>