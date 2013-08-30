var order_fields = new Array;
var order_values = new Array;
function authorizeAndContinue(){
	if(logged==false){
		var rarray = new Array ("email");
		var err_count = 0;
		for(i = 0;i < rarray.length;i++)
		{
			if(TrimString(document.getElementById(rarray[i]).value) == "")
			{
				$("#"+rarray[i]).css('border-color',"red");
				err_count++;
			}
		}
		for(i = 0;i < required_array.length;i++)
		{
			if(TrimString(document.getElementById(required_array[i]).value) == "")
			{
				$("#"+required_array[i]).css('border-color',"red");
				err_count++;
				
			}
		}
		if(!isValidEmail (document.getElementById('email').value,false))
		{
			showAlert("Введите корректный E-Mail !");
			return false;
		}
		
		if(err_count > 0)
		{
			showAlert("Необходимо заполнить все обязательные поля !");
			return false;
		}else{
			document.forms['register-form'].submit();
		}
	}else{
		//document.forms['register-form'].submit();
		afterLogged();
	}
    return true;
}
function afterSavedStep(res){
	/*order_values=[];
	for(var order_field in order_fields){
		order_values.push(order_fields[order_field]+'+'+$("#"+order_fields[order_field]).val());
	}
	showBlock('block3');*/
}
function afterLogged(){
	//$.post("/templates/basket/ajax.php",{action:"saveOrderInfo",order_fields:JSON.stringify(order_values)},afterSavedStep);
	showBlock('block3');
}

window['blocks']=['block1','block2','block3','block4'];
window['menus']=['menu-block1','menu-block2','menu-block3','menu-block4'];
function afterGetBlock(res){
	if(array_search(res,blocks)){
		showBlock(res);
	}else{
		showBlock("block1");
	}
}

function handleBasketLoad(){
	var hash=window.location.hash;
	$.post('/templates/basket/ajax.php',{action:'getAvliableBlock',hash:hash},afterGetBlock);
}

function showBlock(blockid){
	for(var block in blocks){
		top.$('#'+blocks[block]).hide();
	}
	top.$('#'+blockid).show();
	for(var menu in menus){
		top.$('#'+menus[menu]).removeClass('active');
	}
	top.$('#menu-'+blockid).addClass('active');
	slideToBasket();
	window.location.hash='#'+blockid;
}

top.addEventListener('load', handleBasketLoad, false );

function array_search( needle, haystack, strict ) {
	var strict = !!strict;
	for(var key in haystack){
		if( (strict && haystack[key] === needle) || (!strict && haystack[key] == needle) ){
			return key;
		}
	}
	return false;
}

function afterConfirmBonus(res){
	showAlert(res);
}
function confirmBonus(){
	var bonus=$("#use_bonus").val();
	$.post("/templates/basket/ajax.php",{action:"use_bonus",bonus:bonus},afterConfirmBonus);
}
function slideToBasket(){
	top.$("html, body").animate({ scrollTop: top.$('.cart-box').offset().top }, 200);
}
function afterRegistered(){
	showBlock('block3');
}

function afterGetBlock4(res){
	$("#block4").html(res);
	showBlock('block4');
	setTimeout(handleLoad(), 4000);
}
function submitPayDel(){
	/*order_values=[];
	for(var order_field in order_fields){
		order_values.push(order_fields[order_field]+'='+$("#"+order_fields[order_field]).val());
	}
	var paymenttype=$("#paymenttype input[type='radio']:checked").val();
	var delivery=$("#delivery input[type='radio']:checked").val();*/
	$.post("/templates/basket/block4_ajax.php",{action:"getInner"/*,order_values:order_values,paymenttype:paymenttype,delivery:delivery*/},afterGetBlock4);
}
function check_order_data(){
	var rarray = new Array ("b_email");
	var err_count = 0;
	for(i = 0;i < rarray.length;i++){
		if(TrimString(document.getElementById(rarray[i]).value) == ""){
			$("#"+rarray[i]).css('border-color',"red");
			err_count++;
		}
	}
	for(i = 0;i < required_array.length;i++){
		if(TrimString(document.getElementById(required_array[i]).value) == ""){
			$("#"+required_array[i]).css('border-color',"red");
			err_count++;
		}
	}
	if(!isValidEmail (document.getElementById('b_email').value,false)){
		showAlert("Введите корректный E-Mail !");
		return false;
	}
	if(err_count > 0){
		showAlert("Необходимо заполнить все обязательные поля !");
		return false;
	}
	if(document.getElementById("b_soglashenie").checked!=true){
		showAlert("Необходимо подтвердить пользовательское соглашение !");
		return false;
	}
	return true;
}
function send_order(){
	if(check_order_data()){
		$("form[name=main-order-form]").attr("action","/basket/create/");
		$("form[name=main-order-form]").submit();
	}
}
function setFieldValue(fieldname,fieldinfo){
	setSessionKeyValue('b_'+fieldname,fieldinfo);
	setTimeout(showBasketSum(),2000);	
}
function afterShowBasketSum(res){
	$("#full_summ").html(res);
}
function showBasketSum(){
	$.post("/templates/basket/ajax.php",{action:"getBasketSum"},afterShowBasketSum);
}