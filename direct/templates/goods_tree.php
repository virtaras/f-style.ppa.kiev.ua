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
*/
if(isset($_GET["parent"]))
{
	$parentname = execute_scalar("SELECT name FROM catalog WHERE id = ".$_GET["parent"]);
	if($parentname == "")
	{
		$parentname = "Выбрать";
	}
}
else
{
	$parentname = "Выбрать";
}	
?>
<p class="title">
Перейти к группе:&nbsp;<a href="javascript:void(0);" onclick="$('#select_tree').dialog('open');" ><?=$parentname?></a>
</p>
<div id="select_tree" title="Выбрать группу товаров" style="display:none;"></div>
<script language="JavaScript">
function set_parent(id,name)
{
	$('#select_tree').dialog('close');
	document.location.href = 'http://<?=get_url(2,$this->config,array("parent",isset($_GET["sql"]) ? "sql" : ""))?>&parent='+id<?=(isset($_GET["sql"])  ? "+'&sql=".urldecode("SELECT id,name FROM goods WHERE parentid = ")."' + id" : "")?>;
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