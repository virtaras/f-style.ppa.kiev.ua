
<?php
$title = "Ваш заказ";
function get_content()
{?>
<? 
	
	
	global $currency_array;	
	global $base_currency;
	global $catalog_array;
	$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	//print_r($basket_array);
    $key = implode(",",array_keys($basket_array));
//    exit;
	$isgoods = false;
if(count($basket_array) > 0 && !isset($_SESSION["complite"]))
{
    $sql_text = "SELECT goods.* FROM goods WHERE goods.id IN (".$key.")";
    //echo $sql_text;
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
	unset($_SESSION["complite"]);
	unset($_SESSION["bonus"]);
	unset($_SESSION["card"]);
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
}

}
?>