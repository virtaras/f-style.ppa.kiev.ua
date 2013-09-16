<?
session_start();
require($_SERVER["DOCUMENT_ROOT"]."//inc/constant.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/connection.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/global.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/engine.php");
require($_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php");
global $currency_array;

if(isset($_POST["action"])){
	switch($_POST["action"]){
		case "getBlock2":
			?>
			<script language="JavaScript">
				var required_array = new Array;
				var ri = 0;
			</script>
			<div class="cart-box-title"> КОНТАКТНАЯ ИНФОРМАЦИЯ 2 <a href="#" class="gonext">Отправить заказ</a> </div>
			<div class="cart-box-form">
				<?
					$clients_info_array = array();
								$ind = 0;
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
								$orderFields = array();
					//$fsql = mysql_query("SELECT * FROM order_fields ORDER BY showorder");
					$fsql = mysql_query("SELECT * FROM clients_fields WHERE order_field != 0 ORDER BY showorder");
					while($f = mysql_fetch_assoc($fsql))
					{?>
						<div class="cart-box-form-item">
							<div class="cart-box-form-title">
								<?=$f["title"]?>
							</div>
							<div class="cart-box-form-value">
								<div class="cart-box-form-value-input">
									<input id="<?=$f["code"]?>" name="<?=$f["code"]?>" value="<?=$_SESSION["login_user"][$f["code"]]?>" type="text"/>
								</div>
							</div>
							<div class="clear"></div>
						</div>
							<? if($f["isrequired"] == "1") { ?>
							<script language="JavaScript">
								required_array[ri] = "<?=$f["code"]?>";
								ri++;
							</script>
							<?
							} ?>
				<?}?>
				<div class="cart-box-form-item">
					<div class="cart-box-form-title"> Способ оплаты: </div>
					<div class="cart-box-form-value">
						<div class="cart-box-form-value-input">
							<select>
								<option> При получении </option>
								<option> Бесплатно </option>
							</select>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="cart-box-form-title"> E-mail: </div>
				<div class="cart-box-form-item">
					<div class="cart-box-form-value">
						<div class="cart-box-form-value-input">
							<input id="email" name="email" value="<?=(!empty($_SESSION["b_email"]) ? $_SESSION["b_email"] : (isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["email"] : ""))?>" type="text"/>
						</div>
						<div class="cart-box-form-value-check">
							<label>
								<input type="checkbox" />
								Подписаться на акции и новинки
							</label>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="cart-box-form-item">
					<div class="cart-box-form-title"> Комментарий: </div>
					<div class="cart-box-form-value">
						<div class="cart-box-form-value-texarea">
							<textarea rows="7" placeholder="Текст (необязательно)" name="info"><?=(isset($_SESSION["b_info"]) ? $_SESSION["b_info"] : "" )?></textarea>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="login_form" style="float:none;">
				<iframe name="login_frame" style="display:none;"></iframe>
				<form method="POST" target="login_frame" action="/inc/login.php?login">
					<input id="login_email" name="login_email" type="text" placeholder="E-mail" />
					<input id="login_passw" name="login_passw" type="text" placeholder="passw" />
					<input id="event" name="event" type="hidden" value="afterLogged" />
					<input id="login_btn" name="login_btn" type="submit" value="Войти" />
				</form>
			</div>
			<div class="cart-box-nav">
				<a href="javascript:void(0);" onclick="hideShow('block2','block1');" class="goprev">Вернуться в корзину</a>
				<a href="javascript:void(0);" onclick="javascript:authorizeAndContinue()" class="goprev">Шаг 3</a>
				<input class="next" type="button" onclick="javascript:return check_required()" value="Отправить заказ"/>
			</div>
			<?
			break;
		case "getAvliableBlock":
			$blocks=array('#block1','#block2','#block3','#block4');
			$blockid=array_search($_POST["hash"],$blocks) ? (int) substr($_POST["hash"],strlen(($_POST["hash"]))-1) : 1;
			if($blockid>2 && !checkLogged()){
				$blockid=1;
			}
			if($blockid==4){
				$blockid=3;
			}
			$block="block".$blockid;
			echo $block;
			break;
			
		case "saveOrderInfo":
			echo "saveOrderInfo";
			foreach($_POST as $key=>$value){
				$value=prep($value);
			}
			echo $_POST['saveOrderInfo']; exit();
			$values=array();
			$fsql = mysql_query("SELECT * FROM clients_fields WHERE order_field != 0 ORDER BY showorder");
			while($f = mysql_fetch_assoc($fsql)){
				$values[]="".$f["code"]."=".(isset($_POST[$f["code"]]) ? $_POST[$f["code"]] : "");
			}
			$order_ins="INSERT INTO orders (create_date,status) VALUES (NOW(),3)";
			mysql_query($order_ins);
			$orderid=mysql_insert_id();
			$order_upd="UPDATE orders SET ".implode(",",$values)." WHERE id='$id'";
			mysql_query($order_upd);
			$_SESSION["order"]=execute_row_assoc("SELECT orders.* FROM orders WHERE id='$id'");
			break;
			
		case "updBaketValues":
			$basket_array = array();
			$values=json_decode(stripslashes($_POST["values"]));
			//print_r($values);
			foreach($values as $key=>$basketitem){
				$items=explode("->",$basketitem);
				$id=substr($items[0],2);
				$quantity=max((int)($items[1]) , 1);
				$basket_array[$id]=new BasketItem($quantity,"");
			}
			///print_r($basket_array);
			//print_r(unserialize($_SESSION["basket"]));
			$_SESSION["basket"] = serialize($basket_array);
			break;

		case "getBasketSum":
			$sum=getOrderSum();
			echo $sum." ".$currency_array[_DISPLAY_CURRENCY]["shortname"];
			break;
			
		case "use_card":
			$card_id=prepare($_POST["card_id"]);
			$card=isset($_SESSION["card"]) ? unserialize($_SESSION["card"]) : execute_row_assoc("SELECT site_cards.* FROM site_cards WHERE card_id='$card_id'");
			if(isset($card["id"]) && $card["status"]==1){
				mysql_query("UPDATE site_cards SET status=2 WHERE card_id='".$card["id"]."'");
				$_SESSION["card"]=serialize($card);
				//echo "Карта '$card_id' активирована. Сумма на карте - ".$card["price"]."";
				echo "<span class=\"use_text\">Использована карта: </span><span class=\"use_count\">".$card["price"]." ".$currency_array[_DISPLAY_CURRENCY]["shortname"]."</span>";
			}else{
				echo "<span class=\"use_text\">Карта '$card_id' не найдена или уже использована.</span>";
			}
			break;
			
		case "use_bonus":
			if(checkLogged()){
				$bonus=mysql_real_escape_string($_POST["bonus"]);
				$user=execute_row_assoc("SELECT clients.* FROM clients WHERE id='".$_SESSION["login_user"]["id"]."'");
				$_SESSION["bonus"]=max(min($bonus,$user["bonus"]),0);
				//$bonus_left=max($_SESSION["login_user"]["bonus"]-$_SESSION["bonus"],0);
				//$bonus_upd="UPDATE clients SET bonus=".$bonus_left." WHERE id='".$_SESSION["login_user"]["id"]."'";
				//mysql_query($bonus_upd);
				//$_SESSION["login_user"]=execute_row_assoc("SELECT clients.* FROM clients WHERE id='".$_SESSION["login_user"]["id"]."'");
				//echo "Использовано бонусов: ".$_SESSION["bonus"]." . Осталось бонусов: ".$_SESSION["login_user"]["bonus"];
				echo "<a href=\"/templates/basket/bonus-popup.php\" class=\"btn-bonus used\">(изменить)</a><span class=\"use_text\">Использовано бонусов: </span><span class=\"use_count\">".$_SESSION["bonus"]." ".$currency_array[_DISPLAY_CURRENCY]["shortname"]."</span>";
			}else{
				echo "Авторизируйтесь, чтобы использовать бонус!";
			}
			break;
	}
}
?>