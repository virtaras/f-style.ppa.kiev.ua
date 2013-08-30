<?
$title = "Клиенты";


$fsql = db_query("SELECT * FROM clients_fields ORDER BY showorder");
$farray = array();
while($f = db_fetch_assoc($fsql))
{
	$title_fields[$f["code"]] = $f["title"];
	array_push($farray,$f["code"]);
	switch($f["fieldtype"])
	{
		case "1":
			$edit_title_fields[$f["code"]] = $f["title"];
			break;
		case "2":
		$edit_title_fields[$f["code"]] = $f["title"];
			break;
		case "4":
		$controls[$f["code"]] = new Control($f["code"],"longtext",$f["title"]);
			break;
	}
}
$source = "SELECT clients.id,email,0 as summa,clients.discount,discount_group.name as dname, ".implode(",",$farray).", confirmed
FROM clients
LEFT JOIN discount_group ON discount_group.id = clients.discount_group
";

$title_fields["summa"] = "Сумма";
$title_fields["discount"] = "Скидка";
$title_fields["dname"] = "Группа скидок";
$title_fields["confirmed"] = "Подтверждён";




$exclude_fields = array("id","passw","create_date","last_login","last_ip","deliverysumm");

$controls["discount_group"] = new Control("discount_group","list","Группа скидок","SELECT id,name FROM discount_group ORDER BY name");


$eval_fields["summa"] = "show_orders_sum(\$row);";

if(!function_exists("show_orders_sum"))
{
	function show_orders_sum($row)
	{
		$summa = execute_scalar("SELECT (sum(price*quantity)+orders.deliverysumm) FROM ordersrow 
		INNER JOIN orders ON orders.id = ordersrow.parentid 
		WHERE orders.userid = $row[id] ");
		
		echo $summa;
	}
}



$title_fields["email"] = "E-Mail";
$title_fields["discount"] = "Скидка";
$edit_title_fields["discount"] = "Скидка";
$edit_title_fields["email"] = "E-Mail";
$edit_title_fields["passw"] = "Пароль";

$controls["passw"] = new Control("passw","template","Пароль");
$controls["passw"]->template_mode = "standart";
$controls["passw"]->template = "templates/passw.php";

$edit_title_fields["email"] = "E-Mail";

$edit_content_bottom = "templates/orderbyuser.php";

$sort_changes = array("dname"=>"discount_group.name");
$unsorted_fields = array("summa");

$controls["confirmed"] = new Control("confirmed","checkbox","Подтверждён");
?>