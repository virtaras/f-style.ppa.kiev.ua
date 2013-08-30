<?
/*
$catalog_array = array();
function get_tree($parentid,$level,&$catalog_array,$current)
{
	$top_level = mysql_query("SELECT id,name,parentid FROM catalog WHERE parentid = '$parentid' ORDER BY showorder ");
	while($row = mysql_fetch_assoc($top_level))
	{
		$selected = "";
		$blank ="";
		if($current == $row["id"])
		{
			$selected = "SELECTED";
			
		}
		for($i = 0;$i < 100 - $level;$i++)
		{
			$blank .= "&nbsp;&nbsp;";
		}
		
		if((!empty($_GET["itemid"]) && $_GET["itemid"] != $row["id"]) || (empty($_GET["itemid"])))
		{
			$catalog_array[$row["id"]] = $blank.$row["name"];
		}
		?>
		<option <?=$selected ?> value="<?=$row["id"]?>"><?=$blank.$row["name"]?></option>
		<?
		get_tree($row["id"],$level-1,&$catalog_array,$current);
	}
}
?>
<select name="parentid" id="parentid">
<option value="0">...</option>
<? if($_GET["id"] == "-1")
{
	get_tree(0,100,&$catalog_array,isset($_GET["parent"]) ?  $_GET["parent"]: 0);
}
else
{	
	get_tree(0,100,&$catalog_array,$this->value);
}	
	?>
</select>
*/ 

if(isset($_GET["id"]) && $_GET["id"] == "-1")
{
	$this->value = $this->default_value;
}
$parentname = execute_scalar("SELECT name FROM catalog WHERE id = '".$this->value."'");
if($parentname == "")
{
	$parentname = "Выбрать";
}
?>
<input type="hidden" name="parentid" id="parentid" value="<?=$this->value?>" />

<a href="javascript:void(0);" onclick="$('#select_tree').dialog('open');" id="parentname"><?=$parentname?></a>
<div id="select_tree" title="Выбрать владельца" style="display:none;"></div>
<script language="JavaScript">
function set_parent(id,name)
{
	$("#parentid").val(id);
	$("#parentname").html(name);
	$('#select_tree').dialog('close');
}
$(function () {
	$("#select_tree").jstree({ 
		"json_data" : {
			"ajax" : {
				"url" : "udf/tree.php",
				"data" : function (n) { 
						// the result is fed to the AJAX request `data` option
						return { 
							"id" : n.attr ? n.attr("id").replace("node_","") : 0
						}; 
				}
			}
		},
		"plugins" : [ "themes", "json_data" ]
	});
});
</script>