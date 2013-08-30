<?php

$title = "Комментарии к заказу";


$source = "SELECT orders_comments.id,send_date ,clients.r129,comment  FROM orders_comments 
LEFT JOIN clients ON orders_comments.userid = clients.id
WHERE orderid = '$_GET[parent]'";

$title_fields["send_date"] = "Дата";
$title_fields["comment"] = "Комментарий";
$title_fields["r129"] = "Клиент";

$controls["comment"] = new Control("comment","longtext","Комментарий");

$controls["comment"]->rows = 7;

$controls["send_date"] = new Control("send_date","text","Дата");
$controls["send_date"]->default_value = date("Y-m-d H:i:s");

$controls["orderid"] = new Control("orderid","hidden","Дата");
$controls["orderid"]->default_value = $_GET["parent"];

$controls["userid"] = new Control("userid","hidden","Дата");
$controls["userid"]->default_value = 0;

$exclude_fields_edit = array("id","userid","orderid");




?>