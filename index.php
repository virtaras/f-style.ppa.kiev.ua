<?php
/*
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
*/
session_start();
ini_set("display_errors","On");
include("inc/protection.php");
include("inc/constant.php");
include("inc/connection.php");
include("inc/global.php");
include("inc/engine.php");
include("virtaras/functions.php");
include_once(_DIR."inc/cache/menu.php");
/*
function mime_header_encode($str, $data_charset, $send_charset) {
  if($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
  }
  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}
$name_from= $_SERVER['HTTP_HOST'];// имя отправителя
 $email_from="no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']); // email отправителя
                        $name_to=""; // имя получателя
                        $email_to="virtaras@ppa.kiev.ua"; // email получателя
                        $data_charset="UTF-8"; // кодировка переданных данных
                        $send_charset="UTF-8"; // кодировка письма
                        $subject= "BBBBBB "; // тема письма
                        $body="asdfasdf";
$to = mime_header_encode($name_to, $data_charset, $send_charset)
                 . ' <' . $email_to . '>';
  $subject = mime_header_encode($subject, $data_charset, $send_charset);
  $from =  mime_header_encode($name_from, $data_charset, $send_charset) 
                     .' <' . $email_from . '>'; 
  if($data_charset != $send_charset) {
    $body = iconv($data_charset, $send_charset, $body); 
  }
  $headers = "From: $from\r\n";
  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
  $headers .= "Mime-Version: 1.0\r\n";
  $headers .= "X-Mailer: PHP/".phpversion()."\r\n";  
mail($to, $subject, $body, $headers);
echo htmlspecialchars($to.$subject.$body.$headers);
exit();
//send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "","virtaras@ppa.kiev.ua","UTF-8", "UTF-8", "BBBBBB ", "asdfasdf" );
*/
$content_type = "content";
$title = "";
$description = "";
$keywords = "";
$seo_text = "";
if(!isset($_GET["content"])) {
	if(isset($_GET["urlid"]) && $_GET["urlid"] != "")
	{
		$data = execute_row_assoc("SELECT * FROM routing WHERE urlid = '".intval($_GET["urlid"])."'");
		if($data["id"] != "")
		{
			switch($data["content"])
			{
				case "3":
					$content_type = "brand";
					break;		
			}
			$_GET["id"] = $data["contentid"];
		}	
	}
	else
	{

		if(isset($_GET["urlname"]) && $_GET["urlname"] != "")
		{
			
			if(substr($_GET["urlname"],strlen($_GET["urlname"])-1) == "/")
			{
				$_GET["urlname"] = substr($_GET["urlname"],0,strlen($_GET["urlname"])-1);
			}
			
			
			$_GET["id"] = execute_scalar("SELECT contentid FROM routing WHERE urlname = '".$_GET["urlname"]."'");
			
			
			if($_GET["id"] == "")
			{
				$_GET["id"] = "404";
			}
		}
		else
		{
			if(!isset($_GET["content"]))
			{
				$_GET["id"] = execute_scalar("SELECT contentid FROM routing WHERE urlname = 'index'");
			}
		}	
	}
}

if(isset($_GET["content"]))
{
	$content_type = $_GET["content"];
}
if(isset($_GET["id"]) && $_GET["id"] == "")
{
	$content_type = "content";
	$_GET["id"] = "index";
}
define("_CONTENT_TYPE",$content_type);
include("inc/emarket.php");
if(file_exists("inc/".$content_type.".php"))
{
	require("inc/".$content_type.".php");
}
if(_CONTENT_TYPE == "content" && $head["urlname"] == "index")
{
	define("_IS_INDEX",true);
}
else
{
	define("_IS_INDEX",false);
}
if($title == "")
{
	$title = setting("title");
}
if($description == "")
{
	$description= setting("description");
}
if($keywords == "")
{
	$keywords = setting("keywords");
}
if(!isset($_SESSION["basket"]) && isset($_COOKIE["basket"]))
{
	$_SESSION["basket"] = stripslashes($_COOKIE["basket"]); 
}


$_SESSION["template"] = _TEMPLATE;

if(isset($chainlet))
{
	define("_TOP_LEVEL",$chainlet[count($chainlet)-1]);
}
else
{
    define("_TOP_LEVEL",0);
}

if(_CONTENT_TYPE == "search")
{
	content();
}
else
{
	include(_DIR._TEMPLATE."template.html");
}

if($content_type != "basket" && $content_type != "login" && $content_type != "new")
{

	$_SESSION["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
}	
$_SESSION["prev_url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
mysql_close($db);
/*
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
echo "<div style='font-size:11px;'>This page was created in ".$totaltime." seconds</div>"; 
*/
?>

