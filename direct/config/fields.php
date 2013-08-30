<?php 
	//table section
	$table_page = "";//load custom list page
	$edit_page = "";//load custom edit page
	$title = "Дополнительные поля";//Page title
	$source = "SELECT fields.id,fields.name,fieldtype.name as field_type,fields.rname, fields.table_name
	FROM `fields` 
	INNER JOIN  fieldtype ON  fieldtype.id = fields.field_type ";//source sql for table;key field is required
	$source_key_field = "id";//key field,usually primary key
	$table  = "`fields`";//destination table,use for save action	
	$title_fields = array("name"=>"Наименование","field_type"=>"Тип поля","table_name"=>"Имя таблицы","rname"=>"Имя поля");//advanced title field in table header
	$exclude_fields = array("id");//this fields will be exclude from table listing
	$template_fields = array();//this fields will be load from template
	$edit_buttons = array(true,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
	$top_links = array(true,false,true);
	$eval_fields["name"] = "get_link(\$row[\"table_name\"],\$row[\"name\"]);";
	//end table section
	//row section
	$exclude_fields_edit = array("id");//this fields will be exclude from edit page
	$required_fields = array("name");//this fields are required
	$edit_title_fields = array("name"=>"Наименование","field_type"=>"Тип поля","title"=>"Представление");//advanced title field in edit  page
	$number_fields = array();//array represent number fields
	$select_fields = "id,name,title,field_type,search,groupid,isinterval,showtype,isgeneral,issorting,body";//this string reprsent custom select fields
	
	$field_type = new Control("field_type");
	$field_type->type = "list";
	$field_type->source = "SELECT id,name FROM fieldtype";
	$field_type->keyfield = "id";
	$field_type->textfield = "name";
	$field_type->caption= "Тип поля";
	$field_type->required = true;
	$field_type->default_value = 2;
	
	$search = new Control("search");
    $search->type = "checkbox";
	$search->caption = "Фильтр поиска";
	
	$indexing = new Control("indexing");
    $indexing->type = "checkbox";
	$indexing->caption = "Индексация (только текст)";
	
	$controls = array("field_type"=>$field_type,"search"=>$search,"indexing"=>$indexing);//predefined controls
	$controls["groupid"] = new Control("groupid","list","Группа полей","SELECT id,name FROM fields_group");
	
	$controls["isinterval"] = new Control("isinterval","checkbox","Интервал (формат для поиска)");
	$controls["isgeneral"] = new Control("isgeneral","checkbox","Общее поле");
	$controls["showtype"] = new Control("showtype","radio","Вид вывода в фильтрах (только для списков)",array("1"=>"Флажки","2"=>"Списком"));
	
	
	$controls["issorting"] = new Control("issorting","checkbox","Сортировка");
	//end row section
	
	
	//start programm settings
	$sort_tablename = true;//add tablename to sort conditions
	//end programm settings
	
	//filter section
	//end filter section
	
	//start content section
	$table_content_top_1 ="";
	$table_content_top_2 ="";
	$table_content_bottom_1 ="";
	$edit_content_bottom = "";
	//end content section
	
	$body = new Control("body","template","Содержание");
	$body->template = "templates/body.php";
	$controls["body"] = $body;
	
	
	if(!function_exists("get_link"))
	{
		function get_link($table_name,$name)
		{
			if($table_name == "")
			{
				echo $name;
			}
			else
			{
				?>
				<a target="_blank" href="index.php?t=<?=$table_name?>"><?=$name?></a>
				<?
			}
		}
	}
	
?>