<?php
class DB
{
	var $config = "";
	var $id = "-1";
	var $object_name = "";
	var $close_form = false;
	function return_filed_value($id)
	{
		?>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>	
	<script language="JavaScript" type="text/javascript" src="js/jquery.selectboxes.js"></script>	
	<script language="JavaScript" type="text/javascript" src="js/global_function.js"></script>
		<script language="JavaScript" type="text/javascript">
		apply_value('<?=$_GET["parentcontrol"]?>','<?=$id?>','<?=$_POST[$_GET["textfield"]]?>','<?=$_GET["rtype"]?>');
		</script>
		<?
	}
	function check_unique($array,$table,$edit_title_fields)
	{
		global $engine_db;
		$i = 0;
		$res = _MESSAGE_UNIQUEERROR."\\n";
		foreach($array as $current)
		{
			switch($engine_db)
			{
				case "mysql":
					$count = execute_scalar("SELECT count(*) FROM $table WHERE $current = '".trim($_POST["$current"])."'");
					if($count > 0)
					{
						$i++;
						$res .= $edit_title_fields[$current]."\\n";
					}
					break;
				case "fb":
					break;
					
			}
		}
		if($i > 0)
		{
			$_SESSION["last_post"] = $_POST;
			$_SESSION["rowerror"] = $res;
			header("Location: http://".$_SESSION["back_row_url"]);
			exit;
		}
	}
	function DB($config,$id,$object_name)
	{
		$this->config  = $config;
		$this->id  = $id;
		$this->object_name  = $object_name;
		
	}
	function simple_save()
	{
		require_once("function/db.php");
		global $engine_db;
		$use_udf = false;
		if(file_exists("udf/".$this->object_name.".php"))
		{
			$use_udf = true;
			require("udf/".$this->object_name.".php");
		}
		
		foreach(array_keys($_POST) as $key)
		{
			$keystr = explode("_",$key);
			if($keystr[0] == "rf")
			{
				
				$id =  $keystr[1];
				$name = $keystr[2];
				
				if($use_udf)//before insert
				{
					if(function_exists("before_update"))
					{
						before_update($id);
					}
				}
						
				
				
				$result = db_query("SELECT * FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$id."'");
				$coln = db_num_fields($result);
				$sql = "UPDATE ".$this->config->table." SET ";
				
				$checkboxes = array();
				if(isset($_POST["checkbox_array"]))
				{
					$checkboxes = explode(",",$_POST["checkbox_array"]);
				}
				for ($i = 0; $i < $coln; $i++)
				{
					$field_name = db_field_info($result, $i, "name");
					$field_type = db_field_info($result, $i, "type");
					
					if(in_array("rf"."_".$id."_".$field_name,$checkboxes))
					{
						$sql .= $field_name." = ";
						if(isset($_POST["rf"."_".$id."_".$field_name]))
						{
							$sql .= "1,";
						}
						else
						{
							$sql .= "0,";
						}
					}
					else
					{
						if(isset($_POST["rf"."_".$id."_".$field_name]))
						{
							$sql .= "$field_name = ";
							switch($field_type)
							{
								case "real":
									$sql .= str_replace(",",".","'".$_POST["rf"."_".$id."_".$field_name]."'").",";
									break;
								case "string":
									$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES)."',";
									break;		
								case "VARCHAR":
									$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES)."',";
									case "blob":
									$sql .= "'".htmlspecialchars($_POST["rf"."_".$id."_".$field_name],ENT_QUOTES)."',";
									break;
								case "DATE":
									$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES)."',";
									break;
								case "INTEGER":
									$sql .= htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES).",";
									break;
								case "BIGINT":
									$sql .= htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES).",";
									break;
								case "DECIMAL":
									$sql .= str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES)).",";
									break;
								case "CHAR":
									$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES)."',";
									break;
								case "NUMERIC":
									$sql .= "'".str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST["rf"."_".$id."_".$field_name]),ENT_QUOTES))."',";
									break;	
								default :	
									$sql .= "'".$_POST["rf"."_".$id."_".$field_name]."',";
									break;
							}	
						}
					}
				}	
				
				$sql = substr($sql,0,strlen($sql)-1);
				$sql .= " WHERE ".$this->config->source_key_field." = '".$id."'";
				//echo $sql;
				//exit();
				db_query($sql);
				if($use_udf)//after insert
				{
					if(function_exists("after_update"))
					{
						after_update($id);
					}
				}
			}
		}
		header("Location: http://".get_url(1,$this->config));
	}
	function insert()
	{
		
		require_once("function/db.php");
		global $engine_db;
		$this->check_unique(isset($this->config->unique_fields) ? $this->config->unique_fields : array(),$this->config->table,$this->config->edit_title_fields);
		$use_udf = false;
		if(file_exists("udf/".$this->object_name.".php"))
		{
			$use_udf = true;
			require("udf/".$this->object_name.".php");
		}
		if($use_udf)//before insert
		{
			if(function_exists("before_insert"))
			{
				before_insert();
			}
		}
		$result = db_query("SELECT * FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$this->id."'");
		$coln = db_num_fields($result);
		$sql = "INSERT INTO ".$this->config->table." ";
		$field = "";
		$value = "";
		$checkboxes = array();
		if(isset($_POST["checkbox_array"]))
		{
			$checkboxes = explode(",",$_POST["checkbox_array"]);
		}
		for ($i = 0; $i < $coln; $i++)
		{
				$field_name = db_field_info($result, $i, "name");
				$field_type = db_field_info($result, $i, "type");
			if(in_array($field_name,$checkboxes))
			{
				$field .= "$field_name,";
				if(isset($_POST[$field_name]))
				{
					$value .= "1,";
				}
				else
				{
					$value .= "0,";
				}
			}
			else
			{
				if(isset($_POST[$field_name]))
				{
					$field .= "$field_name,";
					switch($field_type)
					{
						case "real":
							$value .= str_replace(",",".","'$_POST[$field_name]'").",";
							break;
						case "string":
							$value .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "VARCHAR":
							$value .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "blob":
							$value .= "'".htmlspecialchars($_POST[$field_name],ENT_QUOTES)."',";
							break;	
						case "DATE":
							$value .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "INT":
							$value .= htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES).",";
							break;
						case "BIGINT":
							$value .= htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES).",";
							break;
						case "DECIMAL":
							$value .= "'".str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES))."',";
							break;
						case "CHAR":
							$value .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "NUMERIC":
							$value .= "'".str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES))."',";
							break;	
						default :	
							$value .= "'$_POST[$field_name]',";
							break;
					}	
				}
			}
		}
		if($engine_db == "fb")
		{
			$lastid = ibase_gen_id($this->config->generator_name,1);
			$field = "($source_key_field,".substr($field,0,strlen($field)-1).")";
			$value = " VALUES ($lastid,".substr($value,0,strlen($value)-1).")";
		}
		else
		{
			$field = "(".substr($field,0,strlen($field)-1).")";
			$value = " VALUES (".substr($value,0,strlen($value)-1).")";
		}
		
		$sql .= $field.$value;
		db_query($sql);
		//echo $sql;
		//exit();
		if($engine_db == "mysql")
		{
			$lastidsql = db_query("select LAST_INSERT_ID() as ID from ".$this->config->table." ");
			$last_id = db_fetch_assoc($lastidsql);
			$lastid = $last_id["ID"];
		}
		if($use_udf)//after insert
		{
			if(function_exists("after_insert"))
			{
				after_insert($lastid);
			}
		}
		
		if(isset($_GET["return"]))
		{
			$this->return_filed_value($lastid);
			exit();
		}
		
		if($this->close_form)
		{
		
			header("Location: http://".get_url(1,$this->config));
		}
		else
		{
			header("Location: http://".get_url(1,$this->config)."&id=$lastid");
		}	
	}
	function update()
	{
		require_once("function/db.php");
		//$this->check_unique(isset($unique_fields) ? $unique_fields : array(),$table,$edit_title_fields);
		$use_udf = false;
		if(file_exists("udf/".$this->object_name.".php"))
		{
			$use_udf = true;
			require("udf/".$this->object_name.".php");
		}
		if($use_udf)//before update
		{
			if(function_exists("before_update"))
			{
				before_update($this->id);
			}
		}
		
		$result = db_query("SELECT * FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$this->id."'");
		$coln = db_num_fields($result);
		$sql = "UPDATE ".$this->config->table." SET ";
		$checkboxes = array();
		if(isset($_POST["checkbox_array"]))
		{
			$checkboxes = explode(",",$_POST["checkbox_array"]);
		}
		for ($i = 0; $i < $coln; $i++)
		{
			$field_name = db_field_info($result, $i, "name");
			$field_type = db_field_info($result, $i, "type");
			
			if(in_array($field_name,$checkboxes))
			{
				$sql .= "$field_name = ";
				if(isset($_POST[$field_name]))
				{
					$sql .= "1,";
				}
				else
				{
					$sql .= "0,";
				}
			}
			else
			{
				if(isset($_POST[$field_name]))
				{
					$sql .= "$field_name = ";
					switch($field_type)
					{
						case "real":
							$sql .= str_replace(",",".","'$_POST[$field_name]'").",";
							break;
						case "string":
							$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;		
						case "VARCHAR":
							$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "blob":
							$sql .= "'".htmlspecialchars($_POST[$field_name],ENT_QUOTES)."',";
							break;	
						case "DATE":
							$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "INTEGER":
							$sql .= htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES).",";
							break;
						case "BIGINT":
							$sql .= htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES).",";
							break;
						case "DECIMAL":
							$sql .= str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)).",";
							break;
						case "CHAR":
							$sql .= "'".htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES)."',";
							break;
						case "NUMERIC":
							$sql .= "'".str_replace(",",".",htmlspecialchars(str_replace("\\","",$_POST[$field_name]),ENT_QUOTES))."',";
							break;	
						default :	
							$sql .= "'$_POST[$field_name]',";
							break;
					}	
				}
			}
		}	
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= " WHERE ".$this->config->source_key_field." = '".$this->id."'";
		db_query($sql);
		if($use_udf)//after update
		{
			if(function_exists("after_update"))
			{
				after_update($this->id);
			}
		}
		if(isset($_GET["return"]))
		{
			$this->return_filed_value($this->id);
			exit();
		}
		if($this->close_form)
		{
			
			header("Location: http://".get_url(1,$this->config));
		}
		else
		{
			header("Location: http://".get_url(1,$this->config)."&id=$this->id");
		}	
		
	}
	function copy()
	{
		require_once("function/db.php");
		global $engine_db;
		$use_udf = false;
		if(file_exists("udf/".$this->object_name.".php"))
		{
			$use_udf = true;
			require("udf/".$this->object_name.".php");
		}
		if($use_udf)//before copy
		{
			if(function_exists("before_copy"))
			{
				before_copy($this->id);
			}
		}
		
		
		
		$result = db_query("SELECT * FROM ".$this->config->table." WHERE ".$this->config->source_key_field." = '".$this->id."'");
		$row = db_fetch_assoc($result);
		$coln = db_num_fields($result);
		$sql = "INSERT INTO ".$this->config->table." ";
		$field = "";
		$value = "";
		for ($i = 0; $i < $coln; $i++)
		{
			$field_name = db_field_info($result, $i, "name");
			$field_type = db_field_info($result, $i, "type");
			if($field_name == "id" || $field_name == "ID")
			{
				continue;
			}
			$field .= "$field_name,";
			$value .= "'".$row[$field_name]."',";
		}	
		$lastid ="";
		if($engine_db == "fb")
		{
			$lastid = ibase_gen_id($this->config->generator_name,1);
			$field = "(".$this->config->source_key_field.",".substr($field,0,strlen($field)-1).")";
			$value = " VALUES ($lastid,".substr($value,0,strlen($value)-1).")";
		}
		else
		{
			$field = "(".substr($field,0,strlen($field)-1).")";
			$value = " VALUES (".substr($value,0,strlen($value)-1).")";
		}
		$sql .= $field.$value;
		db_query($sql);
		if($engine_db == "mysql")
		{
			$lastidsql = db_query("select LAST_INSERT_ID() as ID from  ".$this->config->table);
			$last_id = db_fetch_assoc($lastidsql);
			$lastid = $last_id["ID"];
		}
		if($use_udf)//after copy
		{
			if(function_exists("after_copy"))
			{
				after_copy($this->id,$lastid);
			}
		}
		header("Location: http://".get_url(1,$this->config)."&id=$lastid");
	}
	function execute()
	{
		if($this->id == "-1")
		{
			$this->insert();
		}
		else
		{
			$this->update();
		}
	}
	
}

?>