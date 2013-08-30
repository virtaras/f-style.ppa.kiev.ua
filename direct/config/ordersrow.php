<?php 
$title = "Товары";
$title_fields["odate"] = "Дата";
$title_fields["goods"] = "Детально";

if(isset($_GET["id"]) && $_GET["id"] == "-1")
{
	$controls["parentid"] = new Control("parentid","hidden");
	$controls["parentid"]->default_value = $_GET["parent"];
}
if(isset($_GET["parent"]))
{
	$source = "SELECT id,0 as goods,discount,deliverysumm FROM orders WHERE id = '$_GET[parent]' ORDER BY create_date DESC";
}
$exclude_fields = array("discount","id","deliverysumm");
$template_fields = array("goods"=>"templates/details.php");
$edit_buttons = array(false,true);
//$top_links = array(false,false,false);
$edit_title_fields["quantity"] = "Кол-во";
$edit_title_fields["price"] = "Цена";
$select_fields = "id,goodsid,vname,quantity,price,parentid";
$controls["goodsid"] = new Control("goodsid","longlist","Товар","","id","name");
$controls["goodsid"]->tablename = "goods";
$controls["goodsid"]->showselect = false;
$controls["vname"] = new Control("vname","label","Вариант");
$exclude_fields_edit = array("id","parentid");
?>