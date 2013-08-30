<?php
session_start();
require("inc/constant.php");
require("inc/connection.php");
require("inc/global.php");
require("inc/protection.php");

if(isset($_POST["stext"]))
{
	$_POST["stext"] = stripcslashes(strip_tags($_POST["stext"]));
	$_POST["stext"] = str_ireplace(array(".","drop","delete","update","insert","select","#","\\","/",":","%"),"",$_POST["stext"]);
	if(empty($_POST["stext"]))
	{
		header("Location: "._SITE); 
	}
	else
	{
		header("Location: "._SITE."asearch/".(isset($_POST["scategory"]) ? $_POST["scategory"] : 0).":".urlencode($_POST["stext"]).".html");
	}	
}
else
{
	if($_GET["id"] == "0")
	{
		$get = "";
		foreach($_POST as $key=>$value)
		{
			$get .= "&".$key."=".$value;
		}
		header("Location: "._SITE."catalog-search.html?id=0".$get);
		exit();
	}
	if(empty($_GET["id"]) && !empty($_POST["id"]))
	{
		$_GET["id"] = $_POST["id"];
	}
	
	$url_params = "id=".$_GET["id"];
	
	if((int)$_POST["price_start"] > 0)
	{
		$url_params .= "&price_start=".(int)$_POST["price_start"];
	}
	if(isset($_POST["exist_type"] ) && $_POST["exist_type"] != "0")
	{
		$url_params .= "&et=".(isset($_POST["exist_type"]) ? implode(",",$_POST["exist_type"]) : "0");
	}
	if((int)$_POST["price_finish"] > 0)
	{
		$url_params .= "&price_finish=".(int)$_POST["price_finish"];
	}
	
	if(isset($_POST["brand"] ) && $_POST["brand"] != "0")
	{
		$url_params .= "&brand=".(isset($_POST["brand"]) ? (is_array($_POST["brand"]) ? implode(",",$_POST["brand"]) : $_POST["brand"] ): "0");
	}	
	
	$add_params = explode(",",setting("search-get-params"));
	
	foreach($add_params as $current)
	{
		if(isset($_POST[$current]))
		{
			if($_POST[$current] != "0")
			{
				$url_params .= "&" . $current . "=" .  $_POST[$current];
			}	
		}
	}
	
	$fields = mysql_query("SELECT f.* FROM `fields` as f
    LEFT JOIN categoryfields cf ON   cf.fieldid = f.id
    WHERE f.id IS NOT NULL  AND f.search = 1 AND (cf.categoryid IN ($_GET[id]) OR f.isgeneral = 1)  
    ORDER BY cf.hide_default ASC,cf.showorder");
	while($field = mysql_fetch_assoc($fields))
	{
		
		if($field["isinterval"] == 0 && empty($_POST[$field["rname"]]))
		{
			continue;
		}
		
		switch($field["field_type"])
		{
			case "1": //Текст
				if(isset($_POST[$field["rname"]]))
				{
					$url_params .= "&".$field["rname"]."=".$_POST[$field["rname"]];
				}	
				break;
			case "2": //Список
				if(isset($_POST[$field["rname"]]) && $_POST[$field["rname"]] != "0")
				{
					$url_params .= "&".$field["rname"]."=".(is_array($_POST[$field["rname"]]) ? implode(",",$_POST[$field["rname"]]) : intval($_POST[$field["rname"]]));
				}
				break;
			case "3": //Число
				if($field["isinterval"] == 1)
				{
					if(isset($_POST[$field["rname"]."_start"]) && isset($_POST[$field["rname"]."_finish"]))
					{
						$url_params .= "&".$field["rname"]."s=".((int)$_POST[$field["rname"]."_start"]);
						$url_params .= "&".$field["rname"]."f=".((int)$_POST[$field["rname"]."_finish"]);
					}
				}
				else
				{
					if(isset($_POST[$field["rname"]]) && $_POST[$field["rname"]] > 0)
					{
						$url_params .= "&".$field["rname"]."=".$_POST[$field["rname"]];
					}	
				}
				break;
			case "4": //Флажок
				if(isset($_POST[$field["rname"]]))
				{
					$url_params .= "&".$field["rname"]."=1";
				}
				else
				{
					$url_params .= "&".$field["rname"]."=0";
				}	
				break;	
			case "5":
				if(isset($_POST[$field["rname"]]) && $_POST[$field["rname"]] != "0")
				{
					$url_params .= "&".$field["rname"]."=".(is_array($_POST[$field["rname"]]) ? implode(",",$_POST[$field["rname"]]) : intval($_POST[$field["rname"]]));
				}
				break;
		}
	}
	header("Location: "._SITE."search.html?".$url_params);
}
?>
