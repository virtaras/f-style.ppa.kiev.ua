<!-- <div id="checkContent">-->
     <div class="cart-box jNice">
			<div class="cart-box-title">
				Корзина
				<a href="#" class="gonext">ПРодолжить</a>
			</div>
		<form method='post' name="order" action='<?=_SITE?>basket/recalculate'>
        <div class="cart-box-form">
					<div class="cart-box-result">
						<div class="cart-box-result-title">
							<div class="cart-box-result-title-coll1">
								Товар
							</div>
							<div class="cart-box-result-title-coll2">
								Стоимость
							</div>
							<div class="cart-box-result-title-coll3">
								Количество
							</div>
							<div class="cart-box-result-title-coll4">
								Итого
							</div>
							<div class="clear"></div>
						</div>
        <?
       
        while($r = mysql_fetch_assoc($sql))
        {
            $q = $basket_array[$r["id"]]->q;
            foreach($sum_array as $current)
            {
				$summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"])*$q;
				$base_summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"],true)*$q;
            }
			
            ?>


           <div class="cart-box-result-item">
                <?
				$tovarid = $r["id"];
				$img = "";
				$tovar_name = /*$r["brandname"]." ".*/$r["name"];
				$tovar_url = get_product_url($r);			
				if($r["goodsid"] != "0")
				{
					$tovarid = $r["goodsid"];
					$img = get_main_image($r["goodsid"],80,3);
					if(setting("basket_vname") == "1")
					{
						$tovar_name = execute_scalar("SELECT name FROM goods WHERE id = ".$r["goodsid"])." ".$r["name"];		
					}
					$tovar_url = get_product_url(execute_row_assoc("SELECT goods.id,goods.parentid,goods.urlname,brands.urlname as brandurl FROM goods LEFT JOIN brands ON brands.id = goods.brand WHERE goods.id = ".$r["goodsid"]));
                    $price = get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false);
                }
				else
				{
					$tovarid = $r["id"];
					$main_img = getMainImage($r["id"],3);
					$img = getResizeImageById($main_img["id"],"h",array("height"=>"54"),$main_img["format"]);
				}

                ?>
                <div class="cart-box-result-item-coll1">
								<span class="vfix"></span><img src="<?=$img?>" alt=""/>
							</div>
                <div class="cart-box-result-item-coll2">
								<span class="vfix"></span><div class="text-wrap">
									<div class="title"><span><?=$tovar_name?></span> <?=$r['brandname']?></div>
									<div class="dscr">Женский джемпер из натуральной шерсти.</div>
								</div>
							</div>
                <div data-price-holder="<?=$price?>" class="cart-box-result-item-coll3">
								<span class="vfix"></span><div class="text-wrap">
									<?=$price?> <?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?>
								</div>
							</div>
                <div class="cart-box-result-item-coll4">
								<span class="vfix"></span><div class="text-wrap">
									<input value="<?=$q?>" name="q_<?=$r["id"]?> class="mquant" type="text" />
								</div>
								<a class="dell" href="<?=_SITE?>basket/remove/<?=$r["id"]?>" onclick="javascript:return confirm('Удалить товар из заказа ?');" >Удалить</a>
							</div>
                        <div class="cart-box-result-item-coll5">
								<span class="vfix"></span><div class="text-wrap">
									<div class="summbox">
									</div>
								</div>
							</div>
            <div class="clear"></div>
						</div>
            <?
        }
		?>
		</div>
				</div>
        <div class="global-sum-wrapper">
					<div class="global-sum-item"><div class="title">Подытог:</div><div class="global-sum vall"></div></div>
					<div class="main-sep nm"></div>
					<div class="global-sum-item all"><div class="title">Итого:</div><div class="global-sum-all vall"></div></div>
				</div>
<!--<strong>--><?//=((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])?><!--&nbsp;--><?//=$currency_array[_DISPLAY_CURRENCY]["shortname"]?><!--</strong>-->

		<?
	
//		$discount_summa = 0;
//		$discount = 0;
//		//Discount  ************************************************************************
//	if(setting("discount_mode") == 1 || setting("discount_mode") == 3 || !isset($_SESSION["login_user"])) {
//		$discount = get_discount($base_summa[$base_currency]);
//		if($discount > 0)
//		{
//			$discount_summa = number_format((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY]*$discount/100,setting("price_digit"), '.', '');
//			?>
<!---->
<!--			 -->
<!--			 <tr>-->
<!---->
<!--            <td></td>-->
<!-- -->
<!--            <td colspan="3" ><b>Скидка (--><?//=$discount?><!--%):</b></td>-->
<!---->
<!--            <td style="text-align:center;" >-->
<!--<strong>-&nbsp;--><?//=$discount_summa?><!--&nbsp;--><?//=$currency_array[_DISPLAY_CURRENCY]["shortname"]?><!--</strong></td>-->
<!---->
<!--            <td  style=""></td>-->
<!---->
<!--        </tr>-->
<!--			--><?// }
//		}
	
		//Discount  ************************************************************************			
		
		//Delivery *************************************************************************
//		$current_delivery = isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : execute_scalar("SELECT id FROM delivery");
//		$delivery_summ = ceil(get_delivery_summ(((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY]),$current_delivery));
//		//Delivery *************************************************************************
//		?><!--	-->
<!-- <tr>-->
<!--            <td ></td>-->
<!--            <td colspan="3"> <b>Доставка:</b>-->
<!-- <select name="delivery" onchange="document.forms['order'].submit();" style="margin:0px 0px 0px 10px; width:110px;">-->
<!--		--><?//
//		  $delivery = mysql_query("SELECT * FROM delivery");
//		while($dtype = mysql_fetch_assoc($delivery))
//				{
//					?>
<!--					<option   --><?//=($current_delivery == $dtype["id"] ? "SELECTED" : "" )?><!-- value="--><?//=$dtype["id"]?><!--">--><?//=$dtype["name"]?><!--</option>-->
<!--					--><?//
//				}
//				?>
<!--		</select></td>-->
<!--            <td style="text-align:center;" >-->
<!--<strong>--><?//=$delivery_summ?><!--&nbsp;--><?//=$currency_array[_DISPLAY_CURRENCY]["shortname"]?><!--</strong></td>-->
<!--            <td  style=""></td>-->
<!--        </tr>		-->
<!--        <tr class="basket_summa_td">-->
<!--            <td style=""></td>-->
<!--            <td colspan="3" ><b>Итого к оплате:</b></td>-->
<!--            <td style="text-align:center;" >-->
<!--<strong>--><?//=(((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])+$delivery_summ-$discount_summa)?><!--&nbsp;--><?//=$currency_array[_DISPLAY_CURRENCY]["shortname"]?><!--</strong>-->
<!--<input type="hidden" name="discount" value="--><?//=$discount?><!--" />-->
<!--<input type="hidden" name="hdelivery" value="--><?//=$delivery_summ?><!--" />-->
<!--</td>-->
<!--            <td  style=""></td>-->
<!--        </tr>-->

     <div class="discount-card-box">
					<div class="title">Подарочная карта:</div>
					<input type="text" placeholder="Номер карты" />
					<a href="#" class="button">Применить</a>
				</div>
				<div class="cart-box-nav">
					<a href="#" class="goprev">  Вернуться в каталог</a>
					<input class="next" type="submit" value="ПРодолжить"/>
				</div>
      

		
</div>




<div style="display:none;">


        <div style="margin-top:10px; padding:0px 10px 10px 10px; "><strong>Для оформления заказа заполните Ваши данные:</strong></div>
		<script language="JavaScript">
		var required_array = new Array;
		var ri = 0;
		</script>
		<table 		class="detailst" border="0" cellpadding="2" align="center">
			<? if(setting("paymenttype_visible") == "1") { ?>
		<tr><td>
		Форма оплаты:</td><td>
        <select class="ibox" name="paymenttype" >
				<?
                $paymenttype = mysql_query("SELECT * FROM paymenttype");
				while($ptype = mysql_fetch_assoc($paymenttype))
				{
					?>
					<option  <?=(isset($_SESSION["b_paymenttype"]) && $_SESSION["b_paymenttype"] == $ptype["id"] ? "SELECTED" : "" )?> value="<?=$ptype["id"]?>"><?=$ptype["name"]?></option>
					<?
				}
				?>
			</select></td></tr>
			<? } ?>
			<tr>
				<td style="">E-Mail:</td>
				<td><input class="ibox" id="email" name="email" value="<?=(!empty($_SESSION["b_email"]) ? $_SESSION["b_email"] : (isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["email"] : ""))?>" style="width:300px;" type="text" />&nbsp;<sup style="color:red;">*</sup></td>
			</tr>
		<?
			$clients_info_array = array();
			if(isset($_SESSION["login_user"]))
			{
				$csql = mysql_query("SELECT * FROM clients_fields WHERE order_field != 0 ORDER BY showorder");
				while($crow = mysql_fetch_assoc($csql))
				{
					if(isset($clients_info_array[$crow["order_field"]]))
					{
						$clients_info_array[$crow["order_field"]] = $clients_info_array[$crow["order_field"]]." ".$_SESSION["login_user"][$crow["code"]];
					}
					else
					{
						$clients_info_array[$crow["order_field"]] = $_SESSION["login_user"][$crow["code"]];
					}	
				}
			}
			$fsql = mysql_query("SELECT * FROM order_fields ORDER BY showorder");
			while($f = mysql_fetch_assoc($fsql))
			{
				echo "<tr>";
				if($f["fieldtype"] != "4")
				{
					?>
					<td><?=$f["title"]?></td>
					<td><input class="ibox" id="<?=$f["code"]?>" name="<?=$f["code"]?>" value="<?=(isset($_SESSION["b_".$f["code"]]) ? $_SESSION["b_".$f["code"]] : (isset($clients_info_array[$f["id"]]) ? $clients_info_array[$f["id"]] : "") )?>" style="width:300px;" type="text" />
					<? if($f["isrequired"] == "1") { ?>
					<sup style="color:red;">*</sup>
					<script language="JavaScript">
						required_array[ri] = "<?=$f["code"]?>";
						ri++;
					</script>
					<?
					} ?>
					</td>
					<?
				}
				else
				{
				?>
				<td></td>
				<td >
				<?=$f["title"]?><br />
				<textarea class="ibox" rows="5" style="width:300px;margin-top:3px;" id="<?=$f["code"]?>" name="<?=$f["code"]?>" ><?=(isset($_SESSION["b_".$f["code"]]) ? $_SESSION["b_".$f["code"]] : (isset($clients_info_array[$f["id"]]) ? $clients_info_array[$f["id"]] : ""))?></textarea><? if($f["isrequired"] == "1") { ?>
					<sup style="color:red;">*</sup>
					<script language="JavaScript">
						required_array[ri] = "<?=$f["code"]?>";
						ri++;
					</script>
					<?
					} ?>
					</td>
					<?
				}
				echo "</tr>";
			}
			?>
			<tr>
				<td></td>
				<td align="left">
				Дополнительная  информация по заказу:<br />
				<textarea class="ibox" rows="5" style="width:300px;margin-top:3px;" name="info"><?=(isset($_SESSION["b_info"]) ? $_SESSION["b_info"] : "" )?></textarea>
                </td>
			</tr>
			<tr>
				<td colspan="3" ><sup style="color:red;">*</sup> - поля обязательны для заполнения</td>
			</tr>
			<tr><td colspan="3" align="center" style="padding-top:10px;">
            <input style="text-align:center;" class="buttons" onclick="javascript:return check_required()"  type="submit" value="Заказать"  />
            </td></tr>
		</table>


		<? if(setting("paymenttype_visible") !="1") { ?>
		<input type="hidden" name="paymenttype" value="0" />
		<? } ?>
		</div>
		<div id="contentHeader">
   		<p id="indexBar">дополнительно</p>
    </div>
		<div id="additionallyContent">
  
        
        <div class="rightAdditionally">
        	<h4>комментарии к заказу</h4>
            
            <div class="comment1">  <!--this is comment-->
  			     <textarea cols="10" rows="10"></textarea>
            </div> 
            
            <ul>
                <li><p>итого: <span>6 635 грн</span></p></li>
                <li><a href="#"><img src="/templates/images/imagesCabinet/bottom2.png" alt="" width="144" height="32">
                    <p>продолжить</p></a></li>
  			</ul>
        </div>
  	</div>
        </form>
 </div>