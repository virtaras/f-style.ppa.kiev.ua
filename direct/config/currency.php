<?php 
	//table section
	$table_page = "";//load custom list page
	$edit_page = "";//load custom edit page
	$title = "Валюты";//Page title
	$source = "SELECT * FROM currency";//source sql for table;key field is required
	$source_key_field = "id";//key field,usually primary key
	$table  = $_GET["t"];//destination table,use for save action	
	$title_fields = array("name"=>"Валюта","shortname"=>"Кратко","course"=>"Курс");//advanced title field in table header
	$exclude_fields = array("id");//this fields will be exclude from table listing
	$template_fields = array();//this fields will be load from template
	$eval_fields = array();//this fields contain expression
	$pagesize = 20;
	$unsorted_fields = array();//This fields will not  sort by engine
	$csv_type = "";//0 - use $source,1 - use users query,2 - users defined function
	$csv_source = "";//csv source
	$csv_argument = array();//array of arguments for csv function,only if $csv_type = 2
	$edit_buttons = array(true,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
	$filters = array(); // array of filters
	$top_links = array(true,true,true);//array enable/disable standart  top links - array(<add>,<copy>,<delete>);
	$menu_html = "";//this html add to standart menu
	$count_query = "";//this sql use as a source for  count query
	//end table section
	//row section
	$generator_name = "";//Generator name, only for Firebird
	$exclude_fields_edit = array("id");//this fields will be exclude from edit page
	$required_fields = array("name","shortname","course");//this fields are required
	$edit_title_fields = array("name"=>"Валюта","shortname"=>"Кратко","course"=>"Курс");//advanced title field in edit  page
	$number_fields = array();//array represent number fields
	$select_fields = "*";//this string reprsent custom select fields
	$unique_fields  = array();//this field(s) check for unique value, supported only in MySql
	
	
	$edit_mode_buttons = array(true,true,true); //array enable/disable standart edit button in edit mode - array(<save>,<save & close>,<close>)
	$controls = array();//predefined controls
	$table_edit_mode = 1;
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
	
	global $sourceid;
	$sourceid = 15;
	if(isset($_GET["id"]) && $_GET["id"] != "-1" )
	{
		$edit_content_bottom = "templates/img.php";
	}	
	
?>