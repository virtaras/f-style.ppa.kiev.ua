<?
session_start();
require("../../inc/cache/catalog_array.php");
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
require("../../inc/cache/catalog_array.php");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=price.xls");
?>
<table>
	<tr>
	<th>Код категории</th>
	<th>Категория</th>
	<th>Бренд</th>
	<th>Наличие</th>
	<th>Код товара</th>
	<th>Товар</th>
	<th>Цена</th>
	<th>Цена акционная</th>
	<th>Валюта</th>
	<th>Краткое описание</th>
	<th>Базовый товар (Код)</th>
	</tr>
	<?
	$sql = db_query("SELECT * FROM goods ORDER BY name");
	while($r = db_fetch_assoc($sql))
	{
		?>
		<tr>
	<td><?=execute_scalar("SELECT extid FROM catalog WHERE id = ".$r["parentid"])?></td>
	<td><?=$catalog_array[$r["parentid"]]["name"]?></td>
	<td><?=execute_Scalar("SELECT name FROM brands  WHERE id = $r[brand]")?></td>
	<td><?=$r["exist_type"]?></td>
	<td><?=$r["extid"]?></td>
	<td><?=$r["name"]?></td>
	<td><?=str_replace(".",",",$r["price"])?></td>
	<td><?=str_replace(".",",",$r["price_action"])?></td>
	<td><?=$r["currency"]?></td>
	<td><?=htmlspecialchars_decode($r["description"])?></td>
	<td><?=execute_scalar("SELECT extid FROM goods WHERE id = ".$r["goodsid"])?></td>
	</tr>	
		<?
	}
	
	?>
</table>