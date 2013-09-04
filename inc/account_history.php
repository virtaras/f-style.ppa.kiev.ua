<?
if(!isset($_SESSION["login_user"]))
{
	header("Location: "._SITE);
	exit();
}
$title = "История заказов";
function get_content()
{
	
	?>
	<script language="JavaScript">
		function send_order_comment(id)
		{
			if($("#comment" + id).val() == "")
			{
				alert("Необходимо ввести текст комментария !");
			}
			else
			{
				$.post("/ajax.php",{action:"order_comment",orderid:id,comment:$("#comment" + id).val()},function()  {  alert("Ваше сообщение успешно отправлено !"); document.location.href = '/account_history.html'; }  );
			}
		}
	</script>

	<?
	$sql_text="SELECT orders.id,DATE_FORMAT(orders.create_date,'%d.%m.%Y') as odate,orders.r221 as cname,discount,deliverysumm,orders_status.name as stname FROM orders 
	LEFT JOIN orders_status ON orders_status.id = orders.status
	WHERE orders.userid =  ".$_SESSION["login_user"]["id"]." ORDER BY orders.create_date DESC";
	$sql = mysql_query($sql_text);
	while($row = mysql_fetch_assoc($sql))
	{
		?>
		<table cellpadding="4" cellspacing="2" border="0" id="history_table">
	<tr class="history_header">
		<th >Заказ № <?=$row["id"]?> от <?=$row["odate"]?> / <?=$row["stname"]?></th>
		<th width="10%">Кол-во</th>
		<th width="12%">Цена,грн</th>
		<th width="12%">Сумма,грн</th>
	</tr>
	<?
	$details_sql="SELECT ordersrow.id,ordersrow.goodsname,ordersrow.vname,ordersrow.quantity as rowquant,ordersrow.price as rowprice,ordersrow.goodsid,goods.* FROM ordersrow 
		LEFT JOIN goods ON goods.id = ordersrow.goodsid
		WHERE ordersrow.parentid = '$row[id]'";
	$details = mysql_query($details_sql);
	$s = 0;
	while($r = mysql_fetch_assoc($details))
	{
		$s = $s + ($r["rowquant"]*$r["rowprice"]);
		?>
		<tr class="history_row">
			<td class="name"><?=$r["goodsname"]." ".$r["vname"]?></td>
			<td style="text-align:center;"><?=$r["rowquant"]?></td>
			<td style="text-align:center;"><?=$r["rowprice"]?></td>
			<td style="text-align:center;"><?=($r["rowprice"]*$r["rowquant"])?></td>
		</tr>
		<?
	}
	?>
	<tr>
		<td colspan="3" class="empty" style="text-align:right;">Итого по товарам:</td>
		<td class="summa"><strong><?=number_format($s,2,".","")?></strong></td>
	</tr>
	<?
	if($row["discount"] > 0) {
	?>
	<tr>
		<td colspan="3" class="empty" style="text-align:right;">Скидка (<?=number_format($row["discount"],2,".","")?> %):</td>
		<td class="summa"><strong><?=number_format($s*$row["discount"]/100,2,".","")?></strong></td>
	</tr>
	<? }
	if($row["deliverysumm"] > 0)
	{
	?>
	<tr>
		<td colspan="3" class="empty" style="text-align:right;">Доставка:</td>
		<td class="summa"><strong><?=number_format($row["deliverysumm"],2,".","")?></strong></td>
	</tr>	
	<?
	}
	?>
	<tr>
		<td colspan="3" class="empty" style="text-align:right;">Итого к оплате:</td>
		<td class="summa"><strong><?=number_format(($s - ($s*$row["discount"]/100)) + $row["deliverysumm"],2,".","")?></strong></td>
	</tr>
	</table>
	<div id="comments-<?=$row["id"]?>">
		<?
		$comments_sql  = mysql_query("SELECT * FROM orders_comments WHERE orderid = $row[id]");
		while($rc = mysql_fetch_assoc($comments_sql))
		{
			?>
			<div>
			<i><?=$rc["send_date"]?></i>&nbsp;
			<br /><?php if($rc["userid"] == 0) { ?>
			<strong>Администратор:</strong>
			<? }  ?><?=$rc["comment"]?></div><br />
			<?
		}
		?>
	</div><a name="<?=$row["id"]?>"></a>
	<textarea id="comment<?=$row["id"]?>" style="width:100%;" rows="5"></textarea>
	<br /><br />
	<input type="button" onclick="send_order_comment('<?=$row["id"]?>')" value="Добавить комментарий" />
	<br /><br />
		<div style="border-bottom:1px solid silver;"></div>
	<br />
		<?
	} 
	?>

	<?
	}
?>