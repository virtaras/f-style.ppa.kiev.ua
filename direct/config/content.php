<?php
$title = "Контент";
$source = "SELECT id,0 as eshoworder,name,urlname,title,description,keywords,showorder,ispublish
 FROM content";
$where = " WHERE parentid = 0 ";
if(isset($_GET["parent"]))
{
	$where = " WHERE parentid = '$_GET[parent]'";
}
$source = $source.$where;


$title_fields = array("name"=>"Наименование","caption"=>"Заголовок","title"=>"TITLE","keywords"=>"KEYWORDS","description"=>"DESCRIPTION","eshoworder"=>"Позиция","showorder"=>"Поз.","in_index"=>"На главную","sdate"=>"Дата","ispublish"=>"Публиковать","urlname"=>"URL","content_date"=>"Дата","is_fixed"=>"Топ");//advanced title field in table header


$template_fields["eshoworder"] = "templates/position.php";
$table_edit_mode = 1;

$select_fields = "*,id as img";
$edit_title_fields["pagesize"] = "Размер страницы";
$edit_title_fields["urlname"] = "URL страницы";
$edit_title_fields["in_index"] = "На главную";
$controls["name"] = new Control("name","text","Наименование");
$controls["name"]->css_style = "width:300px;";
$controls["title"] = new Control("title","longtext","TITLE");
$controls["keywords"] = new Control("keywords","longtext","KEYWORDS");
$controls["description"] = new Control("description","longtext","DESCRIPTION");
$controls["tags"] = new Control("tags","longtext","Теги (через запятую)");
$controls["in_index"] = new Control("in_index","checkbox","На главную");
if(isset($_GET["id"]))
{
	$controls["title"]->css_style = "width:400px;";
	$controls["keywords"]->css_style = "width:400px;";
	$controls["description"]->css_style = "width:400px;";
}
if(!isset($_GET["id"]))
{
	$controls["title"]->rows = 1;
	$controls["title"]->js = "onfocus = 'this.rows = 7;' ";
	$controls["keywords"]->rows = 1;
	$controls["keywords"]->js = "onfocus = 'this.rows = 7;'";
	$controls["description"]->rows = 1;
	$controls["description"]->js = "onfocus = 'this.rows = 7;'";
}
$controls["formid"] = new Control("formid","list","Форма ","SELECT id,name FROM requestgroup");
$controls["script"] = new Control("script","list","Модуль","SELECT id,name FROM modules ORDER BY name");

$controls["parentid"] = new Control("parentid","template","Владелец");
$controls["parentid"]->template = "templates/content_tree.php";
$controls["parentid"]->template_mode = "standart";
if(isset($_GET["parent"]))
{
	$controls["parentid"]->default_value = $_GET["parent"];
}
$controls["showtype"] = new Control("showtype","list","Тип контента","SELECT id,name FROM showtype");

$controls["info"] = new Control("info","template");
$controls["info"]->template = "templates/info.php";

$controls["additional"] = new Control("additional","template");
$controls["additional"]->template = "templates/info2.php";

$table_content_top_1 = "templates/path.php";
$controls["img"] = new Control("img","template");
$controls["img"]->template = "templates/img.php";
$controls["videolist"] = new Control("videolist","template");
$controls["videolist"]->template = "templates/videolist.php";
$controls["preview"] = new Control("preview","longtext","Кратко");
$controls["preview"]->rows = 5;
$controls["preview"]->css_style = "width:500px;";
$exclude_fields_edit = array("id","showorder","views","price","siteuser","pagesize","create_date");
global $sourceid;
$sourceid = 1;
$tab1 = array("name","urlname","pagesize","formid","showtype","script","in_index","preview","info","sdate","video","full_video","ispublish","siteuser","hide_content","tags","is_fixed");
$tab5 = array("img");
$tab3 = array("title","description","keywords");
$tab4 = array("parentid");
$tab7 = array("additional");
$tab6 = array("videolist");

if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
   $tabs = array(new Tab("Основные данные","",$tab1),new Tab("Изображения","",$tab5),new Tab("SEO","",$tab3),new Tab("Владелец","",$tab4)); // tabs on page 
}
else
{
    $tabs = array(new Tab("Основные данные","",$tab1),new Tab("SEO","",$tab3),new Tab("Владелец","",$tab4)); // tabs on page
}
$controls["sdate"] = new Control("sdate","date","Дата");
$controls["sdate"]->default_value = date("Y-m-d");

$controls["pagesize"] = new Control("pagesize","text","Размер страницы");
$controls["pagesize"]->default_value = 0;

$controls["video"] = new Control("video","longtext","Видео");
$controls["video"]->css_style = "width:300px;";

$controls["full_video"] = new Control("full_video","longtext","Видео (полное)");
$controls["full_video"]->css_style = "width:300px;";
$controls["full_video"]->rows = 7;

$controls["ispublish"] = new Control("ispublish","checkbox","Публиковать");
$controls["ispublish"]->default_value = 1;

$controls["hide_content"] = new Control("hide_content","checkbox","Скрыть контент");
$controls["is_fixed"] = new Control("is_fixed","checkbox","Топ");

if(!isset($_GET["id"]))
{
	$controls["sdate"] = new Control("sdate","label","Дата");
}

$controls["showtype"] = new Control("showtype","list","Тип контента","SELECT id,name FROM showtype");
$controls["siteuser"] = new Control("siteuser","list","Пользователь","SELECT id,CONCAT(r46,' ',r47,' ',r48,' ',email) as name FROM siteusers");

?>