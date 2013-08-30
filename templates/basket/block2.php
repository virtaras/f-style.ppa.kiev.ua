<div id="block2" style="display: none;"> 
	<script language="JavaScript">
		var required_array = new Array;
		var ri = 0;
		</script>
	<div class="cart-box-title"> ЛИЧНЫЕ ДАННЫЕ <!--<a href="#" class="gonext">Отправить заказ</a>--> </div>
	<div class="cart-box-form" style="width: 500px; float:left">
    <p style="font-size:18px; font-family: 'officinasansmediumcregular'; color: #2f2f2f; margin: 20px">Первый заказ?</p>
    <p style="font-size:12px; font-family: 'officinasansmediumcregular'; color: #2f2f2f; margin: 0 0 30px 20px">Заполните форму ниже, это займет не более 30 секунд. <br /> Данныйе будут сохранены для последующих заказов.</p>
		<iframe name="register_frame" style="display:none;"></iframe>
		<form method="POST" target="register_frame" action="/templates/basket/register.php" name="register-form">
		<?
			$fsql = mysql_query("SELECT * FROM order_fields ORDER BY showorder");
			//$fsql = mysql_query("SELECT * FROM clients_fields WHERE order_field != 0 ORDER BY showorder");
			while($f = mysql_fetch_assoc($fsql))
			{
				$client_field=execute_row_assoc("SELECT clients_fields.* FROM clients_fields WHERE order_field='".$f['id']."'");
				//$value=isset($_SESSION["login_user"][$client_field["code"]]) ? $_SESSION["login_user"][$client_field["code"]] : (isset($_SESSION["b_" . $f["code"]]) ? $_SESSION["b_" . $f["code"]] : "");
				$value=(isset($_SESSION["b_" . $f["code"]]) && $_SESSION["b_" . $f["code"]]!="") ? $_SESSION["b_" . $f["code"]] : (isset($_SESSION["login_user"][$client_field["code"]]) ? $_SESSION["login_user"][$client_field["code"]] : "");?>
				<script language="JavaScript">
					order_fields.push('<?=$f["code"]?>');
				</script>
				<div class="cart-box-form-item">
					<div class="cart-box-form-title">
						<?=$f["title"]?>
					</div>
					<div class="cart-box-form-value">
						<div class="cart-box-form-value-input">
							<input id="<?=$f["code"]?>" name="<?=$f["code"]?>" value="<?=$value?>" type="text" onkeyup="javascript:setSessionKeyValue('b_'+'<?=$f["code"]?>',this.value);" />
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
		<div class="cart-box-form-title"> E-mail: </div>
		<div class="cart-box-form-item">
			<div class="cart-box-form-value">
				<div class="cart-box-form-value-input">
					<input id="email" name="email" value="<?=(!empty($_SESSION["b_email"]) ? $_SESSION["b_email"] : (isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["email"] : ""))?>" type="text"/>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="cart-box-form-item">
			<div class="cart-box-form-title"> Комментарий: </div>
			<div class="cart-box-form-value">
				<div class="cart-box-form-value-texarea">
					<textarea rows="7" placeholder="Текст (необязательно)" name="info" onkeyup="javascript:setSessionKeyValue('b_info',this.value);"><?=(isset($_SESSION["b_info"]) ? $_SESSION["b_info"] : "" )?></textarea>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<input type="hidden" name="action" value="register" />
		</form>
	
    </div>
    <div style=" display: inline-block; width: 420px;">
    
    <p style="font-size:18px; font-family: 'officinasansmediumcregular'; color: #2f2f2f; margin: 20px">Постоянный клиент?</p>
    <p style="font-size:12px; font-family: 'officinasansmediumcregular'; color: #2f2f2f; margin: 0 0 50px 20px">Авторизируйтесь, введя стой e-mail и пароль</p>
    <div style="border: 1px solid #CCC; height: 310px">
	<div class="login_form" style="float:none; margin: 60px; padding-bottom: 45px">
		<?if(!checkLogged()){?>
       <p style="font-size:18px; font-family: 'officinasansmediumcregular'; color: #2f2f2f; margin: 20px 0">АВТОРИЗАЦИЯ</p>
		<iframe name="login_frame" style="display:none;"></iframe>
		<form method="POST" target="login_frame" action="/templates/basket/login.php?login" name="login-form">
			<input id="login_email" name="login_email" type="text" placeholder="E-mail" />
			<input id="login_passw" name="login_passw" type="text" placeholder="passw" />
			<input id="event" name="event" type="hidden" value="afterLogged" />
               <a href="" class="forgot_pass" style="display:block; float:left; width: 150px; margin: 5px 60px 0 20px; color: #999; text-decoration:none">Забыли пароль?</a>
			<input style="margin: 0px" id="login_btn" name="login_btn" type="submit" value="Войти" />
		</form>
		<?}else{?>
			Ви увійшли як <?=$_SESSION["login_user"]["email"]?>
		<?}?>
	</div>
    </div>
    </div>
    <div class="clear"></div>
	<div class="cart-box-nav">
		<a href="javascript:void(0);" onclick="showBlock('block1');" class="goprev">Назад</a>
		<a href="javascript:authorizeAndContinue();" class="newnext">Далее</a>
		<!--<input class="next" type="button" onclick="javascript:return check_required()" value="Отправить заказ"/>-->
	</div>
</div>
