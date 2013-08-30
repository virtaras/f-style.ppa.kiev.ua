<?
session_start();
header('Content-Type: text/javascript; charset=utf-8');

require("inc/constant.php");
require("inc/connection.php");
require("inc/global.php");
require("inc/emarket.php");
require("inc/engine.php");
function show_compared($parent)
{
	  if(isset($_SESSION["compare_".$parent]))
	 {
	 $arr = $_SESSION["compare_".$parent];
	 if(count( $arr) > 0) {
				$sparent = 0;
				switch(setting("goods_compare_type"))
				{
					case "0":
						$sparent = $parent;
						break;
					case "1":
						$sparent = 0;
						break;
					case "2":
						$sparent = $parent;
						break;
				}
				
				$sql = mysql_query("SELECT goods.* ,
				(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1) as imformat
				FROM goods 
				LEFT JOIN brands ON brands.id = goods.brand
				WHERE goods.id IN (".implode(",",$arr).")");
				$ind = 0;
				$itemcount = mysql_num_rows($sql);
                while($r = mysql_fetch_assoc($sql))
                {
                    $ind++;
					$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
					$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
					$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
					$tovar_url = get_product_url($r);
					include(_DIR."templates/compareitem.html");
					
					/*
					?>
					
                <div  class="compare_item"><a href="javascript:void(0);" style="color:#1FB5F4; font-size:10px; text-decoration:none; font-weight:bold; font-family:verdana; padding:0px 0px 0px 0px;" onclick="$('#compare_div').load(baseurl+'ajax.php',{action:'compare',tovarid:<?=$r["id"]?>,parent:<?=$sparent?>,rm:1},after_compare);" >X</a>&nbsp;<a href="<?=$tovar_url?>"><?=$r["brandname"]?> <?=$r["name"]?></a></div>
                    <? */
                }
 }
}
}
if(isset($_POST["action"]))
{
	switch($_POST["action"])
	{
		
		case "show_useful":
			
			$r = execute_row_assoc("SELECT id,useful,unuseful FROM comments WHERE  id = '".intval($_POST["comment"])."'");
			?>
			Отзыв полезен ?&nbsp;<a href="javascript:void(0);" style="color:green;" onclick="set_useful(<?=$r["id"]?>,0)" >Да</a>&nbsp;<?=$r["useful"]?>&nbsp;/&nbsp;<a href="javascript:void(0);" style="color:red;"  onclick="set_useful(<?=$r["id"]?>,1)" >Нет</a>&nbsp;<?=$r["unuseful"]?>
			<?
			
			break;
		
		case "set_useful":
			
			if(!isset($_COOKIE["comment" . $_POST["comment"]])) {
			if($_POST["type"] == "0") //useful
			{
				$current = execute_scalar("SELECT useful FROM comments WHERE id = '".intval($_POST["comment"])."'");

				mysql_query("UPDATE comments SET useful = '".($current + 1)."' WHERE id = '".intval($_POST["comment"])."'");
			}
			else //unusefull
			{
				$current = execute_scalar("SELECT unuseful FROM comments WHERE id = '".intval($_POST["comment"])."'");

				mysql_query("UPDATE comments SET unuseful = '".($current + 1)."' WHERE id = '".intval($_POST["comment"])."'");
			}
			setcookie("comment" . $_POST["comment"],1,(time()+60*60*24*30),"/");
			}
			break;
		
		case "order_comment":
			mysql_query("INSERT INTO orders_comments (send_date,orderid,userid,comment) VALUES (now(),'".intval($_POST["orderid"])."','".$_SESSION["login_user"]["id"]."','".mysql_escape_string(htmlspecialchars(strip_tags($_POST["comment"]),ENT_QUOTES))."')");
			
			send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",setting("contact_email"), "UTF-8", "UTF-8", "Добавлен комментарий к заказу", "<p>
			" . htmlspecialchars($_POST["comment"]) . "
			</p><p>
			<a href='"._SITE."direct/index.php?t=orders&id=".$_POST["orderid"]."' target='_blank' >Перейти к заказу</a>
			</p>" );
			
			break;
		
		case "set_rating":
			if(!isset($_COOKIE["rating" . $_POST["product"]])) {
				
				mysql_query("INSERT INTO ratings(goodsid,rating,create_date,ip) VALUES ('".intval($_POST["product"])."','".mysql_escape_string($_POST["rating"])."',now(),'".$_SERVER["REMOTE_ADDR"]."')");
				
				//recreate rating
				
				$current = execute_row_assoc("SELECT sum(rating)/count(id)  as percent, count(id) as votes FROM ratings WHERE goodsid = '".intval($_POST["product"])."'");
				
				$current["percent"]  = round($current["percent"]*2) / 2;
							
				mysql_query("UPDATE goods SET r233 = '" . $current["percent"] . "', r243 = '" . $current["votes"] . "' WHERE id = '".intval($_POST["product"])."'");
				
				
				
				setcookie("rating" . $_POST["product"],1,(time()+60*60*24*30),"/");
			}	
			break;
		
		case "currency":
            $_SESSION["base_currency"] = $_POST["item"];
			if(isset($_SESSION["prev_url"]))
			{
				//echo $_SESSION["prev_url"];
			}	
            break;
        case "pagesize":
             setcookie("page_size_",$_POST["size"],(time()+60*60*24*30),"/");
            break;
		case "sort":
             setcookie("sort_",$_POST["sorttype"],(time()+60*60*24*30),"/");
            break;	
            case "catalog":			
			$arr = get_catalog_items($_POST["parent"]);			
			foreach($arr as $key=>$value)			
			{				?>				
			<div><a href="<?=_SITE?>catalog<?=$key?>.html"><?=$value?></a></div>
			<?			}			break;
            case "compare":
			header('Content-Type: text/html; charset=utf-8');
                $arr = array();
                if(isset($_POST["rm"]))
				{
					
					foreach($_SESSION["compare_".$_POST["parent"]] as $current)
					{
						if($current == $_POST["tovarid"])
						{
							continue;
						}		
						array_push($arr, $current);
					}
				}
				else
				{
					 if(isset($_SESSION["compare_".$_POST["parent"]]))
					{
						$arr = $_SESSION["compare_".$_POST["parent"]];
					}
					array_push($arr, $_POST["tovarid"]);
                }
				$_SESSION["compare_".$_POST["parent"]] =  $arr;
               	show_compared($_POST["parent"]);
                break;
	case "show_compare":
		show_compared($_POST["parent"]);
		break;
	case "mode":
				$_SESSION["mode_"] = $_POST["mode"];
				break;
case "show_basket":
	$b = new BasketValues(array(_DISPLAY_CURRENCY));
	include("templates/cart_panel.html");
	break;
	case "brand_by_category":
		$child_array = array($_POST["parent"]);
		get_child_id($_POST["parent"],$child_array);

		?>
		<select id="s_brand" onchange="reload_brand()" >
			<option value="0">Выбрать</option>
			<?
				
				$sql = mysql_query("SELECT brands.id,brands.name FROM goods INNER JOIN
				brands ON brands.id = goods.brand
				WHERE goods.parentid IN (".implode(',',$child_array).") GROUP BY brands.id,brands.name ORDER BY brands.name ");
				while($r = mysql_fetch_assoc($sql))
				{
					?>
					<option value="<?=$r["id"]?>"><?=$r["name"]?></option>
					<?
				}
			?>
		</select>
		<?
		break;
	case "goods_by_category_brand":
		?>
		<select id="s_item"  >
			<option value="0">Выбрать</option>
			<?
			$child_array = array($_POST["parent"]);
		get_child_id($_POST["parent"],$child_array);
			$sql = mysql_query("SELECT goods.id,goods.name FROM goods 
				WHERE goods.parentid IN (".implode(',',$child_array).") AND brand = $_POST[brand] ORDER BY goods.name ");
				while($r = mysql_fetch_assoc($sql))
				{
					?>
					<option value="<?=$r["id"]?>"><?=$r["name"]?></option>
					<?
				}
			?>
 		</select>	
		<?
		break;
	case "in_compare":	
		$checked = "false";
		$tovarid = $_POST["tovarid"];
		$parentid = $_POST["parent"];
		if(isset($_SESSION["compare_".$parentid]))
		{
			if(in_array($tovarid,$_SESSION["compare_".$parentid]))
			{
				$checked = "true";
			}
		}
		echo $checked;
		break;	
		case "load_content":
			echo htmlspecialchars_decode(execute_scalar("SELECT content_1 FROM catalog WHERE id = ".$_POST["catalogid"]));
			break;
		case "tovar_info":
			$row = execute_row_assoc("SELECT id,urlname,name FROM goods WHERE id = ".$_POST["id"]);
			?>
			<table	width="100%">
				<tr>
					<td><a><img src="<?=get_tovar_main_image($row["id"],150,"h")?>" alt="<?=$row["name"]?>" /></a></td>
					<td></td>
				</tr>
			</table>
			<?
			break;
			case "show_subcatalog":
				$arr = get_catalog_items($_POST["id"]);
				foreach($arr as $key=>$value)
				{
					echo "<div  id='c$key' class='c".($_POST["l"] + 1)."'>";
					if($catalog_array[$key]["scount"] > 0)
					{
						?>
						<a href="javascript:void(0);" id="ac<?=$key?>" class="plus" onclick="open_child('<?=$key?>',<?=($_POST["l"] + 1)?>)"><img alt="Развернуть ветку" src="<?=_TEMPL?>images/plus.png" /></a>
						<?
					}
					?>
						<a href="<?=_SITE.$catalog_array[$key]["full_url"]?>"><?=$value?></a>
					<?
					echo "</div>";
				}
			break;
			case "load_models":
					?>
						<select name="r190">
						<option value="0">Выберите модель</option>
						<?
						$sql = mysql_query("SELECT id,name FROM r190 WHERE parentid = '$_POST[parent]' ORDER BY name");
						while($r = mysql_fetch_assoc($sql))
						{
							?>
							<option <?=($_POST["selected"] == $r["id"] ? "selected='selected'" : "")?> value="<?=$r["id"]?>"><?=$r["name"]?></option>
							<?
						}
						?>
					</select>
					<?
				break;
				case "reload_cart_popup":
					include(_DIR._TEMPLATE."cart_popup.php");
					break;
        }
}
if(isset($_POST["action"]))
{
	switch($_POST["action"])
	{
		case "fast_search":
		header('Content-Type: text/html; charset=utf-8');
		$stext_array = explode(" ",$_POST["term"]);
		
		$where = "";
		foreach($stext_array as $current)
		{
			if($current == "")
			{
				continue;
			}
			if(strlen($current) >= 3 && in_array( substr($current, strlen($current) - 1, 1), array("и","ы")))
			{
				$current = substr($current, 0, strlen($current) - 1);
			}
			$where .= " AND CONCAT(LCASE(name),LCASE(IF(code IS NULL,'',code))) RLIKE '".($current)."'"; 
		}
		
		
		$sql = mysql_query("SELECT id,name FROM goods WHERE goodsid = 0 AND goods.price > 0 $where LIMIT 0,20");
		
		
		
		echo "{[";
		$ind = 0;
		while($r = mysql_fetch_assoc($sql))
		{
			$ind++;
			echo "\"".str_replace("\"","\\\"",htmlspecialchars_decode($r["name"],ENT_QUOTES))."\"";
			if($ind < mysql_num_rows($sql))
			{
				echo ",";
			}
		}
		echo "]}";
			break;
		
	}
}

if(isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "cart_set_quantity":
				$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :  array();
				$q = (isset($_GET["q"]) ? (int)$_GET["q"] : 1);
				$id = $_GET["id"];
				if($q <= 0)
				{
					$q = 1;
				}
				if(isset($arr[$id]))
				{
					
					$arr[$id]->q = $q;
				}
				else
				{
					$arr[$id] = new BasketItem($q,isset($_GET["params"]) ? $_GET["params"] : "");
				}
				$_SESSION["basket"] = serialize($arr);
				setcookie("basket",$_SESSION["basket"],(time()+60*60*24*30),"/");
			break;
		case "cart_remove_item":
			$arr = isset($_SESSION["basket"]) ? unserialize($_SESSION["basket"]) :   array();
			unset($arr[$_GET["rm"]]);
			$_SESSION["basket"] = serialize($arr);
			setcookie("basket",$_SESSION["basket"],(time()+60*60*24*30),"/");
			break;	
	}
}

?>