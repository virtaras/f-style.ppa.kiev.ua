<table class="t" valign="top" cellpadding="0" cellspacing="1" >
	<tr>
		<th width='1%'>&nbsp;</th>
		<th width='300'>Наименование</th>
		<th width='200'>Вариант</th>
		<th>Кол-во</th>
		<th>Цена</th>
		<th>Сумма</th>
	</tr>
	<?
	$details = db_query("SELECT ordersrow.id as oid,ordersrow.goodsname,ordersrow.vname,ordersrow.quantity,ordersrow.price as rowprice,ordersrow.goodsid,goods.* FROM ordersrow 
LEFT JOIN goods ON goods.id = ordersrow.goodsid
WHERE ordersrow.parentid = '$row[id]'");
$s = 0;
	while($r = db_fetch_assoc($details))
	{
		?>
		<tr>
		<td class='tr'><a href="index.php?t=ordersrow&id=<?=$r["oid"]?>&noheader&nofooter&parent=<?=$row["id"]?>"><img src="images/edit.gif" /></a></td>
		<td class='tr'><?=$r["goodsname"]?></td>
		<td class='tr'><?=$r["vname"]?>
		<div>
		<?
		$fields_sql = db_query("SELECT rname,table_name,field_type,title FROM `fields` f INNER JOIN categoryfields ON  categoryfields.categoryid = '".execute_Scalar("SELECT parentid FROM goods WHERE id = '$r[goodsid]'")."' AND categoryfields.fieldid = f.id 	WHERE source = '0' AND categoryfields.inlist = 0 AND f.field_type != 5 ORDER BY categoryfields.showorder");
		while($frow = db_fetch_assoc($fields_sql))
		{
			echo $frow["title"].":&nbsp;"; 
			switch($frow["field_type"])
				{
					case "2":
						echo "<b>".execute_scalar("SELECT name FROM $frow[table_name] WHERE id =  ".$r[$frow["rname"]])."</b>";
						break;
				}
			echo "&nbsp;";	
		}
		?>
		</div>
		</td>
		<td class='tr'><?=$r["quantity"]?></td>
		<td class='tr'><?=$r["rowprice"]?></td>
		<td class='tr'><?=$r["quantity"]*$r["rowprice"]?></td>
	</tr>
		<?
		$s = $s + ($r["quantity"]*$r["rowprice"]);
	}
	?>
	<tr>
		<td class='tr'>&nbsp;</td>
		<td class='tr' width='300'>&nbsp;</td>
		<td class='tr' width='200'>&nbsp;</td>
		<td class='tr'>&nbsp;</td>
		<td class='tr'><b>Итого:</b></td>
		<td class='tr'><?=number_format($s,2,".","")?></td>
	</tr>
	<tr>
		<td class='tr'>&nbsp;</td>
		<td class='tr' width='300'>&nbsp;</td>
		<td class='tr' width='200'>&nbsp;</td>
		<td class='tr'>&nbsp;</td>
		<td class='tr'><b>Скидка:</b></td>
		<td class='tr'><?=number_format($row["discount"],2,".","")?> % (<?=number_format($s*$row["discount"]/100,2,".","")?>)</td>
	</tr>
	<?
	if($row["deliverysumm"] > 0)
	{
	?>
	<tr>
		<th>&nbsp;</th>
		<th width='300'>&nbsp;</th>
		<th width='200'>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Доставка:</th>
		<th><?=number_format($row["deliverysumm"],2,".","")?></th>
	</tr>	
	<?
	}
	?>
	<tr>
		<th>&nbsp;</th>
		<th width='300'>&nbsp;</th>
		<th width='200'>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Итого к оплате:</th>
		<th><?=number_format(($s - ($s*$row["discount"]/100)) + $row["deliverysumm"],2,".","")?></th>
	</tr>
</table>