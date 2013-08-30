<?php
function db_fetch_assoc($result)
{
	global $engine_db;
	switch($engine_db)
	{
		case "mysql":
			return mysql_fetch_assoc($result);
			break;
		case "fb":
			return ibase_fetch_assoc($result);
			break;
	}
}
function db_fetch_array($result)
{
	global $engine_db;
	switch($engine_db)
	{
		case "mysql":
			return mysql_fetch_array($result);
			break;
		case "fb":
			return get_object_vars(ibase_fetch_object($result));
			break;
	}
}
function db_query($source)
{
	global $engine_db;
	global $db;
	switch($engine_db)
	{
		case "mysql":
			return mysql_query($source);
			break;
		case "fb":
			return ibase_query($db,$source);
			break;
	}
}
function row($name,&$row)
{
	global $engine_db;
	switch($engine_db)
	{
		case "mysql":
			return $row[$name];
			break;
		case "fb":
			return $row[strtoupper($name)];
			break;
	}
}
function db_num_fields($result)
{
	global $engine_db;
	switch($engine_db)
	{
		case "mysql":
			return mysql_num_fields($result);
			break;
		case "fb":
			return ibase_num_fields($result);
			break;
	}
}
function db_field_info($result, $i, $type)
{
	global $engine_db;
	switch($engine_db)
	{
		case "mysql":
			switch($type)
			{
				case "name":
					return mysql_field_name($result, $i); 
					break;
				case "type":
					return mysql_field_type($result, $i);
					break;
				case "table":
					return mysql_field_table($result, $i);
					break;		
			}
			break;
		case "fb":
			$field_array = ibase_field_info($result, $i);
			switch($type)
			{
				case "name":
					return $field_name = $field_array["alias"];
					break;
				case "type":
					return $field_name = $field_array["type"];
					break;
				case "table":
					return $field_name = $field_array["relation"];
					break;	
			}
			break;
	}
}
function db_get_field($query,$field)
{
	$res = db_query($query);
	$row =  db_fetch_assoc($res);
	return $row[$field];
}
function db_get_row($query)
{
	$res = db_query($query);
	$row =  db_fetch_assoc($res);
	return $row;
}
function execute_scalar($sql)
{
	$res = db_query($sql);
	$row =  db_fetch_array($res);
	return $row[0];
}
?>