<script language="JavaScript">
function load_menu()
{
	$("#allitem").load('udf/ajax.php',{action:'showmenu',menuid:<?=$_GET["id"]?>})
	$('#newitem').empty()
}
function save_menuitem(itemid)
{
	$.post('udf/ajax.php',{action:'save_menu',id:itemid,parentid:document.getElementById('parentid'+itemid).value,title:document.getElementById('title'+itemid).value,link:document.getElementById('link'+itemid).value,sql:document.getElementById('sql'+itemid).value,menuid:<?=$_GET["id"]?>,linktemplate:document.getElementById('linktemplate'+itemid).value},load_menu);
}
function rm_menuitem(itemid)
{
	if(confirm('Удалить пункт меню и все подпункты ?'))
	{
		$.post('udf/ajax.php',{action : 'rm_menu',id:itemid},load_menu)
	}
}
function up_menu(itemid,pos)
{
	$.post('udf/ajax.php',{action : 'up_menu',up:itemid,position:pos},load_menu);
}
</script>
<table width="500px" class="tool_bar" border="0" cellpadding="0" cellspacing="0">
<tr><td class="tb"></td>
<td class="tbr"><a class="tbt">Настройка меню</a></td>
</tr>
</table>
<p>
<div style="width:500px"><a onclick="$('#newitem').load('udf/ajax.php',{action:'menuitem',id:-1,parentid:-1,divid:'newitem',menuid:<?=$_GET["id"]?>})" href="#">Добавить новый пункт</a></div>
<div id="newitem"></div>
<div id="allitem" style="margin-top:10px;width:500px;"></div>
<script language="JavaScript">
load_menu();
</script>
</p>