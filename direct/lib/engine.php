<?php
include($engine_path."/lib/config.php");
include($engine_path."/lib/table.php");
include($engine_path."/lib/row.php");
include($engine_path."/lib/control.php");
include($engine_path."/lib/db.php");
include($engine_path."/lib/simple_table.php");
include($engine_path."/lib/filter.php");
include($engine_path."/lib/tabs.php");
function db_action($config)
{

	if(isset($_GET["delete"]))
	{
		$table = new Table($config);
		if($_GET["delete"] != "")
		{
			$table->delete_row($_GET["delete"]);
		}
		else
		{
			$table->delete_rows();
		}
	}
	if(isset($_GET["save"]))
	{
		$db = new DB($config,$_GET["id"],$_GET["t"]);
		if(isset($_GET["close"]))
		{
			$db->close_form = true;
		}
		$db->execute();
	}
	if(isset($_GET["copyrow"]))
	{
		foreach(array_keys($_POST) as $current)
		{
			if(substr($current,0,3) == "cb_")
			{
				$db = new DB($config,$_POST[$current],$_GET["t"]);
				$db->copy();
				break;
			}
		}
		
	}
	if(isset($_GET["simple_save"]))
	{
		$db = new DB($config,"",$_GET["t"]);
		$db->simple_save();
	}
}
function get_header($config)
{
	
	global $config_title;
	
	if(!isset($_GET["noheader"]))
	{
		require("inc/header.php"); 
	}
	else
	{
		require("inc/head.php");
	}
	
}
function get_footer()
{
	if(!isset($_GET["nofooter"]))
	{
		require("inc/footer.php"); 
	}
	else
	{
	?>
		</body>
		</html>
	<?
	}
}

function get_content($config)
{
	
	if(!empty($_GET["t"]))
	{
		get_header($config);
	
        if(!empty($_GET["id"]))
        {
            if(!empty($config->edit_page))
            {
                
				require($config->edit_page);
            }
            else
            {
                $row = new Row($config,$_GET["id"]);
                $row->id = $_GET["id"];
                $row->edit();
            }

        }
        else
        {
            if(!empty($config->table_page))
            {
                if(function_exists($config->table_page))
				{
					call_user_func($config->table_page,$this);	
				}
				else
				{
					require($config->table_page);
				}	
            }
            else
            {
                $table = new Table($config);
                if(isset($_GET["sort"]))
                {
                    $table->sort = $_GET["sort"];
                }
                $table->create_table();
            }
        }

		get_footer();		
				
	}
	else
	{
		get_header($config);get_footer();
	}
	
	
}
?>
