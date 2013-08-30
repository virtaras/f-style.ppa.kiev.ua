<?
$title = "Комментарии";
$title_fields["create_date"] = "Дата";
$controls["create_date"] = new Control("create_date","date","Дата");
$title_fields["name"] = "Имя";
$title_fields["email"] = "E-Mail";
$edit_title_fields["name"] = "Имя";
$edit_title_fields["email"] = "E-Mail";
$title_fields["info"] = "Комментарий";
$title_fields["publish"] = "Публиковать";

$title_fields["experience"] = "Опыт";
$title_fields["rating"] = "Рейтинг";
$title_fields["dignity"] = "Достоинства";
$title_fields["limitations"] = "Недостатки";

$source = "SELECT id,create_date,name,email,experience,rating,dignity,limitations,info,publish FROM comments";
$controls["publish"] = new Control("publish","checkbox","Публиковать");
$controls["info"] = new Control("info","longtext","Комментарий");
$controls["info"]->rows = 7;
$controls["info"]->css_style = "width:300px;";
$controls["goodsid"] = new Control("goodsid","longlist","Товар");
$controls["goodsid"]->tablename = "goods";
?>