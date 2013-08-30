<?
session_start();
require $_SERVER["DOCUMENT_ROOT"]."//inc/constant.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/connection.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/global.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php";
require $_SERVER["DOCUMENT_ROOT"]."//inc/engine.php";
require $_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php";
header('Content-Type: text/html; charset=utf-8');
switch($_POST["action"]){
		case "getInner";
			//print_r($_POST);?>

<div class="cart-box-title"> ПОДТВЕРЖДЕНИЕ </div>
<div class="confirm">
  <div class="cart-box-result-title">
    <div class="cart-box-result-title-coll1"> Товар </div>
    <div class="cart-box-result-title-coll2"> Стоимость </div>
    <div class="cart-box-result-title-coll3"> Количество </div>
    <div class="cart-box-result-title-coll4"> Итого </div>
    <div class="clear"></div>
  </div>
<script type="text/javascript">
	required_array=[];
	ri=0;
</script>
  <form method="POST" action="" name="main-order-form">
  <!------------Товари-------------->
  <?
					$goods_price=0;
					$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
					$key = implode(",",array_keys($basket_array));
					$sql_text = "SELECT goods.* FROM goods WHERE goods.id IN (".$key.")";
					$sql=mysql_query($sql_text);
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
						$goods_price+=$full_price;
						?>
  <div class="cart-box-result-item-2">
    <div class="cart-box-result-item-coll1"> <span class="vfix"></span> <a href="<?=$url?>"><img src="<?=$image?>" alt=""/></a> </div>
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
      <!--<a class="dell" href="javascript:removeFromBasket('<?=$tovar["id"]?>');" onclick="javascript:return confirm('Удалить товар из заказа ?');" >Удалить</a>-->
	</div>
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
  <!------------#Товари--------------> 
  
  <!------------Поля замовлення-------------->
  
  <div class="cart-box-form" style="width: 470px; float:left">
	  <p class="personal_data">Личные данные</p>
	  <p class="personal_data_s">Вы можете изменить свои данные.<br />
		Данные будут сохранены для последующих заказов.</p>
		<?
		$fsql = mysql_query("SELECT * FROM order_fields ORDER BY showorder");
		//$fsql = mysql_query("SELECT * FROM clients_fields WHERE order_field != 0 ORDER BY showorder");
		while($f = mysql_fetch_assoc($fsql)){
			$client_field=execute_row_assoc("SELECT clients_fields.* FROM clients_fields WHERE order_field='".$f['id']."'");
			$value=(isset($_SESSION["b_" . $f["code"]]) && $_SESSION["b_" . $f["code"]]!="") ? $_SESSION["b_" . $f["code"]] : (isset($_SESSION["login_user"][$client_field["code"]]) ? $_SESSION["login_user"][$client_field["code"]] : "");?>
			<div class="cart-box-form-item">
			  <div class="cart-box-form-title">
				<?=$f["title"]?>
			  </div>
			  <div class="cart-box-form-value">
				<div class="cart-box-form-value-input">
				  <input id="b_<?=$f["code"]?>" name="<?=$f["code"]?>" value="<?=$value?>" type="text" onkeyup="javascript:setSessionKeyValue('b_'+'<?=$f["code"]?>',this.value);" />
				</div>
			  </div>
			  <div class="clear"></div>
			</div>
			<? if($f["isrequired"] == "1") { ?>
				<script language="JavaScript">
					required_array[ri] = "b_<?=$f["code"]?>";
					ri++;
				</script>
			<?}?>
		<?}?>
		<div class="cart-box-form-title"> E-mail: </div>
		<div class="cart-box-form-item">
		  <div class="cart-box-form-value">
			<div class="cart-box-form-value-input">
			  <input id="b_email" name="email" value="<?=(!empty($_SESSION["b_email"]) ? $_SESSION["b_email"] : (isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["email"] : ""))?>" type="text"/>
			</div>
		  </div>
		  <div class="clear"></div>
		</div>
		<div class="cart-box-form-item">
		  <div class="cart-box-form-title"> Комментарий: </div>
		  <div class="cart-box-form-value">
			<div class="cart-box-form-value-texarea">
			  <textarea rows="7" placeholder="Текст (необязательно)" name="info" onkeyup="javascript:setSessionKeyValue('b_info',this.value);" ><?=(isset($_SESSION["b_info"]) ? $_SESSION["b_info"] : "" )?></textarea>
			</div>
		  </div>
		  <div class="clear"></div>
		</div>
		<input type="hidden" name="action" value="register" />
	</div>
	<div style="width:450px; display:inline-block; padding-left: 20px">
	  <div class="clear"></div>
	  <!------------#Поля замовлення--------------> 
	  
	  <!------------Доставка-------------->
	  
	  <p class="choice">Доставка</p>
	  <p class="choice_s">Укажите регион и способ доставки или заберите товар сами</p>
	  <div id="delivery" class="confirm_m">
		<?
		$deliveries=getRowsFromDB("SELECT delivery.* FROM delivery");
		$ind=1;
		$checked_delivery=isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : -1;
		foreach($deliveries as $delivery){
			if($checked_delivery==$delivery["id"] || (!($checked_delivery>0) && $ind==1)){
				$checked="checked='checked'";
				$delivery_price=$delivery["price"];
			}else{
				$checked="";
			}?>
		<input type="radio" name="delivery" value="<?=$delivery["id"]?>" onclick="javascript:setFieldValue('delivery',this.value);" <?=$checked?> />
		<div class="input_lable" data-index="<?=$ind?>">
		  <?=$delivery["name"]?>
		</div>
		<br />
			<?++$ind;
		}?>
	  </div>
	  <!------------#Доставка--------------> 
	  
	  <!------------Оплата-------------->
	  <p class="choice">Оплата</p>
	  <p class="choice_s">Выбирите способ оплаты</p>
	  <div id="paymenttype"  class="confirm_m">
			<?$paymenttypes=getRowsFromDB("SELECT paymenttype.* FROM paymenttype");
			$firstpayment=true;
			$checked_paymenttype=isset($_SESSION["b_paymenttype"]) ? $_SESSION["b_paymenttype"] : -1;
			foreach($paymenttypes as $paymenttype){
				if($checked_paymenttype==$paymenttype["id"] || (!($checked_paymenttype>0) && $firstpayment)) { $checked="checked='checked'"; $firstpayment=false; } else { $checked=""; }?>
				<input type="radio" name="paymenttype" value="<?=$paymenttype["id"]?>" onclick="javascript:setFieldValue('paymenttype',this.value);" <?=$checked?> />
				<div class="input_lable" data-index="<?=$ind?>">
				  <?=$paymenttype["name"]?>
				</div>
				<br />
				<?++$ind;
			}?>
	  </div>
	  <div class="clear"></div>
	  <!------------#Оплата--------------> 
	  
	</div>
	<div class="sum_warp"> 
	  <!------------Ціна-------------->
	  
	  <div>
		<div class="title conft">Итого сума к оплате:</div>
		<div class="vall confgl" id="full_summ"><?=($goods_price+$delivery_price)?> <?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></div>
	  </div>
	  <!------------#Ціна--------------> 
	</div>
	<input type="hidden" name="discount" value="0" />
</form>
</div>
<div class="cart-box-nav"> <a href="javascript:void(0);" onclick="showBlock('block3');" class="goprev">Назад</a><p div class="agreement"><input style="margin-right:5px; "type="checkbox" id="b_soglashenie" />Я принимаю все «<a href="">Условия пользовательского соглашения</a>» </p><a href="javascript:void(0);" onclick="javascript:send_order();" class="newnext">ЗАКАЗ ПОДТВЕРЖДАЮ</a> </div>
<?break;
	}
	?>