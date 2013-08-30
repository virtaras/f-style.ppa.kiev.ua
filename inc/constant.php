<?php
define("_DIR",$_SERVER['DOCUMENT_ROOT']."/");//absolute path to site directory
include(_DIR."/direct/config/db.php");
define("_SITE","http://".$_SERVER['HTTP_HOST']."/");//site url
define("_HOST",$db_array[0][0]);//MySql server host
define("_DB",$db_array[0][1]);//MySql database name
define("_DBUSER",$db_array[0][3]);//MySql database user name
define("_DBPASSWORD",$db_array[0][4]);//MySql database user password
define("_TEMPLATE","templates/"); //Site template
define("_TEMPL",_SITE._TEMPLATE);
?>