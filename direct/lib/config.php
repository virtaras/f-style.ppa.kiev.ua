<?php
class Config
{
	//table section
	var $table_page = "";//load custom list page
	var $edit_page = "";//load custom edit page
	var $title = "";//Page title
	var $table_edit_mode = "0"; //0 - standart,1 - edit in tabel mode
	var $source = "";//source sql for table;key field is required
	var $source_key_field = "id";//key field,usually primary key
	var $table  = "";//destination table,use for save action	
	var $title_fields = array();//advanced title field in table header
	var $exclude_fields = array("id");//this fields will be exclude from table listing
	var $template_fields = array();//this fields will be load from template
	var $eval_fields = array();//this fields contain expression
	var $pagesize = 20;
	var $unsorted_fields = array();//This fields will not  sort by engine
	var $edit_buttons = array(true,true);//array enable/disable standart edit button in row - array(<edit>,<delete>)
	var $top_links = array(true,true,true);//array enable/disable standart  top links - array(<add>,<copy>,<delete>);
	var $menu_html = "";//this html add to standart menu
	var $count_query = "";//this sql use as a source for  count query
	var $sum_fields = array();//array of fields which value are added up
	//end table section
	//row section
	var $generator_name = "";//Generator name, only for Firebird
	var $exclude_fields_edit = array("id");//this fields will be exclude from edit page
	var $required_fields = array();//this fields are required
	var $edit_title_fields = array();//advanced title field in edit  page
	var $number_fields = array();//array represent number fields
	var $select_fields = "*";//this string reprsent custom select fields
	var $unique_fields  = array();//this field(s) check for unique value, supported only in MySql
	var $edit_mode_buttons = array(true,true,true); //array enable/disable standart edit button in edit mode - array(<save>,<save & close>,<close>)
	var $controls = array();//predefined controls
	//end row section
	//start programm settings
	var $sort_tablename = true;//add tablename to sort conditions
	var $get_params = array();//array of additional url parametrs
	var $sort_changes = array();//arry of fields ,which name must be change in sort condition
	//end programm settings
	//filter section
	var $filters = array(); // array of filters
	//end filter section
	//start content section
	var $tabs = array(); // tabs on page
	var $table_content_top_1 ="";
	var $table_content_top_2 ="";
	var $table_content_bottom_1 ="";
	var $table_left_content = "";
	var $table_right_content = "";
	var $edit_content_top = "";
	var $edit_content_after_fields  = "";
	var $edit_content_right = "";
	var $edit_content_bottom = "";
	//end content section
	function Config($path = "")
	{
		global $currentuser;
		$this->source = "SELECT * FROM ".$_GET["t"];
		$this->table = $_GET["t"];
		global $base_config_path;	
		if(!empty($base_config_path))
		{
			include ($base_config_path);
		}	
		if(file_exists($path))
		{
			require($path);
		}
		if(isset($table_page))
		{
			$this->table_page = $table_page;
		}
		if(isset($edit_page))
		{
			$this->edit_page = $edit_page;
		}
		if(isset($title))
		{
			$this->title = $title;
		}
		if(isset($table_edit_mode))
		{
			$this->table_edit_mode = $table_edit_mode;
		}
		if(isset($source))
		{
			$this->source = $source;
		}
		if(isset($source_key_field))
		{
			$this->source_key_field = $source_key_field;
		}
		if(isset($table))
		{
			$this->table = $table;
		}
		if(isset($title_fields))
		{
			$this->title_fields = $title_fields;
		}
		if(isset($exclude_fields))
		{
			$this->exclude_fields = $exclude_fields;
		}
		if(isset($template_fields))
		{
			$this->template_fields = $template_fields;
		}
		if(isset($eval_fields))
		{
			$this->eval_fields = $eval_fields;
		}
		if(isset($pagesize))
		{
			$this->pagesize = $pagesize;
		}
		if(isset($unsorted_fields))
		{
			$this->unsorted_fields = $unsorted_fields;
		}
		if(isset($edit_buttons))
		{
			$this->edit_buttons = $edit_buttons;
		}
		if(isset($top_links))
		{
			$this->top_links = $top_links;
		}
		if(isset($menu_html))
		{
			$this->menu_html = $menu_html;
		}
		if(isset($count_query))
		{
			$this->count_query = $count_query;
		}
		if(isset($sum_fields))
		{
			$this->sum_fields = $sum_fields;
		}
		if(isset($generator_name))
		{
			$this->generator_name = $generator_name;
		}
		if(isset($exclude_fields_edit))
		{
			$this->exclude_fields_edit = $exclude_fields_edit;
		}
		if(isset($required_fields))
		{
			$this->required_fields = $required_fields;
		}
		if(isset($edit_title_fields))
		{
			$this->edit_title_fields = $edit_title_fields;
		}
		if(isset($number_fields))
		{
			$this->number_fields = $number_fields;
		}
		if(isset($number_fields))
		{
			$this->number_fields = $number_fields;
		}
		if(isset($select_fields))
		{
			$this->select_fields = $select_fields;
		}
		if(isset($unique_fields))
		{
			$this->unique_fields = $unique_fields;
		}
		if(isset($edit_mode_buttons))
		{
			$this->edit_mode_buttons = $edit_mode_buttons;
		}
		if(isset($controls))
		{
			$this->controls = $controls;
		}
		if(isset($sort_tablename))
		{
			$this->sort_tablename = $sort_tablename;
		}
		if(isset($get_params))
		{
			$this->get_params = $get_params;
		}
		if(isset($sort_changes))
		{
			$this->sort_changes = $sort_changes;
		}
		if(isset($filters))
		{
			$this->filters = $filters;
		}
		if(isset($tabs))
		{
			$this->tabs = $tabs;
		}
		if(isset($table_content_top_1))
		{
			$this->table_content_top_1 = $table_content_top_1;
		}
		if(isset($table_content_top_2))
		{
			$this->table_content_top_2 = $table_content_top_2;
		}
		if(isset($table_content_bottom_1))
		{
			$this->table_content_bottom_1 = $table_content_bottom_1;
		}
		if(isset($table_left_content))
		{
			$this->table_left_content = $table_left_content;
		}
		if(isset($table_right_content))
		{
			$this->table_right_content = $table_right_content;
		}
		if(isset($edit_content_top))
		{
			$this->edit_content_top = $edit_content_top;
		}
		if(isset($edit_content_after_fields))
		{
			$this->edit_content_after_fields = $edit_content_after_fields;
		}
		if(isset($edit_content_right))
		{
			$this->edit_content_right = $edit_content_right;
		}
		if(isset($edit_content_bottom))
		{
			$this->edit_content_bottom = $edit_content_bottom;
		}
		
	}
}
?>
