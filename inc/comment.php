<?php
if(isset($_GET["send"]))
{
    session_start();
    header('Content-Type: text/html; charset=utf-8');
	foreach(array_keys($_GET) as $key)
{
	$_GET[$key] = str_ireplace(array("drop","delete","update","insert","select"),"",$_GET[$key]);
}
foreach(array_keys($_POST) as $key)
{
	$_POST[$key] = str_ireplace(array("drop","delete","update","insert","select"),"",$_POST[$key]);
}
    require("constant.php");
    require("connection.php");
    require("global.php");
	
	$arr = array("name","info","rating");
	foreach($arr as $current)
	{
		if(trim($_POST[$current]) == "")
		{
			?>
			<script language="JavaScript">
	alert("Необходимо заполнить все обязательные поля !");
	</script>
			<?
			exit();
		}
	}
	mysql_query("INSERT INTO comments (goodsid,create_date,name,email,info,experience,rating,dignity, limitations) VALUES (
	$_GET[send],now(),'".htmlspecialchars(mysql_escape_string($_POST["name"]))."','".htmlspecialchars($_POST["comment_email"])."','".htmlspecialchars(mysql_escape_string($_POST["info"]))."','".htmlspecialchars(mysql_escape_string($_POST["experience"]))."','".intval($_POST["rating"])."','".htmlspecialchars(mysql_escape_string($_POST["dignity"]))."','".htmlspecialchars(mysql_escape_string($_POST["limitations"]))."')");
	?>
	<script language="JavaScript">
	window.parent.document.getElementById("comment_name").value = "";
	window.parent.document.getElementById("comment_email").value = "";
	window.parent.document.getElementById("info").value = "";
	alert("Ваш комментарий добавлен в базу данных. Через некоторое время он будет доступен на сайте.");
	window.parent.document.location = window.parent.document.location;
	</script>
	<?
}
else
{
    ?>
	<iframe style="display:none;" name="_comment"></iframe>
	<form method="post"  action="<?=_SITE?>inc/comment.php?send=<?=$tovar["id"]?>" target="_comment">
		<table cellpadding="3" cellspacing="0" border="0" class="comment">
		<tr>
			<td class="comment_name">Имя:</td>
			<td><input type="text" name="name" id="comment_name" maxlength="200" />&nbsp;<sup style="color:red;">*</sup></td>
		</tr>
		<tr>
			<td class="comment_name">E-mail:</td>
			<td><input type="text" name="comment_email" id="comment_email" maxlength="100" /></td>
		</tr>
		<tr>
			<td class="comment_name">Оценка:</td>
			<td>
					<input style="width:20px;" type="radio" name="rating" value="1" />&nbsp;1&nbsp;
					<input style="width:20px;" type="radio" name="rating" value="2" />&nbsp;2&nbsp;
					<input style="width:20px;" type="radio" name="rating" value="3" />&nbsp;3&nbsp;
					<input style="width:20px;" type="radio" name="rating" value="4" />&nbsp;4&nbsp;
					<input style="width:20px;" type="radio" name="rating" value="5" />&nbsp;5&nbsp;
					&nbsp;<sup style="color:red;">*</sup>
			</td>
		</tr>
		<tr>
			<td class="comment_name">Опыт использования:</td>
			<td><input type="text" name="experience" id="experience"  /></td>
		</tr>
		<tr>
			<td class="comment_name">Достоинства:</td>
			<td><textarea name="dignity" id="dignity" rows="5" ></textarea>&nbsp;</td>
		</tr>
		<tr>
			<td class="comment_name">Недостатки:</td>
			<td><textarea name="limitations" id="limitations" rows="5" ></textarea>&nbsp;</td>
		</tr>
		<tr>
			<td class="comment_name">Комментарий:</td>
			<td><textarea name="info" id="info" rows="5" ></textarea>&nbsp;<sup style="color:red;">*</sup></td>
		</tr>
		<tr>
			<td class="comment_name">&nbsp;</td>
			<td><input class="buttons_comment" type="submit" value="Оставить отзыв" /></td>
		</tr>
		</table>
	</form>
    <?
}
?>
