<?php
$title = "Меню";
$source = "SELECT menu.id,menu.name FROM menu";
if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
	$edit_content_right = "templates/menu.php";
}
?>