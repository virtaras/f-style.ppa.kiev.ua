<?php
$title = "Типы меню";	

$source = "SELECT id,name,strongname,id as links FROM menutype";

$title_fields["strongname"] = "Идентификатор";
$title_fields["links"] = "Ссылки";
$edit_title_fields["strongname"] = "Идентификатор";

$required_fields = array("strongname","name");

$eval_fields["links"] = "links(\$row);";


function links($row)
{
	?>
		<a href="index.php?t=menulinks&parent=<?=$row["id"]?>&sort=showorder+ASC">Ссылки</a>
	<?
}
?>