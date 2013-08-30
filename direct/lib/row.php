<?php
class Row
{
	var $config = "";
	var $id = "-1";
	var $copy = "-1";
	function Row($config,$id)
	{
		$this->config  = $config;
		$this->id  = $id;
	}
	function edit()
	{
		global $engine_db;
		global $titles;
		$_SESSION["back_row_url"] = $_SERVER["SERVER_NAME"].(!empty($_SERVER["SERVER_PORT"]) ? ":".$_SERVER["SERVER_PORT"] : "").$_SERVER["REQUEST_URI"];
		
		if(empty($this->config->title) && isset($titles[$_GET["t"]]))
		{
			$this->config->title = $titles[$_GET["t"]];
		}
		require_once("function/db.php");
		
		$result = db_query("SELECT ".$this->config->select_fields." FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$this->id."'");
		
		$row = db_fetch_assoc($result);
		$coln = db_num_fields($result);
		
		?>
		<table class="tool_bar" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="tbt">
		<?php
		if($_GET["id"] == "-1")
		{
			echo _INSERT_NEW;
		}
		else
		{
			echo _EDITING;
		}
		?>
		</td>
		<td class="tbr"><?=$this->config->title?></td>
		</tr>
		</table>
		<form method="post" enctype="multipart/form-data" >
		<?php
		//create hidden fields
		foreach($this->config->controls as $current)
		{
			if($current->type == "hidden")
			{
				?>
				<input value="<?=($current->default_value)?>"  type="hidden" id="<?=$current->id?>" name="<?=$current->name?>" />
				<?
				//unset($this->config->controls[$current->id]);
			}
		}
		//*****************
		
		?>
		
		<?php
		if(!empty($this->config->edit_content_top))
		{
			include($this->config->edit_content_top);
		}
		?>
		<script> var required_array = new Array();var number_array = new Array(); </script>
		
		<?php 
		$tr_controls = array();
		$tab_enabled = false;
		if(isset($this->config->tabs) && count($this->config->tabs) > 0) 
		{ 
			$tabs = new Tabs($this->config->tabs);
			$tabs->create();
			$tab_enabled = true;
		} 
		?>
		<table width="100%" border="0">
		<tr>
		<td style="vertical-align:top;">
		<table id="edit_table" class="edit_table" border="0">
		<?
		$required_index = 0;
		$number_index = 0;
		$checkbox_array = array();
		for ($i = 0; $i < $coln; $i++)
		{
			$is_number = false;
			
			switch($engine_db)
			{
				case "mysql":
					$field_name = mysql_field_name($result, $i);		
					break;
				case "fb":
					$field_name_array = ibase_field_info($result, $i);
					$field_name = $field_name_array["alias"];
					break;
					
			}
			
			if(in_array($field_name,$this->config->exclude_fields_edit))
			{
				continue;
			}
			$ctr = new Control($field_name);
			
			
			if(in_array($field_name,$this->config->required_fields))
			{
				$ctr->required = true;
			}
			if(in_array($field_name,$this->config->number_fields))
			{
				$is_number = true;
			}
			if(isset($this->config->edit_title_fields[$field_name]))
			{
				$ctr->caption = $this->config->edit_title_fields[$field_name];
			}
			else
			{
				$ctr->caption = $field_name;
			}
			if(isset($this->config->controls[$field_name]))
			{
				$ctr  = $this->config->controls[$field_name];
				if($this->id == "-1")
				{
					$ctr->value = $ctr->default_value;
				}
				else
				{
					$ctr->value = $row[$field_name];
				}	
				if(isset($_SESSION["last_post"][$field_name]))
				{
					$ctr->value = $_SESSION["last_post"][$field_name];
				}
				if($this->config->controls[$field_name]->type == "checkbox")
				{
					array_push($checkbox_array,$field_name);
				}
				if($this->config->controls[$field_name]->type == "date")
				{

					if($this->config->controls[$field_name]->date_display != "")
					{					
						$ctr->date_display = $row[$this->config->controls[$field_name]->date_display];
					}
				}
			}
			else
			{
				$ctr->type = "text";
				$ctr->value = $row[$field_name];
				if(isset($_SESSION["last_post"][$field_name]))
				{
					$ctr->value = $_SESSION["last_post"][$field_name];
				}
			}
			if($ctr->type == "hidden")
			{
				continue;
			}
			
			if($tab_enabled)
			{
				array_push($tr_controls,"'tr_".$field_name."'");
			}	
			$ctr->current_row = $row;
			
			
			
			if($ctr->type == "template" && $ctr->template_mode == "full")
			{
				echo "<tr id='tr_".$field_name."'><td colspan='2'>";
				$ctr->create();
				echo "</td></tr>";
			}
			else
			{
				echo "<tr id='tr_".$field_name."'>";
				echo "<td  class='title'>".$ctr->caption."</td>";
				echo "<td>";
				$ctr->create();
				if($ctr->required)
				{
					?>
					<script language="JavaScript"> required_array[<?=$required_index?>] = new Array("<?=$ctr->id?>","<?=$ctr->caption?>","<?=$ctr->type?>"); </script>
					<?
					$required_index++;
				}
				if($is_number)
				{
					?>
					<script language="JavaScript"> number_array[<?=$number_index?>] = new Array("<?=$ctr->id?>","<?=$ctr->caption?>"); </script>
					<?
					$number_index++;
				}
				echo "</td>";
				echo "</tr>";
			}
			
			
		}
		?>
			<tr><td colspan="2">&nbsp;</td></tr>
		</table>
		<?php 
		if(isset($this->config->edit_content_after_fields))
		{
			if(!empty($this->config->edit_content_after_fields))
			{
				include($this->config->edit_content_after_fields);
			}
		}
		if($tab_enabled)
		{
			?>
			<script language="JavaScript">var tr_controls = Array(<?=(implode(",",$tr_controls))?>);SetCurrentTab(document.getElementById("tab_0"),0); </script>
			<?
		}
		?>
		<div class="line"></div>
		<table >
			<tr>
				<td colspan="2">
					<?php if(isset($this->config->edit_mode_buttons) && $this->config->edit_mode_buttons[0]) { ?>
					<input class="buttons" onclick="javascript:return save_form('<?=get_url(1,$this->config)?>&id=<?=$_GET["id"]?>',required_array,number_array,this);"  type="button" value="<?=_BUTTON_SAVE?>" />&nbsp;&nbsp;
					<?php } 
					if(isset($this->config->edit_mode_buttons) && $this->config->edit_mode_buttons[1] && !isset($_GET["return"])) {
					?>
					<input class="buttons" onclick="javascript:return save_form('<?=get_url(1,$this->config)?>&id=<?=$_GET["id"]?>&close',required_array,number_array,this);"  type="button" value="<?=_BUTTON_SAVE_CLOSE?>" />&nbsp;&nbsp;
					<?php } 
					if(isset($_GET["return"]))
					{
						?>
						<input class="buttons" onclick="close_longlist()" type="button" value="<?=_BUTTON_CLOSE?>" />
						<?
					}
					else
					{
					if(isset($this->config->edit_mode_buttons) && $this->config->edit_mode_buttons[2]) {
					?>
						<input class="buttons" onclick="close_form('<?=get_url(1,$this->config)?>',this)" type="button" value="<?=_BUTTON_CLOSE?>" />
					<?php } } ?>
				</td>
			</tr>
		</table>
		</td>
		<td style="vertical-align:top;">
		<?php
		if(!empty($this->config->edit_content_right))
		{
			include($this->config->edit_content_right);
		}
		?>
		</td>
		</tr>
		<tr><td colspan="2">
		<?
		if(isset($this->config->edit_content_bottom))
		{
			if(!empty($this->config->edit_content_bottom))
			{
				include($this->config->edit_content_bottom);
			}
		}
		
		if(count($checkbox_array) > 0)
		{			
			$checkbox_array_string = "";
			foreach($checkbox_array as $current)
			{
				$checkbox_array_string .= $current.",";
				
			}
			?>
			<input type="hidden" name="checkbox_array" value="<?=$checkbox_array_string?>" />
			<?
		}
		?>
		</td></tr></table>
		</form>
		<div id="dlonglist" >
		<iframe id="ilonglist" frameborder="0" style=""></iframe>
        <input onclick="$('#dlonglist').fadeOut();" class="buttons" type="button" value="Закрыть" />
		</div>
		<?
		if(isset($_SESSION["last_post"]))
		{
			unset($_SESSION["last_post"]);
		}
		if(isset($_SESSION["rowerror"]))
		{
			?>
			<script language="JavaScript">
			alert('<?=$_SESSION["rowerror"]?>');
			</script>
			<?
			unset($_SESSION["rowerror"]);
		}
	}

}

?>