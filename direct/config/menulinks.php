<?php
$title = "Настрока меню";





$title_fields["title"] = "Заголовок";
$title_fields["link"] = "Ссылка";
$title_fields["invisible"] = "Скрыть";
$title_fields["showorder"] = "Порядок";
$title_fields["nofollow"] = "Nofollow";
$edit_title_fields["showorder"] = "Порядок";
$edit_title_fields["title"] = "Заголовок";
$edit_title_fields["link"] = "Ссылка";
$source = "SELECT * FROM  menulinks WHERE parentid  = '" . intval($_GET["parent"]) . "'";

$exclude_fields = array("id","parentid");

$controls["parentid"] = new Control("parentid","hidden","Владелец");
$controls["parentid"]->default_value = intval($_GET["parent"]);



$controls["invisible"] = new Control("invisible","checkbox","Скрыть");
$controls["nofollow"] = new Control("nofollow","checkbox","Nofollow");
$table_edit_mode = 1;
?>
