<?
session_start();
require("../../inc/constant.php");
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
header("Content-Type: application/vnd.ms-excel; charset=utf-8"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=orders.xls");

$source = "SELECT DATE_FORMAT(orders.create_date,'%d.%m.%Y') as odate,CONCAT(orders.lastname,' ',.orders.firstname,' ',orders.patronymic) as cname,orders.*,delivery.name as dname, ordersrow.goodsname,ordersrow.quantity,ordersrow.price as gprice,ordersrow.goodsid,goods.*,orders.id as oid,paymenttype.name as pname
FROM orders 
INNER JOIN ordersrow ON ordersrow.parentid = orders.id
LEFT JOIN delivery ON delivery.id = orders.deliverytype
LEFT JOIN goods ON goods.id = ordersrow.goodsid
LEFT JOIN paymenttype ON paymenttype.id = orders.paymenttype
WHERE  1=1 ";
$date = explode("_",$_GET["source"]);

if($date[0] != "")
{
    $source .= " AND orders.create_date >= '$date[0]'";
}
if($date[1] != "")
{
    $source .= " AND orders.create_date <= '$date[1]'";
}
$source .= " ORDER BY orders.id DESC";
$sql = db_query($source);
?>
<table border="1">
	<tr style="background-color:silver;">
		<th>Дата</th>
		<th>Ф.И.О.</th>
		<th>Телефон</th>
		<th>E-Mail</th>
		<th>Адрес</th>
		<th>Тип доставки</th>
		<th>Форма оплаты</th>
		<th>Доп. инфо</th>
		<th colspan="5">Детали заказа</th>
	</tr>
	<tr style="background-color:silver;">
		<th colspan="8"></th>
		<td>Товар</td>
		<td>Параметры</td>
		<td>Кол-во</td>
		<td>Цена</td>
		<td>Сумма</td>
	</tr>

<?
$new_order = "";
$old_order = "";
$i = 1;
$s = 0;
while($r = db_fetch_assoc($sql))
{
	$new_order = $r["oid"];
	if($new_order != $old_order && $i > 1) 
	{
		?>
		<tr style="background-color:silver;">
			<td colspan="12"></td>
			<td><strong><?=$s?></strong></td>
		</tr>
		<?
		$s = 0;
	}
	?>
	<tr>
		<? if($new_order != $old_order) { ?>
		<td><?=$r["odate"]?></td>
		<td><?=$r["cname"]?></td>
		<td><?=$r["phone1"]?>, <?=$r["phone2"]?></td>
		<td><?=$r["email"]?></td>
		<td><?=("$r[city], $r[street] $r[house]".(!empty($r["entrance"]) ? ", ".$r["entrance"]."-й подъезд" : "").(!empty($r["floor"]) ? ", ".$r["floor"]."-й этаж" : "").(!empty($r["flat"]) ? ", кв. № ".$r["flat"] : "").(!empty($r["code"]) ? ",код подъезда: ".$r["code"] : "")."")?></td>
		<td><?=$r["dname"]?></td>
		<td><?=$r["pname"]?></td>
		<td><?=$r["info"]?></td>
		<? } else { ?>
		<td colspan="8"></td>
		<? } ?>
		<td><?=$r["goodsname"]?></td>
		<td>
			<?
		$fields_sql = db_query("SELECT f.name,f.table_name,f.field_type,f.rname,f.title FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL AND ((cf.inlist = 1 AND cf.categoryid = '$r[parentid]') OR f.isgeneral = 1)  AND f.field_type != 5
    ORDER BY cf.showorder");
		while($frow = db_fetch_assoc($fields_sql))
		{
			echo $frow["title"].":&nbsp;"; 
			switch($frow["field_type"])
				{
					case "2":
						echo "<b>".execute_scalar("SELECT name FROM $frow[table_name] WHERE id =  ".$r[$frow["rname"]])."</b>";
						break;
				}
			echo "&nbsp;<br />";	
		}
		?>
		</td>
		<td><?=$r["quantity"]?></td>
		<td><?=$r["gprice"]?></td>
		<td><?=$r["quantity"]*$r["gprice"]?></td>
	</tr>
	
	<?
	$s = $s + $r["quantity"]*$r["gprice"];
	$old_order = $r["oid"];
	$i++;
}
if($i > 0)
{
	?>
		<tr style="background-color:silver;">
			<td colspan="12"></td>
			<td><strong><?=$s?></strong></td>
		</tr>
		<?
}
?>

</table>