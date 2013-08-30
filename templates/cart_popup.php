<? /*$row = execute_row_assoc("SELECT id,urlname,name,goodsid,brand FROM goods WHERE id = ".$_GET["add"]);
	if($row["goodsid"] != 0)
	{
		$parent = execute_row_assoc("SELECT id,urlname,name,goodsid,brand FROM goods WHERE id = ".$row["goodsid"]);
		?>
		<table	width="100%" border="0">
			<tr>
				<td style="vertical-align:top;"><a href="javascript:void(0);"><img src="<?=get_tovar_main_image($parent["id"],150,"h")?>" alt="<?=$parent["name"]?>" /></a></td>
				<td style="vertical-align:middle;">
				<strong><?=execute_scalar("SELECT name FROM brands WHERE id = ".$parent["brand"])?></strong>
				<br />
				<strong><?=$parent["name"]?></strong>
				<br />
				<strong><?=$row["name"]?></strong>
				</td>
			</tr>
		</table>
	<?
	}
	else
	{
		$main_img = getMainImage($row["id"],3);
		$img = getResizeImageById($main_img["id"],"l",array("height"=>"180","width"=>"120"),$main_img["format"]);
	?>
		<table	width="100%">
			<tr>
				<td style="vertical-align:top;"><a href=""><img src="<?=$img ?>" alt="<?=$row["name"]?>" /></a></td>
				<td style="vertical-align:middle;">
				<strong><?=execute_scalar("SELECT name FROM brands WHERE id = ".$row["brand"])?></strong>
				<br />
				<strong><?=$row["name"]?></strong>
				
				</td>
			</tr>
		</table>
	<?
	} */ ?>
	<div id="wrapperBasket">
	<h1 style="left:350px;">Ваш заказ</h1>
      <div class="topmenu">
        <td>ФОТО</td>
        <td>ТОВАР</td>
        <td>ЦЕНА</td>
        <td>КОЛ-ВО</td>
        <td>ИТОГО</td>
        <td>УДАЛИТЬ</td>
     </div>
     <?php
	 global $currency_array;
	global $base_currency;
	global $catalog_array;
	 $basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();


	 $sum_array = array(_DISPLAY_CURRENCY);
	if($base_currency != _DISPLAY_CURRENCY)
	{array_push($sum_array,$base_currency);}
	$summa = array();
	$base_summa = array();
	foreach($sum_array as $current)
	{
		$summa[$current] = 0;
		$base_summa[$current] = 0;
	}
	if(count($basket_array) > 0 && !isset($_SESSION["complite"]))
	{
		$key=implode(",",array_keys($basket_array));
		$sql_text = "SELECT goods.* FROM goods WHERE goods.id IN (".$key.")";
		$sql = mysql_query($sql_text);
        while($tovar = mysql_fetch_assoc($sql))
        {
			$main_img = getTovarImage($tovar,3);
			$image = getResizeImageById($main_img["id"],"h",array("height"=>"155"),$main_img["format"]);
			$name = $tovar["name"];
			$brandname = execute_scalar("SELECT name FROM brands WHERE id='".$tovar["brand"]."'");
			$color = execute_scalar("SELECT name FROM r1193 WHERE id='".$tovar["r1193"]."'");
			$size = execute_scalar("SELECT name FROM r1194 WHERE id='".$tovar["r1194"]."'");
			$price = get_price($tovar["price_action"] > 0 ? $tovar["price_action"] : $tovar["price"],_DISPLAY_CURRENCY,$tovar["goodsid"],$tovar["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false);
            $quantity = $basket_array[$tovar["id"]]->q;
			$full_price = $price*$quantity;
			$url = get_product_url($tovar);
			foreach($sum_array as $current)
            {
				$summa[$current] += get_price($tovar["price_action"] > 0 ? $tovar["price_action"] : $tovar["price"] ,$current,$tovar["id"],$tovar["currency"])*$quantity;
				$base_summa[$current] += get_price($tovar["price_action"] > 0 ? $tovar["price_action"] : $tovar["price"] ,$current,$tovar["id"],$tovar["currency"],true)*$quantity;
            }
            ?>
			<div class="cart-box-result-item">
			  <div class="cart-box-result-item-coll1">
				<span class="vfix"></span>
				<a href="<?=$url?>"><img src="<?=$image?>" alt=""/></a>
			  </div>
			  <div class="cart-box-result-item-coll2" style="margin:0 10px;width:auto;"> <span class="vfix"></span>
				<div class="text-wrap">
				  <div class="title"><span>
					<?=$name?>
					</span>
					<?=$brandname?>
				  </div>
				  <div class="dscr">цвет:
					<?=$color?>
					, размер:
					<?=$size?>
				  </div>
				</div>
			  </div>
			  <div data-price-holder="<?=$price?>" class="cart-box-result-item-coll3" style="margin:0 10px;width:auto;"> <span class="vfix"></span>
				<div class="text-wrap">
				  <?=$price?>
				  <?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?>
				</div>
			  </div>
			  <div class="cart-box-result-item-coll4" style="margin:0 10px;width:auto;"> <span class="vfix"></span>
				<div class="text-wrap">
				  <input value="<?=$quantity?>" name="q_<?=$tovar["id"]?>" class="mquant" type="text" />
				</div>
				<!--<a class="dell" href="javascript:removeFromBasket('<?=$tovar["id"]?>');" onclick="javascript:return confirm('Удалить товар из заказа ?');" >Удалить</a> </div>-->
				<a class="dell" onclick="rc('<?=$tovar["id"]?>');" onclick="javascript:return confirm('Удалить товар из заказа ?');" >Удалить</a> </div>
			  <div class="cart-box-result-item-coll5" style="margin:0 10px;width:auto;"> <span class="vfix"></span>
				<div class="text-wrap">
				  <div class="summbox">
					<?=$full_price?>
					&nbsp;
					<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?>
				  </div>
				</div>
			  </div>
			  <div class="clear"></div>
			</div>
		<?}
	}
	 ?>
    <div class="total">
        <div>итого: <span><?=((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></span></div>
        <div>
			<div style="display:inline-block;"><a href="/cart"><img src="/templates/images/imageBasket/basketbottom1.png" alt="" width="164" height="32" title="оформить заказ" /></a></div>
			<div style="display:inline-block;"><a href="javascript:void(0);" onclick="$('#to_basket').dialog('close');"><img src="/templates/images/imageBasket/basketbottom2.png" alt="" width="208" height="32" title="продолжить покупки" /></a></div>
		</div>
    </div>
</div>