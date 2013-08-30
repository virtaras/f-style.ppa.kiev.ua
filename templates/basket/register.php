<?session_start();?>
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

<script language="JavaScript">
function show_message(text){
	/*window.parent.$("#registration_info").html(text);
	window.parent.$("#registration_info").dialog("open");*/
	alert(text);
}
</script>
<?
require $_SERVER["DOCUMENT_ROOT"]."//inc/constant.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/connection.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/global.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/engine.php";
require $_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php";
if(isset($_POST["action"])){
	switch($_POST["action"]){
		case "register":
			if(!checkLogged()){
				foreach($_POST as $key=>$value){
					$value=prep($value);
				}
				$values=array();
				$fsql = mysql_query("SELECT * FROM order_fields ORDER BY showorder");
				while($f = mysql_fetch_assoc($fsql)){
					$values[]="".$f["code"]."=".$_POST[$f["code"]];
				}
				$email=trim($_POST["email"]);
				if(!check_email($email)){?>
					<script language="JavaScript">show_message('Вы ввели некорректный E-mail адрес !'+'<?=$email?>');</script>
					<?exit();
				}
				$passw=random_password();
				$exists = execute_row_assoc("SELECT * FROM clients WHERE email = '$email'");
				if(isset($exists["id"]) && $exists["id"]!=$_SESSION["login_user"]["id"]){?>
					<script language="JavaScript">show_message('Email <?=$email?> уже есть в базе данных!');</script>
					<?exit();
				}
				else{
					mysql_query("INSERT INTO clients(create_date,email,passw,last_login,last_ip,confirmed) VALUE (now(),'$email','".md5(trim($passw))."',now(),'".$_SERVER["REMOTE_ADDR"]."',0)");
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
					if(execute_scalar("SELECT count(*) FROM emailsdb WHERE email = '".$_POST["email"]."'") == 0){
						mysql_query("INSERT INTO emailsdb (email) VALUES ('".$_POST["email"]."')");
					}
				}
			}
			?><script type="text/javascript">afterRegistered();</script><?
		break;
	}
}?>