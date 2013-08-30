var tr_controls = new Array;
function get_select_value(id)
{
	var obj = document.getElementById(id);
	return obj.options[obj.selectedIndex].value;
}
function get_value(id)
{
	var obj = document.getElementById(id);
	return obj.value;
}
function get_checked(id)
{
	var obj = document.getElementById(id);
	return obj.checked;
}
function check_all(obj)
{
	frm = document.getElementsByTagName("form");

	for(i=0;i<frm.length;i++) 
	{
		c = frm[i].elements;
		for(j=0;j<c.length;j++) 
		{
			if(c[j].getAttribute("type") == "checkbox") 
			{
				if(c[j].id.substring(0,3) == "cb_")
				{
					c[j].checked = obj.checked;
				}	
			}
		}
	}
}
function simple_save_form(path)
{
	document.forms[0].action = "http://"+path+"&simple_save";
	document.forms[0].submit();
}
function close_form(path,button)
{
	document.forms[0].action = "http://"+path;
	document.forms[0].submit();
	button.disabled = true;
}
function save_form(path,required_array,number_array,button)
{
	if(check_required(required_array) && check_number(number_array))
	{
		document.forms[0].action = "http://"+path+"&save";
		document.forms[0].submit();
		button.disabled = true;
	}
	else
	{
		return false;
	}
}
function delete_row(url)
{
	if(confirm(delete_message))
	{
		document.forms[0].action = "http://"+url;
		document.forms[0].submit();
	}
}
function copy_row(url)
{
	document.forms[0].action = "http://"+url;
	document.forms[0].submit();
}
function check_required(required_array)
{
	var i = 0;
	var str = required_message + "\n";
	
	for(q = 0;q < required_array.length;q++)
	{
		var obj = document.getElementById(required_array[q][0])
		var val = "";
		if(required_array[q][2] == "list")
		{
			val = obj.options[obj.selectedIndex].value;
		}
		else
		{
			val = obj.value;
		}
		
		
		if(val == "" || val == null || val == "-1")
		{
			str = str + required_array[q][1]+"\n";
			i++;
		}
		
		
	}
	if(i == 0)
	{
		return true;
	}
	else
	{
		alert(str);
		return false;
	}
}
function check_number(number_array)
{
	var i = 0;
	var str = number_message + "\n";
	for(q = 0;q < number_array.length;q++)
	{
		
		var obj = document.getElementById(number_array[q][0]);
		var val = obj.value.replace(",",".");
		if(isNaN(val))
		{
			str = str + number_array[q][1]+"\n";
			i++;
		}
	}
	if(i == 0)
	{
		return true;
	}
	else
	{
		alert(str);
		return false;
	}
}
function check_length(obj,ln)
{
    if(obj.value.length > ln)
	{
		return false;
	}
}
function in_array(arr,val)
{
	if(arr.length > 0)
	{
		
		for(z = 0;z < arr.length;z++)
		{
			if(arr[z] == val)
			{
				return true;
			}
		}
	}
	return false;
}
	function SetCurrentTab(obj,ind)
	{
	if(obj.className == "tabOn")
	{
		obj.className = "tabOff";
	}
	else
	{
		obj.className = "tabOn";
	}	
	for(i = 0; i < tabs.length;i++)
	{
		if(tabs[i]  == "")
		{
			continue;
		}
		if(tabs[i] != obj.id)
		{
			document.getElementById(tabs[i]).className = "tabOff";
		}
		
	}

	for(i = 0;i < tr_controls.length;i++)
	{
		
		if(in_array(fields[ind],tr_controls[i]))
		{
			if(document.all)
			{
				document.getElementById(tr_controls[i]).style.display = "block";
			}
			else
			{
				document.getElementById(tr_controls[i]).style.display = "table-row";
			}	
		}
		else
		{
			document.getElementById(tr_controls[i]).style.display = "none";
		}
		
		
	}


	}
	function getBounds(element)
{
  var left = element.offsetLeft;
  var top = element.offsetTop;
  for (var parent = element.offsetParent; parent; parent = parent.offsetParent)
  {
    left += parent.offsetLeft;
    top += parent.offsetTop;
  }
  return {left: left, top: top, width: element.offsetWidth, height: element.offsetHeight};
}
function set_time(id)
{
	var r = document.getElementById(id);
	r.value = get_select_value("h_"+id)+":"+get_select_value("m_"+id);
}
function show_filter(obj)
{
	var filter = document.getElementById("filter");
	
	var xy = getBounds(obj);
	filter.style.top = xy.top + 20;
	filter.style.left = xy.left;

    $("#filter").fadeIn();
    
}
function apply_value(control,id,name,type)
{
	if(type == 0)
	{
		window.parent.document.getElementById(control).value = id;
		window.parent.document.getElementById("t_"+control).innerHTML = name;
	}
	else
	{
		if(window.parent.$("#"+control).containsOption(id))
		{
			window.parent.$("#"+control).selectOptions(id, true);
		}
		else
		{
			window.parent.$("#"+control).addOption(id, name);
			window.parent.$("#"+control).selectOptions(id, true);
		}
	}	
	window.parent.document.getElementById("dlonglist").style.display = 'none';
}
function reset_select(id)
{
	var select = document.getElementById(id);
	select.selectedIndex = 0;
}
var tr_class_name = "";
var tr_id = "";
function highlight(id)
{
	if(tr_class_name != "")
	{
		document.getElementById(tr_id).className = tr_class_name;
	}
	var tr = document.getElementById("tr"+id);
	tr_class_name = tr.className;
	tr_id = "tr"+id;
	tr.className = "ctr";
}
function edit_longlist(url,width,height,obj,parent)
{
	var input_div = document.getElementById("dlonglist");
	var input_frame = document.getElementById("ilonglist");
	input_div.style.display = "inline";
	input_div.style.width = width;
	input_div.style.height = parseInt(height) + 30;
	var xy = getBounds(obj);
	input_div.style.top = xy.top;
	input_div.style.left = xy.left;
	input_frame.style.width = width;
	input_frame.style.height = height;
    if(parent != "")
    {
        var parentid = get_select_value(parent);
        if(parentid != "-1")
        {
            url = url + "&parent="+parentid;
        }
    }
	input_frame.src = url;
	
}
function close_longlist()
{
	window.parent.document.getElementById('ilonglist').src = '';
	window.parent.document.getElementById('dlonglist').style.display = 'none';
}
function show_wait(id)
{
	document.getElementById(id).innerHTML = "<img alt='Загрузка' src='images/loader.gif' />";
}
//****************************************************
var trans = [];
for (var i = 0x410; i <= 0x44F; i++)
  trans[i] = i - 0x350; 
trans[0x401] = 0xA8;    
trans[0x451] = 0xB8;    

var escapeOrig = window.escape;

window.escape = function(str)
{
  var ret = [];
  for (var i = 0; i < str.length; i++)
  {
    var n = str.charCodeAt(i);
    if (typeof trans[n] != 'undefined')
      n = trans[n];
    if (n <= 0xFF)
      ret.push(n);
  }
  return escapeOrig(String.fromCharCode.apply(null, ret));
}
//******************************************************
function show_hide_fields(obj)
{
    var xy = getBounds(obj);
    $("#fields_list").attr("style","top:"+xy.top.toString()+"px;left:"+(xy.left-100).toString()+"px;");
    $("#fields_list").fadeIn();
}
function hide_fields()
{
      $("#fields_list").fadeOut();
      
}
function after_show_hide()
{
    document.location = document.location.href;
}
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return (setStr);
}
function getExpDate(days, hours, minutes) {
    var expDate = new Date();
    if (typeof days == "number" && typeof hours == "number" && typeof hours == "number") {
        expDate.setDate(expDate.getDate() + parseInt(days));
        expDate.setHours(expDate.getHours() + parseInt(hours));
        expDate.setMinutes(expDate.getMinutes() + parseInt(minutes));
        return expDate.toGMTString();
    }
}
function set_show_hide(t)
{
    var f = "";
    var cb = document.getElementsByName("show_hide");
    var w = document.getElementsByName("t_field_width");
	var ind = 0;
	
    for(var i = 0;i < cb.length;i++)
    {
        if(!cb[i].checked)
        {

            f = f + cb[i].value + ",";
            ind++;
        }
    }
	setCookie(t+"_hide", f, getExpDate(365, 0, 0), "/");
	var wstring = "";
	for(var i = 0;i < w.length;i++)
    {
        if(w[i].value != "")
        {
			wstring = wstring + w[i].id.replace("w_","")+":"+w[i].value+";";
        }
    }
	setCookie(t+"_width", wstring, getExpDate(365, 0, 0), "/");
	
	after_show_hide();
    //$.post('udf/ajax.php',{action:'show_hide',table:t,fields:f.toString()},after_show_hide);
}
function get_checked_row()
{
	frm = document.getElementsByTagName("form");
	var idarray = new Array;
	var ind = 0;
	for(i=0;i<frm.length;i++) 
	{
		c = frm[i].elements;
		for(j=0;j<c.length;j++) 
		{
			if(c[j].getAttribute("type") == "checkbox") 
			{
				if(c[j].id.substring(0,3) == "cb_")
				{
					if(c[j].checked)
					{
						idarray[ind] = c[j].value;
						ind++;
					}
				}	
			}
		}
	}
	var res = idarray.toString();
	return res;
}
function select_result(id){
  if(document.all){
    var range = document.body.createTextRange();
    var tab=document.getElementById(id);
    range.moveToElementText(tab);
    range.select();
  } else {
    var selection = window.getSelection();
    var range = document.createRange();
    var tab = document.getElementById(id);
 
    range.selectNodeContents(tab);
    selection.addRange(range);
  }
}
function reload_page(r)
{
	document.location.href = document.location.href;
}