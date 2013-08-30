<?
$title = "Бренды";
$source = "SELECT id,name,urlname FROM brands";
	global $sourceid;
	$sourceid = 7;
	if(isset($_GET["id"]) && $_GET["id"] != "-1" )
	{
		$edit_content_bottom = "templates/img.php";
	}	
	$body = new Control("body","template","Содержание");
	$body->template = "templates/body.php";
	$controls["body"] = $body;
	
	$controls["ishide"] = new Control("ishide","checkbox","Скрыть");
	$title_fields["urlname"] = "URL";
	$edit_title_fields["urlname"] = "URL";
	$table_edit_mode = 1;
	$title_fields["ishide"] = "Скрыть";
?>