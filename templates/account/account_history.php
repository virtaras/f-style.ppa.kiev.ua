<?
include _DIR."templates/account/menu.php";
$order_statuses=getRowsFromDB("SELECT orders_status.* FROM orders_status");
?>


<div class="mr_bar-right">
	<div class="order-top" style="display:inline-block;">
		<div style="width: 105px">Дата</div>
		<div style="width: 130px">№ заказа</div>
		<div style="width: 125px">Товаров</div>
		<div style="width: 130px">На сумму</div>
		<div style="width: 155px">Статус</div>
		<div style="width: 50px; text-align: center">Товары</div>
	</div>

	<?
	$sql_text="SELECT orders.id,DATE_FORMAT(orders.create_date,'%d.%m.%Y') as odate,orders.r221 as cname,discount,deliverysumm,orders_status.name as stname FROM orders 
	LEFT JOIN orders_status ON orders_status.id = orders.status
	WHERE orders.userid =  ".$_SESSION["login_user"]["id"]." ORDER BY orders.create_date DESC";
	$sql = mysql_query($sql_text);
	while($row = mysql_fetch_assoc($sql))
	{
	$details_sql="SELECT ordersrow.id,ordersrow.goodsname,ordersrow.vname,ordersrow.quantity as rowquant,ordersrow.price as rowprice,ordersrow.goodsid,goods.* 
		FROM ordersrow 
		LEFT JOIN goods ON goods.id = ordersrow.goodsid
		WHERE ordersrow.parentid = '$row[id]'";
	$details = mysql_query($details_sql);
	$s = 0;
	$orders_row_html="";
	while($r = mysql_fetch_assoc($details))
	{
		$image=getTovarImage($r);
		$s = $s + ($r["rowquant"]*$r["rowprice"]);
		$orders_row_html.="<div class=\"history_row\">
			<div class=\"item1\"><img src='/images/files/".$image["image"]."' style='height:100px;'/></div>
			<div class=\"item2\">".$r["goodsname"]." ".$r["vname"]."</div>
			<div class=\"item3\">".$r["rowquant"]."</div>
			<div class=\"item4\">".$r["rowprice"]."</div>
			<div class=\"item5\">".number_format($r["rowprice"]*$r["rowquant"],2,".","")."</div>
		</div>";
	}
	$orders_footer_html="<div id=\"comments-".$row["id"]."\">";
	$comments_sql  = mysql_query("SELECT * FROM orders_comments WHERE orderid = $row[id]");
	while($rc = mysql_fetch_assoc($comments_sql))
	{
		$orders_footer_html.="<div>";
		$orders_footer_html.="<span class='c-date'><i>".$rc["send_date"]."</i></span>";
		if($rc["userid"] == 0) {
			$orders_footer_html.="<strong>Администратор:</strong>";
		}
		$orders_footer_html.="<span class='c-info'>".$rc["comment"]."</span>";
		$orders_footer_html.="</div>";
	}
	$orders_footer_html.="</div>";
	$orders_footer_html.="<textarea id=\"comment".$row["id"]."\"></textarea>
	<input type=\"button\" onclick=\"send_order_comment('".$row["id"]."')\" value=\"Добавить комментарий\" />";
	$orders_footer_html.="</div>";
	$order_num=$row["id"];
	$order_date=$row["odate"];
	$order_cnt=execute_scalar("SELECT COUNT(*) FROM ordersrow WHERE parentid='".$row["id"]."'");
	$order_price=number_format(($s - ($s*$row["discount"]/100)) + $row["deliverysumm"],2,".","");
	$order_status=$row["stname"];
	$order_heading_html="<div class='order-toprow' order-id='".$row["id"]."'><div class='item1'>".$order_date."</div><div class='item2'>".$order_num."</div><div class='item3'>".$order_cnt."</div><div class='item4'>".$order_price."</div><div class='item5'>".$order_status."</div><div class='item6'></div></div>";
	$order_heading_html.="<div class='clear'></div>";
	$order_heading_html.="<div class='hidden-block' id='h-".$row["id"]."'>";?>
	<div class="order" style="display:inline-block;">
	<?echo $order_heading_html;
	echo $orders_row_html;
	echo $orders_footer_html;?>
	</div>
	<?}
?>	
</div>
<div style="clear: both"></div>
<?include _DIR."templates/account/footer.php";?>

<style>
.item{
	float:left;
	width:120px;
}
.order-toprow{
	display:inline-block;
}
.history_row{
	display:inline-block;
}
</style>
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
<?function findByKeyValueInArray($array,$key,$value){
	$ind=-1;
	foreach($array as $k=>$v){
		if($v[$key]==$value) $ind=$k;
	}
	return $ind;
}?>
<script>
$(document).ready(function(){
	$(".order-toprow").click(function () {
		  $(this).toggleClass('bg2');
		  $("#h-"+$(this).attr("order-id")).slideToggle("slow");
	});
})	
</script>