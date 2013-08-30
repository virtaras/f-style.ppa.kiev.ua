<?
$title = "Отзывы";
$title_fields["create_date"] = "Дата";
$title_fields["name"] = "Автор";
$title_fields["email"] = "E-Mail";
$title_fields["review"] = "Отзыв";
$title_fields["publish"] = "Публиковать";
$title_fields["answer"] = "Ответ";

$edit_title_fields["name"] = "Автор";
$edit_title_fields["email"] = "E-Mail";

$controls["publish"] = new Control("publish","checkbox","Публиковать");
$controls["create_date"] = new Control("create_date","date","Дата");
$controls["review"] = new Control("review","longtext","Отзыв");
$controls["review"]->rows = 9;
$controls["review"]->css_style = "width:300px;";

$controls["answer"] = new Control("answer","longtext","Ответ");
$controls["answer"]->rows = 9;
$controls["answer"]->css_style = "width:300px;";
?>