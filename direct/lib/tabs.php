<?php 
class Tabs
{
	var $items = array();
	function Tabs($items)
	{
		$this->items = $items;
	}
	function AddTab($tab)
	{
		array_push($tab,$this->items);
	}
	function create()
	{
		?>
		<script language="JavaScript" type="text/javascript">
		var tabs = Array(<?php $i = 0; foreach($this->items as $current) {  echo "'tab_$i',"; $i++; } ?>'');
		var fields = new Array();
		<?php
		$i = 0;
		foreach($this->items as $current) 
		{
			?>
			fields[<?=$i?>] = new Array(<?=(implode(",",$current->fields))?>);
			<?
			$i++;
		}
		?>
		</script>
		<div>&nbsp;</div>
		<table class="tabs" width="100%;" cellspacing="0" >
		<tr>
			<td style="width:15px;">&nbsp;</td>
			<?php $i = 0; foreach($this->items as $current) {  ?>
			<td class="tabOff" id="tab_<?=$i?>" onclick="SetCurrentTab(this,<?=$i?>);<?=$current->onclick?>" ><?=$current->caption?></td>
			<?php $i++; } ?>
			<td></td>
		</tr>
		</table>
		
		<?
	}

}
class Tab
{
	var $caption = ""; // tab's caption
	var $onclick = ""; // JavaScript function onclik
	var $fields = array();// array of controls aasigned with this tab
	function Tab($caption,$onclick,$fields = array())
	{
		$this->caption = $caption;
		$this->onclick = $onclick;
		foreach($fields as $current)
		{
			array_push($this->fields,"'tr_$current'");
		}
	}
}

?>