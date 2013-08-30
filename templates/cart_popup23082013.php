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

    	<div></div>

    <table bgcolor='' cellpadding='1' cellspacing='1' border='0' bordercolor='' align='center' width='780px'>
      <tr style="color:#955b44" height='35' align=center>
        <td>ФОТО</td>
        <td>ТОВАР</td>
        <td>ЦЕНА</td>
        <td>КОЛ-ВО</td>
        <td>ИТОГО</td>
        <td>УДАЛИТЬ</td>
     </tr>
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
		$sql = mysql_query("SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
				IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
				(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
				(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat,exist_type.name as extype
			FROM goods
			LEFT JOIN catalog ON catalog.id = goods.parentid
			LEFT JOIN brands ON brands.id = goods.brand
			LEFT JOIN exist_type ON exist_type.id = goods.exist_type
		WHERE goods.id IN (".implode(",",array_keys($basket_array)).")");
		while($r = mysql_fetch_assoc($sql))
		{
			$q = $basket_array[$r["id"]]->q;
            foreach($sum_array as $current)
            {
				$summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"])*$q;
				$base_summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"],true)*$q;
            }
				$main_img = getMainImage($r["id"],3);
				$img = getResizeImageById($main_img["id"],"h",array("height"=>"85"),$main_img["format"]);

			?>
  <tr height='113' align=center>
        <td><img src="<?=$img?>" alt=""  /></td>
        <td><?=$r["name"]?></td>
        <td><?=get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false)?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></td>
        <td><input class="numbers" type="text" name="cq<?=$r["id"]?>" id="cq<?=$r["id"]?>" value="<?=$q?>" style="color: #999;" onkeyup="scq(<?=$r["id"]?>)"
             onfocus="if (this.value == '0') {this.value = ''; this.style.color = '#000';}" onblur="if (this.value == '') {this.value = '0'; this.style.color = '#777';}" />
        </td>
        <td><?=get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false)*$q?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></td>
        <td><a href="javascript:void(0);" onclick="rc(<?=$r["id"]?>)">del</a> </td>
  </tr>
			<?
		}
	}
	 ?>

	</table>


    <ul class="total">
        <li><p>итого: <span><?=((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></span></p></li>
        <li><a href="/cart"><img src="/templates/images/imageBasket/basketbottom1.png" alt="" width="164" height="32" />
        	<p>оформить заказ</p></a></li>
        <li><a href="javascript:void(0);" onclick="	$('#to_basket').dialog('close');"><img src="/templates/images/imageBasket/basketbottom2.png" alt="" width="208" height="32" />
        	<p class="continue">продолжить покупки</p></a></li>
    </ul>




</div>