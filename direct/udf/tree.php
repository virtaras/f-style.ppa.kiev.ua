<?
session_start();
header('Content-Type: text/javascript; charset=utf-8');
require("../config/global.php");
require("../lib/user.php");
require("../function/connection.php");
require("../function/db.php");
require("../function/global.php");

if(isset($_GET["id"]))
{
	$sql = db_query("SELECT id,name FROM catalog WHERE parentid = ".$_GET["id"]." ORDER BY name");
	$ind = 0;
	if(mysql_num_rows($sql) > 0) { 
		echo "[ ";
		if($_GET["id"] == "0")
		{
			echo "{\"data\":{\"title\":\"Без  владельца\",\"attr\":{\"onclick\":\"set_parent(0,'Выбрать');\",\"href\":\"javascript:set_parent(0,'Выбрать');\"}},\"attr\" : {\"id\":\"0\"}},";
		}
		while($r = mysql_fetch_assoc($sql))
		{
			$ind++;
			
			echo "{\"data\":{\"title\":\"".$r["name"]."\",\"attr\":{\"onclick\":\"set_parent($r[id],'$r[name]');\",\"href\":\"javascript: set_parent($r[id],'$r[name]');;\"}},\"attr\" : {\"id\":\"$r[id]\"},\"children\":[],\"state\":\"closed\"}";
			if($ind < mysql_num_rows($sql))
			{
				echo ",";
			}
			
		}
		echo "]";
	}
}

?>