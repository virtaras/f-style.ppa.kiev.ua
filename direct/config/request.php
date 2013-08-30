<?php
$title = "Настройка запросов";
$source = "SELECT id,title,showorder FROM request  WHERE parentid = '$_GET[parent]' ORDER BY showorder";
$controls["parentid"] = new Control("parentid","list","Форма","SELECT * FROM requestgroup");
$controls["parentid"]->default_value = $_GET["parent"];
$title_fields["title"] = "Заголовок";
$title_fields["showorder"] = "Порядок вывода";
$edit_title_fields["title"] = "Заголовок";
$edit_title_fields["modules"] = "Модуль";
$controls["isrequired"] = new Control("isrequired","checkbox","Обязательное поле");
$controls["items"] = new Control("items","longtext","Позиции");
$controls["items"]->rows = "10";
$controls["fieldtype"] = new Control("fieldtype","list","Тип поля","SELECT * FROM requestfieldtype");
$controls["description"] = new Control("description","longtext","Описание");
$edit_title_fields["showorder"] = "Порядок вывода";
$table_edit_mode = 1;
?>