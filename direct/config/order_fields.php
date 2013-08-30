<?
$title = "Поля заказа";
$exclude_fields = array("id","fieldtype");
$title_fields["code"] = "Идентификатор";
$title_fields["title"] = "Заголовок";
$title_fields["isrequired"] = "Обязательное поле";
$title_fields["items"] = "Список";
$edit_title_fields["title"] = "Заголовок";
$edit_title_fields["code"] = "Идентификатор";
$controls["code"] = new Control("code","label","Идентификатор");
$controls["isrequired"] = new Control("isrequired","checkbox","Обязательное поле");
$edit_title_fields["showorder"] = "Порядок вывода";
$title_fields["showorder"] = "Порядок вывода";
$controls["items"] = new Control("items","longtext","Позиции");
$controls["items"]->rows = "10";
$controls["fieldtype"] = new Control("fieldtype","list","Тип поля","SELECT * FROM requestfieldtype WHERE id IN(1,2,4)");
$controls["fieldtype"]->required = true;
$required_fields = array("title");
?>