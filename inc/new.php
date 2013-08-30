<?
if(isset($_GET["rg"]))
{
	
	session_start();
	//ini_set("display_errors","On");
	header('Content-Type: text/html; charset=utf-8');
	
	require("constant.php");
	require("connection.php");
	require("global.php");
	require("emarket.php");
	require("protection.php");
	foreach(array_keys($_POST) as $key)
	{
		//$_POST[$key] = str_ireplace("'","''",$_POST[$key]);
		$_POST[$key] = prep($_POST[$key]);
	}
	
	?>
	<script language="JavaScript">
		function show_message(text)
		{
			/*window.parent.$("#registration_info").html(text);
			window.parent.$("#registration_info").dialog("open");*/
			alert(text);
		}
	</script>
	<?
	$_POST["email"] = trim($_POST["email"]);
	$email = execute_scalar("SELECT count(*) FROM clients WHERE email = '$_POST[email]'");
	$r_email=$_POST["email"];
	$r_passw=$_POST["passw"];
	
	if($email > 0)
	{
		?>
		<script language="JavaScript">show_message('Email <?=$_POST["email"]?> уже есть в базе данных !');</script>
		<?
		exit();
	}
	else
	{
		if(!check_email($r_email))
		{?>
				<script language="JavaScript">show_message('Вы ввели некорректный E-mail адрес !'+'<?=$r_email?>');</script>
				<?
				exit();
		}
		if(strlen($r_passw) < 5)
		{
			?>
				<script language="JavaScript">show_message('Длинна пароля меньше, чем 5 символов !');</script>
				<?
			exit();
		}


		$valid = true;
		if($valid)
		{
			$values = array();
			mysql_query("INSERT INTO clients(create_date,email,passw,last_login,last_ip,confirmed) VALUE (now(),'".$_POST["email"]."','".md5(trim($_POST["passw"]))."',now(),'".$_SERVER["REMOTE_ADDR"]."',0)");
			$id = get_last_id("clients");
			$confirmstr=md5($id.$email);
			mysql_query("UPDATE clients SET confirmstr='$confirmstr' WHERE id='$id'");
			$_SESSION["login_user"] = execute_row_assoc("SELECT * FROM clients WHERE id = $id");
			
			//Send mail
			
			$body = html2("registr_mail");
			$body = str_replace("@email",$r_email,$body);
			$body = str_replace("@passw",trim($r_passw),$body);
			$confirm_email="<br/><br/>Подтвердить E-mail можно <a href='http://".$_SERVER["HTTP_HOST"]."/confirm-email/".$confirmstr."'>здесь</a>";
			$body.=$confirm_email;
			send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$r_email,"UTF-8", "UTF-8", "Уведомление о регистрации на сайте ".$_SERVER['HTTP_HOST'], $body );
			if(execute_scalar("SELECT count(*) FROM emailsdb WHERE email = '".$_POST["email"]."'") == 0)
			{
				mysql_query("INSERT INTO emailsdb (email) VALUES ('".$_POST["email"]."')");
			}
			
			?>
			<script language="JavaScript">
				window.parent.location = '<?=_SITE?>account.html';
			</script>
				<?
		}
		else
		{
			?>
			<script language="JavaScript">show_message("Вы ввели неправильный код картинки !");</script>
			<?
			exit();
		}

	} 
}
else
{
	$title = "Регистрация нового пользователя";
	function get_content()
	{
		include(_DIR._TEMPLATE."register.html");
	}
}
?>
