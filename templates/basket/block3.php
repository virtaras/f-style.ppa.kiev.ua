
<div id="block3" style="display: none;">
	<div class="cart-box-title"> ДОСТАВКА И ОПЛАТА </div>
        <p class="block3_del">Доставка</p>
    <p class="block3_del_s">Укажите регион и способ доставки или заберите товар сами</p>
    
    <div class="block3_1">
	<div id="delivery"  class="payment">
		<?$deliveries=getRowsFromDB("SELECT delivery.* FROM delivery");
		$ind=1;
		$checked_delivery=isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : -1;
		foreach($deliveries as $delivery){
			if($checked_delivery==$delivery["id"] || (!($checked_delivery>0) && $ind==1)) $checked="checked='checked'"; else $checked="";?>
			<input class="block3_radio" c type="radio" name="delivery" value="<?=$delivery["id"]?>" onclick="javascript:setSessionKeyValue('b_delivery','<?=$delivery["id"]?>');" <?=$checked?> />
			<div class="input_info" data-index="<?=$ind?>"">
			<div class="input_info_left">
				<p>Стоимость доставки<br />Срок<br />Время<br />Возможность примерки</p>
				</div>
							<div>
				<p>бесплатно<br />1-2 рабочих дня<br />с 10:00 до 19:00<br />есть</p>
				</div>
				<p>Перед оплатой Вы можете открыть упаковку и примерить заказанные позиции. Таким образом, Вы приобретаете только те наименования, которые Вам понравились и подошли. Время ожидания курьера, за которые Вы можете произвести примерку - 20 минут.<?=$ind?></p>
			</div>
			<div class="input_lable" data-index="<?=$ind?>"><?=$delivery["name"]?>
			<!--
			<?=$delivery["price"]?>
			<?=$delivery["maxsum"]?>
            -->
			</div> <br />

			<?++$ind;
		}?>
	</div>
	</div>
    
    
   <div style="clear:both;"></div>
   
           <p class="block3_pay">Оплата</p>
    <p class="block3_pay_s">Выбирите способ оплаты</p>
    
   
    <div class="block3_2">
	<div id="paymenttype" class="payment">
		<?$paymenttypes=getRowsFromDB("SELECT paymenttype.* FROM paymenttype");
		$firstpayment=true;
		$checked_paymenttype=isset($_SESSION["b_paymenttype"]) ? $_SESSION["b_paymenttype"] : -1;
		foreach($paymenttypes as $paymenttype){
			if($checked_paymenttype==$paymenttype["id"] || (!($checked_paymenttype>0) && $firstpayment)) { $checked="checked='checked'"; $firstpayment=false; } else { $checked=""; }?>
			<input style="list-style: none"  type="radio" name="paymenttype" value="<?=$paymenttype["id"]?>" onchange="javascript:if(this.value){setSessionKeyValue('b_paymenttype','<?=$paymenttype["id"]?>');}" <?=$checked?> />
			<div class="input_lable" data-index="<?=$ind?>"><?=$paymenttype["name"]?>
			<!--
			<?=$paymenttype["price"]?>
			<?=$paymenttype["maxsum"]?>
            -->
			</div> <br />
			<div class="input_info" data-index="<?=$ind?>" style="display:none;">
			
			<div class="input_info_left">
				<p>Стоимость доставки<br /> 
				Срок <br />
				Время<br />
				Возможность примерки</p>
				
				</div>
				<div>
				<p>бесплатно<br />1-2 рабочих дня<br />с 10:00 до 19:00<br />есть</p>
				</div>
				<p>Перед оплатой Вы можете открыть упаковку и примерить заказанные позиции. Таким образом, Вы приобретаете только те наименования, которые Вам понравились и подошли. Время ожидания курьера, за которые Вы можете произвести примерку - 20 минут.<?=$ind?></p>
			</div>
			<?++$ind;
		}?>
	</div>
	</div>
  <div style=" clear:both"></div>
	<div class="cart-box-nav">
		<a href="javascript:void(0);" onclick="showBlock('block2');" class="goprev">Назад</a>
		<a href="javascript:void(0);" onclick="submitPayDel();" class="newnext">Далее</a>
	</div>
</div>
<script type="text/javascript">
	$(".input_lable").hover(
		function(){showPayDel($(this).attr("data-index"))}, 
		function(){hidePayDel($(this).attr("data-index"))}
	);	
	function showPayDel(id){
		$(".input_info[data-index='"+id+"']").show();
	}
	function hidePayDel(id){
		$(".input_info[data-index='"+id+"']").hide();
	}
	/*
	$(".input_lable").click(
		function(){showPayDel($(this).attr("data-index"))}
	);
	function showPayDel(id){
		$(".input_info").hide();
		$(".input_info[data-index='"+id+"']").show();
		$(":radio[value="+id+"]").attr('checked', true);
		//$(":radio[value="+id+"]").change();
	}
	//showPayDel($(":radio[value="+id+"]"));*/
</script>