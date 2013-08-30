<script language="JavaScript">
	function show_goods_list(parent,text)
	{
		document.getElementById("goods_list").innerHTML = "<img alt='Загрузка ...' src='images/loader.gif' />";
		$("#goods_list").load('udf/ajax.php',{action:'show_goods_list',parentid:parent,findtext:text});
	}
	function load_goods_satellites()
	{
		document.getElementById("goods_satellite").innerHTML = "<img alt='Загрузка ...' src='images/loader.gif' />";
		$("#goods_satellite").load('udf/ajax.php',{action:'show_goods_satellites',parent:<?=$_GET["id"]?>});
	}
	function add_item()
	{
		if($('#goods_from_list').val() != "")
		{
		document.getElementById("goods_satellite").innerHTML = "<img alt='Загрузка ...' src='images/loader.gif' />";
		$("#goods_satellite").load('udf/ajax.php',{action:'add_satellites',parent:<?=$_GET["id"]?>,id:$('#goods_from_list').val()},load_goods_satellites);
		}
	}
	function rm_item()
	{
		if($('#goods_to_list').val() != "")
		{
		$("#goods_satellite").load('udf/ajax.php',{action:'rm_satellites',id:$('#goods_to_list').val()},load_goods_satellites);
		}
	}
</script>
<table>
	<tr>
		<td class="title">Сопуствующие товары</td>
		<td></td>
		<td  class="title">Все товары</td>
	</tr>
	<tr>
		<td width="220px" style="vertical-align:top;">
			<div id="goods_satellite">
				<img alt="Загрузка" src="images/loader.gif" />
			</div>
		</td>
		<td style="vertical-align:middle;">
			<input type="button" onclick="add_item()" class="buttons" value="<<<" />
			<br />
			<br />
			<input type="button" onclick="rm_item()" class="buttons" value=">>>" />

		</td>
		<td width="220px"  style="vertical-align:top;">
		<?
		$parentcatalog = new Control("parentcatalog");
		$parentcatalog->caption = "Владелец";
		$parentcatalog->type = "function";
		$parentcatalog->source = 'get_catalog_list';
		$parentcatalog->argument = array("0","100");
		$parentcatalog->required = true;
		$parentcatalog->js="onchange=\"show_goods_list(this.options[this.selectedIndex].value,$('#ftext').val());\" ";
		$parentcatalog->create();
		?>
		<br />	<br />
		<input id="ftext" type="text" onkeyup="show_goods_list(get_select_value('parentcatalog'),this.value)" class="input_box" />	
		<br />	<br />
		<div id="goods_list"></div>	
		</td>
	</tr>
</table>
<script language="JavaScript">
load_goods_satellites();
</script>