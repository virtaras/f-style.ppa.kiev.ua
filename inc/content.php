<?php
global $db;
$sql_text = "SELECT content.*,modules.path,DATE_FORMAT(sdate,'%d.%m.%Y') as item_date, 
	(SELECT count(c.id) FROM content c WHERE c.ispublish = 1 AND c.parentid = content.id) as scount
FROM content LEFT JOIN modules ON modules.id = content.script
WHERE content.id = '".trim($_GET["id"])."' AND ispublish = 1 ";
$head = execute_row_assoc($sql_text);


if($head["id"] == "")
{
	header("HTTP/1.0 404 Not Found");
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$sql_text = "SELECT content.*,modules.path,DATE_FORMAT(sdate,'%d.%m.%Y') as item_date
	FROM content
LEFT JOIN modules ON modules.id = content.script
WHERE content.url = '404' ";

	$head =  dbGetRow($sql_text,$db);
}
$parent = $head["parentid"];
$title = $head["title"] != "" ? $head["title"] : $head["name"];
$keywords = $head["keywords"];
$description = $head["description"];
$script = $head["script"];

$path_arr = array($_GET["id"]);
get_path_array($_GET["id"],&$path_arr);
asort($path_arr);
define("_CONTENT_TOP_LEVEL",$path_arr[count($path_arr)-1]);
reset($path_arr);




function get_content()
{

    global $head;

	include(_DIR._TEMPLATE."content.html");
	
	/*
	$pagesize = $head["pagesize"] > 0 ? $head["pagesize"] : setting("default_page_size");
	$sql_text = "SELECT *,DATE_FORMAT(sdate,'%d.%m.%Y') as s_date,(SELECT count(c.id) FROM content c WHERE c.ispublish = 1 AND c.parentid = content.id) as scount,
	(SELECT id FROM images WHERE source = 1 AND parentid = content.id AND is_main = 1  LIMIT 0,1) as imid,
						(SELECT format FROM images WHERE source = 1 AND parentid = content.id AND is_main = 1 LIMIT 0,1) as imformat
	 FROM content WHERE parentid = '$head[id]' AND ispublish = 1 ORDER BY sdate DESC";
	$count_sql_str = "SELECT count(id) FROM content WHERE parentid = '$head[id]' AND ispublish = 1  ";
	include("inc/paging.php");
	$head["items"] = dbQuery($sql_text . $limit,$db);
    */
}

?>
