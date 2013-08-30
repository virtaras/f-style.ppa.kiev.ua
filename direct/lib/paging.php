<?php
$count_sql_str = "";
if(isset($this->config->count_query) && !empty($this->config->count_query))
{
	$count_sql_str = $this->config->count_query;
}
else
{
	$sql_arr = explode("order by",strtolower($this->config->source));
	$sql_arr2 = explode("from",$sql_arr[0]);
    $sql_arr2 = $sql_arr2[count($sql_arr2) - 1];
	$count_sql_str = "SELECT count(*) as all_count FROM $sql_arr2";
    
}

$count_sql = db_query($count_sql_str);
$count  = 0;
switch($engine_db)
{
	case "mysql":
		while( $count_res = db_fetch_array($count_sql))
		{
			$count = $count + $count_res["all_count"];
		}
		mysql_free_result($count_sql);
		break;
	case "fb":	
		while( $count_res = db_fetch_array($count_sql))
		{
			$count = $count + $count_res["ALL_COUNT"];
		}
		ibase_free_result($count_sql);
		break;
}

if(!isset($this->config->pagesize))
{
	$pagesize = 10;
}
else
{
	$pagesize = $this->config->pagesize;
}
if(isset($_SESSION["pagesize_".$_GET["t"]."_all"]))
{
	$pagesize = 10000000;
}
$limit = "";
$pagecount = ceil($count/$pagesize);
$pager_size = 5;
$current_page = isset($_GET["page"]) ? $_GET["page"] : 1;

switch($engine_db)
{
	case "mysql":
		$limit = "LIMIT ".($pagesize*($current_page-1)).",$pagesize";
		break;
	case "fb":
		$limit = " FIRST $pagesize SKIP ".($pagesize*($current_page-1))." ";
		break;
}	
$pager = "";

	if(isset($_SESSION["pagesize_".$_GET["t"]."_all"]))
	{
		$pager = "<a class='pl' style='cursor:pointer;' onclick=\"$.post('udf/ajax.php',{action:'show_pager',t:'".$_GET["t"]."'},reload_page)\" >Пейджинг</a>&nbsp;";
	}
	else
	{
		if($pagecount > 1)
		{		
		$pager = "<a class='pl' style='cursor:pointer;' onclick=\"$.post('udf/ajax.php',{action:'show_all',t:'".$_GET["t"]."'},reload_page)\" >Все</a>&nbsp;";
		}
}
$intervals = ceil($pagecount/$pager_size);
for($i = 0;$i < $intervals;$i++)
{
	
	$finish = ($i+1)*$pagesize*$pager_size;
	$start = $finish - ($pagesize*$pager_size) + 1;
	if(($current_page*$pagesize ) >= $start && $current_page*$pagesize <= $finish)
	{
		for($ind = 0; $ind < $pager_size;$ind++)
		{
			$end_value = $start+($ind*$pagesize) + $pagesize - 1;
			$page = ceil($end_value/$pagesize);
			if($i > 0 && $ind == 0)
			{
				$pager .= "<a class='pl' href='http://".get_url(2,$this->config)."&page=".($page - 1)."'>...</a>&nbsp;&nbsp;";
			}
			
			if($end_value >= $count)
			{
				$end_value = $count;
			}
			if($page == $current_page)
			{
				$class = "cpl";
				$pager .= "<label class='$class'>".($start+($ind*$pagesize))."...$end_value</label>";
			}
			else
			{
				$class = "pl";
				$pager .= "<a class='$class' href='http://".get_url(2,$this->config)."&page=$page'>".($start+($ind*$pagesize))."...$end_value</a>";
			}
			$pager .= "&nbsp;&nbsp;";
			if($end_value >= $count)
			{
				break;
			}
			if((($pager_size - $ind) == 1) && (($intervals - $i) > 1))
			{
				$pager .= "<a class='pl' href='http://".get_url(2,$this->config)."&page=".($page + 1)."'>...</a>";
			}
		}
	}
}
switch($engine_db)
{
	case "mysql":
		$this->config->source = $this->config->source." ".$limit;
		break;
	case "fb":
		$this->config->source = str_replace(array("select","SELECT"),"SELECT $limit ",$this->config->source);
		break;
}		
?>