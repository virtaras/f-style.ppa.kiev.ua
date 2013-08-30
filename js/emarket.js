var isAjax = false;
function set_page_size(pagesize)
{
    $.post('/ajax.php',{ action:'pagesize', size:pagesize},
	function() {document.location.href = document.location.href;} );
    return false;
}
function set_sort(sort)
{
    $.post('/ajax.php',{ action:'sort',sorttype:sort},function() {document.location.href = document.location.href;});
    return false;
}
function get_selected_value(id)
{
    var obj = document.getElementById(id);
    return obj.options[obj.selectedIndex].value;
}
function redirect(r)
{
	document.location = '/redirect.php?url='+r;
}
function set_currency(id)
{
	$.post('/ajax.php',{
        action:'currency',
        item:id
    },function () { document.location.href = document.location.href;  })
}
function isValidEmail (email, strict)
{
 if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
 return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

function check_required()
{
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
		alert("Введите корректный E-Mail !");
		return false;
	}
	
	if(err_count > 0)
	{
		alert("Необходимо заполнить все обязательные поля !");
		return false;
	}
    document.forms['order'].action = '/basket/create';
    document.forms['order'].submit();
    return true;
}
function TrimString(sInString){
    sInString = sInString.replace(/ /g,' ');
    return sInString.replace(/(^\s+)|(\s+$)/g, "");
}
function set_input_text(obj,text,action)
{
    if(action == 1)
    {
        if(TrimString(obj.value) == "")
        {
            obj.value = text;
        }
    }
    else if(action == 0)
    {
        if(obj.value == text)
        {
            obj.value = '';
        }
    }
}
function uncheck_by_name(name)
{
    var obj = document.getElementsByName(name);
    for(var i = 0;i < obj.length;i++)
    {
        obj[i].checked = false;
    }

}
function show_cart()
{
	//$("#basket_panel").html("<img alt='Загрузка' align='center' src='/templates/images/loading.gif' />");
	$("#cart_panel").load('/ajax.php',{action:"show_basket"},function() {});
}

var last_add_item = 0;
function after_basket_add(r)
{
	//$("#test").html(r);
	show_cart();
	$("#to_basket").html(r);
	$("#to_basket").dialog('open');
//	$('select').selectbox();
	
}
 function send_to_basket(id,parent)
 {
	send_to_cart(id,parent);
 }
  function send_to_cart(id,parent,color,size)
{
	last_add_item = id;
	var q = $("#q"+parent).val();
	if(q == 0 || q == "" || isNaN(q))
	{
		q = 1;
	}
     var color = color;
    var size = size;
	/*
	var imageElement = document.getElementById("timage"+id);
	var cartElement = document.getElementById("basket_panel");
	
	var imageToFly = $(imageElement);
	var position = imageToFly.position();
	var positionCart = $(cartElement).position();
	
	var flyImage = imageToFly.clone().insertBefore(imageToFly);
	flyImage.css({ "position": "absolute", "left": position.left, "top": position.top });
	flyImage.animate({ width: 0, height: 0, left: positionCart.left, top: positionCart.top }, 600, 'linear');
	flyImage.queue($.proxy(function() {
					$(flyImage).remove();
					$.get('/basket/add/'+id+'/'+q,{},after_basket_add);
				}));
	*/
	/*
	$( "#timage"+id ).effect( "transfer", { to: "#cart_panel", className: "ui-effects-transfer" }
			, 1000, function() {  $.get('/basket/add/'+id+'/'+q,{},after_basket_add); } );
	*/
	$.get('/basket/add/'+id+'/'+q,{color:color, size:size},after_basket_add);
	
}
function after_compare(r)
{
    $("#compare_div").html(r);
	$("#compare_div").show();
}
function compare(id,parentid,obj)
{
	if(obj.checked)
	{
		$.post("/ajax.php",{action:'compare',tovarid:id,parent:parentid},after_compare);
	}
	else
	{
		$.post("/ajax.php",{action:'compare',tovarid:id,parent:parentid,rm:1},after_compare);
	}
	
}
function set_mode(catalog,m)
{
	 $.post('/ajax.php',{
        action:'mode',
        id:catalog,
		mode:m
    },function() {document.location.href = document.location.href;});
    return false;
}
function check_in_compare(id,parentid)
{
	$.post('/ajax.php', {action:'in_compare',tovarid:id,parent:parentid},
		function(data) 
		{
			if(data == "true")
			{
				$('#in_compare_'+id).attr("checked",data);
			}	
		});

}
function open_child(parent,level)
{
	if ($("#ch"+parent).length > 0)
	{
		$("#ch"+parent).slideToggle('slow',function() {  if($(this).css('display') == "none") {  $("#ac"+parent).find("img").attr("src","/templates/images/plus.png"); } else { $("#ac"+parent).find("img").attr("src","/templates/images/minus.png"); }  } );
	}
	else
	{
		$("#ac"+parent).find("img").attr("src","/templates/images/minus.png");
		$.post(baseurl+"ajax.php",{action:"show_subcatalog",l:level,id:parent},function(r) { $("#c"+parent).after("<div style='display:none;' id='ch"+parent+"'>"+r+"</div>"); $("#ch"+parent).slideDown('slow'); });
	}
	
	
}
function rmitem(id)
{
	if(confirm("Удалить товар из заказа ?"))
	{
		document.location.href = '/basket/remove/'+id;
	}
}
 function ShowPassword(obj,type)
  {
	  var psw = $("#login_passw");
	  if (type == 0) {
		  obj.style.display = "none";
		  psw.show();
		  psw.focus();
	  }
	  if (type == 1) 
	  {
		  if (obj.value == "") {
			  obj.style.display = "none";
			  $("#login_passw_on").show();
		  }
	  }
  }
  	$(function() {
		$("#stext").autocomplete({
			source:function (request, response) {
                $.post("/ajax.php",{action:"fast_search",term:request.term},function(r) { response(eval(r)); });
            } ,
			minLength: 3,
			width:'300px',
			select: function(event, ui) {
				this.value = ui.item.value;
				document.forms["search_form"].submit();
			}
			
			});
		});
		$().ready(function() {
		
			$("#to_basket").dialog({
			modal: true,
			autoOpen: false,
			minHeight:200,
			width:840,
			minWidth:400/*,
			buttons: {
				'Продолжить покупки': function() {
					$(this).dialog('close');
				}
				,
				'Оформить заказ': function() {
					document.location.href = '/basket.html';
				}
			}*/
		}); 		
		});
	function set_rating(id,val) {
		$.post("/ajax.php",{action:"set_rating",product:id,rating:val});
	}	
	
	function show_useful(id)
	{
		$("#useful" + id).load("/ajax.php",{action:"show_useful",comment:id});
	}
	
	function set_useful(id,val)
	{
		$.post("/ajax.php",{action:"set_useful",comment:id,type:val}, function() { alert("Спасибо !"); show_useful(id); }  );
	}
	function scq(product)
{
	var quantity = $("#cq" + product).val();
	$.get("/ajax.php",{action:"cart_set_quantity",id:product,q:quantity}, function()  { 
	
	$("#cart_panel").load('/ajax.php',{action:"show_basket"},function() { } );
	
	
	$.post("/ajax.php",{action:"reload_cart_popup"},function(r) { 	$("#wrapperBasket").replaceWith(r); $('select').selectbox();  },"text");
	
	});
}
function rc(product)
{
	
	$.get("/ajax.php",{action:"cart_remove_item",rm:product}, function()
	{
		$("#cart_panel").load('/ajax.php',{action:"show_basket"},function() { } );
		$.post("/ajax.php",{action:"reload_cart_popup"},function(r) { 	$("#wrapperBasket").replaceWith(r);  $('select').selectbox(); },"text");
	}
	);
}
function show_cart_popup()
{
	$.post("/ajax.php",{action:"reload_cart_popup"},function(r) { 
		$("#to_basket").html(r);
		$("#to_basket").dialog('open');
		$('select').selectbox();
	},"text");
}