<?php 
	//table section
	$table_page = "";//load custom list page
	$edit_page = "";//load custom edit page
	$title = "Страницы";//Page title
	$source = "SELECT id,caption,title,keywords,description FROM pages";//source sql for table;key field is required
	$source_key_field = "id";//key field,usually primary key
	$table  = "pages";//destination table,use for save action	
	$title_fields = array("name"=>"Наименование","caption"=>"Заголовок","title"=>"TITLE","keywords"=>"KEYWORDS","description"=>"DESCRIPTION");//advanced title field in table header
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
	$required_fields = array("name","caption");//this fields are required
	$edit_title_fields = array("name"=>"Наименование (строка латиницей без пробелов)","script"=>"Скрипт","caption"=>"Заголовок");//advanced title field in edit  page
	$number_fields = array();//array represent number fields
	$select_fields = "*";//this string reprsent custom select fields
	
	$controls = array();//predefined controls
	$controls["formid"] = new Control("formid","list","Форма ","SELECT id,name FROM requestgroup");
	$controls["title"] = new Control("title","longtext","TITLE");
	$controls["keywords"] = new Control("keywords","longtext","KEYWORDS");
	$controls["description"] = new Control("description","longtext","DESCRIPTION");
	if(isset($_GET["id"]))
	{
		$controls["title"]->css_style = "width:400px;";
		$controls["keywords"]->css_style = "width:400px;";
		$controls["description"]->css_style = "width:400px;";
	}
	$controls["script"] = new Control("script","list","Модуль","SELECT id,name FROM modules ORDER BY name");
	$edit_mode_buttons = array(true,true,true); //array enable/disable standart edit button in edit mode - array(<save>,<save & close>,<close>)
	
	$unique_fields  = array("name");
	//end row section
	if(!isset($_GET["id"]))
	{
		$controls["title"]->rows = 1;
		$controls["title"]->js = "onfocus = 'this.rows = 7;' ";
		$controls["keywords"]->rows = 1;
		$controls["keywords"]->js = "onfocus = 'this.rows = 7;'";
		$controls["description"]->rows = 1;
		$controls["description"]->js = "onfocus = 'this.rows = 7;'";
	}
	$controls["hide_content"] = new Control("hide_content","checkbox","Скрыть контент");
	
	//start programm settings
	$sort_tablename = true;//add tablename to sort conditions
	//end programm settings
	
	//filter section
	//end filter section
	
	//start content section
	$tab1 = array("name","caption","script","formid","hide_content");
	$tab2 = array("title","description","keywords");
	$tabs = array(new Tab("Основные данные","",$tab1),new Tab("SEO","",$tab2)); // tabs on page
	$table_content_top_1 ="";
	$table_content_top_2 ="";
	$table_content_bottom_1 ="";
	$table_left_content = "";
	$table_right_content = "";
	$edit_content_top = "";
	$edit_content_after_fields  = "templates/pages_content.php";
	$edit_content_right = "";
	$edit_content_bottom = "";
	//end content section
	$table_edit_mode = 1;
	
?>