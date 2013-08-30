<?
$title = "Дополнительные поля";
$source = "SELECT id,fieldid,showorder,inlist,hide_default FROM categoryfields  
WHERE categoryid = '$_GET[parent]' ";

$controls["fieldid"] = new Control("fieldid","list","Поле","SELECT id,name FROM `fields` WHERE isgeneral != 1 ORDER BY name");
$controls["inlist"] = new Control("inlist","checkbox","Выводить в списке");
$controls["hide_default"] = new Control("hide_default","checkbox","По умолчанию скрыть");
$title_fields = array("fieldid"=>"Поле","showorder"=>"Порядок вывода","inlist"=>"В списке","hide_default"=>"Скрывать");
$edit_title_fields["showorder"] = "Порядок вывода";
$edit_title_fields["fieldid"] = "Поле";
$controls["categoryid"] = new Control("categoryid","hidden");
$controls["categoryid"]->default_value = $_GET["parent"]; 
$exclude_fields_edit = array("id","categoryid");
$table_edit_mode = 1;
//$unique_fields = array("fieldid");
?>