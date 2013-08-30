<?
function get_options($parentid,$level,$selected)
{
	$sql = mysql_query("SELECT id,name FROM content WHERE parentid = '$parentid' ".(isset($_GET["id"]) ? " AND id != '$_GET[id]'" : "")." ORDER BY showorder");
	$blank = "";
	for($i = 0;$i < $level;$i++)
	{
		$blank .= "&nbsp;&nbsp;";
	}
	$level++;
	while($r = mysql_fetch_assoc($sql))
	{
		
		?>
		<option <?=($r["id"] == $selected ? "SELECTED" : "")?> value="<?=$r["id"]?>"><?=$blank.$r["name"]?></option>
		<?
		get_options($r["id"],$level,$selected);
	}
	
}
if($_GET["t"] == "content")
{
?>
<select name="parentid" id="parentid" class="select_box">
<? } else { ?>
<select name="contentid" id="contentid" class="select_box">
<? } ?>
}
<option value="0">...</option>
<?=get_options(0,0,$this->value)?>
</select>