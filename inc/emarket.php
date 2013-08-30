<?
@include(_DIR."inc/cache/catalog_array.php");
$catalog_array = $ca;
@include(_DIR."inc/cache/currency_array.php");
@include(_DIR."inc/cache/cross_course.php");
@include(_DIR."inc/cache/fields_array.php");	
@include_once(_DIR."inc/cache/settings.php");	
function get_catalog_id_by_url($url)
{
	global $catalog_array;	
	$url = urldecode($url);
	foreach(array_keys($catalog_array) as $key)
	{
		if(strtolower($catalog_array[$key]["full_url"]) == strtolower($url))		
		{			
			return $key;
		}	
	}
}
if(!isset($_SESSION["base_currency"]))
{
	$_SESSION["base_currency"] = _display_currency;
}
define("_DISPLAY_CURRENCY",$_SESSION["base_currency"]);
/*
if(!isset($_SESSION["basket"]) && isset($_COOKIE["basket"]))
{
	$_SESSION["basket"] = stripslashes($_COOKIE["basket"]);
}
*/
 $base_currency = _base_currency;
function get_opened($array,$id,&$opened,&$chainlet)
{
	
	if(isset($array[$id]["parent"]) & $array[$id]["parent"] != 0)
	{
		array_push($chainlet,$array[$id]["parent"]);
		get_opened($array,$array[$id]["parent"],$opened,$chainlet);
	}
	else
	{
		$opened = $id;
	}
	
}


if(isset($content_type) && ( $content_type == "catalog" || $content_type == "tovar" || $content_type == "search"))
{
	$opened = 0;
	$chainlet = array();
	if(_CONTENT_TYPE == "tovar")
	{
		/*if(!is_numeric($_GET["id"]))
		{
			print $_GET["id"];
			$productparent = execute_scalar("SELECT parentid FROM goods WHERE urlname = '".mysql_escape_string($_GET["id"])."'");
			
		}
		else
		{
			
			$productparent = execute_scalar("SELECT parentid FROM goods WHERE id = '$_GET[id]'");
		} */
		$productparent = execute_scalar("SELECT parentid FROM goods WHERE id = '$_GET[id]'");
		if($productparent == "")
		{
			$productparent = "0";
		}
		array_push($chainlet,$productparent);
		get_opened($catalog_array,$productparent,$opened,$chainlet);
	}
	else if(_CONTENT_TYPE == "search")
	{
		//array_push($chainlet,$_GET["id"]);
		//get_opened($catalog_array,$_GET["id"],$opened,$chainlet);
	}
	else
	{
		if($_GET["id"][strlen($_GET["id"]) - 1] == "/")
		{
			$_GET["id"] =  substr($_GET["id"],0,strlen($_GET["id"]) - 1);
		}
		
		define("_BASE_CATALOG_URL",$_GET["id"]);
		if(!is_numeric($_GET["id"]) && isset($urls[$_GET["id"]]))
		{
			
			
			$_GET["id"] = $urls[$_GET["id"]];
			//$_GET["id"] = get_catalog_id_by_url($_GET["id"]);
		}
		array_push($chainlet,intval($_GET["id"]));
		get_opened($catalog_array,intval($_GET["id"]),$opened,$chainlet);
	}

}

//set sort order
if(isset($content_type) &&  (_CONTENT_TYPE == "brand" || _CONTENT_TYPE == "catalog" || _CONTENT_TYPE == "search" || _CONTENT_TYPE == "asearch"))
{
	$sortname = " vprice ASC";
	$sorttype = 1;
	//0 - asc, 1 - desc
	$ascdesc[0] = "0"; 
	$ascdesc[1] = "0";
	if(isset($_COOKIE["sort_"]))
	{
		$_GET["sort"] = $_COOKIE["sort_"];
	}
	if(!isset($_GET["sort"]))
	{
		$_GET["sort"] = "10";
	}
	if(isset($_GET["sort"]))
	{
		switch(substr($_GET["sort"],0,1))
		{
			case "0":
				$sortname = " goods.name [sort]";
				$sorttype = 0;
				break;
			case "1":
				$sortname = "vprice [sort]";
				$sorttype = 1;
				break;	
		}
		switch(substr($_GET["sort"],1,1))
		{
			case "0":
				$sortname = str_replace("[sort]"," ASC ",$sortname);
				$ascdesc[substr($_GET["sort"],0,1)] = "1";
				break;
			case "1":
				$sortname = str_replace("[sort]"," DESC ",$sortname);
				$ascdesc[substr($_GET["sort"],0,1)] = "0";
				break;	
		}
	}
	define("_SORT_NAME"," goods.exist_type ASC, " . $sortname);
}


function get_price($price,$currencyid,$goodsid,$base_currency = "",$base_price = false)
{
	global $currency_array;
	global $cross_course;
	$gcurrency = "";
	$money_course = "";
	$money_course = $currency_array[$currencyid]["course"];
	$discount = 0;
	if(setting("discount_mode") == 2   && isset($_SESSION["login_user"]) && !$base_price)
	{
		$discount= $_SESSION["login_user"]["discount"];
		$discount_summa = $price*$discount/100;
		$price = $price - $discount_summa;
	}
	if(!empty($base_currency))
	{
		$gcurrency = $base_currency;
	}
	else
	{
		$gcurrency = execute_scalar("SELECT currency FROM goods WHERE id = '$goodsid'");
	}
	if($gcurrency == $currencyid)
	{
		return number_format($price, setting("price_digit"), '.', '');
	}
	else
	{	
		if(isset($cross_course[$gcurrency][$currencyid])) {
			$current_cross_course = $cross_course[$gcurrency][$currencyid];
		}
		else
		{		
			$current_cross_course = execute_scalar("SELECT course FROM currency_course WHERE from_currency = $gcurrency AND to_currency = ".$currencyid);
		}	
		$cross_price = $price*$current_cross_course;
		
		return number_format($cross_price, setting("price_digit"), '.', '');
		
		
	}
		
	
	return number_format($price*$money_course, setting("price_digit"), '.', '');
	
	
}
class BasketItem
{
	var $q = 0;
	var $params = array(); //массив до параметров
	function BasketItem($q,$params)
	{
		$this->q = $q;
		$this->params = $params;
	}
}
class BasketValues
{
    var $q = 0;
    var $sum = array();
    function BasketValues($currency = array())
    {
        global $base_currency;
		$basket_array = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) : array();
        $base_summa = array();
		foreach($currency as $key)
		{
			$this->sum[$key] = 0;
			$base_summa[$key] = 0;
		}
		$ind = 0;
		
        if(count($basket_array) > 0)
        {
           
           $sql = mysql_query("SELECT goods.id,goods.price,goods.price_action,goods.currency FROM goods
                WHERE goods.id IN (".implode(",",array_keys($basket_array)).")");
            while($r = mysql_fetch_assoc($sql))
            {
                $base_summa[$base_currency] =  $base_summa[$base_currency] + get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],$base_currency,$r["id"],$r["currency"],true)*$basket_array[$r["id"]]->q;
				foreach($currency as $key)
                {
					$this->sum[$key] =  $this->sum[$key] + get_price($r["price_action"] > 0 ? $r["price_action"] : $r["price"],$key,$r["id"],$r["currency"],(setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? true : false)*$basket_array[$r["id"]]->q;
					
					 $this->q = $this->q + $basket_array[$r["id"]]->q;
					$ind++;
                }
            }
			
			 if(setting("discount_mode") == 1 || setting("discount_mode") == 3 || !isset($_SESSION["login_user"])) 
			 {	
				$discount = get_discount($base_summa[$base_currency]);
				if($discount > 0)
				{
					$discount_summa = number_format(((setting("discount_mode") == 3 && isset($_SESSION["login_user"])) ? $base_summa[_DISPLAY_CURRENCY] : $this->sum[_DISPLAY_CURRENCY])*$discount/100,setting("price_digit"), '.', '');
					$this->sum[_DISPLAY_CURRENCY] = $this->sum[_DISPLAY_CURRENCY] - $discount_summa;
				}
				
			 }
        }
		if($ind == 0)
		{
			$this->q = 0;
		}
		else
		{
				$current_delivery = isset($_SESSION["b_delivery"]) ? $_SESSION["b_delivery"] : execute_scalar("SELECT id FROM delivery");
				$delivery_summ = ceil(get_delivery_summ($this->sum[_DISPLAY_CURRENCY],$current_delivery));
			$this->sum[_DISPLAY_CURRENCY] = $this->sum[_DISPLAY_CURRENCY] +0 ;
				
		}
    }
}
function get_discount($summa)
{
	$discount = max($summa > 0 ? execute_scalar("SELECT percent FROM discounts WHERE summa <= ".$summa." ORDER BY percent DESC ") : 0,isset($_SESSION["login_user"]) ? $_SESSION["login_user"]["discount"] : 0);
	return number_format($discount, setting("price_digit"), '.', '');
}
function get_accompanying_goods($tovar,$rand = false,$limit = 10)
{
    global $base_currency;
    $arr = array();
    $sql = mysql_query("SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
        FROM goods
		INNER JOIN satellites ON satellites.goodsid = goods.id
        LEFT JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
	WHERE satellites.parentid = $tovar  ".($rand ? " ORDER BY rand() " : "ORDER BY name")." LIMIT 0,$limit");
    while($r = mysql_fetch_assoc($sql))
    {
        $r["show_price"] = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$r["show_price_action"]  = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$r["currency_symbol"] = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$r["tovar_url"] = get_product_url($r);
		array_push($arr, $r);
    }
    return $arr;
}
function get_products($parent,$rand = false,$limit = 10,$where = "",$orderby = "")
{
    global $base_currency;
	global $currency_array;
    $arr = array();
    
	$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
            IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
			(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imformat
        FROM goods
        LEFT JOIN catalog ON catalog.id = goods.parentid
		LEFT JOIN brands ON brands.id = goods.brand
	WHERE 1 = 1 ".($parent != 0 ? " AND goods.parentid IN ($parent) " : "")."  $where ".($rand ? " ORDER BY rand() " : $orderby)." LIMIT 0,$limit";
	
	
	global $db;
	$result = dbQuery($sql_text,$db);
	
    foreach($result as $r)
    {
        $r["show_price"] = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$r["show_price_action"]  = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
		$r["currency_symbol"] = $currency_array[_DISPLAY_CURRENCY]["shortname"];
		$r["tovar_url"] = get_product_url($r);
		array_push($arr, $r);
    }
    return $arr;
}
function get_category($code,$rand = false,$limit = 10,$orderby = "")
{
    return get_products(0,$rand,$limit,$where = " AND goods.".$code." = 1 ",$orderby);
}
function get_child_id($parentid,&$child_array)
{
	global $catalog_array;

	foreach(array_keys($catalog_array) as $current)
	{
		if($catalog_array[$current]["parent"] == $parentid)
		{
			array_push($child_array,$current);
			get_child_id($current,$child_array); 
		}
	}
}
function get_delivery_summ($summa,$deliveryid,$to_base = false)
{
	global $base_currency;
	global $cross_course;
	if($deliveryid == "")
	{
		return 0;
	}
	$res = 0;
	$row = execute_row_assoc("SELECT * FROM delivery WHERE id = ".$deliveryid);
	$cross_course = $cross_course[$base_currency][_DISPLAY_CURRENCY];

	if($to_base)
	{
		$cross_course = 1;
	}
	if($summa >= $row["maxsum"]*$cross_course)
	{
		$res =  "0";
	}
	else
	{
		$res = $row["price"]*$cross_course;
	}
	return $res;
}
function get_catalog_items($parent = 0)
{	
	global $catalog_array;	
	$arr = array();	
	foreach(array_keys($catalog_array) as $key)	
	{		
		if($catalog_array[$key]["parent"] == $parent)		
		{			
			$arr[$key] = $catalog_array[$key]["name"];		
		}	
	}	
	return $arr;
}
function get_catalog_items_all($parent = 0)
{	
	global $catalog_array;	
	$arr = array();	
	foreach(array_keys($catalog_array) as $key)	
	{		
		if($catalog_array[$key]["parent"] == $parent)		
		{			
			$arr[$key] = $catalog_array[$key];		
		}	
	}	
	return $arr;
}

function return_catalog_items(&$arr,$parent,$ind)
{
	global $catalog_array;	
	$ind++;
	foreach(array_keys($catalog_array) as $key)	
	{		
		if($catalog_array[$key]["parent"] == $parent)		
		{			
			$arr[] = array("name"=>$catalog_array[$key]["name"],"id"=>$key,"index"=>$ind);	
			return_catalog_items($arr,$key,$ind);			
		}	
	}	
}
function get_catalog_tree()
{
	$arr = array();
	return_catalog_items($arr,0,0);
	return $arr;
}

function get_catalog_path()
{
	global $chainlet;
	global $catalog_array;
	$arr =  $chainlet;
	$arr = array_reverse($arr);
	reset($arr);
	$is_catalog = TRUE;
	include(_DIR._TEMPLATE."breadcrumbs.html");
}
function get_tovar_main_image($id,$resize = 130,$action = "0",$imid = "",$format = "") //$action 0 - default, h, w
{
	if($imid != "" && $format != "")
	{
		$images["id"] = $imid;
		$images["format"] = $format;
	}
	else
	{
		$images = execute_row_assoc("SELECT id,format FROM images WHERE parentid = '$id' AND source = 3  ORDER BY is_main DESC LIMIT 0,1");
	}
	
	if(file_exists(_DIR."images/thumbs/".$images["id"]."_$resize.".$images["format"]))
	{
		return 	_SITE."images/thumbs/".$images["id"]."_$resize.".$images["format"];
	}
	else
	{
		if($images != "")
		{
			switch($action)
			{
				case "h":
					return  _SITE."images/h/$resize/".$images["id"].".jpg";
					break;
				case "w":
					return _SITE."images/w/$resize/".$images["id"].".jpg";
				break;	
				default:
					return _SITE."images/$resize/".$images["id"].".jpg";
				break;
			}
		}
		else
		{
			return _TEMPL."images/nofoto.jpg";;
		}
	}
}
function get_catalog_main_image($id,$resize = 130,$action = "0",$imid = "",$format = "") //$action 0 - default, h, w
{
	if($imid != "" && $format != "")
	{
		$images["id"] = $imid;
		$images["format"] = $format;
	}
	else
	{
		$images = execute_row_assoc("SELECT id,format FROM images WHERE parentid = '$id' AND source = 2  ORDER BY is_main DESC LIMIT 0,1");
	}
	
	if(file_exists(_DIR."images/thumbs/".$images["id"]."_$resize.".$images["format"]))
	{
		return 	_SITE."images/thumbs/".$images["id"]."_$resize.".$images["format"];
	}
	else
	{
		if($images != "")
		{
			switch($action)
			{
				case "h":
					return  _SITE."images/h/$resize/".$images["id"].".jpg";
					break;
				case "w":
					return _SITE."images/w/$resize/".$images["id"].".jpg";
				break;	
				default:
					return _SITE."images/$resize/".$images["id"].".jpg";
				break;
			}
		}
		else
		{
			return _TEMPL."images/nofoto.jpg";;
		}
	}
}
function tovar_compare_checkbox($id,$parentid,$text = "")
{
	$parent = 0;
	switch(setting("goods_compare_type"))
	{
		case "0":
			$parent = $parentid ;
			break;
		case "1":
			$parent = 0;
			break;
		case "2":
			$parent = _TOP_LEVEL;
			break;
	}
	?>
	<input type="checkbox" onclick="compare(<?=$id?>,<?=$parent?>,this)" id="in_compare_<?=$id?>"  /><?=($text != "" ? "&nbsp;&nbsp;".$text : "")?><script type="text/javascript" language="JavaScript">check_in_compare(<?=$id?>,<?=$parent?>);</script><?
}
function send_mime_mail($name_from, // имя отправителя
                        $email_from, // email отправителя
                        $name_to, // имя получателя
                        $email_to, // email получателя
                        $data_charset, // кодировка переданных данных
                        $send_charset, // кодировка письма
                        $subject, // тема письма
                        $body // текст письма
                        ) {
  $to = mime_header_encode($name_to, $data_charset, $send_charset)
                 . ' <' . $email_to . '>';
  $subject = mime_header_encode($subject, $data_charset, $send_charset);
  $from =  mime_header_encode($name_from, $data_charset, $send_charset) 
                     .' <' . $email_from . '>'; 
  if($data_charset != $send_charset) {
    $body = iconv($data_charset, $send_charset, $body); 
  }
  $headers = "From: $from\r\n";
  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
  $headers .= "Mime-Version: 1.0\r\n";
  $headers .= "X-Mailer: PHP/".phpversion()."\r\n";  

  return mail($to, $subject, $body, $headers);
}

function mime_header_encode($str, $data_charset, $send_charset) {
  if($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
  }
  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}
function get_opened_nodes($parent,$level,$use_plusminus = false)
{
	global $catalog_array;
	global $chainlet;

	$arr = get_catalog_items($parent);
	if(count($arr) > 0) {
	?><div class="sublevel" id="ch<?=$parent?>" ><?
	foreach($arr as $key=>$value)
	{
			$link = _SITE.$catalog_array[$key]["full_url"];
			?><div id="c<?=$key?>" class="c<?=$level.(in_array($key,$chainlet) ? "current" : "")?>"><?
			if($use_plusminus && $catalog_array[$key]["scount"] > 0)
			{
				?>
				<a rel="nofollow" href="javascript:void(0);" id="ac<?=$key?>"  class="<?=(in_array($key,$chainlet) ? "minus" : "plus")?>" onclick="open_child('<?=$key?>',<?=$level?>)"><img alt="Развернуть ветку" src="<?=_TEMPL?>images/<?=(in_array($key,$chainlet) ? "minus" : "plus")?>.png" /></a>
				<?
			}
			else
			{
				?>
				<?
			}
			?><a rel="nofollow"  href="<?=$link?>"><?=$value?></a></div><?
			if(in_array($key,$chainlet))
			{
				get_opened_nodes($key,$level+1);
			}
			
	}
	?></div><? }
}

function get_tree($use_plusminus = false,$catalog_class_array = array()) //create html tree, $catalog_class_array - custom class for link
{
	$level = 1;
	global $catalog_array;
	$arr = get_catalog_items();
	foreach($arr as $key=>$value)
	{
		$link = _SITE.$catalog_array[$key]["full_url"];
		
		?><div id="c<?=$key?>" class="c<?=$level.(_TOP_LEVEL == $key ? "current" : "")?>"><?
		
		if($use_plusminus && $catalog_array[$key]["scount"] > 0)
		{
			?>
			<a rel="nofollow" href="javascript:void(0);" id="ac<?=$key?>"  class="<?=(_TOP_LEVEL == $key ? "minus" : "plus")?>" onclick="open_child('<?=$key?>',<?=$level?>)"><img alt="Развернуть ветку" src="<?=_TEMPL?>images/<?=(_TOP_LEVEL == $key ? "minus" : "plus")?>.png" /></a>
			<?
		}
		?>
		<a  <?=(isset($catalog_class_array[$key]) ? "class='".$catalog_class_array[$key]."'" : "")?> href="<?=$link?>"><?=$value?></a></div><?
		if(_TOP_LEVEL == $key)
		{
			get_opened_nodes($key,$level+1,$use_plusminus);
		}
		
	}
	
}
function get_product_url($r)
{
	$tovar_url = _SITE;
	if(setting("seo_url") == "1")
	{
		global $catalog_array;	
		//$tovar_url .= $catalog_array[$r["parentid"]]["url"]."/".$r["brandurl"]."/".$r["urlname"].".html";
		//$tovar_url .= $catalog_array[$r["parentid"]]["full_url"]."/".$r["urlname"].".html";
		$tovar_url .= $r["urlname"]."-p" . $r["id"] . "/";
	}
	else
	{
		if(isset($r["goodsid"]) && $r["goodsid"] > 0)
		{
			$r["id"] = $r["goodsid"];
		}
		
		if(trim($r["urlname"]) != "")
		{
			$tovar_url .= "t".$r["id"]."-".urlencode(str_replace("&amp;","_",strtolower(trim($r["urlname"])))).".html";
		}
		else
		{
			$tovar_url .= "t".$r["id"]."-".create_urlname($r["name"]).".html";
		}
	}
	return strtolower($tovar_url);
}

function get_viewed_products($limit,$id = 0)
{
	$arr = is_array($_SESSION["viewed"]) ? $_SESSION["viewed"] : array();
	$arr = array_reverse($arr);
	$ind = 0;
	$new_array = array();
	foreach($arr as $current)
	{
		$ind++;
		$new_array[] = $current;
		if($ind == 5)
		{
			break;
		}
	}
	$arr = get_products(0,false,$limit,"AND goods.id != " . $id . " AND goods.id IN(".implode(",",$new_array).")");
	return $arr;
	
}
?>
