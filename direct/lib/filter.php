<?php
class Filter
{
	var $filters = array();
	function Filter($filters)
	{
		$this->filters = $filters;
	}
	function init()
	{
		if(count($this->filters))
		{
			?>
			<div class="filter" id="filter" ><table class="filter" border="0">
			<?php
			$post_exists = false;
			foreach($this->filters as $current)
			{
				$current->name = $_GET["t"]."_".$current->name;
				if(isset($_POST[$current->name]))
				{
					$post_exists = true;
					if($current->is_multiple)
					{
						$current->value = implode(",",$_POST[$current->name]);
					}
					else
					{
						$current->value = $_POST[$current->name];
					}
					$_SESSION[$current->name] = $current->value;
					if($current->type == "checkbox")
					{
						$current->value = 1;
						$_SESSION[$current->name] = 1;
					}					
					
				}
				else
				{
					if(isset($_SESSION[$current->name]))
					{
						$current->value = $_SESSION[$current->name];
						if($current->type == "checkbox")
						{
							$current->value = 1;
						}	
					}	
					
				}
				if($post_exists)
				{
					if($current->type == "checkbox" && !isset($_POST[$current->name]))
					{
						$current->value = 0;
						unset($_SESSION[$current->name]);
					}
				}
				if($current->type == "checkbox")
				{
					echo "<tr><td class='ftd'>".$current->caption."</td><td class='ftd'>";
					$current->create();
					echo "</td></tr>";
				}
				else
				{
					echo "<tr><td class='ftd'>".$current->caption."</td><td class='ftd'>";
					$current->create();
					echo "</td></tr>";
				}	
			}
			?>
			<tr><td class='ftd' colspan="2"><input type="submit" class="buttons" value="<?=_APPLY?>" />&nbsp;<input onclick="$('#filter').fadeOut();" type="button" class="buttons" value="<?=_CLOSE_FILTER?>" /></td>
			</tr></table></div>
			<?
		}
	}
}
function filters_get_value($filter)
{
	$name = $_GET["t"]."_".$filter->name;
	if(isset($_POST[$name]))
	{
		if($filter->is_multiple)
		{
			return  implode(",",$_POST[$name]);
		}
		else
		{
			return  $_POST[$name];
		}	
		
	}
	else
	{
		if(isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
	}
}



?>