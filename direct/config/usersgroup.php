<?php
$title = "Группы пользователей";
$source = "SELECT usersgroup.id,usersgroup.name,menu.name as menu FROM usersgroup LEFT JOIN menu ON menu.id = usersgroup.menuid";
$controls["menuid"] = new Control("menuid","list","Меню","SELECT id,name FROM menu");
$title_fields["menu"] = "Меню";
?>