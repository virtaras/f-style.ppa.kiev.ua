<?php 
	//table section
	$table_page = "";//load custom list page
	$edit_page = "";//load custom edit page
	$title = "Системные установки";//Page title
	$source = "SELECT id,title,name,val FROM settings";//source sql for table;key field is required
	$source_key_field = "id";//key field,usually primary key
	$table  = "settings";//destination table,use for save action	
	$title_fields = array("title"=>"Наименование","name"=>"Идентификатор","val"=>"Значение");//advanced title field in table header
	$pagesize = 30;
	$exclude_fields = array("id");//this fields will be exclude from table listing
	$template_fields = array();//this fields will be load from template
	$edit_buttons = array(true,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
	//end table section
	//row section
	$exclude_fields_edit = array("id");//this fields will be exclude from edit page
	$required_fields = array("name");//this fields are required
	$number_fields = array();//array represent number fields
	$edit_title_fields = array("title"=>"Наименование","name"=>"Идентификатор","val"=>"Значение");//advanced title field in edit  page
	$select_fields = "*";//this string reprsent custom select fields

	
	$controls = array();//predefined controls
	$edit_mode_buttons = array(true,true,true); //array enable/disable standart edit button in edit mode - array(<save>,<save & close>,<close>)
	//end row section
	$controls["val"] = new Control("val","longtext","Значение");
	if(isset($_GET["id"]) && $_GET["id"] == "27")
	{
		$controls["val"]->rows = 15;
		$controls["val"]->css_style = "width:400px;";
	}
	else
	{
		$controls["val"]->rows = 7;
	}	
	//start programm settings
	$sort_tablename = false;//add tablename to sort conditions
	//end programm settings
	
	//filter section
	//end filter section
	
	//start content section
	$table_content_top_1 ="";
	$table_content_top_2 ="";
	$table_content_bottom_1 ="";
	$edit_content_bottom = "";
	//end content section
	
?>