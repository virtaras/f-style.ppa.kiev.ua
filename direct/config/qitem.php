<?php

$title = "Варианты ответов";
$source = "SELECT * FROM qitem WHERE parentid = '$_GET[parent]'";
$controls["parentid"] = new Control("parentid","hidden","");
$controls["parentid"]->default_value = $_GET["parent"];
$title_fields = array("title"=>"Вопрос","click"=>"Голосов");
$edit_title_fields = array("click"=>"Голосов");
$exclude_fields = array("parentid","id");
$exclude_fields_edit = array("parentid","id");
$controls["title"] = new Control("title","longtext","Ответ");
$controls["title"]->rows = 5;
?>