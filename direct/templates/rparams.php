<?
$rsql = db_query("SELECT * FROM ".$this->value);
while($r = db_fetch_assoc($rsql))
{
	$checked = "";
	if(execute_scalar("SELECT count(*) FROM s".$this->value." WHERE goodsid = '$_GET[id]' AND valueid = '$r[id]'") == 1)
	{
		$checked = " CHECKED";
	}
	?>
	<div><input <?=$checked?> type="checkbox" name="<?=$this->value?>[]" class="cb" value="<?=$r["id"]?>" />&nbsp;<?=$r["name"]?></div>
	<?
}

?>