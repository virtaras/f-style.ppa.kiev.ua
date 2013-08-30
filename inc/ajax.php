<?
session_start();
header('Content-Type: text/javascript; charset=utf-8');

require("inc/constant.php");
require("inc/connection.php");
require("inc/global.php");
require("inc/emarket.php");
function show_compared($parent)
{
	  if(isset($_SESSION["compare_".$parent]))
	 {
	 $arr = $_SESSION["compare_".$parent];
	 if(count( $arr) > 0) {
				?>
<div class="panel_title"></div>		   

         <div class="catalog_border">
                <?
				$sql = mysql_query("SELECT id,name,parentid FROM goods WHERE id IN (".implode(",",$arr).")");
                while($r = mysql_fetch_assoc($sql))
                {
                    ?>
					<div class="headerblue blockheader"> Сравнить </div><br/>
                <div  class="compare_item"><a href="javascript:void(0);" style="color:#1FB5F4; font-size:10px; text-decoration:none; font-weight:bold; font-family:verdana; padding:0px 0px 0px 0px;" onclick="$('#compare_div').load(baseurl+'ajax.php',{action:'compare',tovarid:<?=$r["id"]?>,parent:<?=(setting("goods_compare_type") == "0" ? $r["parentid"] : 0)?>,rm:1},after_compare);" >X</a>&nbsp;<a href="<?=_SITE?>tovar<?=$r["id"]?>.html"><?=$r["name"]?></a></div>
                    <?
                }
                ?>
                   	
					<a class="btn_compare" href="<?=_SITE?>compare/<?=$parent?>.html"></a>
					<div style="clear:both;"></div>
		</div>	
		<div class="catalog_bottom">&nbsp;</div>
                <? }
}
}
if(isset($_POST["action"]))
{
	switch($_POST["action"])
	{
		case "currency":
            $_SESSION["base_currency"] = $_POST["item"];
			if(isset($_SESSION["prev_url"]))
			{
				echo $_SESSION["prev_url"];
			}	
            break;
        case "pagesize":
            if($_POST["s"] == "search")
            {
                $id = explode(":",$_POST['id']);
                setcookie("page_size_".$_POST["s"]."_".$id[0],$_POST["size"],(time()+60*60*24*30),"/");
            }
            else {
            setcookie("page_size_".$_POST["s"]."_".$_POST["id"],$_POST["size"],(time()+60*60*24*30),"/");
            }
            if(!empty($_POST["sorting"]))
			{
			$url = _SITE.$_POST["s"]."/".($_POST["sorting"] != "" ? "sort/".$_POST["sorting"]."/" : "").$_POST['id'].".html";
			}
			else
			{
					if($_POST["s"] == "search")
				{
					$url = _SITE.$_POST["s"]."/".$_POST['id'].".html";
				}
				else
				{
					$url = _SITE.$_POST["s"].$_POST['id'].".html";
				}	
			}
            echo $url ;
            break;
            case "catalog":			
			$arr = get_catalog_items($_POST["parent"]);			
			foreach($arr as $key=>$value)			
			{				?>				
			<div><a href="<?=_SITE?>catalog<?=$key?>.html"><?=$value?></a></div>
			<?			}			break;
            case "compare":
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
        }
}
if(isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "fast_search":
		header('Content-Type: text/html; charset=utf-8');
		$sql = mysql_query("SELECT id,name FROM search_goods WHERE name RLIKE '".$_GET["term"]."'");
		echo "[";
		$inf = 0;
		while($r = mysql_fetch_assoc($sql))
		{
			$ind++;
			echo "\"$r[name]\"";
			if($ind < mysql_num_rows($sql))
			{
				echo ",";
			}
		}
		echo "]";
			break;
	}
}
?>