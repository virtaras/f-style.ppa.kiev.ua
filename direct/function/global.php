<?php
	//$type
	//0 - base url
	//1 - base url + sort (if exists)
	function get_url($type,$config,$rmget = array())
	{
		$url = "";
		$base_url = $_SERVER["HTTP_HOST"]."$_SERVER[PHP_SELF]?t=$_GET[t]".(isset($_GET["noheader"]) ? "&noheader" : "").(isset($_GET["nofooter"]) ? "&nofooter" : "").(isset($_GET["parent"]) && !in_array("parent",$rmget) ? "&parent=$_GET[parent]" : "").(isset($_GET["noaction"]) ? "&noaction" : "").(isset($_GET["catalog"])  ? "&catalog=$_GET[catalog]" : "").(isset($_GET["sql"]) && !in_array("sql",$rmget)  ? "&sql=$_GET[sql]" : "").(isset($_GET["parentcontrol"])  ? "&parentcontrol=$_GET[parentcontrol]" : "").(isset($_GET["textfield"])  ? "&textfield=$_GET[textfield]" : "").(isset($_GET["keyfield"])  ? "&keyfield=$_GET[keyfield]" : "").(isset($_GET["rtype"])  ? "&rtype=$_GET[rtype]" : "").(isset($_GET["return"])  ? "&return" : "");
foreach($config->get_params as $key)
{
	if(in_array($key,$rmget))
	{
		continue;
	}
	$base_url .= (isset($_GET[$key])  ? "&$key=$_GET[$key]" : "");
}		
		switch($type)
		{
			case "0":
				$url = $base_url.(isset($_GET["page"]) ? "&page=$_GET[page]" : "");
				break;
			case "1":
				$url = $base_url.(isset($_GET["sort"]) ? "&sort=".str_replace(" ","+",$_GET["sort"]) : "").(isset($_GET["page"]) ? "&page=$_GET[page]" : "");
				break;
			case "2":
				$url = $base_url.(isset($_GET["sort"]) ? "&sort=".str_replace(" ","+",$_GET["sort"]) : "");
				break;
			case "3":
				$url = $base_url.(isset($_GET["sort"]) ? "&sort=".str_replace(" ","+",$_GET["sort"]) : "");
				break;	
		}
		return $url;
	}
	function get_last_id($table)
	{
		$lastidsql = mysql_query("select LAST_INSERT_ID() as ID from $table ");
		$lastid = mysql_fetch_assoc($lastidsql);
		return $lastid["ID"];
	}

	if(!function_exists("mb_eregi_replace"))
	{
		function mb_eregi_replace($find,$replace,$string)
		{
			if(!is_array($find))
				$find = array($find);
				
			if(!is_array($replace))
			{
				if(!is_array($find))
					$replace = array($replace);
				else
				{
					// this will duplicate the string into an array the size of $find
					$c = count($find);
					$rString = $replace;
					unset($replace);
					for ($i = 0; $i < $c; $i++)
					{
						$replace[$i] = $rString;
					}
				}
			}
			foreach($find as $fKey => $fItem)
			{
				$between = explode(strtolower($fItem),strtolower($string));
				$pos = 0;
				foreach($between as $bKey => $bItem)
				{
					   $between[$bKey] = substr($string,$pos,strlen($bItem));
					   $pos += strlen($bItem) + strlen($fItem);
				}
				$string = implode($replace[$fKey],$between);
			}
			return($string);
		}
	}
	if ( !function_exists('htmlspecialchars_decode') )
{	
    function htmlspecialchars_decode($text)
    {
        return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
    }
	}
	function get_tree_id($id,&$arr,&$url)
	{

		$row = db_get_row("SELECT id,parentid,urlname FROM catalog WHERE id = '".execute_scalar("SELECT parentid FROM catalog WHERE id = '".$id."' LIMIT 0,1")."'");
		
		if($row["id"] != "")
		{
			array_push($arr,$row["parentid"]);
			array_push($url,$row["urlname"]);
			
		}
		
		if($row["parentid"] != "" && $row["parentid"] != 0 && count($row) == 1)
		{
			get_tree_id($row["id"],$arr,$url);
		}

	}

   function ruslat ($string) # Задаём функцию перекодировки кириллицы в транслит.
{
$string = mb_eregi_replace("ж","zh",$string);
$string = mb_eregi_replace("ё","yo",$string);
$string = mb_eregi_replace("й","i",$string);
$string = mb_eregi_replace("ю","yu",$string);
$string = mb_eregi_replace("ь","",$string);
$string = mb_eregi_replace("ч","ch",$string);
$string = mb_eregi_replace("щ","sh",$string);
$string = mb_eregi_replace("ц","c",$string);
$string = mb_eregi_replace("у","u",$string);
$string = mb_eregi_replace("к","k",$string);
$string = mb_eregi_replace("е","e",$string);
$string = mb_eregi_replace("н","n",$string);
$string = mb_eregi_replace("г","g",$string);
$string = mb_eregi_replace("ш","sh",$string);
$string = mb_eregi_replace("з","z",$string);
$string = mb_eregi_replace("х","h",$string);
$string = mb_eregi_replace("ъ","",$string);
$string = mb_eregi_replace("ф","f",$string);
$string = mb_eregi_replace("ы","y",$string);
$string = mb_eregi_replace("в","v",$string);
$string = mb_eregi_replace("а","a",$string);
$string = mb_eregi_replace("п","p",$string);
$string = mb_eregi_replace("р","r",$string);
$string = mb_eregi_replace("о","o",$string);
$string = mb_eregi_replace("л","l",$string);
$string = mb_eregi_replace("д","d",$string);
$string = mb_eregi_replace("э","ye",$string);
$string = mb_eregi_replace("я","ja",$string);
$string = mb_eregi_replace("с","s",$string);
$string = mb_eregi_replace("м","m",$string);
$string = mb_eregi_replace("и","i",$string);
$string = mb_eregi_replace("т","t",$string);
$string = mb_eregi_replace("б","b",$string);
$string = mb_eregi_replace("Ё","yo",$string);
$string = mb_eregi_replace("Й","I",$string);
$string = mb_eregi_replace("Ю","YU",$string);
$string = mb_eregi_replace("Ч","CH",$string);
$string = mb_eregi_replace("Ь","",$string);
$string = mb_eregi_replace("Щ","SH",$string);
$string = mb_eregi_replace("Ц","C",$string);
$string = mb_eregi_replace("У","U",$string);
$string = mb_eregi_replace("К","K",$string);
$string = mb_eregi_replace("Е","E",$string);
$string = mb_eregi_replace("Н","N",$string);
$string = mb_eregi_replace("Г","G",$string);
$string = mb_eregi_replace("Ш","SH",$string);
$string = mb_eregi_replace("З","Z",$string);
$string = mb_eregi_replace("Х","H",$string);
$string = mb_eregi_replace("Ъ","",$string);
$string = mb_eregi_replace("Ф","F",$string);
$string = mb_eregi_replace("Ы","Y",$string);
$string = mb_eregi_replace("В","V",$string);
$string = mb_eregi_replace("А","A",$string);
$string = mb_eregi_replace("П","P",$string);
$string = mb_eregi_replace("Р","R",$string);
$string = mb_eregi_replace("О","O",$string);
$string = mb_eregi_replace("Л","L",$string);
$string = mb_eregi_replace("Д","D",$string);
$string = mb_eregi_replace("Ж","Zh",$string);
$string = mb_eregi_replace("Э","Ye",$string);
$string = mb_eregi_replace("Я","Ja",$string);
$string = mb_eregi_replace("С","S",$string);
$string = mb_eregi_replace("М","M",$string);
$string = mb_eregi_replace("И","I",$string);
$string = mb_eregi_replace("Т","T",$string);
$string = mb_eregi_replace("Б","B",$string);
return $string;
}
function create_urlname($name)
{
	$name = htmlspecialchars_decode($name,ENT_QUOTES);
	$name = trim($name);
	$name = str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\"","&"),"!",$name);
	$name = mb_strtolower($name, 'UTF-8');
	$name = ruslat($name);
    $name = mb_ereg_replace("!!!","-",$name);
    $name = mb_ereg_replace("!!","-",$name);
	$name = mb_ereg_replace("!","-",$name);
    return $name;
}

?>