<?php
$title = "Заказы";

$fsql = db_query("SELECT * FROM order_fields ORDER BY showorder");
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

if(isset($_GET["parent"]))
{
	$source = "SELECT orders.id,orders_status.name,DATE_FORMAT(create_date,'%d.%m.%Y %H:%i:%s') as odate,0 as goods,discount,deliverysumm FROM orders 
	LEFT JOIN orders_status ON orders_status.id = orders.status
	WHERE userid = '$_GET[parent]' AND 1=1 ";
	$exclude_fields = array("discount","id","deliverysumm");
	$pagesize = 5;
}
else
{
	$source = "SELECT orders.id,orders_status.name,DATE_FORMAT(create_date,'%d.%m.%Y %H:%i:%s') as odate,0 as summa,deliverysumm,discount,".implode(",",$farray)." FROM orders  
	LEFT JOIN orders_status ON orders_status.id = orders.status
	WHERE 1=1  ";
}
$exclude_fields = array("id","deliverysumm");

$title_fields["email"] = "E-Mail";

$title_fields["name"] = "Статус";

$title_fields["discount"] = "Скидка ";


$edit_title_fields["email"] = "E-Mail";


$title_fields["id"] = "№";
$title_fields["odate"] = "Дата";
$title_fields["info"] = "Инфо";
$title_fields["goods"] = "Детально";
$title_fields["discount"] = "Скидка %";
$title_fields["summa"] = "Сумма";

$eval_fields["summa"] = "show_orders_sum(\$row);";

if(!function_exists("show_orders_sum"))
{
	function show_orders_sum($row)
	{
		$summa = execute_scalar("SELECT sum(price*quantity) FROM ordersrow WHERE parentid = ".$row["id"]);
		$discount = number_format($summa*$row["discount"]/100,2,".","");
		echo $summa+$row["deliverysumm"]-$discount;
	}
}

$controls["userid"] = new Control("userid","longlist","Клиент","SELECT id,r129  FROM clients ORDER BY r129","id","r129");
$controls["userid"]->tablename = "clients";


$template_fields = array("goods"=>"templates/details.php");
$controls["create_date"] = new Control("create_date","label","Дата создания");
$controls["ip"] = new Control("ip","label","IP");
$controls["info"] = new Control("info","longtext","Дополнительно");
$controls["address"] = new Control("address","longtext","Адрес");
$edit_title_fields["email"] = "E-Mail";
$edit_title_fields["phone"] = "Телефон";
$edit_title_fields["fio"] = "Ф.И.О.";
$edit_title_fields["discount"] = "Скидка";
$edit_title_fields["deliverysumm"] = "Сумма доставки";
$edit_content_bottom = "templates/ordersrow.php";
$title_fields["dsicount"] = "Скидка";
$controls["delivery_date"] = new Control("delivery_date","label","Дата доставки");
$controls["paymenttype"] = new Control("paymenttype","list","Форма оплаты:","SELECT id,name FROM paymenttype");
$controls["deliverytype"] = new Control("deliverytype","list","Тип доставки:","SELECT id,name FROM delivery");
$controls["district"] = new Control("district","list","Район доставки","SELECT id,name FROM districts");
$controls["status"] = new Control("status","list","Статус","SELECT id,name FROM orders_status");
$f_startdate = new Control("start_date","date","Начало периода:");
$f_finishdate = new Control("finish_date","date","Окончание периода:");

$unsorted_fields = array("summa");

$sort_changes = array("odate"=>"orders.create_date");

$filters = array($f_startdate,$f_finishdate);

$startdate = filters_get_value($f_startdate);
$finishdate = filters_get_value($f_finishdate);
if($startdate != "")
{
    $source .= " AND orders.create_date >= '$startdate'";
}
if($finishdate != "")
{
    $source .= " AND orders.create_date <= '$finishdate'";
}
//echo $source;

//$menu_html = "&nbsp;<input type='button' class='buttons' value='Экспорт в Excel' onclick=\"window.open('templates/orders_excel.php?source=".$startdate."_".$finishdate."','','')\" />";

?>