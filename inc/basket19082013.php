
<?php
$title = "Ваш заказ";
function get_content()
{?>
<? 
	
	
	global $currency_array;	
	global $base_currency;
	global $catalog_array;
	$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
//	print_r($basket_array);
    $key = implode(",",array_keys($basket_array));
   $colors = $basket_array[$key]->params[0];
    $sizes = $basket_array[$key]->params[2];
//    exit;
	$isgoods = false;
if(count($basket_array) > 0 && !isset($_SESSION["complite"]))
{
    $sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,r1193.name as goods_color,r1194.name as goods_size,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat,exist_type.name as extype
        FROM goods
        LEFT JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
		LEFT JOIN r1193 ON r1193.id = goods.r1193
		LEFT JOIN r1194 ON r1194.id = goods.r1194
		LEFT JOIN exist_type ON exist_type.id = goods.exist_type
	WHERE goods.goodsid IN (".$key.") AND goods.r1193 = ".$colors." AND goods.r1194 = ".$sizes."";
    //echo $sql_text;exit;
	$sql = mysql_query($sql_text);
	if(mysql_num_rows($sql) > 0)
	{
		$isgoods = true;
	}
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

}
if(count($basket_array) > 0 && !isset($_SESSION["complite"]) && $isgoods)
{
		//Delivery *************************************************************************
		$current_delivery = isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : execute_scalar("SELECT id FROM delivery");
		//Delivery *************************************************************************
	
	include(_TEMPLATE."basket.php");
}
else
{
	if(!isset($_SESSION["complite"]))
	{
			echo html("basket_empty");	
	}	
}
if(isset($_SESSION["complite"]))
{

	if(isset($_SESSION["continue_payment"]))
	{
		$module = execute_scalar("SELECT module FROM paymenttype WHERE id = ".$_SESSION["continue_payment"]);
		if(file_exists($module))
		{
			include($module);
			if(function_exists("get_payment_form"))
			{
				get_payment_form();
			}
		}
	}
	else
	{
		html("basket_after_order");
	}	
	unset($_SESSION["complite"]);

}
	/*
	
	$isgoods = false;
	if(count($arr) > 0 && !isset($_SESSION["complite"]))
	{
		$sql = mysql_query("SELECT goods.*,exist_type.name as extype,brands.name as brandname ,brands.urlname as brandurl FROM goods 
		LEFT JOIN exist_type ON exist_type.id = goods.exist_type
		LEFT JOIN brands ON brands.id = goods.brand
		WHERE goods.id IN (".implode(",",array_keys($arr)).")");
		if(mysql_num_rows($sql) > 0)
		{
			$isgoods = true;
		}

	}
	if(count($arr) > 0 && !isset($_SESSION["complite"]) && $isgoods)
	{
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
        ?>
<div style="padding:0px 10px;">
        <form method='post' name="order" action='<?=_SITE?>basket/recalculate'>
        <table class="baskett" cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr style="">
                <td width="35%" class="td_bask"><label>Товар</label></td>
				<td width="10%" class="td_bask"><label>Наличие</label></td>
                <td width="20%" class="td_bask"><label>Цена</label></td>
                <td width="10%" class="td_bask"><label>Кол-во</label></td>
                <td width="15%" class="td_bask"><label>Сумма</label></td>
                <td width="10%" class="td_bask"><label>Удалить</label></td>
            </tr>
        <?
       
        while($r = mysql_fetch_assoc($sql))
        {
            $q = $arr[$r["id"]]->q;
            foreach($sum_array as $current)
            {
				$summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"])*$q;
				$base_summa[$current] += get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"] ,$current,$r["id"],$r["currency"],true)*$q;
            }
			
            ?>
            <tr class="basket_tr" style="text-align:center;">
               
				<td style="padding-bottom:6px;" class="bask_fields">
                <?
				$tovarid = $r["id"];
				$img = "";
				$tovar_name = $r["brandname"]." ".$r["name"];
				$tovar_url = get_product_url($r);			
				if($r["goodsid"] != "0")
				{
					$tovarid = $r["goodsid"];
					$img = get_main_image($r["goodsid"],80,3);
					if(setting("basket_vname") == "1")
					{
						$tovar_name = execute_scalar("SELECT name FROM goods WHERE id = ".$r["goodsid"])." ".$r["name"];		
					}
					$tovar_url = get_product_url(execute_row_assoc("SELECT goods.id,goods.parentid,goods.urlname,brands.urlname as brandurl FROM goods LEFT JOIN brands ON brands.id = goods.brand"));
                }
				else
				{
					$tovarid = $r["id"];
					$img = get_main_image($r["id"],80,3);
				}

                ?>
				<div style="text-align:center; margin-top: 5px;"><a href="<?=$tovar_url?>"><img alt="" src="<?=$img?>" /></a></div>
				<h3><a href="<?=$tovar_url?>"><?=$tovar_name?></a></h3> 
				<? if(setting("basket_show_params") == 1) { ?>
				<p >
				<?
		$fields_sql = mysql_query("SELECT f.name,f.table_name,f.field_type,f.rname,f.title FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL AND ((cf.inlist = 1 AND cf.categoryid = '$r[parentid]') OR f.isgeneral = 1)  AND f.field_type != 5
    ORDER BY cf.showorder");
		while($frow = mysql_fetch_assoc($fields_sql))
		{
			echo "<div style='font-size:11px;'>".$frow["title"].":&nbsp;"; 
			switch($frow["field_type"])
				{
					case "2":
						echo "<b>".execute_scalar("SELECT name FROM $frow[table_name] WHERE id =  ".$r[$frow["rname"]])."</b>";
						break;
				}
			echo "</div>&nbsp;";	
		}
		?>
				</p>
				<? } ?>
				</td>
				 <td class="bask_fields">
					<label ><?=($r["extype"] != "" ? $r["extype"] : execute_scalar("SELECT name FROM exist_type WHERE id = 1"))?></label>
				</td>
                <td class="bask_fields">
<strong><?=get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false)?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></strong>
				</td>
                <td class="bask_fields"><input align="left" name="q_<?=$r["id"]?>"type="text" value="<?=$q?>" style="width:50px;" /></td>
                <td class="bask_fields">              
<strong><?=get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false)*$q?>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></strong>
                </td>
                <td class="bask_fields" align="center">
               <a class="rm_link" href="<?=_SITE?>basket/remove/<?=$r["id"]?>" onclick="javascript:return confirm('Удалить товар из заказа ?');">Удалить</a>
                </td>
            </tr>
            <?
        }
		?>
		<tr>			 
            <td></td>
            <td colspan="3" ><b>Итого по товарам:</b></td>
            <td style="text-align:center;" >
<strong><?=((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])?></strong>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></td>
            <td style=""></td>
        </tr>
		<?
	
		$discount_summa = 0;
		$discount = 0;
		//Discount  ************************************************************************	
	if(setting("discount_mode") == 1 || setting("discount_mode") == 3 || !isset($_SESSION["login_user"])) {		
		$discount = get_discount($base_summa[$base_currency]);
		if($discount > 0)
		{
			$discount_summa = number_format((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY]*$discount/100,setting("price_digit"), '.', '');
			?>

			 
			 <tr>

            <td></td>
 
            <td colspan="3" ><b>Скидка (<?=$discount?>%):</b></td>

            <td style="text-align:center;" >
<strong>-&nbsp;<?=$discount_summa?></strong>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></td>

            <td  style=""></td>

        </tr>
			<? }
		}		
	
		//Discount  ************************************************************************			
		
		//Delivery *************************************************************************
		$current_delivery = isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : execute_scalar("SELECT id FROM delivery");
		$delivery_summ = ceil(get_delivery_summ(((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY]),$current_delivery));
		//Delivery *************************************************************************
		?>	
 <tr>
            <td ></td>
            <td colspan="3"> <b>Доставка:</b>
 <select name="delivery" onchange="document.forms['order'].submit();" style="margin:0px 0px 0px 10px; width:110px;">
		<?
		  $delivery = mysql_query("SELECT * FROM delivery");
		while($dtype = mysql_fetch_assoc($delivery))
				{
					?>
					<option   <?=($current_delivery == $dtype["id"] ? "SELECTED" : "" )?> value="<?=$dtype["id"]?>"><?=$dtype["name"]?></option>
					<?
				}
				?>
		</select></td>
            <td style="text-align:center;" >
<strong><?=$delivery_summ?></strong>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></td>
            <td  style=""></td>
        </tr>		
        <tr>
            <td style=""></td>
            <td colspan="3" ><b>Итого к оплате:</b></td>
            <td style="text-align:center;" >
<strong><?=(((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $summa[_DISPLAY_CURRENCY])+$delivery_summ-$discount_summa)?></strong>&nbsp;<?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?>
<input type="hidden" name="discount" value="<?=$discount?>" />
<input type="hidden" name="hdelivery" value="<?=$delivery_summ?>" />
</td>
            <td  style=""></td>
        </tr>
        </table>
		
        <table  width="100%" cellpadding="2"><tr><td align="center">      
			<input class="buttons" style="margin-top:5px;" type="submit" value="Пересчитать"  />&nbsp;
      
		<?
		if(isset($_SESSION["url"]))
		{
			?>
			<input  class="buttons" style="margin-top:5px;" onclick="document.location.href = '<?=$_SESSION["url"]?>';" type="button" value="К покупкам"  />
			
			<?
		}
		?>
		</td></tr>
        </table>
		








        <div style="margin-top:10px; padding:0px 10px 10px 10px; color:#8C1F36;"><strong>Для оформления заказа заполните Ваши данные:</strong></div>
		<script language="JavaScript">
		var required_array = new Array;
		var ri = 0;
		</script>
		<table width="400"  class="detailst" border="0" cellpadding="2" align="center">
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
				Дополнительная  информация по заказу:
				<textarea class="ibox" rows="5" style="width:300px;margin-top:3px;" name="info"><?=(isset($_SESSION["b_info"]) ? $_SESSION["b_info"] : "" )?></textarea>
                </td>
			</tr>
			<tr>
				<td colspan="2" ><sup style="color:red;">*</sup> - поля обязательны для заполнения</td>
			</tr>
			<tr><td colspan="2" align="center" style="padding-top:10px;">
            <input style="text-align:center;" class="buttons" onclick="javascript:return check_required()"  type="submit" value="Заказать"  />
            </td></tr>
		</table>
		</div>

		<? if(setting("paymenttype_visible") !="1") { ?>
		<input type="hidden" name="paymenttype" value="0" />
		<? } ?>
        </form>
	</div>
        <?
	}
	else
	{
		if(!isset($_SESSION["complite"]))
		{
				echo html("basket_empty");	
		}	
	}
	if(isset($_SESSION["complite"]))
	{

		if(isset($_SESSION["continue_payment"]))
		{
			$module = execute_scalar("SELECT module FROM paymenttype WHERE id = ".$_SESSION["continue_payment"]);
			if(file_exists($module))
			{
				include($module);
				if(function_exists("get_payment_form"))
				{
					get_payment_form();
				}
			}
		}
		else
		{
			html("basket_after_order");
		}	
		unset($_SESSION["complite"]);

	}
	?>
		
	<?
	*/

}
?>