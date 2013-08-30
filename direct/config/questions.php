<?php

$title = "Опросы";
$title_fields = array("start_date"=>"Дата начала","finish_date"=>"Дата окончания","invisible"=>"Скрыть","name"=>"Вопрос");
$controls["start_date"] = new Control("start_date","date","Начало показов");
$controls["finish_date"] = new Control("finish_date","date","Окончание показов");
$controls["invisible"] = new Control("invisible","checkbox","Скрыть");
$controls["name"] = new Control("name","longtext","Вопрос");
$controls["name"]->rows = 5;
$edit_content_right = "templates/qitem.php";
?>