<?php
if (!function_exists("htmlspecialchars_decode")) {
function htmlspecialchars_decode($string,$style=ENT_COMPAT)
    {
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
        return strtr($string,$translation);
    } }
function execute_scalar($query)
{
//	echo $query;
    $res = mysql_query($query);
	$row =  mysql_fetch_array($res);
	return $row[0];
}
function execute_row_assoc($query)
{
	$res = mysql_query($query);
	return mysql_fetch_assoc($res);
}
function execute_row_array($query)
{
	$res = mysql_query($query);
	return mysql_fetch_array($res);
}
function get_last_id($table)
{
	$lastidsql = mysql_query("select LAST_INSERT_ID() as ID from $table ");
	$lastid = mysql_fetch_assoc($lastidsql);
	return $lastid["ID"];
}
function get_main_image($id,$resize = 130,$source = 0)
{
	$images = execute_scalar("SELECT id FROM images WHERE parentid = '$id' AND source = '$source'  ORDER BY is_main DESC LIMIT 0,1");
	//echo "SELECT id FROM images WHERE parentid = '$id' AND source = '$source'  ORDER BY is_main DESC LIMIT 0,1";
	if($images != "")
	{
		return _SITE."images/$resize/$images.jpg";
	}
	else
	{
		return _TEMPL."images/nofoto.jpg";;
	}
}
function get_main_image_id($id,$source = 0)
{
	return execute_scalar("SELECT id FROM images WHERE parentid = '$id' AND source = '$source'  ORDER BY is_main DESC LIMIT 0,1");
}
function get_main_image_path($id,$source = 0)
{
	return "/images/files/".execute_scalar("SELECT image FROM images WHERE parentid = '$id' AND source = '$source'  ORDER BY is_main DESC LIMIT 0,1");
}

function highlight_this($text, $words) {
   $words = trim($words);
    $wordsArray = explode(' ', $words);
    foreach($wordsArray as $word) {
        if(strlen(trim($word)) != 0)
           $text = eregi_replace($word, '<span class="highlight">\\0</span>', $text); 
    }

    return $text;
} 
function cyr_strtolower($a) {
        $offset=32;
        $m=array();
        for($i=192;$i<224;$i++)$m[chr($i)]=chr($i+$offset);
        return strtr($a,$m);
}
function cyr_strtoupper($a) {
        $offset=32;
        $m=array();
        for($i=192;$i<224;$i++)$m[chr($i+$offset)]=chr($i);
        return strtr($a,$m);
}
function maxsite_str_word($text, $counttext = 30, $sep = ' ') {
	$words = split($sep, $text);

	if ( count($words) > $counttext )
		$text = join($sep, array_slice($words, 0, $counttext));

	return $text;
}
function html($name)
{
	if(file_exists(_DIR."inc/cache/$name.inc"))
	{
		$file = file_get_contents(_DIR."inc/cache/$name.inc");
		echo htmlspecialchars_decode($file,ENT_QUOTES);
		//include(_DIR."inc/cache/$name.inc");
	}
	else
	{
		echo htmlspecialchars_decode(execute_scalar("SELECT html FROM contentarea WHERE strongname = '$name'"),ENT_QUOTES);
}
	}	
function html2($name)
{
	if(file_exists(_DIR."inc/cache/$name.inc"))
	{
		$content = file_get_contents(_DIR."inc/cache/$name.inc");
		return $content;
	}
	else
	{
		return htmlspecialchars_decode(execute_scalar("SELECT html FROM contentarea WHERE strongname = '$name'"),ENT_QUOTES);
	}	
}
function content($name = "content",$id = "")
{
	switch($name)
	{
		case "content":
			if(function_exists("get_content"))
			{
				get_content();
			}
		break;
		case "meta":
			global $title;
			global $description;
			global $keywords;
			?>
			<title><?=$title?></title>
			<meta name="description" content="<?=$description?>" />
			<meta name="keywords" content="<?=$keywords?>" />
			<?
		break;
	case "form":
		include("form.php");
		get_fields($id);
		break;	
	}
}
function setting($name)
{
	if(defined("_".$name))
	{
		return constant("_".$name);
	}
	else
	{
		return execute_scalar("SELECT val FROM settings WHERE name = '$name'");
	}	
}
function check_email($email)
{
	if(eregi('^([a-z0-9_]|\\-|\\.)+'.'@'.'(([a-z0-9_]|\\-)+\\.)+'.'[a-z0-9]{2,4}$', $email))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function get_path_array($id,&$arr = array())
{
	$sql_text = "SELECT parentid FROM content WHERE id = '$id'";
    if(!is_numeric($id))
    {
		$sql_text = "SELECT parentid FROM content WHERE urlname = '$id'";
    }
    $parent = execute_scalar($sql_text);
	
	if($parent != "0" && $parent != "")
	{
		array_push($arr,$parent);
		get_path_array($parent,$arr);
	}
}
function set_visited($id)
{
	if(setcookie("content".$id,"1",time()+60*60*24*30,"/"))
	{
		
	}
	else
	{
		$_SESSION["content".$id] = "1";
	}
}
function check_visited($id)
{
	if(isset($_COOKIE["content".$id]) || isset($_SESSION["content".$id]))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_content_positions($parent = 0,$limit = "")
{
	$arr = array();
	$sql = mysql_query("SELECT id,name,urlname FROM content WHERE parentid = '$parent' ORDER BY showorder ASC ".($limit != "" ? "LIMIT 0,".$limit : ""));
	while($r = mysql_fetch_assoc($sql))
	{
		if($r["urlname"] != "")
		{
			$arr[urlencode($r["urlname"])] = $r["name"];
		}
		else
		{
			$arr[$r["id"]] = $r["name"];
		}	
	}
	return $arr;
}
function get_field($table,$id,$field = "name")
{
	return execute_scalar("SELECT $field FROM $table WHERE id = '$id'");
}
function get_child($parentid,&$childarray)
{
	$sql = mysql_query("SELECT id FROM catalog WHERE parentid = '$parentid'");
	while($r = mysql_fetch_assoc($sql))
	{
		array_push($childarray,$r["id"]);
		get_child($r["id"],&$childarray);
	}
}
function cp1251_utf8( $sInput )
{
    $sOutput = "";

    for ( $i = 0; $i < strlen( $sInput ); $i++ )
    {
        $iAscii = ord( $sInput[$i] );

        if ( $iAscii >= 192 && $iAscii <= 255 )
            $sOutput .=  "&#".( 1040 + ( $iAscii - 192 ) ).";";
        else if ( $iAscii == 168 )
            $sOutput .= "&#".( 1025 ).";";
        else if ( $iAscii == 184 )
            $sOutput .= "&#".( 1105 ).";";
        else
            $sOutput .= $sInput[$i];
    }

    return $sOutput;
}
function get_title()
{
	global $content_type;
	switch($content_type)
	{
		case "static":
			global $currentpage;
			echo $currentpage["caption"];
			break;
		case "content":
			global $head;
			echo $head["name"];
			break;
		case "catalog":
			global $head;
			echo $head["name"];
			break;
        case "tovar":
			global $tovar;
			echo /*$tovar["cname"]." ".*/$tovar["name"];
			break;
        case "news":
            global $title;
			echo $title;
             break;
		case "new":
            global $head;
			echo "Регистрация";
             break;
		case "allquestion":
            global $head;
			echo $head;
             break;
		case "login":
             global $head;
				echo "Авторизация";
             break;
		 case "brand":
            global $brandrow;
			echo $brandrow["name"];
             break;	 
		default:
			global $title;
			echo $title;
			break;
		
	}
}
function get_path()
{
	global $content_type;
	switch($content_type)
	{
		case "static":
			global $currentpage;
			?>
			<a  href="<?=_SITE?>">Главная</a><a><?=$currentpage["caption"]?></a>
			<?
			break;
		case "basket":
			include(_DIR._TEMPLATE."breadcrumbs.html");
			break;	
		case "content":
			global $head;
			global $path_arr;
			include(_DIR._TEMPLATE."breadcrumbs.html");
			break;
		case "catalog":
			global $head;
			get_catalog_path();
			break;
		case "search":
			global $head;
			get_catalog_path();
			break;	
        case "tovar":
			global $tovar;
			get_catalog_path();
			break;
        case "news":
		global $newsrow;
		if(isset($_GET["id"])) {
		?><a href="<?=_SITE?>">Главная</a><a href="<?=_SITE?>news.html">Новости</a><a><?=$newsrow["title"]?></a><?
		} else {
		?>
		 <a href="<?=_SITE?>">Главная</a><a>Новости</a>
		<?
		}		
             break;
		case "allquestion":
		?>
            <a  href="<?=_SITE?>">Главная</a><a class="path" href="<?=_SITE?>questions.html">Архив опросов</a><?
             break;
		case "new":
		?>
            <a  href="<?=_SITE?>">Главная</a><a>Регистрация нового пользователя</a><?
             break;
		case "login":
		?>
            <a  href="<?=_SITE?>">Главная</a><a>Авторизация</a><?
             break;
		case "compare":
		global $catalog_array;
		?>
            <a  href="<?=_SITE?>">Главная</a><a  href="<?=_SITE?>compare/<?=$_GET["id"]?>.html">Сравнение товаров</a><a href="/<?=$catalog_array[$_GET["id"]]["full_url"]?>" ><?=$catalog_array[$_GET["id"]]["name"]?></a><?
             break;	 
		case "brand":
			global $brandrow;
			include(_DIR._TEMPLATE."breadcrumbs.html");
			break;
		case "asearch":
				global $sarr;
				?>
            <a  href="<?=_SITE?>">Главная</a><a>Поиск по запросу:&nbsp;<?=$sarr[1]?></a><?
			break;
		 case "account_info":
		?>
            <a  href="<?=_SITE?>">Главная</a><a  href="<?=_SITE?>account.html">Личный кабинет</a><a>Персональная информация</a><?
             break;	
		 case "account_registration":
		?>
            <a  href="<?=_SITE?>">Главная</a><a  href="<?=_SITE?>account.html">Личный кабинет</a><a>Смена пароля</a><?
             break;		
		case "account":
		?>
            <a  href="<?=_SITE?>">Главная</a><a>Личный кабинет</a><?
             break;	 
		case "account_history":
			?>
            <a  href="<?=_SITE?>">Главная</a><a  href="<?=_SITE?>account.html">Личный кабинет</a><a>История заказов</a><?
			break;
		case "tags":
		global $title;
			?>
            <a  href="<?=_SITE?>">Главная</a><a href="<?=_SITE?>tag<?=$_GET["id"]?>/<?=$_GET["text"]?>.html"><?=$title?></a><?
			break;	
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



function write_menu($template,$parentid,$selected = "",$add_style_array = array("",""))
{
	global $menuarray;
	$html = "";
	if(is_array($menuarray) && isset($menuarray[$parentid]))
	{
		$i = 0;
		$current_menu = $menuarray[$parentid];
		foreach($current_menu as $key=>$value)
		{
			$i++;
			$add_style = "";
			if($i == 1)
			{
				$add_style = $add_style_array[0];
			}
			if($i == count($menuarray))
			{
				$add_style = $add_style_array[1];
			}
			$pos1 = stripos($_SERVER["REQUEST_URI"], str_replace(".html","",$value["link"]));
			if ($pos1 === false) { 			
			$html .= sprintf($template,$value["nofollow"] == 1 ? "rel='nofollow'" : "","",$value["link"],$add_style,$value["name"]); } else 
			{
				if($selected != "")
				{
					$html .= sprintf($template,$value["nofollow"] == 1 ? "rel='nofollow'" : "",$selected,$value["link"],$add_style,$value["name"]);
				}
				else
				{
					$html .= sprintf($template,$value["nofollow"] == 1 ? "rel='nofollow'" : "","",$value["link"],$add_style,$value["name"]);
				}	
			}
		}
	}
	return $html;
}
function get_correct_str($num, $str1, $str2, $str3) {
    $val = $num % 100;

    if ($val > 10 && $val < 20) return $str3;
    else {
        $val = $num % 10;
        if ($val == 1) return $str1;
        elseif ($val > 1 && $val < 5) return $str2;
        else return $str3;
    }
}
function YearTextArg($year) {
	$year = abs($year);
	$t1 = $year % 10;
	$t2 = $year % 100;
	return ($t1 == 1 && $t2 != 11 ? "год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? "года" : "лет"));
}
function prep($str){
	return mysql_real_escape_string(strip_tags($str));
}
function random_password($length = 8, $allow_uppercase = true, $allow_numbers = true){
	$out = '';
	$arr = array();
	for($i=97; $i<123; $i++) $arr[] = chr($i);
	if ($allow_uppercase) for($i=65; $i<91; $i++) $arr[] = chr($i);
	if ($allow_numbers) for($i=0; $i<10; $i++) $arr[] = $i;
	shuffle($arr);
	for($i=0; $i<$length; $i++)
	{
		$out .= $arr[mt_rand(0, sizeof($arr)-1)];
	}
	return $out;
}
?>
