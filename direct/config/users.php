<?php
$title = "Пользователи";
$source = "SELECT id,login,name,DATE_FORMAT(last_login,'%d.%m.%Y %H:%i:%s') as last_login,last_ip FROM users";
$controls["last_ip"] = new Control("last_ip","label","Последний IP");
$controls["last_login"] = new Control("last_login","label","Последнее время входа:");
$edit_title_fields["login"] = "Имя пользователя";
$edit_title_fields["passw"] = "Пароль";
$controls["groupid"] = new Control("groupid","list","Группа прав","SELECT id,name FROM usersgroup");
$required_fields = array("name","login","passw");
$title_fields = array("last_login"=>"Последний вход","last_ip"=>"Последний IP","login"=>"Имя пользователя","name"=>"Полное имя");
$controls["menuid"] = new Control("menuid","list","Меню","SELECT id,name FROM menu");
?>