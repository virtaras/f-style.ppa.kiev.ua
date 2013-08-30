<?php
$head = execute_row_assoc("SELECT * FROM catalog WHERE ID = '$_GET[id]'");
if($head["id"] == "")
{
	header("HTTP/1.0 404 Not Found");
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	die(); 

}
$title = $head["title"] != "" ? $head["title"] : $head["name"];
$description = $head["description"];
$keywords = $head["keywords"];
$title = $head["name"];
function get_content()
{
	
	global $head;
	global $catalog_array;
	global $currency_array;
	global $base_currency;
	$current_category = $_GET["id"];
	
	if(isset($_COOKIE["page_size_"]))
	{
		$pagesize = $_COOKIE["page_size_"];
	}
	else
	{
		$pagesize = setting("default_page_size");
	}	
		//require("filters.php");

		//require("sort.php");

		
		
			$catalogID = explode(",",$_GET["id"]);
			
			
			
			if(count($catalogID)  > 1)
			{
				$child_array = array();
				
				foreach($catalogID as $ID)
				{
					$child_array[] = $ID;
					get_child_id($ID,&$child_array);
				}	
			}
			else
			{
				$child_array = array($head["id"]);
				get_child_id($_GET["id"],&$child_array);
			}
			
			$where = "";
			if(isset($_GET["price_start"]) && $_GET["price_start"] >= 0)
			{
                $min = explode(',', $_GET["price_start"]);
                sort($min);
                reset($min);

                $max = explode(',', $_GET["price_finish"]);
                rsort($max);
                reset($max);
				$where .= " AND  IF(goods.price_action > 0,goods.price_action,goods.price)*(SELECT course FROM currency_course WHERE from_currency = goods.currency AND to_currency = "._DISPLAY_CURRENCY.") >=   ".$min[0];
			}
			if(isset($_GET["price_finish"]) && $_GET["price_finish"] > 0)
			{
				$where .= " AND  IF(goods.price_action > 0,goods.price_action,goods.price)*(SELECT course FROM currency_course WHERE from_currency = goods.currency AND to_currency = "._DISPLAY_CURRENCY.") <= ".$max[0];
			}
			$varinats_brands_where = "";
			if(isset($_GET["brand"]))
			{
				if(setting("use_variants") == "0")
				{
					$where .= " AND goods.brand IN (".$_GET["brand"].")  ";
				}	
				else
				{
					$varinats_brands_where = " AND goods.brand IN (".$_GET["brand"].")  ";
				}
			}
			if(isset($_GET["et"]))
			{
				if(setting("use_variants") == "0")
				{
					$where .= " AND goods.exist_type IN (".$_GET["et"].")  ";
				}	
				else
				{
					$varinats_brands_where = " AND goods.exist_type IN (".$_GET["et"].")  ";
				}
			}
			$fields = mysql_query("SELECT f.* FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL  AND f.search = 1 AND (cf.categoryid IN ($_GET[id]) OR f.isgeneral = 1)  
    ORDER BY cf.hide_default ASC,cf.showorder");
			$i = 4;
			while($field = mysql_fetch_assoc($fields))
			{
				if(!isset($_GET[$field["rname"]]))
				{
					continue;
				}
				switch($field["field_type"])
				{
					case "1": //Текст
						if(isset($_GET[$field["rname"]]) != "")
						{
							$where .= " AND goods.".$field["rname"]." RLIKE '%".$_GET[$field["rname"]]."%'";
						}
						break;
					case "2": //Список
						if(isset($_GET[$field["rname"]]))
						{
							$where .= " AND goods.id IN (SELECT goodsid FROM goods WHERE goods.".$field["rname"]." IN (".$_GET[$field["rname"]]."))";
						}
						break;
					case "3": //Число
						
						if($field["isinterval"] == 1)
						{
							if(isset($_GET[$field["rname"]."s"]) && intval($_GET[$field["rname"]."s"]) > 0)
							{
								$where .= " AND goods.".$field["rname"]." >=  ".$_GET[$field["rname"]."s"];
							}
							if(isset($_GET[$field["rname"]."f"]) && intval($_GET[$field["rname"]."f"]) > 0)
							{
								$where .= " AND goods.".$field["rname"]." <= ".$_GET[$field["rname"]."f"];
							}	
						}
						else
						{
							if(isset($_GET[$field["rname"]]))
							{
								$where .= " AND goods.".$field["rname"]." = ".$_GET[$field["rname"]]."";
							}
						}
						break;	
					case "4": //Флажок
						if($search[$i] == "1")
						{
							$where .= " AND goods.".$_GET[$field["rname"]]." = 1";
						}
						break;	
					case "5":
							$where .= " AND goods.id IN (SELECT goodsid FROM s" . $field["table_name"] . " WHERE valueid IN (".$_GET[$field["rname"]].") ) "; 
						break;
				}
				$i++;
			}
			if(setting("enable_search_variant") == 1)
			{
				$where .= " AND goods.goodsid > 0 ";
			}
			else
			{
				$where .= " AND goods.goodsid = 0 ";
			}
			
			//echo $where;
			
			
				$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
				IF(goods.price_action > 0,goods.price_action,goods.price)*(SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,exist_type.name as extype,
				(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imid,
			(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imformat
			FROM goods
			INNER JOIN catalog ON catalog.id = goods.parentid
			LEFT JOIN brands ON brands.id = goods.brand
			LEFT JOIN exist_type ON exist_type.id = goods.exist_type
			WHERE goods.parentid IN (".implode(",",$child_array).") AND goods.exist_type != 2 AND goods.price > 0  $where ORDER BY "._SORT_NAME;
			
			$count_sql_str = "SELECT count(goods.id)
			FROM goods
			INNER JOIN catalog ON catalog.id = goods.parentid
			WHERE goods.parentid IN (".implode(",",$child_array).")  AND goods.price > 0 $where ";

		
//		echo $sql_text;
		//echo $count_sql_str ;
		
		require("paging.php");
		include(_TEMPLATE."catalog_header.html");
        $sql_text = $sql_text.$limit;
		
        require("catalogitem.php");

        include(_TEMPLATE."catalog_footer.html");
}
?>