<?
//print_r($basket_array);
?>
<div id="block1">
<div class="cart-box-title"> КОРЗИНА <!--<a href="#" class="gonext">ПРодолжить</a>--> </div>
<form method='post' name="order" action='<?=_SITE?>basket/recalculate'>
<div class="cart-box-form">
	<div class="cart-box-result">
		<div class="cart-box-result-title">
		  <div class="cart-box-result-title-coll1"> Товар </div>
		  <div class="cart-box-result-title-coll2"> Стоимость </div>
		  <div class="cart-box-result-title-coll3"> Количество </div>
		  <div class="cart-box-result-title-coll4"> Итого </div>
		  <div class="clear"></div>
		</div>
		<?
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
            ?>
		<div class="cart-box-result-item">
		  <div class="cart-box-result-item-coll1">
			<span class="vfix"></span>
			<a href="<?=$url?>"><img src="<?=$image?>" alt=""/></a>
		  </div>
		  <div class="cart-box-result-item-coll2"> <span class="vfix"></span>
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
		  <div data-price-holder="<?=$price?>" class="cart-box-result-item-coll3"> <span class="vfix"></span>
			<div class="text-wrap">
			  <?=$price?>
			  <?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?>
			</div>
		  </div>
		  <div class="cart-box-result-item-coll4"> <span class="vfix"></span>
			<div class="text-wrap">
			  <input value="<?=$quantity?>" name="q_<?=$tovar["id"]?>" class="mquant" type="text" />
			</div>
			<a class="dell" href="javascript:removeFromBasket('<?=$tovar["id"]?>');" onclick="javascript:return confirm('Удалить товар из заказа ?');" >Удалить</a> </div>
		  <div class="cart-box-result-item-coll5"> <span class="vfix"></span>
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
    <?}?>
	</div>
</div>
<div class="global-sum-wrapper">
  <div class="global-sum-item">
    <div class="title">Подытог:</div>
    <div class="global-sum vall"></div>
  </div>
  <div class="main-sep nm"></div>
  <div class="global-sum-item all">
    <div class="title">Итого:</div>
    <div class="global-sum-all vall"></div>
  </div>
</div>
<input type="hidden" value="2" name="delivery">

<div style="float: left" class="discount-card-box">
  <div class="title">Подарочная карта:</div>
  <input type="text" placeholder="Номер карты" />
  <a href="#" class="button">Применить</a>
  
</div>
<a href="/templates/basket/bonus-popup.php" class="btn-bonus">Использовать бонусы</a>

<div style=" clear:both"></div>
<div class="cart-box-nav"> <a href="#" class="goprev"> Вернуться в каталог</a>
  <!--<input class="next" type="button" onclick="hideShow('block1','block2');" value="Продолжить"/>-->
  
  <a href="javascript:showBlock('block2');" class="newnext">Далее</a>
</div>

<? if(setting("paymenttype_visible") !="1") { ?>
	<input type="hidden" name="paymenttype" value="0" />
<? } ?>
</form>
</div>