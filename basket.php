<?php
session_start();
ini_set("display_errors","On");
include("inc/constant.php");
include("inc/global.php");
include("inc/emarket.php");
include("inc/engine.php");
require("virtaras/functions.php");
function set_basket_cookie()
{
	setcookie("basket",$_SESSION["basket"],(time()+60*60*24*30),"/");
}
function basket_add($id)
{
	$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :  array();
	if(isset($arr[$id]))
	{
		$arr[$id]->q = $arr[$id]->q + (isset($_GET["q"]) ? (int)$_GET["q"] : 1);
        $arr[$id]->params = (isset($_GET["params"]) ? $_GET["params"] : '');
	}
	else
	{
		$arr[$id] = new BasketItem(isset($_GET["q"]) ? (int)$_GET["q"] : 1,isset($_GET["params"]) ? $_GET["params"] : "");
	}
	$_SESSION["basket"] = serialize($arr);
	set_basket_cookie();
}
if(isset($_GET["add"]))
{
//    echo  $_SERVER['REQUEST_URI']."<br>"; print_r($_GET);exit;
    basket_add($_GET["add"]);
	include("inc/connection.php");
	include(_DIR._TEMPLATE."cart_popup.php");
}
else if (isset($_GET["recalculate"]))
{
    require("inc/connection.php");
	$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	foreach(array_keys($arr) as $current)
	{
		if(!is_numeric($_POST["q_".$current]))
		{
			$_POST["q_".$current] = 1;
		}
		if($_POST["q_".$current] < 0)
		{
			$_POST["q_".$current] = $_POST["q_".$current] * (-1);
		}
		if($_POST["q_".$current] == 0)
		{
			unset($arr[$current]);
			continue;
		}
		
		$arr[$current]->q = $_POST["q_".$current];
	}
	$_SESSION["basket"] = serialize($arr);
	
	$_SESSION["b_paymenttype"] = $_POST["paymenttype"];
	$_SESSION["b_delivery"] = $_POST["delivery"];
	$_SESSION["b_info"] = $_POST["info"];
	$_SESSION["b_email"] = $_POST["email"];
	
	$fsql = mysql_query("SELECT code FROM order_fields ORDER BY showorder");
	while($f = mysql_fetch_assoc($fsql))
	{
		$_SESSION["b_".$f["code"]] = $_POST[$f["code"]];
	}
	set_basket_cookie();
	
	header("Location: "._SITE."cart/");
}
else if(isset($_GET["create"]))
{
	require("inc/connection.php");
	$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	if(count($arr) == 0)
	{
		unset($_SESSION["basket"]);
		setcookie("basket",$_SESSION["basket"],(time()-60*60*24*30),"/");
		$_SESSION["complite"] = true;
		header("Location: "._SITE."cart/");
		exit();
	}
	$values = array();
	$tvalues = array();
	$fsql = mysql_query("SELECT title,code FROM order_fields ORDER BY showorder");
	$values["email"] = "'".htmlspecialchars(stripslashes(strip_tags($_POST["email"])),ENT_QUOTES)."'";
	$values["info"] = "'".htmlspecialchars(stripslashes(strip_tags($_POST["info"])),ENT_QUOTES)."'";
	while($f = mysql_fetch_assoc($fsql))
	{
		$values[$f["code"]] = "'".htmlspecialchars(stripslashes(strip_tags($_POST[$f["code"]])),ENT_QUOTES)."'";
		$tvalues[$f["title"]] = "'".htmlspecialchars(stripslashes(strip_tags($_POST[$f["code"]])),ENT_QUOTES)."'";
	}	
	
	mysql_query("INSERT INTO orders (create_date,ip,status,".implode(",",array_keys($values)).",userid,paymenttype,deliverytype,discount) VALUES (now(),'$_SERVER[REMOTE_ADDR]',1,".implode(",",$values).",'".(isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["id"] : "-1")."','$_POST[paymenttype]','$_POST[delivery]','$_POST[discount]')");

	
	$id = get_last_id("orders");
	$order_title = "Заказ № $id от ".date("d.m.Y H:i:s");
	$html = "
	<h3>$order_title</h3>
	<table>
		<tr>
			<td>E-Mail:</td>
			<td><b>$_POST[email]</b></td>
		</tr>";
		foreach($tvalues as $key=>$value)
		{
			if(trim($value) != "")
			{
				$html .= "
				<tr>
					<td>$key</td>
					<td><b>".str_replace("'","",htmlspecialchars_decode($value,ENT_QUOTES))."</b></td>
				</tr>";
			}
		}
		$html .="<tr>
			<td>Дополнительно:</td>
			<td><b>$_POST[info]</b></td>
		</tr>
		<tr>
			<td>Тип доставки:</td>
			<td><b>".//execute_scalar("SELECT name FROM delivery WHERE id = '$_POST[delivery]'").
                "</b></td>
		</tr>
		<tr>
			<td>Форма оплаты:</td>
			<td><b>".//execute_scalar("SELECT name FROM paymenttype WHERE id = '$_POST[paymenttype]'").
                "</b></td>
		</tr>
	</table>
	<hr /> 
	<table >
		<tr style='font-weight:bold;background-color:#DDDDDD;'>
			<td>Товар</td>
			<td style='text-align:center;'>Кол-во</td>
			<td style='text-align:center;'>Цена</td>
			<td style='text-align:center;'>Сума</td>
		</tr>
	";
	if(count($arr) > 0 )
	{
		$base_currency = setting("base_currency");
        $s = 0;
		$bs = 0;
        $sql = mysql_query("SELECT goods.id,goods.name,goods.price,goods.price_action,goods.currency,goods.goodsid,brands.name as brandname
        FROM goods
		LEFT JOIN brands ON brands.id = goods.brand
		WHERE goods.id IN (".implode(",",array_keys($arr)).")");
		while($row = mysql_fetch_assoc($sql))
		{
			
			$arr[$row["id"]]->q = isset($_POST["q_".$row["id"]]) ? intval($_POST["q_".$row["id"]]) : $arr[$row["id"]]->q;

			if($arr[$row["id"]]->q == 0)
			{
				continue;
			}
			
			// Base price
			$base_price = get_price($row["price_action"] > 0 ? $row["price_action"] : $row["price"],$base_currency,$row["id"],$row["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false);	
			//End Of Base Price
			

            $s = $s + $base_price*$arr[$row["id"]]->q;
			$bs = $bs + get_price($row["price_action"] > 0 ? $row["price_action"] : $row["price"],$base_currency,$row["id"],$row["currency"],true);	
			$tovar_name = $row["brandname"]." ".$row["name"];
			$varinat_name = "";
			if($row["goodsid"] != 0)
			{
				$tovar_name = execute_scalar("SELECT name FROM goods WHERE id = ".$row["goodsid"]);
				$varinat_name = $row["name"];
			}
			
			mysql_query("INSERT INTO ordersrow (parentid,goodsid,quantity,goodsname,vname,price) VALUES ('$id','$row[id]','".$arr[$row["id"]]->q."','$tovar_name','$varinat_name','$base_price')");
			
			if(setting("basket_vname") == "1")
			{
				$tovar_name = execute_scalar("SELECT name FROM goods WHERE id = ".$row["goodsid"])." ".$row["name"];		
			}
			
			
            $html .= "
			<tr>
			<td width='300px'>$tovar_name</td>
			<td width='50px' style='text-align:center;'>".$arr[$row["id"]]->q."</td>
			<td width='100px' style='text-align:center;'>".$base_price." ".$currency_array[$base_currency]["shortname"]."</td>
			<td width='100px' style='text-align:center;'>".($base_price*$arr[$row["id"]]->q)." ".$currency_array[$base_currency]["shortname"]." </td>
		</tr>
			";
			
		}

		//****** discount ************************************************			
		if(setting("discount_mode") == 1 || setting("discount_mode") == 3 || !isset($_SESSION["login_user"])) 		{					
			$discount = $_POST["discount"];
			$sum_discount = ((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $bs : $s)*$discount/100;
			
			mysql_query("UPDATE orders SET discount = '$discount' WHERE id = '$id'");
			if($discount > 0)
			{
				$html .= "
				<tr style='font-weight:bold;'>
				<td ></td>
				<td width='50px' style='text-align:center;'></td>
				<td width='100px' style='text-align:center;'>Итого:</td>
				<td width='100px' style='text-align:center;' nowrap><b>$s ".$currency_array[$base_currency]["shortname"]."</b></td>
			</tr>";
				$s = $s - $sum_discount;
				$html .= "
				<tr style='font-weight:bold;'>
				<td ></td>
				<td width='50px' style='text-align:center;'></td>
				<td width='100px' style='text-align:center;'>Скидка:</td>
				<td width='100px' style='text-align:center;' nowrap><b>".number_format($discount,2,".","")." % ($sum_discount ".$currency_array[$base_currency]["shortname"].")</b></td>
			</tr>";		
			}
		}
		//****** discount ************************************************
		//****** delivery *****************************
		
			//$delivery_summ = $_POST["hdelivery"];
			$delivery_summ = execute_scalar("SELECT price FROM delivery WHERE id='".prepare($_POST["delivery"])."'");
		$html .= "<tr style='font-weight:bold;background-color:#DDDDDD;'>
			<td ></td>
			<td width='50px' style='text-align:center;'></td>
			<td width='100px' style='text-align:center;'>Доставка:</td>
			<td width='100px' style='text-align:center;' nowrap><b>$delivery_summ ".$currency_array[$base_currency]["shortname"]."</b></td>
		</tr>";
	
		//********************************************
		$html .= "<tr style='font-weight:bold;background-color:#DDDDDD;'>
			<td ></td>
			<td width='50px' style='text-align:center;'></td>
			<td width='100px' style='text-align:center;'>К оплате:</td>
			<td width='100px' style='text-align:center;' nowrap><b>".($s+$delivery_summ)." ".$currency_array[$base_currency]["shortname"]."</b></td>
		</tr>
		</table><hr /> ";
		
		mysql_query("UPDATE orders SET deliverysumm = '$delivery_summ' WHERE id = $id");
		/*
		mail(execute_scalar("SELECT val FROM settings WHERE name = 'contact_email'"),"Новый заказ с сайта ".$_SERVER['HTTP_HOST'],$html,'Content-type: text/html; charset=utf-8'. "\r\n". 'X-Mailer: PHP/' . phpversion()."\r\n"."From: ".$_SERVER['HTTP_HOST']." <no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']).">");
		*/
		send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",setting("contact_email"), 						"UTF-8", "UTF-8", "Новый заказ с сайта ".$_SERVER['HTTP_HOST'], $html );
	if(check_email($_POST["email"]))
	{
		
		if(execute_scalar("SELECT count(*) FROM emailsdb WHERE email = '".$_POST["email"]."'") == 0)
		{
			mysql_query("INSERT INTO emailsdb (email) VALUES ('".$_POST["email"]."')");
		}
		/*
		mail($_POST["email"],"Информация о заказе на сайте ".$_SERVER['HTTP_HOST'],$html,'Content-type: text/html; charset=utf-8'. "\r\n". 'X-Mailer: PHP/' . phpversion()."\r\n".
														 "From: ".$_SERVER['HTTP_HOST']." <no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']).">"); */
														 
		send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$_POST["email"], 						"UTF-8", "UTF-8", "Информация о Вашем заказе на сайте ".$_SERVER['HTTP_HOST'], $html );												 
	}	
	}
	unset($_SESSION["basket"]);
	setcookie("basket",0,(time()-60*60*24*30),"/");
	$_SESSION["complite"] = true;
	
//	if(execute_scalar("SELECT is_online_payment FROM paymenttype WHERE id = $_POST[paymenttype]") == "1")
//	{
//		$_SESSION["continue_payment"] = $_POST["paymenttype"];
//		$_SESSION["last_order_summa"] = $s+$delivery_summ;
//		$_SESSION["last_order_title"] = $order_title;
//		$_SESSION["last_order_id"] = $id;
//	}
	
	header("Location: "._SITE."cart/");
	exit();
}
else if(isset($_GET["rm"]))
{
	$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	unset($arr[$_GET["rm"]]);
	$_SESSION["basket"] = serialize($arr);
	set_basket_cookie();
	header("Location: "._SITE."cart/");
}
else
{
	if(isset($_SESSION["url"]))
	{
		header("Location: ".$_SESSION["url"]);
	}
	else
	{
		header("Location: "._SITE);
	}
}	
?>