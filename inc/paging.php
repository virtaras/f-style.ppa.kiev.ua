<?php
if(!function_exists("get_url")) {
function get_url($id)
{
	$url = "";
	if(_CONTENT_TYPE == "catalog")
	{
		global $catalog_array;
		global $head;
		$url = _SITE.$catalog_array[$head["id"]]["url"]."/$id";
		return $url;
	}
	if(_CONTENT_TYPE == "asearch")
	{
		 $url = _SITE."asearch/$_GET[stext]/$id.html";
		 return $url;
	}
	if(_CONTENT_TYPE == "search")
	{
		$_GET["page"] = $id;
		
		$url = _SITE."search.html?id=".$_GET["id"]."&";
		foreach($_GET as $key=>$value)
		{
			if($key == "content" || $key == "url" || $key == "sort" || $key == "id")
			{
				continue;
			}
			$url .= $key."=".$value."&";
		}
	}
    if(_CONTENT_TYPE == "content" )
	{
		global $head;
		$url =  "/" . $head["url"] . "/" . $id;
	}
	  if(_CONTENT_TYPE == "tag" )
	{
		global $head;
		$url =  "/tag/" .$_GET["text"] . "/" . $id;
	}

	if(_CONTENT_TYPE == "news")
	{
		$url =  _SITE."news/page/$id.html";
	}

	return $url;
}
}
//echo $sql_text;

//echo $count_sql_str;
if(!isset($count))
{
	$sql_arr = explode("order by",strtolower($sql_text));
	$sql_arr2 = explode("from",$sql_arr[0]);
	$sql_arr2 = $sql_arr2[count($sql_arr2 = $sql_arr2)-1];
	$count_sql_str = !isset($count_sql_str) ? "SELECT count(*)  FROM ".$sql_arr2 : $count_sql_str;
	$count  = execute_scalar($count_sql_str);
}
$pagesize = isset($pagesize) ? $pagesize : setting("default_page_size");
$pager_size = isset($pager_size) ? $pager_size : 20;
$limit = "";
$pagecount = ceil($count/$pagesize);

$current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
$limit = " LIMIT ".($pagesize*($current_page-1)).",$pagesize";
$pager = "";
$intervals = ceil($pagecount/$pager_size);
if($pagecount > $pager_size && $current_page > $pager_size)
{
//	$pager .= "<a  href='".get_url(1)."'>Первая</a>";
}
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
				$page_number = isset($arr2) ? $arr2[$page - 1] : $page - 1;
				//$pager .= "<a class='navig-left' href='".get_url($page_number)."'>« Назад</a>";
			}
			
			if($end_value >= $count)
			{
				$end_value = $count;
			}
			if($page == $current_page)
			{
				$page_number = isset($arr2) ? $arr2[$page] : $page;
				$pager .= "<li class='active' > <a  href='".get_url($page_number)."'>".$page."</a> </li>";
			}
			else
			{
				$class = "";//"pl";
				$page_number = isset($arr2) ? $arr2[$page] : $page;
				$pager .= "<li> <a  href='".get_url($page_number)."'>".$page."</a> </li>";
			}
			$pager .= "";
			if($end_value >= $count)
			{
				break;
			}
			if((($pager_size - $ind) == 1) && (($intervals - $i) > 1))
			{
				$page_number = isset($arr2) ? $arr2[$page + 1] : $page + 1;
				//$pager .= "<a class='navig-right' href='".get_url($page_number)."'>Вперёд »</a>";
			}
		}
	}
}
if($pagecount > $pager_size && $current_page < $pagecount)
{
//	$pager .= "<a  href='".get_url($pagecount)."'>Последняя ($pagecount)</a>";
}
