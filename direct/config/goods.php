<?php 

	$title = "Товары";
	$table_edit_mode = "1";
	$source_key_field = "id";
	$table  = "goods";
	$title_fields = array("name"=>"Наименование","price"=>"Цена","price_action"=>"Акционная цена");
	$exclude_fields = array("id");
	$pagesize = 40;

	$edit_title_fields = array("name"=>"Наименование","extid"=>"Внешний код","title"=>"TITLE","urlname"=>"URL");
	$number_fields = array("price");
	$select_fields ="id,name,urlname,code,brand,parentid,description,full_description,price,price_action,currency,exist_type,title,meta_description,keywords,0 as satellites,extid,is_export,add_description,id as saleitem";
	if(isset($_GET["goods"]))
	{
		$select_fields = "id,goodsid,name,brand,description,price,price_action,currency,exist_type,is_export";
	}
	//*****************************************
	$parentid = new Control("parentid");
	$parentid->caption = "Владелец";
	$parentid->type = "template";
	$parentid->template = "templates/select_tree.php";
	$parentid->template_mode = "standart";
	if(isset($_GET["parent"]))
	{	
		$parentid->default_value = $_GET["parent"];

	}
	
	$saleitem =  new Control("saleitem");
	$saleitem->type = "template";
	$saleitem->template = "templates/saleitem.php";
	
	
	$price = new Control("price");
	$price->caption = "Цена";
	$price->type = "text";
	$price->css_style = "width:80px;";
	$price->default_value = "0";
	
	$price_action = new Control("price_action");
	$price_action->caption = "Акционная цена";
	$price_action->type = "text";
	$price_action->css_style = "width:80px;";
	$price_action->default_value = "0";
	
	$full_description = new Control("full_description");
	$full_description->caption = "Полное описание";
	$full_description->type = "template";
	$full_description->template = "templates/html.php";
	
	$add_description = new Control("add_description");
	$add_description->caption = "Доп. описание";
	$add_description->type = "template";
	$add_description->template = "templates/html.php";
	$add_description->template_mode = "standart";
	
	$exist = new Control("exist_type");
	$exist->caption = "Наличие";
	$exist->type = "list";
	$exist->keyfield = "id";
	$exist->textfield = "name";
	$exist->source = "SELECT id,name FROM exist_type ORDER BY name";

	
	$meta_description = new Control("meta_description");
	$meta_description->caption = "DESCRIPTION";
	$meta_description->type = "longtext";
	$meta_description->rows = 7;
	$meta_description->css_style = "width:300px;";
	
	$keywords = new Control("keywords");
	$keywords->caption = "KEYWORDS";
	$keywords->type = "longtext";
	$keywords->rows = 7;
	$keywords->css_style = "width:300px;";
	

	
	$short_description = new Control("description");
	$short_description->caption = "Краткое описание";
	$short_description->type = "longtext";
	$short_description->rows = 7;
	$short_description->css_style = "width:300px;";
	
	
	
	//*****************************************
	
	$controls = array("parentid"=>$parentid,"price"=>$price,"description"=>$short_description,"full_description"=>$full_description,"exist_type"=>$exist,"meta_description"=>$meta_description,"keywords"=>$keywords,"price_action"=>$price_action,"add_description"=>$add_description,"saleitem"=>$saleitem);
	
	$controls["gname"] = new Control("gname","label","Товар");
	
	$controls["currency"] = new Control("currency","list","Валюта цены","SELECT id,name FROM currency");
	$controls["currency"]->default_value = 1;
	$controls["title"] = new Control("title");
	$controls["title"]->caption = "TITLE";
	$controls["title"]->type = "longtext";
	$controls["title"]->rows = 7;
	$controls["title"]->css_style = "width:300px;";
	
	$controls["brand"] = new Control("brand","list","Бренд","SELECT id,name FROM brands ORDER BY name");
	if(isset($_GET["goods"]) && $_GET["id"] == "-1")
	{
		$controls["brand"]->default_value = execute_scalar("SELECT brand FROM goods WHERE id = '$_GET[goods]'");
	}
	
	$controls["brandname"] = new Control("brand","label","Бренд");
	$controls["satellites"] = new Control("satellites","template");
	$controls["satellites"]->template = "templates/satellites.php";
	
	$controls["is_export"] = new Control("is_export","checkbox","Экспорт");
	$controls["is_export"]->default_value = 1;
	
	//************************Goods Groups****************************
	$groups_sql = db_query("SELECT * FROM goods_groups");
	$ggarray = array();
	while($grow = db_fetch_assoc($groups_sql))
	{
		$controls[$grow["code"]] = new Control($grow["code"],"checkbox",$grow["name"]);
		$title_fields[$grow["code"]] = $grow["name"];
		array_push($ggarray,$grow["code"]);
		$select_fields .= ",".$grow["code"];
	}
	
	//*************************End OF Goods Groups***************************
	
	$goods_groups = new Control("gg","list","Категории товара","SELECT id,name FROM goods_groups");
	$fbrands = new Control("b","list","Бренд","SELECT * FROM brands ORDER BY name");
	$filters = array($fbrands);
	$fb = filters_get_value($fbrands);
	
		//Add user's fields
	if((isset($_GET["id"]) && $_GET["id"] != "-1" ) || (isset($_GET["id"]) && isset($_GET["parent"])) || isset($_GET["goods"]))
	{
		$catalogid = execute_scalar("SELECT parentid FROM goods WHERE id = $_GET[id]");
		if($catalogid == "" && isset($_GET["parent"]))
		{
			$catalogid = $_GET["parent"];
		}
		else
		{
			$catalogid = 0;
		}
		
		$user_fields_array = array();
		$fields_sql = mysql_query("SELECT name,table_name,field_type,rname 
		FROM `fields` f 
		LEFT JOIN categoryfields ON  categoryfields.fieldid = f.id
		WHERE source = '0' AND f.field_type != 5 AND
		(categoryfields.categoryid = '$catalogid' OR f.isgeneral = 1 )
		ORDER BY categoryfields.showorder");
		
		
		while($field = mysql_fetch_assoc($fields_sql))
		{
			switch($field["field_type"])
			{
				case "1":
					$control = new Control($field["rname"]);
					$control->caption = $field["name"];
					$control->type = "text";
					$controls[$field["rname"]] = $control;
					break;
				case "2":
					$control = new Control($field["rname"]);
					$control->caption = $field["name"];
					$control->type = "list";
					$control->source = "SELECT id,name FROM $field[table_name]";
					$control->keyfield = "id";
					$control->textfield = "name";
					$controls[$field["rname"]] = $control;
					break;
				case "3":
					$control = new Control($field["rname"]);
					$control->caption = $field["name"];
					$control->type = "text";
					$controls[$field["rname"]] = $control;
					array_push($number_fields,$field["rname"]);
					break;
				case "4":
					$control = new Control($field["rname"]);
					$control->caption = $field["name"];
					$control->type = "checkbox";
					$controls[$field["rname"]] = $control;
					array_push($number_fields,$field["rname"]);
				break;	
			}
			array_push($user_fields_array,$field["rname"]);
			$title_fields[$field["rname"]] = $field["name"];
			$select_fields .= ",$field[rname]";
		} 
	}	
	
	
	if(isset($_GET["goods"]))
	{
		$source = "SELECT goods.id,goods.name,goods.price,goods.price_action".(isset($user_fields_array) && count($user_fields_array) > 0 ? ",".implode(",",$user_fields_array) : "").(count($ggarray) > 0 ? ",".implode(",",$ggarray) : "")."   FROM goods WHERE goodsid = $_GET[goods]";
		$select_fields = "id,goods.goodsid,name,code,goods.brand,parentid,price,price_action,currency,exist_type,extid" . (isset($user_fields_array) && count($user_fields_array) > 0 ? ",".implode(",",$user_fields_array) : "");
		if(isset($_GET["id"]))
		{
			$controls["name"] = new Control("name","text","Товар");
			$controls["name"]->default_value = execute_scalar("SELECT name FROM goods WHERE id = '$_GET[goods]'");

            $controls["price"] = new Control("price","text","Цена");
			$controls["price"]->default_value = execute_scalar("SELECT price FROM goods WHERE id = '$_GET[goods]'");
		}
		else
		{
			$controls["name"] = new Control("name","label","Товар");
		}
		
		$exclude_fields_edit = array("id");
	}
	else
	{
		if(!isset($_GET["parent"]) || (isset($_GET["parent"]) && $_GET["parent"] == "0"))
		{
			$source = "SELECT goods.id,goods.parentid,0 as gvariant,goods.name ,goods.urlname,goods.code,goods.price,goods.price_action,goods.exist_type,brands.name as brandname".(isset($user_fields_array) && count($user_fields_array) > 0 ? ",".implode(",",$user_fields_array) : "").(count($ggarray) > 0 ? ",".implode(",",$ggarray) : "")."  ,(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imid
			
			FROM goods 
			LEFT JOIN brands ON brands.id = goods.brand WHERE goodsid = 0 
			";
			$count_query = "SELECT count(*) as all_count FROM goods WHERE goodsid = 0  ";
		}
		else
		{
			
			$source = "SELECT goods.id,goods.parentid,0 as gvariant,goods.name,goods.urlname,goods.code,goods.price,goods.price_action,brands.name as brandname ".(isset($user_fields_array) && count($user_fields_array) > 0 ? ",".implode(",",$user_fields_array) : "").(count($ggarray) > 0 ? ",".implode(",",$ggarray) : "")." 
			,(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1 LIMIT 0,1) as imid
			FROM goods 
			LEFT JOIN brands ON brands.id = goods.brand
			WHERE parentid = '$_GET[parent]' AND goodsid = 0 ";
			$count_query = "SELECT count(*) as all_count FROM goods WHERE parentid = '$_GET[parent]' AND goodsid = 0  ";

		}

		if($fb != "" && $fb != "-1")
		{
			$source .= " AND goods.brand = $fb ";
			$count_query .= " AND goods.brand = $fb ";		
		}
		
	}	

	array_push($exclude_fields,"parentid");
	array_push($exclude_fields,"imid");	

	
	$title_fields["exist_type"] = "Наличие";
	$title_fields["gname"] = "Товар";
	$title_fields["gvariant"] = "&nbsp;";
	$title_fields["brandname"] = "Бренд";
	$title_fields["is_export"] = "Экспорт";
	$title_fields["urlname"] = "URL";
	if(isset($_GET["goods"]))
	{
		$controls["goodsid"] = new Control("goodsid","longlist","Владелец","SELECT id,name FROM goods WHERE goodsid = 0");
		$controls["goodsid"]->tablename = "goods";
		$controls["goodsid"]->default_value = $_GET["goods"];
	}	
	
	$tab1_array = array_merge(array("code","name","urlname","brand","parentid","price","price_action","exist_type","description","currency","goodsid","extid","is_export"),$ggarray);
	
	$tab1 = new Tab("Основные данные","",$tab1_array);
	$tab2 = new Tab("Полное описание","",array("full_description","add_description"));
	$tab3 = new Tab("SEO","",array("title","meta_description","keywords"));
	$tab5 = new Tab("Сопутствующие товары","",array("satellites"));
	$tab6= new Tab("Торговые предложения","",array("saleitem"));
	if(isset($_GET["goods"]))
	{
		
		$tabs = array($tab1);
	}
	else
	{
		$tabs = array($tab1,$tab2,$tab3,$tab5,$tab6);
	}
	if(isset($user_fields_array)) 
	{
		if(count($user_fields_array) > 0)
		{
			$tab4 = new Tab("Параметры","",$user_fields_array);
			if(isset($_GET["goods"]))
			{
				$tab1->fields = array_merge($tab1->fields,$tab4->fields);
				$tabs = array($tab1);
			}
			else
			{
				
				$tab1->fields = array_merge($tab1->fields,$tab4->fields);
			}	
		}
	}
	if(isset($_GET["id"]) && $_GET["id"] != "-1" )
	{
		$tab_sql = db_query("SELECT name,table_name,field_type,rname 
	FROM `fields` f 
	LEFT JOIN categoryfields ON  categoryfields.fieldid = f.id
	WHERE source = '0' AND f.field_type = 5 AND
    (categoryfields.categoryid = '$catalogid' OR f.isgeneral = 1 )
    ORDER BY categoryfields.showorder");

		while($rtab = mysql_fetch_assoc($tab_sql))
		{
			$select_fields .= ",'$rtab[table_name]' as sr$rtab[rname]";
			$controls["sr$rtab[rname]"] = new Control("sr$rtab[rname]","template");
			$controls["sr$rtab[rname]"]->template = "templates/rparams.php";
			$rtab = new Tab($rtab["name"],"",array("sr$rtab[rname]"));
			array_push($tabs,$rtab);
		}
	}
	
	global $sourceid;
	$sourceid = 3;
	if(isset($_GET["id"]) && $_GET["id"] != "-1" )
	{
		$edit_content_bottom = "templates/img.php";
	}	
	if(!isset($_GET["goods"]))
	{
		$table_content_top_1 = "templates/goods_tree.php";
	}
	else
	{
		$table_content_top_1 = "templates/goods_path.php";
	}
	$get_params = array("goods");
	if(!function_exists("html_variant"))
	{
		function html_variant($parent,$catalog,$im)
		{
			?>
			<img onclick="window.open('index.php?t=goods&parent=<?=($catalog)?>&goods=<?=$parent?>&noheader&nofooter','','width=950,height=500,scrollbars=1')" src="images/goods.png" alt="Перейти к вариантам" title="Перейти к товарам" />&nbsp;
			<img onclick="window.open('index.php?t=goods_images&id=<?=$parent?>&sourceid=3&noheader&nofooter','','width=750,height=650')" alt="Изображения товара" src="images/foto.gif" title="Изображения товара" />&nbsp;
            <?
			if($im != "")
			{
				?>
				<img alt="" src="../thumb.php?k=40&id=<?=$im?>" />
				<?
			}
		}
	
	}
	$eval_fields["gvariant"] = "html_variant(\$row[\"id\"],\$row[\"parentid\"],\$row[\"imid\"]);";
	$unsorted_fields = array("gvariant");
	$title_fields["code"] = "Артикул";
	$edit_title_fields["code"] = "Артикул";
	if(!isset($_GET["goods"]))
	{
		$menu_html = "&nbsp;Поиск по наименованию/коду:&nbsp;<input type='text' id='stext' style='width:300px;' title='Быстрый поиск' value='' />&nbsp;";
	}
	$sort_changes["brandname"] = "brands.name";
	
?>