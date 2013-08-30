<?
function create_cache()
{
	$file = "../inc/cache/currency_array.php";
	$str = "<? \$currency_array = array();";
	$all_id_sql = mysql_query("SELECT * FROM currency ");
	$vi = 0;
	while($row = mysql_fetch_assoc($all_id_sql))
	{
		$str .= "\$currency_array[$row[id]][\"course\"] = '$row[course]';";
		$str .= "\$currency_array[$row[id]][\"shortname\"] = '$row[shortname]';";
        $str .= "\$currency_array[$row[id]][\"name\"] = '$row[name]';";
		
	}
	//$str .= "\$base_currency = ".execute_scalar("SELECT val FROM settings WHERE name = 'base_currency'").";";
	$str .= " ?>";
	unlink($file);
	file_put_contents($file,$str);
	
	//start creating cros courses
	db_query("TRUNCATE TABLE currency_course ");
	include($file);
	$base_currency = execute_scalar("SELECT val FROM settings WHERE name = 'base_currency'");
	
	$file = "../inc/cache/cross_course.php";
	$str = "<? \$cross_course = array();";
	foreach($currency_array as $key=>$value)
	{
		foreach($currency_array as $k=>$v)
		{
		
			$cross_course = $value["course"]/$v["course"];
			db_query("INSERT INTO currency_course(from_currency,to_currency,course) VALUES 
			($key,$k,'$cross_course')");
			$str .= "\$cross_course[$key][$k] = '$cross_course';";
			
			
		}
	}
	
	
	$str .= " ?>";
	unlink($file);
	file_put_contents($file,$str);
}
function after_update($id)
{
	create_cache();
}
function after_insert($id)
{
	create_cache();
}
function after_delete($id)
{
	create_cache();
}
?>