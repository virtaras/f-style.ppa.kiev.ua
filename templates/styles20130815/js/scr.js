function doMenu(menu,type){

	var menuwidth=$(menu).width();
	var el=$('>ul>li>a',menu);
	var sumElWidth=0;
	if(type==0){
		el.each(function(){
			$(this).css({'padding':'0'});
			sumElWidth=sumElWidth+$(this).width();
		});
		sumElWidth=sumElWidth+2*el.length-2;//corector
		elpadd=Math.floor(((menuwidth-sumElWidth)/el.length)/2);
		el.css({'padding':'0 '+elpadd+'px'});
		//el width fix
		var sumElWidth=0;
		el.each(function(){
			sumElWidth=sumElWidth+$(this).width()+elpadd*2;
		});
		sumElWidth=sumElWidth+2*el.length-2;//corector
		fixtmp=menuwidth-sumElWidth;
		//if(fixtmp<0){fixtmp=fixtmp*(-1); }
		$('>ul>li>a',menu).last().css({'padding':'0 '+elpadd+'px 0 '+(elpadd+fixtmp*1)+'px'});
	}
	if(type==1){
		menuwidth=menuwidth-2*el.length;
		elwidth=Math.floor(menuwidth/el.length);
		el.width(elwidth);
		menu.css({padding:'0 '+Math.floor(($(menu).width()-elwidth*el.length-2*el.length)/2)+'px'})
	}
}


function textExecution(block,width){
	block.each(function(){
		var blockTmp=$(this).clone();
		var wrap=$('<div style="position:absolute; width:2000px; height:10px;" />');
		var text=$(this).html().split(' ');
		wrap.append(blockTmp);
		$('body').append(wrap);
		$(this).html('');
		blockTmp.html('');
		var tmpString='';
		for(i=0;i<text.length;i++){
			blockTmp.html(blockTmp.html()+' '+text[i]);
			if(blockTmp.width()>width){
				$(this).append('<div class="text-row">'+tmpString+'</div><br/>');
				tmpString='';
				blockTmp.html(text[i]);
			}
			tmpString+=' '+text[i];
		}
		$(this).append('<div class="text-row">'+tmpString+'</div><br/>');
		wrap.remove();
	});
}

function powerClick(){
	$('.cart-box-title .gonext').click(function(){
		$('.cart-box-nav .next input').click();
		return false
	});
}

function magicSumm(box){
	var globalSum=0;
	box.each(function(){
		var price=$('div[data-price-holder]',this).attr('data-price-holder');
		var result=price*1*$('.mquant',this).val();
		console.log(price,result);
		$('.summbox',this).html(parseFloat(result).toFixed(2)+' грн');
		globalSum+=result;
		
	});
	$('.global-sum').html(parseFloat(globalSum).toFixed(2)+' грн');
	globalSum*=((100-discount.percent)/100);
	globalSum-=discount.money;
	$('.global-sum-all').html(parseFloat(globalSum).toFixed(2)+' грн');
}


function crasherBox(item,caller){
	caller.click(function(){
		$(this).parents(item).remove();
		magicSumm($('.cart-box-result-item'));
	})
}

function wonderClick(){
	$('.discount-card-box .button').click(function(){
		alert('server request');
		alert('number ok')
		discount= {money:0,percent:7};
		magicSumm($('.cart-box-result-item'));
		return false;
	});
}
function checkBoxInit(){
	$('input[type="checkbox"]:checked').parents('label').addClass('labelCheck');
	$('label input[type="checkbox"]').change(function(){
		$(this).parents('label').toggleClass('labelCheck');
	});	
}


function filterListedInit(){
	$('.filter-listed a').click(function(){
		if($(this).parents('li').is('.active')){
            $(this).parents('li').removeClass('active');
             reload_products();
				return false;
			}else{
//				$(this).parents('.filter-listed').find('li').removeClass('active')
				$(this).parents('li').addClass('active');
				$('input',$(this).parents('.filter-listed')).val($(this).attr('data-filter-id'));
            reload_products();
				return false;
			}
	});
}

function InitTagList(){
	$('.tag-filter-list').html(' ');
	$('.tagFilterSelectArea').each(function(){
		var boxIndex=$(this).attr('data-tag-box');
		$('li',this).each(function(){
			if($(this).is('.active')){
				var tagIndex=$(this).attr('data-tag-index');
				var tagName=$('.tag-name',this).html();
				var tagBox='<a data-tag-index='+tagIndex+' href="#">'+tagName+'</a><br/>';
				$('.tag-filter-item[data-tag-box='+boxIndex+'] .tag-filter-list').append(tagBox);
			}
		});
	});
	
	
	reload_products();
}

function SortByOrder(a, b){
  var aName = a.ORDER;
  var bName = b.ORDER; 
  return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}

function set_colors(obj)
{
	/*
	var id = obj.options[obj.selectedIndex].value;
	$(".stuff-color-menu a").hide();
	$.each(sizes[id], function(index, value) {
		$("#c" + value.ID).show();
	});
	*/
}

function reload_sizes(colorindex)
{
	var html = "<select onchange='set_colors(this)' class='size_select'>";
	html += "<option value='0'>Выбрать размер</option>";

$.each(colors, function(index, value) {
	if(value.ID == colorindex)
	{  
		value.SIZES.sort(SortByOrder);
		$.each(value.SIZES, function(i, v) {
				html += "<option value='" + v.ID + "'>" + v.NAME + "</option>";
		});
	}	
});
	
	html += "</select>";
	$("#size_select_div").html(html);
	$('.stuff-dscr-menu-select').removeClass("jNice-done");
	$('.stuff-dscr-menu-select').jNice();
}

function ColorTabs(){
	$('.stuff-color-menu a').click(function(){
		var colorindex=$(this).attr('data-color-index');
		$('.curent-color-item').removeClass('curent');
		$('.curent-color-item[data-color-index='+colorindex+']').addClass('curent');
		$('.visual-preview-box-item').removeClass('curent');
		$('.visual-preview-box-item[data-color-index='+colorindex+']').addClass('curent');
		$('.stuff-color-menu a').removeClass('curent');
		$(this).addClass('curent');
		reload_sizes(colorindex);
		return false;
	});
}

function init_pager_link()
{
	$(".catalog-return-filter-pagination").find("a").click(
			function()
			{
				$("#catalog_inner_content").load($(this).attr("href"),{},
				function()
				{
					init_pager_link();
				}
				);
				return false;
			}
		);
}

function reload_products()
{
	var url = "/search.html?";
	var category = new Array;
	$('.category_select').find("li").each(function(){
			if($(this).is('.active')){
				category.push($(this).attr('data-tag-index'));
			}
		});
	if(category.toString() == "")
	{
		category =  topID;
	}	
	url = url + "id=" + category.toString();	
	var brands = new Array;
	$('.brand_select').find("li").each(function(){
			if($(this).is('.active')){
				
				brands.push($(this).attr('data-tag-index'));
			}
		});
	if(brands.toString() != "") {
		url = url + "&brand=" + brands.toString();	
	}
	var sizes = new Array;	
	$('.size-box').find("input").each(function(){
	
			if($(this).is(':checked')){
			var label = $(this).parents("label");
			var tagIndex=label.attr('data-tag-index');
			if(tagIndex != 0) { 
				sizes.push(tagIndex); }
			}
		});
		if(sizes.toString() != "") {
			url = url + "&r1194=" + sizes.toString();	
		}
    var color = new Array;
	$('.colorType').find("li").each(function(){
			if($(this).is('.active')){
				color.push($(this).attr('data-filter-id'));
			}
		});
	if(color.toString() != "") {
			url = url + "&r1193=" + color.toString();
		}
    var season = new Array;
	$('.seasonType').find("li").each(function(){
			if($(this).is('.active')){
				season.push($(this).attr('data-filter-id'));
			}
		});
	if(season.toString() != "") {
			url = url + "&r1195=" + season.toString();
		}
    var price_start = new Array;
    var price_finish = new Array;
	$('.priceRanger').find("li").each(function(){
			if($(this).is('.active')){
				price_start.push($(this).attr('data-filter-id-start'));
                price_finish.push($(this).attr('data-filter-id-finish'));
			}
		});
	if(price_start.toString() != "") {
			url = url + "&price_start=" + price_start.toString();
		}
    if(price_finish.toString() != "") {
			url = url + "&price_finish=" + price_finish.toString();
		}
	$.post(url,{},function(r)
	{
		$("#catalog_inner_content").html(r);
		isAjax = true;
		init_pager_link();
	}
	);
}

function reset_sizes(obj)
{
	if(obj.checked)
	{
		$('.size-box').find("input").each(function(){
			$(this).attr("checked","checked");
		});
	}
	else
	{
		$('.size-box').find("input").each(function(){
			$(this).removeAttr("checked");
		});
	}
	init_sizes();
}

function init_sizes()
{
	$('.tag-filter-item[data-tag-box=3] .tag-filter-list').html(' ');
	$('.size-box input').change(function(){
		
		var label = $(this).parents("label");
		var tagIndex=label.attr('data-tag-index');
		if(this.checked)
		{
			var tagName=label.find('.tag-name').html();
			var tagBox='<a style="display:block;" class="size_a_'+tagIndex+'" data-tag-index='+tagIndex+' href="#">'+tagName+'</a>';
			$('.tag-filter-item[data-tag-box=3] .tag-filter-list').append(tagBox);
		}
		else
		{
			$(".size_a_" + tagIndex).remove();
		}
		reload_products();
		return false;
	});
}

function ControllTagList(){
	$('.tagFilterSelectArea a').click(function(){
		$(this).parents('li').toggleClass('active');
		InitTagList();
		return false;
	})
	$('.tagFilterSelectArea input').change(function(){
		$(this).parents('li').toggleClass('active');
		InitTagList();
		return false;
	});
	
		
	init_sizes();
	
	$('.tag-filter-item a').live('click',function(){
		var tagIndex=$(this).attr('data-tag-index');
		var boxIndex=$(this).parents('.tag-filter-item').attr('data-tag-box');
		$('.tagFilterSelectArea[data-tag-box='+boxIndex+'] li[data-tag-index='+tagIndex+'] input').prop('checked',false).change();
		if($('.tagFilterSelectArea[data-tag-box='+boxIndex+'] li[data-tag-index='+tagIndex+']').is('.active')){
			$('.tagFilterSelectArea[data-tag-box='+boxIndex+'] li[data-tag-index='+tagIndex+']').removeClass('active');
		}
		InitTagList();
		return false;
	});
	$('.clearall').click(function(){
		$('.filterMarker input:checkbox').prop('checked',false).change();
		$('.filterMarker input[type="text"]').val('');
		$('.filterMarker label').removeClass('labelCheck');
		$('.filterMarker .active').removeClass('active');
		InitTagList();
		return false;
	});
}

function StuffSliderInit(){
	$('.visual-preview-box-item').each(function(){
		var slider=$(this);
		$(this).show();
		$('.visual-preview-box-slider ul',this).anythingSlider({ 
			buildNavigation     : true,      // If true, builds a list of anchor links to link to each panel 
			buildStartStop      : false, 
			hashTags            : false,
			resizeContents		:false,
			onInitialized       : function() {
				
				if(slider.is('.curent')){}else{
					slider.hide();
				}
			}
	
		});
	});
}

function ControlThumbs(){
	$('.stuff-thumbs-box-control-item').click(function(){
		$('.stuff-thumbs-box-control-item').removeClass('active');
		$(this).addClass('active');
		$('.stuff-thumbs-box-show-item').removeClass('active');
		$('.stuff-thumbs-box-show-item[data-box-id='+$(this).attr('data-box-id')+']').addClass('active');
	});
}

$(document).ready(function(){
	ControllTagList();
	InitTagList();
	magicSumm($('.cart-box-result-item'));
	filterListedInit();
	powerClick();
	wonderClick()
	crasherBox('.cart-box-result-item',$('.dell'))
	checkBoxInit();
	ColorTabs();
	ControlThumbs();
	
	
	$('#colorSlider').jcarousel({});
	
	
	
	$('.mquant').change(function(){
		magicSumm($('.cart-box-result-item'));
		}
	)
	
	$('.scroll-pane').jScrollPane();
	
	
	
	$('#slider').anythingSlider({ 
		buildNavigation     : false,      // If true, builds a list of anchor links to link to each panel 
		buildStartStop      : false, 
		 hashTags           : false
	
	});
	
	$('#slider1').anythingSlider({ 
		buildNavigation     : false,      // If true, builds a list of anchor links to link to each panel 
		buildStartStop      : false, 
		 hashTags           : false
	
	});
	
	$('#slider2').anythingSlider({ 
		buildNavigation     : true,      // If true, builds a list of anchor links to link to each panel 
		buildStartStop      : false, 
		 hashTags           : false
	
	});
	
	$('#mycarousel').jcarousel({
        // Configuration goes here
    });
	
	
	$('.header-enter-box').hide();
	$('.header-enter-butt').click(function(){
		if($(this).is('.active')){
			$('.header-enter-box').hide();$('.header-enter-butt').removeClass('active');
		}else{
			$('.header-enter-butt').removeClass('active');
			$(this).addClass('active');
			var index=$(this).attr('data-tab-index');
			$('.header-enter-box').hide();
			$('.header-enter-box[data-tab-index='+index+']').show();
		}
	});
	
	
	$('.visual-preview-box-thumbs a').click(function(){		
		$('.visual-preview-box-slider .thumbNav a:eq('+$('.visual-preview-box-thumbs a').index($(this))+')').click();
		return false;
	});
	
});

$(window).load(function(){
	doMenu($('.header-menu'),0);
	textExecution($('.news-box-item-img-text'),250);
	StuffSliderInit();
})