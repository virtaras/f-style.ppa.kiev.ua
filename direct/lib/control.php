<?php
class Control
{
	var $caption = "";
	var $type = "text";//text,date,longtext,list,checkbox,label,template,radio,function,hidden,longlist,time
	var $source = "";
	var $keyfield = "id";
	var $textfield = "name";
	var $css_class = "";
	var $css_style = "";
	var $js = ""; //javascript 
	var $id = ""; // control id
	var $name = ""; //control name
	var $required = false; // is required
	var $value = "";
	var $template = "";
	var $max_length = "";
	var $argument = array();//for function
	var $rows = 3;//for longtext
	var $default_value = "";
	var $is_multiple = "";//for list
	var $size = "";//for list
	var $onchange = "";//for list
	var $tooltip = "";
	var $date_display = "";
	var $template_mode = "full"; //full,standart
	var $current_row = array();
	var $html = ""; //additional html in control area
	var $tablename = ""; //name of table for control with type 'longlist', this property is required
	var $showselect = false; //option for longlist, in this case select field will  display
	var $windowheight = "400px";
	var $windowwidth = "600px";
	var $disabled = false;//now only for longlist
    var $parentcontrol = ""; //name of the parent control, only for longlist
	function Control($name,$type = "",$caption = "",$source = "",$value = "",$text = "")
	{
		$this->name = $name;
		$this->id = $name;
		if($caption != "")
		{
			$this->caption = $caption;
		}
		else
		{
			$this->caption = $name;
		}
		if($source != "")
		{
			$this->source = $source;
		}
		if($type != "")
		{
			$this->type = $type;
		}
		if($value != "")
		{
			$this->keyfield = $value;
		}	
		if($text != "")
		{
			$this->textfield = $text;
		}
	}
	function set_base_params()
	{
		
	}
	function create()
	{
		
		global $hide_x_list;
		if(isset($_GET["t"]))
        {
            if(file_exists("udf/$_GET[t].php"))
            {
                include_once("udf/$_GET[t].php");
            }
        }
		
		switch($this->type)
		{
			case "text":
				?>
				<input title="<?=$this->tooltip?>" <?=($this->max_length != "maxlength='$this->max_length'" ? "" : "")?> type="text" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='input_box'")?> <?=$this->js?> name="<?=$this->name?>" id="<?=$this->id?>"  value="<?=$this->value?>" />
				<?
				break;	
			case "date":
				if($this->date_display == "" || $_GET["id"] == "-1") {$this->date_display = $this->value;}
				?>
				<span <?=$this->js?> <?=($this->css_class != "" ? "class='$this->css_class'" : "")?> title="<?=$this->tooltip?>" id="da<?=$this->id?>"><?=$this->date_display?></span>
				<input   type="hidden" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?>   name="<?=$this->name?>" id="<?=$this->id?>"  value="<?=$this->value?>" />
				&nbsp;<button style="height:20px;" id="b_<?=$this->id?>" class="buttons">...</button>
			    <script type="text/javascript">//<![CDATA[
			      Zapatec.Calendar.setup({
			        firstDay          : 1,
			        showOthers        : true,
			        range             : [2007.01, 2999.12],
			        electric          : false,
			        inputField        : "<?=$this->id?>",
			        button            : "b_<?=$this->id?>",
			        ifFormat          : "%Y-%m-%d",
			        daFormat          : "%d.%m.%Y",
					displayArea       : "da<?=$this->id?>"
			      });
			    //]]></script>
                &nbsp;
                <input type="button" class="buttons" onclick="document.getElementById('da<?=$this->id?>').innerHTML = '';document.getElementById('<?=$this->id?>').value = '';" value="X"/>
				<?
				break;
			case "longtext":
				?>
				<textarea title="<?=$this->tooltip?>" <?=($this->max_length != "" ? "onkeypress=\"javascript:return check_length(this,$this->max_length)\"" : "")?> <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='text_area'")?> <?=$this->js?> name="<?=$this->name?>" id="<?=$this->id?>" rows="<?=$this->rows?>" ><?=$this->value?></textarea>
				<?
				break;
			case "list":
				?>
				<select onchange="<?=$this->onchange?>" title="<?=$this->tooltip?>" <?=($this->size != "" ? "size='$this->size'" : "")?> <?=($this->is_multiple ? " multiple='true'" : "")?> <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='select_box'")?> <?=$this->js?> name="<?=$this->name?><?=($this->is_multiple ? "[]" : "")?>" id="<?=$this->id?>" >
				<option value="-1"><?=_UNSELECTED?></option>
				<?php
					$value_array = array();
					if($this->is_multiple)
					{
						$value_array = explode(",",$this->value);
					}
					if(is_array($this->source))
					{
						foreach($this->source as $key=>$value)
						{
							if($this->is_multiple)
							{
								$selected = in_array($key,$value_array) ? " SELECTED ": "";
							}
							else
							{
								$selected = $key == $this->value ? " SELECTED ": "";
							}
							?>
							<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
							<?
						}
					}
					else
					{
						$result = db_query($this->source);
						
						while($row = db_fetch_assoc($result))
						{
							$selected = "";
							if($this->is_multiple)
							{
								$selected = in_array($row[$this->keyfield],$value_array) ? " SELECTED ": "";
							}
							else
							{
								$selected = $row[$this->keyfield] == $this->value ? " SELECTED ": "";
							}
							?>
							<option <?=$selected?> value="<?=$row[$this->keyfield]?>"><?=$row[$this->textfield]?></option>
							<?
						}
					}
				?>
				</select>&nbsp;
				<? if(!$hide_x_list) { ?>
				<input type="button" value="X" class="buttons" onclick="reset_select('<?=$this->id?>')" style="width:30px;" />
				<? }
				break;
			case "checkbox":
				?>
				<input title="<?=$this->tooltip?>" type="checkbox" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='cb'")?> <?=$this->js?> name="<?=$this->name?>" id="<?=$this->id?>"  value="<?=$this->value?>" <?=($this->value == 1 ? " CHECKED " : "")?> />
				<?
				break;
			case "label":
				?>
				<label title="<?=$this->tooltip?>" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "")?> <?=$this->js?>  id="<?=$this->id?>"  ><?=$this->value?></label>
				<?
				break;
			case "template":
				require($this->template);
				break;
			case "radio":
					if(is_array($this->source))
					{
						foreach($this->source as $key=>$value)
						{
							?>
							<input title="<?=$this->tooltip?>" <?=( $key == $this->value ? " CHECKED ": "")?> type="radio" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='rb'")?> <?=$this->js?> name="<?=$this->name?>"   value="<?=$key?>" />
							&nbsp;<?=$value?>
							<?
						}
					}
					else
					{
						$result = db_query($this->source);
						while($row = db_fetch_assoc($result))
						{
							?>
							<input title="<?=$this->tooltip?>" <?=( $row[$this->keyfield] == $this->value ? " CHECKED ": "")?> type="radio" <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='rb'")?> <?=$this->js?> name="<?=$this->name?>"   value="<?=$row[$this->keyfield]?>" />
							&nbsp;<?=$row[$this->textfield]?>
							<?
						}
					}
					break;
			case "function":
				if(!empty($this->argument) && count($this->argument) > 0)
				{
					array_push($this->argument,$this);
					call_user_func_array($this->source,$this->argument);	
				}
				else
				{
					call_user_func($this->source,$this);	
				}
				break;
			case "longlist":
				if($this->tablename == "")
				{
					echo "Internal error: empty property 'tablename'";
				}
				else
				{
					if($this->showselect)
					{
						?>
						<select name="<?=$this->name?>" title="<?=$this->tooltip?>" <?=($this->size != "" ? "size='$this->size'" : "")?> <?=($this->is_multiple ? " multiple='true'" : "")?> <?=($this->css_style != "" ? "style='$this->css_style'" : "")?> <?=($this->css_class != "" ? "class='$this->css_class'" : "class='select_box'")?> <?=$this->js?> name="<?=$this->name?><?=($this->is_multiple ? "[]" : "")?>" id="<?=$this->id?>">
						<option value="-1"><?=_UNSELECTED?></option>
						<?php
							$result = db_query($this->source);
							$value_array = array();
							if($this->is_multiple)
							{
								$value_array = explode(",",$this->value);
							}
							while($row = db_fetch_assoc($result))
							{
								$selected = "";
								if($this->is_multiple)
								{
									$selected = in_array($row[$this->keyfield],$value_array) ? " SELECTED ": "";
								}
								else
								{
									$selected = $row[$this->keyfield] == $this->value ? " SELECTED ": "";
								}
								?>
								<option <?=$selected?> value="<?=$row[$this->keyfield]?>"><?=$row[$this->textfield]?></option>
								<?
							}
						?>
						</select>
						<? if(!$this->disabled) { ?>
						<input type="button"  onclick="edit_longlist('index.php?t=<?=$this->tablename?>&noheader&nofooter&noaction&sql=<?=urlencode($this->source)?>&parentcontrol=<?=$this->id?>&keyfield=<?=$this->keyfield?>&textfield=<?=$this->textfield?>&rtype=<?=($this->showselect ? "1" : "0")?>&return&rtype=1&id=-1','<?=$this->windowwidth?>','<?=$this->windowheight?>',this);" class="buttons" value="+"/>
						<?
					} }
					else
					{
					?>
					<input type="hidden" name="<?=$this->name?>" id="<?=$this->id?>" value="<?=$this->value?>" />
					<?
					echo "<label id='t_".$this->id."' class='text_box'>".execute_scalar("SELECT ".$this->textfield." FROM ".$this->tablename." WHERE ".$this->keyfield. " = '".$this->value."'")."</label>";
					
					if(!$this->disabled) {
					?>&nbsp;
					<input type="button" onclick="edit_longlist('index.php?t=<?=$this->tablename?>&noheader&nofooter&noaction&sql=<?=urlencode($this->source)?>&parentcontrol=<?=$this->id?>&keyfield=<?=$this->keyfield?>&textfield=<?=$this->textfield?>&rtype=<?=($this->showselect ? "1" : "0")?>&rtype=0','<?=$this->windowwidth?>','<?=$this->windowheight?>',this,'<?=$this->parentcontrol?>');" class="buttons" value="..."/>
					<?
					} }
				if(!$this->disabled) {
				?>	
				
				&nbsp;
				<input type="button" class="buttons" onclick="document.getElementById('<?=$this->id?>').value = '-1';document.getElementById('t_<?=$this->id?>').innerHTML = '';" value="X"/>
				<?
				} }
				break;	
			case "time":
				$time = explode(":",$this->value);
				$h = $time[0];
				$m = $time[1];
				
				?>
				<input type="hidden" name="<?=$this->name?>" id="<?=$this->id?>" value="<?=$this->value?>" />
				<select onchange="set_time('<?=$this->id?>')" id="h_<?=$this->id?>">
				<?php
				for($i = 0; $i < 24;$i++)
				{
					?>
					<option <?=($h == $i ? "SELECTED" : "")?> value="<?=$i?>"><?=$i?></option>
					<?
				}
				?>
				</select>&nbsp;:&nbsp;<select onchange="set_time('<?=$this->id?>')" id="m_<?=$this->id?>">
				<?php
				for($i = 0; $i < 60;$i++)
				{
					?>
					<option <?=($m == $i ? "SELECTED" : "")?> value="<?=$i?>"><?=$i?></option>
					<?
				}
				?>
				</select>
				<?
				break;
		}
		echo $this->html;
	}


}
?>