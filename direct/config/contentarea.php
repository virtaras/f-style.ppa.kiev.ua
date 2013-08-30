<?php 
	//table section
	$table_page = "";//load custom list page
	$edit_page = "";//load custom edit page
	$title = "Области контента";//Page title
	$source = "SELECT id,title,strongname FROM contentarea";//source sql for table;key field is required
	$source_key_field = "id";//key field,usually primary key
	$table  = "contentarea";//destination table,use for save action	
	$title_fields = array("title"=>"Наименование","strongname"=>"Идентификатор");//advanced title field in table header
	$exclude_fields = array("id");//this fields will be exclude from table listing
	$template_fields = array();//this fields will be load from template
	$pagesize = 20;
	$unsorted_fields = array();//This fields will not  sort by engine
	$csv_type = "";//0 - use $source,1 - use users query,2 - users defined function
	$csv_source = "";//csv source
	$csv_argument = array();//array of arguments for csv function,only if $csv_type = 2
	$edit_buttons = array(true,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
	$filters = array(); // array of filters
	//end table section
	//row section
	$generator_name = "";//Generator name, only for Firebird
	$exclude_fields_edit = array("id");//this fields will be exclude from edit page
	$required_fields = array("title","strongname");//this fields are required
	$edit_title_fields = array("title"=>"Наименование","strongname"=>"Идентификатор");//advanced title field in edit  page
	$number_fields = array();//array represent number fields
	$select_fields = "*";//this string reprsent custom select fields
	
	$controls = array();//predefined controls	
	$controls["html"] = new Control("html","template","HTML код");
	$controls["html"]->template = "templates/html.php";
	$edit_mode_buttons = array(true,true,true); //array enable/disable standart edit button in edit mode - array(<save>,<save & close>,<close>)
	//end row section
	
	
	
	//start programm settings
	$sort_tablename = true;//add tablename to sort conditions
	//end programm settings
	
	//filter section
	//end filter section
	
	//start content section
	$tabs = array(); // tabs on page
	$table_content_top_1 ="";
	$table_content_top_2 ="";
	$table_content_bottom_1 ="";
	$table_left_content = "";
	$table_right_content = "";
	$edit_content_top = "";
	$edit_content_after_fields  = "";
	$edit_content_right = "";
	$edit_content_bottom = "";
	//end content section
	$controls["plaintext"] = new Control("plaintext","checkbox","Отключить редактор");
	$controls["plaintext"]->js = " onclick='if(this.checked) { CKEDITOR.instances.html.destroy();
 } else {after_load_content();}'";
?>