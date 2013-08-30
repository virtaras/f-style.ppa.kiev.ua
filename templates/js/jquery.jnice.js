/*
 * jNice
 * version: 1.0 (11.26.08)
 * by Sean Mooney (sean@whitespace-creative.com) 
 * Examples at: http://www.whitespace-creative.com/jquery/jnice/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * To Use: place in the head 
 *  <link href="inc/style/jNice.css" rel="stylesheet" type="text/css" />
 *  <script type="text/javascript" src="inc/js/jquery.jNice.js"></script>
 *
 * And apply the jNice class to the form you want to style
 *
 * To Do: Add textareas, Add File upload
 *
 ******************************************** */
(function($){
	$.fn.jNice = function(reinit){
		if (typeof (reinit)!=='undefined'){
			$('input:text:visible, input:password, input[type="email"]', this).each(TextReinit);
			$('textarea', this).each(TareaReinit);
			return false;
		}
		var self = this;
		if (self.is('.jNice-done')) return false;
		self.addClass('jNice-done');
		/* Apply document listener */
		$(document).mousedown(checkExternalClick);
		/* each form */
		return this.each(function(){
			$('input:submit, input:reset, input:button,.button', this).each(ButtonAdd);
			$('.button',this).each(function(){
				$(this).mousedown(function(){ 
					$(this).addClass('jNiceClicked');
					return false;
				});
			});
			$(document).mouseup(function(){
				$('.button.jNiceClicked').removeClass('jNiceClicked');
			})
			$('textarea', this).each(TareaAdd);
			$('input:text:visible, input:password, input[type="email"]', this).each(TextAdd);
			$('input:checkbox', this).each(CheckAdd);
//			$('input:radio', this).each(RadioAdd);
			$('input:file', this).each(IfileAdd);
			$('select', this).each(function(index){ SelectAdd(this, index); });
			/* Add a new handler for the reset action */
			$(this).bind('reset',function(){var action = function(){ Reset(this); }; window.setTimeout(action, 10); });
			$('.jNiceHidden').css({opacity:0});
		});		
	};/* End the Plugin */


	var Reset = function(form){
		var sel;
		$('.jNiceSelectWrapper select', form).each(function(){sel = (this.selectedIndex<0) ? 0 : this.selectedIndex; $('ul', $(this).parent()).each(function(){$('a:eq('+ sel +')', this).click();});});
		$('a.jNiceCheckbox, a.jNiceRadio', form).removeClass('jNiceChecked');
		$('input:checkbox, input:radio', form).each(function(){if(this.checked){$('a', $(this).parent()).addClass('jNiceChecked');}});
	};

	var RadioAdd = function(){
		var $input = $(this);
		$input.addClass('jNiceHidden').wrap('<span class="jRadioWrapper jNiceWrapper"></span>');
		var $form=$input.parents('form');
		var $wrapper = $input.parent();
		var $a = $('<span class="jNiceRadio"></span>');
		$wrapper.prepend($a);
		/* Click Handler */
		$a.click(function(){
			$input.click();
			/*
				var $input = $(this).addClass('jNiceChecked').siblings('input').attr('checked',true);
				/* uncheck all others of same name * /
				$('input:radio[name="'+ $input.attr('name') +'"]').not($input).each(function(){
					$(this).attr('checked',false).siblings('.jNiceRadio').removeClass('jNiceChecked');
				});*/
				return false;
		});
		$input.change(function(){
			if(this.checked){
				/* uncheck all others of same name */
				$('input:radio[name="'+ $input.attr('name') +'"]',$form).not($input).each(function(){
//					$(this).css('border','1px solid red');
					$(this).attr('checked',false).siblings('.jNiceRadio').removeClass('jNiceChecked');
				});
				$a.addClass('jNiceChecked');
				$(this).attr('checked',true)
			}
		}).focus(function(){ $a.addClass('jNiceFocus'); }).blur(function(){ $a.removeClass('jNiceFocus'); });

		/* set the default state */
	};

	var CheckAdd = function(){
		var iclass=$(this).attr('class');
		var $input = $(this);
		$input.addClass('jNiceHidden').wrap('<span class="jNiceWrapper jCheckWrapper '+iclass+'"></span>');
		var $wrapper = $input.parent().append('<span class="jNiceCheckbox"></span>');
		/* Click Handler */
		var $a = $wrapper.find('.jNiceCheckbox')
		$a.click(function(){
			$input.click();
/*				var $a = $(this);
				var input = $a.siblings('input')[0];
				if (input.checked===true){
					input.checked = false;
					$a.removeClass('jNiceChecked');
				}
				else {
					input.checked = true;
					$a.addClass('jNiceChecked');
				}*/
				return false;
		});
			
		$input.change(function(){
			if(this.checked){ $a.addClass('jNiceChecked'); 	}
			else { $a.removeClass('jNiceChecked'); }
		}).focus(function(){ $a.addClass('jNiceFocus'); }).blur(function(){ $a.removeClass('jNiceFocus'); });
		
		/* set the default state */
		if (this.checked){$('.jNiceCheckbox', $wrapper).addClass('jNiceChecked');}
	};

	var TextReinit = function(){
		var $input = $(this);
		var $wrapper = $input.parents('.jNiceInputWrapper');
		if ($input.is('.jNice-error')) {
			$wrapper.addClass('jNice-error');
			$input.removeClass('jNice-error');
		}
	};
	var TextAdd = function(){
		var iw=$(this).width();
		if ($(this).is('.mquant')){
			var $input=$(this);
			$input.wrap('<span class="mquant_w"></span>');
			var $parent=$input.parent();
			$parent.append('<span class="qcontrol qinc"></span>').prepend('<span class="qcontrol qdec"></span>')

			$('.qcontrol',$parent).click(function(){
				if ($(this).is('.qinc')) $input.val($input.val()*1+1);
				else $input.val($input.val()>1 ? $input.val()-1 : 1);
				$input.change();
				return false;
			})
			$input.keypress(function(e){
				var str1='';
				var c = String.fromCharCode(e.which);
				var str=$(this).val()+c;
				for (i=0;i<=str.length;i++)
					if(str[i]*1>0) str1=str1+str[i];
				str1=str1.substr(0,5);
				$(this).val(str1);
				$(this).change();
				return false;
			})		
		}
		else{
			var $input = $(this).addClass('jNiceInput').wrap('<span class="jNiceInputWrapper"></span>').before('<span class="jNiceInputBg"><span class="jNiceInputLeft"></span><span class="jNiceInputRight"></span></span>');
			var $wrapper = $input.parents('.jNiceInputWrapper');
			if ($input.is('.jNice-error')) {
				$wrapper.addClass('jNice-error');
				$input.removeClass('jNice-error');
			}
//			$wrapper.width(iw);
			if(typeof($input.attr('iscr'))!=='undefined'){
				$wrapper.addClass('iimg').append('<span class="imgNice"><img src="'+$input.attr('iscr')+'" alt=""/></span>');
			}
			$(this).width($wrapper.width()-($(this).outerWidth()-$(this).width()));
			$(window).resize(function(){
				$input.width($wrapper.width()-($input.outerWidth()-$input.width()));
			})
			$input.focus(function(){ 
				$wrapper.addClass('jNiceInputWrapper_hover');
				$wrapper.removeClass('jNice-error');
			}).blur(function(){
				$wrapper.removeClass('jNiceInputWrapper_hover');
			});
		}
	};
	var IfileAdd = function(){
		iclass=$(this).attr('class');
		title=$(this).attr('title');
		var $input = $(this).addClass('jNiceInput').wrap('<span class="jNiceInputWrapper fileupload '+(typeof(iclass)!='undefined'? iclass : '') +'"></span>').before('<span class="jNiceInputBg"><span class="jNiceInputLeft">'+(typeof($(this).attr('placeholder'))!='undefined' ? $(this).attr('placeholder') : '')+'</span><span class="jNiceInputRight"></span></span><span class="ubutton"><span class="ileft">'+(typeof(title)!=='undefined' ? title : 'Îבחמנ')+'</span><span class="iright"></span></span>');
		var $wrapper = $input.parents('.jNiceInputWrapper');
		$input.focus(function(){ 
			$wrapper.addClass('jNiceInputWrapper_hover');
		}).blur(function(){
			$wrapper.removeClass('jNiceInputWrapper_hover');
		});
		$input.change(function(){
			filename=$(this).val();
			filename=filename.split('\\');
			$('.jNiceInputBg .jNiceInputLeft',$wrapper).html(filename[filename.length-1]).css('color','#030303');
		});
	};
	var TareaReinit = function(){
		var $tarea = $(this);
		var $wrapper = $tarea.parents('.jNiceTareaWrapper');
		if ($tarea.is('.jNice-error')) {
			$wrapper.addClass('jNice-error');
			$tarea.removeClass('jNice-error');
		}
	};
	var TareaAdd = function(){
		var $tarea = $(this).addClass('jNiceTarea').wrap('<span class="jNiceTareaWrapper"><span class="jNiceTareaBg"></span></span>');
		$(this).after('<span class="tl"></span><span class="tr"></span><span class="bl"></span><span class="br"></span><span class="sl"></span><span class="sr"></span>');
		var $wrapper = $tarea.parents('.jNiceTareaWrapper');
		if ($tarea.is('.jNice-error')) {
			$wrapper.addClass('jNice-error');
			$tarea.removeClass('jNice-error');
		}
		taWidth=$(this).width();
		taHeight=$(this).height();
		$tarea.focus(function(){ 
			$wrapper.addClass('jNiceTareaWrapper_hover');
			$wrapper.removeClass('jNice-error');
		}).blur(function(){
			$wrapper.removeClass('jNiceTareaWrapper_hover');
		});
//		$(".tl,.bl",$wrapper).css({"width":taWidth+$(".tr",$wrapper).width()+"px"});
//		$(".sl,.sr",$wrapper).css({"height":taHeight+"px"});
	};

	var ButtonAdd = function(){
		if (!$(this).is('.button')){
			value=$(this).attr('value');
			iclass=$(this).attr('class');
			$(this).attr('class','');
			$(this).attr('value','');
			$(this).wrap('<span class="button '+(typeof(iclass)!='undefined'? iclass : '') +'"></span>');
			$(this).parent().append('<span class="ileft">'+value+'</span>');
			$(this).parent().append('<span class="iright"></span>');
		}
		else{
			value=$(this).html();
			$(this).html('<span class="ileft">'+value+'</span><span class="iright"></span>');
		}
	};

	/* Hide all open selects */
	var SelectHide = function(){
			$('.jNiceSelectWrapper ul:visible').hide();
	};

	/* Check for an external click */
	var checkExternalClick = function(event) {
		if ($(event.target).parents('.jNiceSelectWrapper').length === 0) { SelectHide(); }
	};

	var SelectAdd = function(element, index){
		var $select = $(element);
		index = index || $select.css('zIndex')*1;
		index = (index) ? index : 0;
		/* First thing we do is Wrap it */
		$select.width($select.outerWidth());
		$select.wrap($('<span class="jNiceSelectWrapper '+(element.disabled ? ' disabled' : '')+'"></span>').css({zIndex: 100-index}));
		$select.addClass('jNiceHidden').after('<span class="jNiceSelectText"></span><span class="jNiceSelectOpen"></span></span><ul></ul></span>');
		var $wrapper = $(element).parents('.jNiceSelectWrapper');
		var width = $select.width();
		$('.jNiceSelectText', $wrapper).css({'right':$('.jNiceSelectOpen', $wrapper).width()});
		/* IF IE 6 */
		/* Now we add the options */
		SelectUpdate(element);
		/* Apply the click handler to the Open */
		if (!element.disabled){
		$('span', $wrapper).click(function(){
			var $ul = $('ul',$wrapper);
			if ($ul.css('display')=='none'){ SelectHide(); } /* Check if box is already open to still allow toggle, but close all other selects */
			$ul.slideToggle(0);
			var offSet = ($('a.selected', $ul).offset().top - $ul.offset().top);
			$ul.animate({scrollTop: offSet});
			return false;
		});
		/* Add the key listener */
		$select.keydown(function(e){
			var selectedIndex = this.selectedIndex;
			switch(e.keyCode){
				case 40: /* Down */
					if (selectedIndex < this.options.length - 1){ selectedIndex+=1; }
					break;
				case 38: /* Up */
					if (selectedIndex > 0){ selectedIndex-=1; }
					break;
				default:
					return;
					break;
			}
			$('ul a', $wrapper).removeClass('selected').eq(selectedIndex).addClass('selected');
			$('span:eq(0)', $wrapper).html($('option:eq('+ selectedIndex +')', $select).attr('selected', 'selected').text());

			return false;
		}).focus(function(){ $wrapper.addClass('jNiceFocus'); }).blur(function(){ $wrapper.removeClass('jNiceFocus'); });
		}
	};
	var SelectUpdate = function(element){
		var flag=0;
		var $select = $(element);
		var $wrapper = $select.parents('.jNiceSelectWrapper');
		var $ul = $wrapper.find('ul').find('li').remove().end().hide();
		$('option', $select).each(function(i){
			$ul.append('<li class="'+($(this).is('.disabled') ? 'disabled' : '')+'"><a href="#" index="'+ i +'">' +(typeof($(this).attr('color'))!=='undefined' ? '<b class="jNiceSelectColor" style="background:#'+$(this).attr('color')+'"></b>' : '')+(typeof($(this).attr('img'))!=='undefined' ? '<img src="'+$(this).attr('img')+'" alt="" />' : '')+ this.text +'</a></li>');
		});
		/* Add click handler to the a */
		$ul.find('a').click(function(){
			$('a.selected', $wrapper).removeClass('selected');
			$(this).addClass('selected');	
			/* Fire the onchange event */
			if ($select[0].selectedIndex != $(this).attr('index') && $select[0].onchange) { $select[0].selectedIndex = $(this).attr('index'); $select[0].onchange();}
			$select[0].selectedIndex = $(this).attr('index');
			$('.jNiceSelectText', $wrapper).html($(this).html());
			$ul.hide();
			if (flag==0) {flag=1}
			else{$select.change();}
			
			return false;
		});
		/* Set the defalut */

		$('a:eq('+ $select[0].selectedIndex +')', $ul).click();
	};

	var SelectRemove = function(element){
		var zIndex = $(element).parents('.jNiceSelectWrapper').css('zIndex');
		$(element).css({zIndex: zIndex}).removeClass('jNiceHidden');
		$(element).parents('.jNiceSelectWrapper').remove();
	};

	/* Utilities */
	$.jNice = {
			SelectAdd : function(element, index){ 	SelectAdd(element, index); },
			SelectRemove : function(element){ SelectRemove(element); },
			SelectUpdate : function(element){ SelectUpdate(element); }
	};/* End Utilities */

	/* Automatically apply to any forms with class jNice */
	$(function(){$('.jNice').jNice();	});
})(jQuery);