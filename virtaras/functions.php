<?
function getRowsFromDB($sql){
	$result=array();
	$query=mysql_query($sql);
	while($row=mysql_fetch_assoc($query)){
		$result[]=$row;
	}
	return $result;
}
function prepare($str){
	return mysql_real_escape_string(strip_tags(trim($str)));
}
function prepareArray($arr){
	foreach($arr as $key=>$value){
		if(!is_array($arr[$key])) $arr[$key]=prepare($value);
	}
	return $arr;
}
function inArray($item,$array){
	foreach($array as $arritem){
		if(serialize($item)==serialize($arritem))
			return true;
	}
	return false;
}
////////////ENGINE
function addToBasket($id,$quantity=1,$params=""){
	if($id>0 && $quantity>0){
		$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
		$basket_array[$id]=new BasketItem($quantity,$params);
		$_SESSION["basket"] = serialize($basket_array);
		return true;
	}
	return false;
}
function removeFromBasket($id){
	if($id>0){
		$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
		unset($basket_array[$id]);
		$_SESSION["basket"] = serialize($basket_array);
		return true;
	}
	return false;
}
function get_goods_filters($good,$filtercode,$goods_where=""){
	$filters=array();
	$goodsid=($good['goodsid']) ? $good['goodsid'] : $good['id'];
	$filters_sql="SELECT F.* FROM `$filtercode` F WHERE EXISTS (SELECT * FROM `goods` WHERE `goodsid`='$goodsid' AND `$filtercode`=F.id $goods_where)";
	return getRowsFromDB($filters_sql);
}
function checkLogged(){
	return (bool)(isset($_SESSION["login_user"]["id"]) && ($_SESSION["login_user"]["id"]>0));
}
function getTovarByFieldsFromArr($goods,$fields){
	foreach($goods as $good){
		$found=true;
		foreach($fields as $key=>$value){
			if($good[$key]!=$value) $found=false;
		}
		if($found)
			return $good;
	}
	return false;
}
function getOrderSum(){
	$sum=0;
	$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
	$key = implode(",",array_keys($basket_array));
	$sql_text = "SELECT goods.* FROM goods WHERE goods.id IN (".$key.")";
	$sql=mysql_query($sql_text);
	while($tovar = mysql_fetch_assoc($sql)){
		$price = get_price($tovar["price_action"] > 0 ? $tovar["price_action"] : $tovar["price"],_DISPLAY_CURRENCY,$tovar["goodsid"],$tovar["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false);
		$quantity = $basket_array[$tovar["id"]]->q;
		$full_price = $price*$quantity;
		$sum+=$full_price;
	}
	if(isset($_SESSION["b_delivery"])){
		$sum+=execute_scalar("SELECT price FROM delivery WHERE id='".$_SESSION["b_delivery"]."'");
	}
	if(isset($_SESSION["card"])){
		$card=unserialize($_SESSION["card"]);
		$sum-=$card["price"];
	}
	if(isset($_SESSION["bonus"]) && checkLogged()){
		$user=execute_row_assoc("SELECT clients.* FROM clients WHERE id='".$_SESSION["login_user"]["id"]."'");
		$bonus=max(min($_SESSION["bonus"],$user["bonus"]),0);
		$_SESSION["bonus"]=$bonus;
		$sum-=$bonus;
	}
	$sum=max($sum,0);
	return $sum;
}
////////////ENGINE