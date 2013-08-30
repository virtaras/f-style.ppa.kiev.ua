<?php
class Table
{
	var $config = "";
	var $sort = "";
	function Table($config)
	{
		$this->config  = $config;
	}
	function create_table()
	{
		global $engine_db;
		global $db;
		global $titles;
		$checkbox_array = array();
        $hide_array = array();
		$width_array = array();
        $farray = array();
        if(isset($_COOKIE[$_GET["t"]."_hide"]))
        {
            $hide_array = explode(",",$_COOKIE[$_GET["t"]."_hide"]);
        }
		 if(isset($_COOKIE[$_GET["t"]."_width"]))
        {
            $temp_width_array = explode(";",urldecode($_COOKIE[$_GET["t"]."_width"]));
			foreach( $temp_width_array as $current)
			{
				if($current != "")
				{
					$item = explode(":",$current);
					$width_array[$item[0]] = $item[1];
				}
			}
			
        }
		if(empty($title) && isset($titles[$_GET["t"]]))
		{
			$this->config->title = $titles[$_GET["t"]];
		}
		require_once("function/db.php");
		$use_udf = false;
		if(file_exists("udf/".$this->config->table.".php"))
		{
			$use_udf = true;
			require_once("udf/".$this->config->table.".php");
		}
		if(isset($_GET["sql"]) && !empty($_GET["sql"]))
		{
			$this->config->source = stripcslashes(urldecode($_GET["sql"]));
		}
		if(empty($this->config->source))
		{
			echo "Internal error: empty source.";
			return;
		}
		?>
		<form method="post" enctype="multipart/form-data" >
		<table class="tool_bar" border="0" cellpadding="0" cellspacing="0">
		<tr><td class="tb">
		<?php
		if($this->config->table_content_top_1 != "")
			{
				require($this->config->table_content_top_1);
			}
		$links_array = array(true,true,true);
		if(isset($this->config->top_links))
		{
			$links_array = $this->config->top_links;
		}
		$hide_checkbox = 2;
		?>
		<?php if($links_array[0]) { ?>
		<input type="button" style="width:100px;background-image: url(images/add.gif);background-repeat:no-repeat;" class="buttons" onclick="document.location.href='http://<?=get_url(1,$this->config)?>&id=-1';" value="<?=_ADD_NEW?>" />&nbsp;
		<?php } if($links_array[1]) { $hide_checkbox--; ?>
		<input type="button" style="width:100px;background-image: url(images/copy.gif);background-repeat:no-repeat;" class="buttons" onclick="copy_row('<?=get_url(1,$this->config)?>&copyrow')" value="<?=_COPY?>" />&nbsp;
		<?php } if($links_array[2]) { $hide_checkbox--; ?>
		<input type="button" class="buttons" style="width:100px;background-image: url(images/del.gif);background-repeat:no-repeat;" onclick="delete_row('<?=get_url(1,$this->config)?>&delete')" value="<?=_DELETE?>" />&nbsp;
		<?php } 
		if(isset($this->config->filters) && count($this->config->filters) > 0) { ?>
		<input type="button" style="width:100px;background-image: url(images/filters.jpg);background-repeat:no-repeat;" class="buttons" onclick="show_filter(this)" value="<?=_FILTER?>" />&nbsp;
		<?php } 
		if(isset($this->config->menu_html)) {echo $this->config->menu_html; }
		?></td>
		<td class="tbr"><img onclick="show_hide_fields(this)" alt="" src="images/fields.gif" />&nbsp;<?=$this->config->title?></td>
		</tr>
		</table>
		<?php
			
			
			if(isset($this->config->filters))
			{
				$filters = new Filter($this->config->filters);
				$filters->init();
			}
		?>
		
	
		<?php
		
		include("lib/paging.php");
		
		if($this->sort != "")
		{
			$this->sort = str_replace("+"," ",$this->sort);
			if(strpos($this->config->source,"ORDER BY") === false)
			{
				$source_array = explode("LIMIT",$this->config->source);
				
			    $first_part = array_merge($source_array);
				
				unset($first_part[count($first_part) - 1]);
				
				$this->config->source = implode(" LIMIT ",$first_part) . " ORDER BY $this->sort " . (isset($source_array[count($source_array) - 1]) ? " LIMIT " . $source_array[count($source_array) - 1] : "");
			}
			else
			{
				
			}
		}
		
		//print $this->config->source;
		
		

		$result = db_query($this->config->source);
		$coln = db_num_fields($result);
		$footer_array = array();
		
									?>
		
		<table width="100%" border="0"  class="ct">
			<tr>
				<?php if(!empty($this->config->table_left_content)) { ?>
				<td  class="ltd"><?php require($this->config->table_left_content); ?></td>
				<?php } ?>
				<td class="ctd" >			
						<?php
			if($this->config->table_content_top_2 != "")
			{
				require($this->config->table_content_top_2);
			}
		?>
					<? //if($pagecount > 1) { ?>
					<table width="100%">
<tr>
<td><?
	if(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1")
						{
							?>
							<p><input type="button" class="buttons" onclick="simple_save_form('<?=get_url(1,$this->config)?>');" value="<?=_BUTTON_SAVE?>" />
							&nbsp;&nbsp;
							<input type="reset" class="buttons"  value="<?=_BUTTON_CANCEL?>" />
							</p>
							<?
						} ?></td><td>
					<div class="paging"><?=$pager?></div></td></tr>
					</table>
					<? //} ?>
					<table class='t' <?=($pagecount <= 1) ? "style='margin-top:0px;'" : ""?>  cellpadding="2" cellspacing="1" >
					<tr >
					<?php 
					if($hide_checkbox < 2) { ?>
					<th style="width:1%;" class="td0"  ><input class="cb" onclick='check_all(this)' type='checkbox'  /></th>
					<?php 
					$footer_array[1] = 0;
					}
					if(isset($this->config->edit_buttons) && ($this->config->edit_buttons[0] || $this->config->edit_buttons[1]) && !isset($_GET["noaction"]) )
					{ ?>
					<th width="1%" class="td0"  >&nbsp;</th>
					<?php
						$footer_array[2] = 0;
					}
					if(isset($_GET["parentcontrol"]) && !empty($_GET["parentcontrol"]))
					{
						?>
						<th  class="td0"  width="1%" >&nbsp;</th>
					<?php
					$footer_array[3] = 0;
					}
					
					$field_array = array();
					//create table title
					
					for ($i = 0; $i < $coln; $i++)
					{
						
						$field_name =  db_field_info($result, $i, "name");
						$table_name = db_field_info($result, $i, "table");
						
						
                        if(in_array($field_name,$this->config->exclude_fields))
						{
							continue;
						}
                        array_push($farray,$field_name);
                        if(in_array($field_name,$hide_array))
						{
							continue;
						}
						?><th <?=(isset($width_array[$field_name]) ? "style='width:".$width_array[$field_name]."'" : "")?> nowrap >
						
						<?
						
						$sort_field = ($this->config->sort_tablename ? $table_name."." : "").$field_name;
						if(isset($this->config->sort_changes[$field_name]))
						{
							$sort_field = $this->config->sort_changes[$field_name];
						}
						$this_sort = false;
						$sort_direction = "asc";
						if(isset($_GET["sort"]) && str_ireplace(array(" DESC"," ASC"),"",$_GET["sort"]) == $sort_field)
						{
							$this_sort = true;
							$sort_direction = strtolower(trim(str_ireplace($sort_field,"",$_GET["sort"])));
							
						}
						if(isset($this->config->unsorted_fields) && !in_array($field_name,$this->config->unsorted_fields))
						
						{
							?>
							<a href="http://<?=get_url(0,$this->config)?>&sort=<?=$sort_field."+".($sort_direction == "asc" ? "desc" : "asc")?>">
							<?
						}
						$field_array[$field_name] = true;
						if(isset($this->config->title_fields[$field_name]))
						{
							echo $this->config->title_fields[$field_name];
						}
						else
						{
							echo $field_name;
						}
						if(isset($this->config->unsorted_fields) && !in_array($field_name,$this->config->unsorted_fields))
						
						{
							?></a>&nbsp;&nbsp;<a href="http://<?=get_url(0,$this->config)?>&sort=<?=$sort_field."+".($sort_direction == "asc" ? "desc" : "asc")?>" ><img alt="" src="images/<?=$sort_direction?>.gif" /></a>
							<?
						}
						?>
						</th>
						<?
					}
					echo "</tr>";
					//end table title
					//create table row
					$item_index = 1;
					while($row = db_fetch_assoc($result))
					{
						
						if($item_index == 1)
						{
							$item_index = 0;
						}
						else
						{
							$item_index = 1;
						}
						echo "<tr ";
						if($use_udf && function_exists("row_create"))
						{
							row_create($row,$this->config);
						}	
						echo "onclick='highlight(".row($this->config->source_key_field,$row).")' id='tr".row($this->config->source_key_field,$row)."' class='".($item_index == 1 ? "a": "")."tr'>";
						if($hide_checkbox < 2 )
						{
						echo "<td style='width:1%;' class='td0'  ><input class='cb' type='checkbox' id='cb_".row($this->config->source_key_field,$row)."' name='cb_".row($this->config->source_key_field,$row)."' value='".row($this->config->source_key_field,$row)."' /></td>";
						}
						if(isset($_GET["parentcontrol"]) && !empty($_GET["parentcontrol"]))
						{
							?>
							<td class='td0' ><img alt=""  onclick="apply_value('<?=$_GET["parentcontrol"]?>','<?=row($_GET["keyfield"],$row)?>','<?=htmlspecialchars(row($_GET["textfield"],$row))?>',<?=$_GET["rtype"]?>)" src="images/check.gif" /></td>
						<?php
						}
						if(isset($this->config->edit_buttons) && ($this->config->edit_buttons[0] || $this->config->edit_buttons[1]) && !isset($_GET["noaction"]) )
						{
							echo "<td class='td0' >";
							if($this->config->edit_buttons[0])
							{
								echo "<a href='http://".get_url(1,$this->config)."&id=".row($this->config->source_key_field,$row)."'><img src='images/edit.gif' alt=''  /></a>&nbsp;";
							}
							if($this->config->edit_buttons[1])
							{		
								echo "<img onclick=\"delete_row('".get_url(1,$this->config)."&delete=".row($this->config->source_key_field,$row)."')\" src='images/del.gif' />";
							}	
							echo "</td>";
						}
						if(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1")
						{
							$edit_mode_result = db_query("SELECT ".$this->config->select_fields." FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$row[$this->config->source_key_field]."'");
							$edit_mode_row = db_fetch_assoc($edit_mode_result);
						}
						foreach(array_keys($field_array) as $key)
						{
							
							if(in_array($key,$this->config->sum_fields))
							{
								if(!isset($footer_array[$key]))
								{
									$footer_array[$key] = 0;
								}
								$footer_array[$key] = $footer_array[$key] + $row[$key];
							}
							else
							{
								$footer_array[$key] = 0;
							}
							echo "<td ".(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1" ? " nowrap " : "")." >";
							if(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1" && !in_array($key,array_keys($this->config->template_fields)) && !in_array($key,array_keys($this->config->eval_fields)))
							{
								
								if(!isset($edit_mode_row[$key]))
								{
									$edit_mode_row[$key] = $row[$key];
								}
								if(isset($this->config->controls[$key]))
								{
									$text = $this->config->controls[$key];
									$text->id = "rf_".$row[$this->config->source_key_field]."_".$key;
									$text->name = "rf_".$row[$this->config->source_key_field]."_".$key;
									if($text->type == "list" || ($text->type == "longlist" && $text->showselect = true))
									{
										$text->css_style = $this->config->controls[$key]->css_style."width:80px;";
									}
									$text->value = $edit_mode_row[$key];
									$text->current_row = $edit_mode_row;
									$text->create();
									if($this->config->controls[$key]->type == "checkbox")
									{
										array_push($checkbox_array,$this->config->controls[$key]->name);
									}
								}
								else
								{
									$text = new Control("rf_".$row[$this->config->source_key_field]."_".$key,"text","");
									$text->css_style = "width:100%;";
									$text->current_row = $edit_mode_row;
									$text->value = $edit_mode_row[$key];
									$text->create();
								}
							}
							else
							{
								if(isset($this->config->template_fields[$key]))
								{
									require($this->config->template_fields[$key]);
								}
								else if(isset($this->config->eval_fields) && isset($this->config->eval_fields[$key]))
								{
									eval($this->config->eval_fields[$key]);
								}
								else
								{
									echo $row[$key];
								}	
							}
							echo "</td>";
						}
						echo "</tr>";
					}
					//end table row
					if(count($footer_array) > 2) //it's wrong way, but i have no time now
					{
					//create footer
					
					echo "<tr>";
					foreach($footer_array as $key=>$value)
					{
						if(in_array($key,$this->config->sum_fields))
						{
						?>
						<th><?=$value?></th>
						<?
						}
						else
						{
								?>
						<th>&nbsp;</th>
						<?
						}
					}
					echo "</tr>";
					}
					//create footer
					?>
						</table>
						<?php
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
										if(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1")
						{
							?>
							<p><input type="button" class="buttons" onclick="simple_save_form('<?=get_url(1,$this->config)?>');" value="<?=_BUTTON_SAVE?>" />
							&nbsp;&nbsp;
							<input type="reset" class="buttons"  value="<?=_BUTTON_CANCEL?>" />
							</p>
							<?
						}
						?>
					</td>
					<?php if(!empty($this->config->table_right_content)) { ?>
				<td class="rtd">
				<?php require($this->config->table_right_content); ?>
				</td>
				<?php } ?>
				</tr>
				
			</table>
			<div class="paging"><?=$pager?></div>
			<?
				
		if($this->config->table_content_bottom_1 != "")
		{
			require($this->config->table_content_bottom_1);
		}
		?></form><?
		//if(isset($this->config->table_edit_mode) && $this->config->table_edit_mode == "1")
		//{
			?>
			<div id="dlonglist" >
            <iframe id="ilonglist" frameborder="0" style=""></iframe>
            <input onclick="$('#dlonglist').fadeOut();" class="buttons" type="button" value="Закрыть" />
            </div>
			<?
		//}
        //?>
        <div id="fields_list">
        Настройка полей:
		<table>
        <?
        foreach($farray as $key)
        {
            ?>
            <tr><td><input name="show_hide" <?=(!in_array($key,$hide_array) ? "checked" : "")?> type="checkbox" class="cb" value="<?=$key?>" />&nbsp;<?=(isset($this->config->title_fields[$key]) ? $this->config->title_fields[$key] : $key)?>&nbsp;</td><td><input name="t_field_width" type="text" style="width:30px;" id="w_<?=$key?>" title="Установить ширину поля" value="<?=(isset($width_array[$key]) ? $width_array[$key] : "")?>" /></td></tr>
            <?
        }
        ?>
		</table>
        <div><a onclick="set_show_hide('<?=$_GET["t"]?>')" href="javascript:void(0);">Сохранить</a>&nbsp;&nbsp;&nbsp;<a onclick="hide_fields()" href="javascript:void(0);">Закрыть</a></div>
        </div>
        <?
		if($engine_db == "fb")
		{
			ibase_free_result($result);
		}
	}
	function delete_row($id)
	{
		require_once("function/db.php");
		$use_udf = false;
		if(file_exists("udf/".str_replace("`","",$this->config->table).".php"))
		{
			$use_udf = true;
			
			require_once("udf/".str_replace("`","",$this->config->table).".php");
		}
		if($use_udf)//before delete
		{
			if(function_exists("before_delete"))
			{
				before_delete($id);
			}
		}
		db_query("DELETE FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '$id'");
		if($use_udf)//after delete
		{
			if(function_exists("after_delete"))
			{
				after_delete($id);
			}
		}
		header("Location: http://".get_url(1,$this->config));
	}
	function delete_rows()
	{
		require_once("function/db.php");
		$use_udf = false;
		if(file_exists("udf/".str_replace("`","",$this->config->table).".php"))
		{
			$use_udf = true;
			require_once("udf/".str_replace("`","",$this->config->table).".php");
			
		}
		foreach(array_keys($_POST) as $current)
		{
			if(substr($current,0,3) == "cb_")
			{
				if($use_udf)//before delete
				{
					if(function_exists("before_delete"))
					{
						before_delete($_POST[$current]);
					}
					
				}
				db_query("DELETE FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '$_POST[$current]'");
				if($use_udf)//after delete
				{
					if(function_exists("after_delete"))
					{
						after_delete($_POST[$current]);
					}
				}
			}
		}
		
		header("Location: http://".get_url(2,$this->config));
	}
}
?>