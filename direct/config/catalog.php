<?
$title = "Группы товаров";
$source = "SELECT id,showorder as eshoworder,name,title,keywords,description,showorder,invisible,in_index,is_export FROM catalog ";
$where = " WHERE parentid = 0 ";
if(isset($_GET["parent"]))
{
	$where = " WHERE parentid = '$_GET[parent]'";
}
$source = $source.$where;
$table_content_top_1 = "templates/epath.php";
$title_fields["title"] = "TITLE";
$title_fields["keywords"] = "KEYWORDS";
$title_fields["description"] = "DESCRIPTION";
$title_fields["invisible"] = "Скрыть";
$title_fields["eshoworder"] = "&nbsp;";
$title_fields["showorder"] = "Позиция";
$title_fields["add_child"] = "&nbsp;";
$title_fields["is_export"] = "Экспорт";
$title_fields["in_index"] = "На главную";

$template_fields["eshoworder"] = "templates/eposition.php";
$select_fields = "id,parentid,name,urlname,one_name,in_list,title,description,keywords,contentid,invisible,head,brand,id as categoryfields,id as params,discount,extid,in_index,is_export,id as content";
$table_edit_mode = "1";
$unsorted_fields = array("add_child","subcatalog","goods","eshoworder");
$controls["title"] = new Control("title","longtext","TITLE");
$controls["keywords"] = new Control("keywords","longtext","KEYWORDS");
$controls["description"] = new Control("description","longtext","DESCRIPTION");
$controls["head"] = new Control("head","longtext","HEAD");
$controls["head"]->css_style = "width:300px;";
$controls["head"]->rows = 7;
if(!isset($_GET["id"]))
{
	$controls["title"]->rows = 1;
	$controls["title"]->js = "onfocus = 'this.rows = 7;' ";
	$controls["keywords"]->rows = 1;
	$controls["keywords"]->js = "onfocus = 'this.rows = 7;'";
	$controls["description"]->rows = 1;
	$controls["description"]->js = "onfocus = 'this.rows = 7;'";
}
else
{
	$controls["title"]->rows = 5;
	$controls["keywords"]->rows = 7;
	$controls["title"]->css_style = "width:300px;";
	$controls["keywords"]->css_style = "width:300px;";
	$controls["description"]->rows = 7;
	$controls["description"]->css_style = "width:300px;";
}
$controls["invisible"] = new Control("invisible","checkbox","Скрыть");
$controls["parentid"] = new Control("parentid","template","Владелец");
$controls["parentid"]->template = "templates/select_tree.php";
$controls["parentid"]->template_mode = "standart";


$controls["content"] = new Control("content","template");
$controls["content"]->template = "templates/catalog_content.php";

if(isset($_GET["parent"]))
{
	$controls["parentid"]->default_value = $_GET["parent"];
}
if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
	$controls["categoryfields"] = new Control("categoryfields","template");
	$controls["categoryfields"]->template = "templates/categoryfields.php";

}
$controls["is_export"] = new Control("is_export","checkbox","Экспорт");
$controls["in_index"] = new Control("in_index","checkbox","На главную");
$pagesize = 100;
$edit_title_fields["one_name"] = "Единственное число";
$edit_title_fields["in_list"] = "Наименование в списке";
$edit_title_fields["extid"] = "Внешний код";
$edit_title_fields["urlname"] = "URL";
$edit_title_fields["discount"] = "Скидка";

$controls["brand"] = new Control("brand","list","Производитель","SELECT id,name FROM brands");

$tab1 = new Tab("Основные данные","",array("parentid","name","extid","one_name","in_list","contentid","invisible","urlname","discount","is_export","in_index","brand"));
$tab2 = new Tab("SEO","",array("name","title","description","keywords","head"));
$tab3 = new Tab("Контент","load_content(this)",array("name","content"));
$tabs = array($tab1,$tab2,$tab3);
if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
	$tab4 = new Tab("Дополнительные поля","",array("name","categoryfields"));
	array_push($tabs,$tab4);
}
global $sourceid;
$sourceid = 2;
if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
	$edit_content_bottom = "templates/img.php";
}
$controls["contentid"] = new control("contentid","template","Каталог статей");
$controls["contentid"]->template_mode = "standart";
$controls["contentid"]->template = "templates/content_tree.php";

?>