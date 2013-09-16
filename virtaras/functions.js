function afterAddToBasket(res){
	//alert(res);
}
function addToBasket(id,quantity,params){
	id = id || -1;
	quantity = quantity || 1;
	params = params || "";
	if(id!=-1){
		$.post("/virtaras/ajax.php",{action:"addToBasket",id:id,quantity:quantity,params:params},afterAddToBasket);
	}else{
		alert("INVALID_ID");
	}
}
function afterRemoveFromBasket(res){
	//alert(res);
}
function removeFromBasket(id){
	$.post("/virtaras/ajax.php",{action:"removeFromBasket",id:id},afterRemoveFromBasket);
}
function afterSetSessionKeyValue(res){
	//alert(res);
}
function setSessionKeyValue(key,value){
	$.post("/virtaras/ajax.php",{action:"setSessionKeyValue",key:key,value:value},afterSetSessionKeyValue);
}
function showAlert(msg){
	alert(msg);
}
function clear_basket(){
	$.post("/virtaras/ajax.php",{action:"clear_basket"},function(r){document.location.reload(true);});
}

function load_ajax_info(){
	show_cart();
	showIzbrannoe();
	//show_otlozhenie();
}
///////////Otlozhenie
function after_add_otlozhenie(res){
	show_otlozhenie();
}
function add_otlozhenie(goodsid){
	$.post("/virtaras/ajax.php",{action:"add_otlozhenie",goodsid:goodsid},after_add_otlozhenie);
}
function after_show_otlozhenie(res){
	$(".otlozhenie-box").html(res);
}
function show_otlozhenie(){
	$.post("/virtaras/ajax.php",{action:"show_otlozhenie"},after_show_otlozhenie);
}
function after_remove_otlozhenie(res){
	show_otlozhenie();
}
function remove_otlozhenie(goodsid){
	$.post("/virtaras/ajax.php",{action:"remove_otlozhenie",goodsid:goodsid},after_remove_otlozhenie);
}
///////////!Otlozhenie