<?php
if(isset($_GET["restore"]) || isset($_GET["login"]))
{
	session_start();
	require("constant.php");
	require("connection.php");
	require("global.php");
	require("emarket.php");
	header('Content-Type: text/html; charset=utf-8');
	if(isset($_GET["restore"]))
	{
		$sql = mysql_query("SELECT * FROM clients WHERE email = '$_POST[remail]'");
		$row = mysql_fetch_assoc($sql);
	if($row["id"] == "")
	{
		?>
		<script>alert('E-Mail не найден !');</script>
		<?
	}
	else
	{
		$newpassword = random_password(5,false,true) ;
		mysql_query("UPDATE clients SET passw = '".md5($newpassword)."' WHERE id = ".$row["id"]);
		
		/*
		mail($row["email"],"Восстановление пароля на ".$_SERVER["SERVER_NAME"],"<p>Ваш новый пароль: $newpassword</p>", "From: ".$_SERVER['HTTP_HOST']." <no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']).">"."\r\n".'Content-type: text/html; charset=utf-8'."\r\n");
		*/
		
		send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$row["email"], "UTF-8", "UTF-8", "Восстановление пароля на ".$_SERVER["SERVER_NAME"], "<p>Ваш новый пароль: $newpassword</p>" );
		
		
		?>
		<script>alert('Пароль отправлен на Ваш E-Mail !');</script>
		<?
		
		}
		exit();
	}
	if(isset($_GET["login"]))
	{
		$sql = mysql_query("SELECT * FROM clients WHERE email = '".htmlspecialchars($_POST["login_email"],ENT_QUOTES)."' AND passw = '".md5(trim($_POST["login_passw"]))."'");
		$row = mysql_fetch_assoc($sql);
		if($row["id"] == "")
		{
			?>
			<script>alert('Неверный логин или пароль !');</script>
			<?exit();
		}
		else if($row["confirmed"]==0){
			?>
			<script>alert('E-mail не подтвержден !');</script>
			<?exit();
		}
		else
		{
			if($row["discount_group"] != "-1")
			{
			
			$row["discount"] = max($row["discount"],execute_scalar("SELECT discount FROM discount_group WHERE id = ".$row["discount_group"]));		
			}
			$_SESSION["login_user"] = $row;
			
			
			
			mysql_query("UPDATE clients SET last_login = now(), last_ip = '".$_SERVER["REMOTE_ADDR"]."' WHERE id = '$row[id]'" );
			if(!isset($_POST["event"])){?>
			<script>
			window.parent.location.href = '/account/main.html';
			</script>
			<?}else{?>
			<script>
				afterLogged();
			</script>
			<?}
		}
	}
	
}
else
{
	$title = "Вход для зарегистрированных пользователей";
	function get_content()
	{
		include(_DIR._TEMPLATE."login.html");
	}
}

?>