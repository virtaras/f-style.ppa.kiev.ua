<?
$title = "Архив опросов";
function get_content()
{


$sql = mysql_query("SELECT questions.*,DATE_FORMAT(start_date,'%d.%m.%Y') as sdate,DATE_FORMAT(finish_date,'%d.%m.%Y') as fdate FROM questions WHERE invisible = '0' ORDER BY finish_date DESC ");

while($r = mysql_fetch_assoc($sql))
{	
	?>
	<div style="font-size:14px;text-align:left;padding-right:5px;margin:10px 0px 0px 25px;font-weight:bold;"><?=$r["name"]?></div>
	<div style="margin:10px 0px 10px 25px;font-size:11px;">Период проведения опроса:&nbsp;<?=$r["sdate"]?>&nbsp;-&nbsp;<?=$r["fdate"]?></div>
	<?
	$all = execute_scalar("SELECT sum(click) FROM qitem WHERE parentid = '$r[id]'");
	$sql2 = mysql_query("SELECT * FROM qitem WHERE parentid = '$r[id]' ORDER BY click DESC");
	$i = 0;
	$color_array = explode(",",setting("color_array"));
	while($r2 = mysql_fetch_assoc($sql2))
	{
		if($all == 0)
		{
			$percent = 0;
		}
		else
		{
		$percent =  floor($r2["click"]/$all*100);
		}
		?>
		<table width="100%" border="0" style="margin:0px 0px 0px 25px;">
					<tr>
						<td class="question_item" width="200px"><?=$r2["title"]?>&nbsp;(<?=$r2["click"]?>)</td>
						<td>
							<table	width="100%" border="0" class="linet">
								<tr>
									<td class="linetd" height="5" style="background-color:<?=$color_array[$i]?>;<?=($percent == 0 ? "display:none;" : "")?>font-size:5px;" width="<?=$percent?>%">&nbsp;</td>
									<td class="question_item"><?=$percent?>%</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
		<?
		$i++;
	}
	?>
	<p style="font-size:12px;text-align:left;padding-left:25px;padding-right:5px; font-weight:bold; margin:0px 0px 20px 0px;">Всего проголосовало:&nbsp;<?=$all?></p>
	<?
	
}
}
?>


