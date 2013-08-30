<?php
function get_fields($id)
{
	?>
	<iframe id="rframe" name="rframe" style="display:none;" ></iframe>
	<form method="post" target="rframe" enctype="multipart/form-data" action="/request.php?action=add&id=<?=$id?>">
    <table id="page_form" align="center" border="0">
	<?
	$sql = mysql_query("SELECT * FROM request WHERE parentid = '$id' ORDER BY showorder");
	while($r = mysql_fetch_assoc($sql))
	{
		?>
		<tr>
			<td  valign="top"><?=$r["title"]?></td>
			<td style="vertical-align:top;">
			<?php
			switch($r["fieldtype"])
			{
				case "1": //text
					?>
					<input class="ibox" type="text" name="f<?=$r["id"]?>" />
					<?
					break;
				case "2": //list
				?>
				<select class="ibox" name="f<?=$r["id"]?>">
				<option value="">.....</option>
				<?
					$arr = explode(",",$r["items"]);
					foreach($arr as $current)
					{
						?>
						<option value="<?=$current?>" ><?=$current?></option>
						<?
					}
					?></select><?
					break;	
				case "3": //number
				?>
				<input class="ibox" type="text" name="f<?=$r["id"]?>" />
				<?
					break;	
				case "4": //long text
				?>
				<textarea class="ibox" rows="5" name="f<?=$r["id"]?>"></textarea>
				<?
					break;
				case "5": //e-mail
				?>
				<input class="ibox" type="text" name="f<?=$r["id"]?>" />
				<?
					break;	
				case "6": //file
				?>
					<input class="ibox" type="file" name="f<?=$r["id"]?>" />
				<?
					break;
				case "7": //programm
				?>
					<input  type="hidden" id="f<?=$r["id"]?>" name="f<?=$r["id"]?>" />
				<?
					if(file_exists($r["modules"])) 
					{
						include($r["modules"]);
					}
					break;					
				case "8": //radio list
				?>
				<?
					$arr = explode(",",$r["items"]);
					foreach($arr as $current)
					{
						?>
						<input type="radio" name="f<?=$r["id"]?>" value="<?=$current?>" />&nbsp;-<?=$current?>&nbsp;&nbsp;
						<?
					}
					break;	
			}
			if($r["isrequired"] == "1")
			{
				?>
				<sup style="color:red;">*</sup>
				<?
			}
			if($r["description"] != "")
			{
				?>
				<div style="font-size:10px;text-align:justify;"><?=$r["description"]?></div>
				<?
			}
			?>
			</td>
		</tr>
		<?
	}
	?>
	<tr>
		<td>Введите текст на картинке:</td>
		<td><img src="/inc/captcha/securimage/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>" id="captcha" align="absmiddle" />
					<br /><br />
					<a onclick="document.getElementById('captcha').src = '<?=_SITE?>inc/captcha/securimage/securimage_show.php?' + Math.random(); return false" href="javascript:void(0);">Обновить картинку</a>
					<br /><br />
					<input type="text" name="code" class="ibox" />
					</td>
	</tr>
	<tr>
		<td></td>
		<td><input  type="submit"  class="buttons" value="<?=execute_scalar("SELECT buttontext FROM requestgroup WHERE id = '$id'")?>"  /></td>
	</tr>
		<tr>
		<td colspan="2"><sup style="color:red;">*</sup> - поля обязательные для заполнения
		<br /><br />
		</td>
	</tr>
	</table>
	</form>
	<?
}
if(!function_exists("get_content"))
{
	function get_content()
	{
		?>
		<h1><?=execute_scalar("SELECT name FROM requestgroup WHERE id = '$_GET[id]'")?></h1>
		<?
		get_fields($_GET["id"]);
	}
}
?>