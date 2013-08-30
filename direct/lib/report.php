<?php
class Report
{
	var $filters = array();
	var $title_fields = array();//advanced title field in table header
	var $sum_fields = array();//display summ for this fields
	var $source = array();//array of sql query
	var $title = "";//title of report
	var $exclude_fields = array();//this fields will be exclude from table listing
	var $template_fields = array();//this fields will be load from template
	var $content_before = "";//content before filters and report  table
	var $content_after = "";//content  after report  table
	function Report($source,$filters = array(),$title = "",$title_fields = array(),$sum_fields = array())
	{
		$this->source = $source;
		$this->filters = $filters;
		$this->title = $title;
		$this->title_fields = $title_fields;
		$this->sum_fields = $sum_fields;
	}
	function create()
	{
		require_once("function/db.php");
		global $engine_db;
		?><form method="post" action="index.php?t=<?=$_GET["t"]?>&report&show"><div class="tt"><?=$this->title?></div><?
		if(!empty($this->content_before) && file_exists($this->content_before))
		{
			require($this->content_before);
		}
		$filters = new Filter($this->filters);
		$filters->init();
		?><div>&nbsp;</div><?
		if(isset($_GET["show"]))
		{
			
			foreach(array_keys($this->source) as $current)
			{
				?><div class="section_name"><?=$current?></div><?
				$table = new SimpleTable($this->source[$current],$this->title_fields,$this->sum_fields);
				$table->exclude_fields = $this->exclude_fields;
				$table->template_fields = $this->template_fields;
				$table->create();
				?><div>&nbsp;</div><?
			}	
			
		}
		if(!empty($this->content_after) && file_exists($this->content_after))
		{
			require($this->content_after);
		}
		
		?></form><?
	}
}

?>