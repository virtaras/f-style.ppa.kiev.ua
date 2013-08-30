<?
$title = "Объявления";
$title_fields["create_date"] = "Дата";
$title_fields["title"] = "Заголовок";
$edit_title_fields["title"] = "Заголовок";
$title_fields["info"] = "Текст";
$controls["create_date"] = new Control("create_date","date","Дата");
$controls["info"] = new Control("info","longtext","Текст");
$controls["info"]->rows = 10;
$controls["info"]->css_style = "width:400px;";
$controls["publish"] = new Control("publish","checkbox","Публиковать");
$title_fields["publish"] = "Публиковать";
?>