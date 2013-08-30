<?
function before_update($id)
{
	if(isset($_POST["urlname"]) && trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = trim(ruslat(strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim($_POST["name"])))));
	}	
}
function before_insert()
{
	if(trim($_POST["urlname"]) == "")
	{
		$_POST["urlname"] = trim(ruslat(strtolower(str_replace(array("+","_","/","\\","(",")","*",":","'",".",";","`","'"," ","	","#","`","~","+","=","-","*",",","<",">","!","?","@","¶","%","{","}","_","[","]","|","®","©","\""),"-",trim($_POST["name"])))));
	}	
    
}


?>