<?
if(isset($_GET["send"]))
{
	session_start();
	ini_set("display_errors","On");
	header('Content-Type: text/html; charset=utf-8');
	require("../config/global.php");
	require("../lib/user.php");
	require("../function/connection.php");
	require("../function/db.php");
	require("../function/global.php");
	require("../../inc/emarket.php");
	
	$from_name = $_SERVER['HTTP_HOST'];
	$from_email = $_POST["email"];
	if(stripos($from_email,"@") === false)
	{
		$from_name = $_POST["email"];
		$from_email = " no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST'])."";
	}
	foreach($_POST["emails"] as $email)
	{
		send_mime_mail($from_name, $from_email, "",$email, "UTF-8", "UTF-8", $_POST["subject"], stripslashes($_POST["message"]) );
		
	}
	?>
	<script language="JavaScript">
	alert("Сообщения успешно отправлены");
	</script>
	<?
}
else
{
?>
<iframe name="send_email" style="display:none;" ></iframe>
<form method="post" action="templates/emails.php?send" target="send_email">
<script language="JavaScript">
function check_all(obj)
{
	var images = document.getElementsByName("emails[]");
	for(var i = 0;i < images.length;i++)
	{
		images[i].checked = obj.checked;
	}
}
</script>
<table class="tool_bar" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="tb">
		</td>
		<td class="tbr">Рассылка по E-Mail</td>
		</tr>
		</table>
		
<table style="margin-top:15px;">
	<tr>
		<td style="vertical-align:top;" width="300">
		<div><input type="checkbox"  class="cb" onclick="check_all(this)"  />&nbsp;<strong>Отметить все</strong></div>
		<div style="height:400px;overflow:auto;">
		<?
		$sql = db_query("SELECT email FROM emailsdb ORDER BY email  ");
		while($r = db_fetch_assoc($sql))
		{
			?>
			<div><input type="checkbox" name="emails[]" class="cb" value="<?=$r["email"]?>" />&nbsp;<?=$r["email"]?></div>
			<?
		}
		?>
		</div>
		</td>
		<td style="vertical-align:top;">
			<table>
				<tr>
					<td class="title">Отправитель:</td>
					<td ><input type="text" class="ibox" name="email" value="<?=execute_scalar("SELECT val FROM settings WHERE name = 'emails_address'")?>" style="width:500px;" /></td>
				</tr>
				<tr>
					<td class="title">Тема сообщения:</td>
					<td ><input type="text" class="ibox" name="subject" style="width:500px;" /></td>
				</tr>
				<tr>
					<td class="title">Сообщение:</td>
					<td >
					<textarea id="message" name="message"></textarea>
					<script language="JavaScript">
					CKEDITOR.replace( 'message');
					</script>
					</td>
				</tr>
				<tr>
					<td class="title"></td>
					<td ><input type="submit" class="buttons" value="Отправить сообщения" onclick="javascript:return confirm('Отправить сообщение выбранным адресатам ?');" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>	
</form>	
<? } ?>