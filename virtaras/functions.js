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
/*
function afterGetIdByGoodsidFilters(res){
	console.log(res,'asdf');
	return res;
}
function getIdByGoodsidFilters(goodsid,filters){
	var id=-1;
	$.post("/virtaras/ajax.php",{action:"getIdByGoodsidFilters",filters:filters},
		function(res){afterGetIdByGoodsidFilters(res);variable=2;});
		
	$.when($.ajax("/virtaras/ajax.php")).then(function(data, textStatus, jqXHR){
		alert( jqXHR.status ); // alerts 200
		id=data;
		return id;
	});
//	$.ajax({
//		type: "GET",
//		url: "test.js",
//		dataType: "script"
//	});
}*/