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
		$_POST[$key] = str_ireplace("'","''",$_POST[$key]);
		//$_POST[$key] = prep($_POST[$key]);
	}
	
	?>
	<script language="JavaScript">
		function show_message(text)
		{
			window.parent.$("#registration_info").html(text);
			window.parent.$("#registration_info").dialog("open");
		}
	</script>
	<?
	$_POST["email"] = trim($_POST["email"]);
	$email = execute_scalar("SELECT count(*) FROM clients WHERE email = '$_POST[email]'");
	
	if($email > 0)
	{
		?>
		<script language="JavaScript">show_message('Email <?=$_POST["email"]?> уже есть в базе данных !');</script>
		<?
		exit();
	}
	else
	{
		$array = array("email","passw","repassw");
		$title_array = array("email"=>"E-Mail","passw"=>"Пароль","repassw"=>"Повторите пароль");
		$varray = array();	
		$fsql = mysql_query("SELECT * FROM clients_fields WHERE in_register = 1 ORDER BY showorder");
		while($f = mysql_fetch_assoc($fsql))
		{
			$varray[] = $f["code"];
			$title_array[$f["code"]] = $f["title"];
			if($f["isrequired"] == "1")
			{
				$array[] = $f["code"];
			}
		}
		$err_count = 0;
		$str_error = "<br />";
		foreach($array as $current)
		{
			if(empty($_POST[$current]))
			{
				$err_count++;
				$str_error .= $title_array[$current]."<br />";
				?>
					<script language="JavaScript"> window.parent.$("#<?=$current?>").css('border-color',"red"); </script>
				<?
				
				
			}
		}
		if($err_count > 0)
		{
			?>
				<script language="JavaScript">show_message('<strong>Необходимо заполнить все обязательные поля :</strong> <?=$str_error?>');window.parent.$("#email").css('border-color',"red");</script>
				<?
				exit();
		}
		if(!check_email($_POST["email"]))
		{
			?>
				<script language="JavaScript">show_message('Вы ввели некорректный E-mail адрес !');</script>
				<?
				exit();
		}
		if($_POST["passw"] != $_POST["repassw"])
		{
			?>
				<script language="JavaScript">show_message('Поля Пароль и Повторите пароль не совпадают !');</script>
				<?
			exit();
		}
		if(strlen($_POST["passw"]) < 5)
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
			foreach($varray as $current)
			{
				array_push($values,"'".htmlspecialchars(stripslashes(strip_tags($_POST[$current])),ENT_QUOTES)."'");
			}
			mysql_query("INSERT INTO clients(create_date,email,passw,".implode(",",$varray).",last_login,last_ip) VALUE (now(),'".$_POST["email"]."','".md5(trim($_POST["passw"]))."',".implode(",",$values).",now(),'".$_SERVER["REMOTE_ADDR"]."')");
			
			$id = get_last_id("clients");
			
			$_SESSION["login_user"] = execute_row_assoc("SELECT * FROM clients WHERE id = $id");
			
			//Send mail
			
			$body = html2("registr_mail");
			$body = str_replace("@email",$_POST["email"],$body);
			$body = str_replace("@passw",trim($_POST["passw"]),$body);
			send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$_POST["email"],"UTF-8", "UTF-8", "Уведомление о регистрации на сайте ".$_SERVER['HTTP_HOST'], $body );
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
