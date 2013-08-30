<?php 
	ini_set("ibase.dateformat","%d.%m.%Y");//date format for firebird
	$language = "ru";//interface language
	$config_title = "Evolution Маркет v 2.5.1";//default title for all document
	$engine_path = dirname(getenv('SCRIPT_FILENAME'));//path to engine folder
	$engine_db = "mysql";//fb - Firebird,mysql - MySql
	$ibase_connect_charset = "WIN1251";//charset for firebird connection
	$base_config_path = dirname(getenv('SCRIPT_FILENAME'))."/config/base.php"; //path to custom default config path
	$menu = array(); //menu for framework
	$titles = array("brands"=>"Бренды","exist_type"=>"Признак наличия","delivery"=>"Типы доставки");//array of titles, use when config file not found
	$sourceid = 0;

	
?>