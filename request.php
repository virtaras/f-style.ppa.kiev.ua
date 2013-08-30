<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

require("inc/constant.php");
require("inc/connection.php");
require("inc/global.php");
require("inc/emarket.php");


	$sql = mysql_query("SELECT * FROM request WHERE parentid = '$_GET[id]' ORDER BY showorder");
	while($r = mysql_fetch_assoc($sql))
	{
		if($r["fieldtype"] == "6")
		{
		
		}
		else
		{
			if(empty($_POST["f".$r["id"]]) && $r["isrequired"] == "1")
			{
				
				?>
				<script>
				alert("Поле '<?=$r["title"]?>' обязательно для заполнения !");
				</script>
				<?
				exit();
			}
		}	
		if($r["fieldtype"] == "3")
		{
			$_POST["f".$r["id"]] = str_replace(",",".",$_POST["f".$r["id"]]);
			if(!is_numeric($_POST["f".$r["id"]]))
			{
				?>
				<script>
				alert("Введите в поле '<?=$r["title"]?>' число !");
				</script>
				<?
				exit();
			}
		}
		if($r["fieldtype"] == "5")
		{
			if(!empty($_POST["f".$r["id"]]) && !check_email($_POST["f".$r["id"]]))
			{
				?>
				<script>
				alert("Введите в поле '<?=$r["title"]?>' корректный E-Mail !");
				</script>
				<?
				exit();
			}
		}
	}
	
	include_once ('inc/captcha/securimage/securimage.php');
	$securimage = new Securimage();
	$valid = $securimage->check($_POST['code']);
	if(!$valid)
	{
		?>
		<script language="JavaScript">
		alert("Вы ввели неправильный код !");
		</script>
		<?
		exit();
	}
	
	
	mysql_query("INSERT INTO requests (create_date,parentid,ip) VALUES (now(),'$_GET[id]','$_SERVER[REMOTE_ADDR]')");
	$id = get_last_id("requests");
	$sql = mysql_query("SELECT * FROM request WHERE parentid = '$_GET[id]' ORDER BY showorder");
	$html = "<h1>".execute_scalar("SELECT name FROM requestgroup WHERE id = '$_GET[id]'")."</h1>
	<table>
		
	";
	while($r = mysql_fetch_assoc($sql))
	{
		
		if($r["fieldtype"] == "6" )
		{
			$key = "f".$r["id"];
			$_POST["f".$r["id"]] = $_FILES[$key]['name'];
			
		}
		mysql_query("INSERT INTO requestitem (parentid,fieldid ,val) VALUES ($id,$r[id],'".$_POST["f".$r["id"]]."')");
		$html .= "
		<tr>
			<td>$r[title]: </td>
			<td>".$_POST["f".$r["id"]]."</td>
		</tr>
		";
		if($r["fieldtype"] == "6")
		{
			$id = get_last_id("requestitem");
			if(!file_exists(_DIR."formfiles/"))
			{
				mkdir(_DIR."formfiles/");
			}
			$uploaddir = _DIR."formfiles/$id/";
			$key = "f".$r["id"];
			if(!file_exists($uploaddir))
			{
				mkdir($uploaddir);
				
			}
			$uploadfile = $uploaddir.basename($_FILES[$key]['name']);
			move_uploaded_file($_FILES[$key]["tmp_name"],$uploadfile);
			
		}
		
	}
	$html .= "</table>";
	$mail = execute_scalar("SELECT email FROM requestgroup WHERE id = '$_GET[id]'");

if($mail == "")
{
	$mail = execute_scalar("SELECT val FROM settings WHERE name = 'contact_email'");
}

$mail = explode(",",$mail);
foreach($mail as $current)
{
	send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$current, "UTF-8", "UTF-8", "Добавлено :".execute_scalar("SELECT name FROM requestgroup WHERE id = '$_GET[id]'"), $html );															 
}															 
	?>
	<script language="JavaScript">
		alert("Данные успешно отправлены");
		window.parent.document.location = window.parent.document.location;
	</script>
	<?
?>