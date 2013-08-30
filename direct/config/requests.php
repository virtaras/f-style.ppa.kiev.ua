<?php
$title = "Запросы";

$sql = db_query("SELECT id,title,showorder FROM request  WHERE parentid = '$_GET[parent]' ORDER BY showorder");
$farray = array();
$unsorted_fields = array();
while($r = db_fetch_assoc($sql))
{
	$title_fields["r".$r["id"]] =  str_replace(":","",$r["title"]);
	array_push($farray,"(SELECT val FROM requestitem WHERE requestitem.parentid = requests.id AND requestitem.fieldid = $r[id]) as  r".$r["id"]);
	array_push($unsorted_fields,"r".$r["id"]);
}

$title_fields["cdate"] = "Дата запроса";
$source = " SELECT requests.id,requests.create_date as cdate,".implode(",",$farray)." FROM requests 
WHERE requests.parentid = '$_GET[parent]' 
ORDER BY create_date DESC";


$controls["create_date"] = new Control("create_date","date","Дата");
$edit_content_bottom = "templates/requests.php";
$controls["parentid"] = new Control("parentid","list","Форма","SELECT * FROM requestgroup");
$controls["parentid"]->default_value = $_GET["parent"];
$controls["ip"] = new Control("ip","label","IP");

$sort_tablename = false;

?>