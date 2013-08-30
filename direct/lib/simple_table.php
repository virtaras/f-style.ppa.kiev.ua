<?php
class SimpleTable
{
	var $query = "";//sql query for table creating
	var $title_fields = array();//advanced title field in table header
	var $sum_fields = array();//display summ for this fields
	var $exclude_fields = array();//this fields will be exclude from table listing
	var $template_fields = array();//this fields will be load from template
	function SimpleTable($query,$title_fields = array(),$sum_fields = array())
	{
		$this->query = $query;
		$this->title_fields = $title_fields;
		$this->sum_fields = $sum_fields;
	}
	function create()
	{
		global $engine_db;
		global $db;
		require_once("function/db.php");
		$result = db_query($this->query);
		$coln = db_num_fields($result);
		$field_array = array();	
		$sum_array = array();
		?>
		<table class='t' border="1" >
		<tr>
		<?
		for ($i = 0; $i < $coln; $i++)
		{	
			$field_name =  db_field_info($result, $i, "name");
			$table_name = db_field_info($result, $i, "table");
			if(in_array($field_name,$this->exclude_fields))
			{
				continue;
			}
			if(in_array($field_name,$this->sum_fields))
			{
				$sum_array[$field_name] = 0;
			}
			$field_array[$field_name] = true;
			?><td><?
			if(isset($this->title_fields[$field_name]))
			{
				echo $this->title_fields[$field_name];
			}
			else
			{
				echo $field_name;
			}
			?>
			</td>
			<?
		}
		?>
		</tr>
		<?php
		while($row = db_fetch_assoc($result))
		{
			
			?><tr class='tr'><?
			foreach(array_keys($field_array) as $key)
			{
				if(in_array($key,$this->sum_fields))
				{
						$sum_array[$key] = $sum_array[$key] + $row[$key];
				}
				echo "<td class='td'>";
				if(isset($this->template_fields[$key]))
				{
					require($this->template_fields[$key]);
				}
				else
				{
					$val = $row[$key];
					if((is_int($val) || is_numeric($val)) && $val < 0)
					{
						?><label style="color:red;"><?=$val?></label><?
					}
					else
					{
						echo $val;			
					}
				}	
				
				
				echo "</td>";
			}
			?></tr><?
		
		
		}
		if(count($this->sum_fields) > 0)
		{
			?><tr><?
			foreach(array_keys($field_array) as $key)
			{
				echo "<td ><b>";
				echo isset($sum_array[$key]) ? $sum_array[$key] : "";
				echo "</b></td>";
			}
			?></tr><?
		}
		?>
		</table>
		<?	
	
	}

}






?>