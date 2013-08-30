<script src="/templates/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script  type="text/javascript" src="/js/ui.jquery.js"></script>
<script  type="text/javascript" src="/js/emarket.js"></script>
<link rel="stylesheet" type="text/css" href="/templates/css/style.css" media="all" />

<script src="/templates/js/jquery.mousewheel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/js/smoothness/ui.jquery.css" media="all" />
<script src="/templates/fancybox/jquery.fancybox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/templates/fancybox/jquery.fancybox.css" media="all" />
<script src="/templates/js/jquery.jnice.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/templates/css/jnice.css" media="all" />
<script src="/templates/js/jquery.anythingslider.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/templates/css/anythingslider.css" media="all" />
<script src="/templates/js/jquery.jcarousel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/templates/css/skin.css" media="all" />
<link rel="stylesheet" type="text/css" href="/templates/css/skin1.css" media="all" />
<script src="/templates/js/jquery.jscrollpane.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/templates/css/jquery.jscrollpane.css" media="all" />

<script src="/templates/js/cloud-zoom.1.0.2.min.js" type="text/javascript"></script>

<script src="/templates/js/scr.js" type="text/javascript"></script>
<script  type="text/javascript">var discount= {money:0,percent:0}; var topID = 0; </script>

<script src="/templates/basket/basket.js" type="text/javascript"></script>

	<!--[if lt IE 9]>
		<script src="js/pie.js" type="text/javascript"></script>
	<![endif]-->

<?php
if(isset($_GET["restore"]) || isset($_GET["login"]))
{
	session_start();
	require $_SERVER["DOCUMENT_ROOT"]."//inc/constant.php";
	require $_SERVER["DOCUMENT_ROOT"]."//inc/connection.php";
	require $_SERVER["DOCUMENT_ROOT"]."//inc/global.php";
	require $_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php";
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
			window.parent.location.href = '/account.html';
			</script>
			<?}else{?>
			<script>
				afterLogged();
			</script>
			<?}
		}
	}
	
}
?>