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
		$.fancybox({
			href		: '#bonus-card',
			padding		: 20
		})		
		discount= {money:0,percent:7};
		magicSumm($('.cart-box-result-item'));
		return false;
	});
	$('.bonus-used a').click(function(){
		$.fancybox({
			href		: '#bonus-card',
			padding		: 20
		})		
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
				return false;
			}else{
				$(this).parents('.filter-listed').find('li').removeClass('active')
				$(this).parents('li').addClass('active');
				$('input',$(this).parents('.filter-listed')).val($(this).attr('data-filter-id'));
				return false;
			}
	});
};
function filterListedInit1(){
	$('.filter-listed1 a').click(function(){
		if($(this).is('.active')){
				return false;
			}else{
				$(this).parents('.filter-listed1').find('a').removeClass('active')
				$(this).addClass('active');
				$('input',$(this).parents('.filter-listed1')).val($(this).attr('data-filter-id'));
				return false;
			}
	});
};

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

$(function(){
    $.fn.scrollToTop=function(){
        $(this).hide().removeAttr("href");
        if($(window).scrollTop()!="0"){
            $(this).fadeIn("slow")
        }
        var scrollDiv=$(this);
        $(window).scroll(function(){
            if($(window).scrollTop()=="0"){
                $(scrollDiv).fadeOut("slow")
            }else{
                $(scrollDiv).fadeIn("slow")
            }
        });
        $(this).click(function(){
            $("html, body").animate({scrollTop:0},"slow")
        })
    }
	$("#toTop").scrollToTop();
});

$(document).ready(function(){
	filterListedInit1();
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
	
	$('.button1.button2').click(function(){
		var id=$(this).attr('data-bonusid')
		alert(' activator of #'+id+' bonus');
	});
	
	$('#colorSlider').jcarousel({});
	
	
	
	$('.mquant').change(function(){
		magicSumm($('.cart-box-result-item'));
		}
	)
	
	$('.scroll-pane').jScrollPane();
	
	$('.fancybox').fancybox();
	$('.fancybox1').fancybox({
		fitToView		: false,
		padding			: 0,
		wrapCSS			: 'closeFix'
	});
	
	$('.btn-tsize').click(function(){
		$.fancybox({
			type : 'ajax',
			href : '/ajax-table-size.html',
			autoSize:true,
			padding: 0,
			fitToView:false,
			closeBtn: false
		})
		return false;
	});
	$('.btn-tsize').click(function(){
		$.fancybox({
			type : 'ajax',
			href : '/ajax-table-size.html',
			autoSize:true,
			padding: 0,
			fitToView:false,
			closeBtn: false
		})
		return false;
	});
	
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
	
	$(document).on('click','.close-btn',function(){
		$.fancybox.close();
	});
	
});

$(window).load(function(){
	doMenu($('.header-menu'),0);
	textExecution($('.news-box-item-img-text'),250);
	StuffSliderInit();
})