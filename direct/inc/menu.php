<script language="JavaScript" type="text/javascript">
navHover = function() {
	var lis = document.getElementById("navmenu-h").getElementsByTagName("LI");
	for (var i=0; i<lis.length; i++) {
		lis[i].onmouseover=function() {
			this.className+=" iehover";
		}
		lis[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" iehover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", navHover);
</script>
<ul id="navmenu-h">  
  <?php
 
  function showmenu($parent = -1,&$html)
  {
	global $currentuser;	 
	 $menuid = $currentuser->Menu != "-1" ? $currentuser->Menu : execute_scalar("SELECT menuid FROM usersgroup WHERE id = '$currentuser->Group'");
	 $sql = db_query("SELECT * FROM menuitem WHERE menuid = '$menuid' AND parentid = '$parent' ORDER BY showorder");
	 if($parent != "-1")
	 {
		$html .= "<ul>";
	 }
	  while($r = mysql_fetch_assoc($sql))
	  {
			if($r["childsql"] != "" && $r["linktemplate"] != "")
			{
				
				$subsql = mysql_query($r["childsql"]);
				while($row = mysql_fetch_assoc($subsql))
				{
					$link = $r["linktemplate"];
					foreach(array_keys($row) as $key)
					{
						$link = str_replace("[$key]",$row[$key],$link);
					}
					$html .= "
				<li>$link";
				showmenu($r["id"],$html);
				$html .= "</li>";
				}
			}
			else
			{
				$html .= "
				<li><a href='$r[link]'>$r[title]</a>";
				showmenu($r["id"],$html);
				$html .= "</li>";
			}	
	  }
	   if($parent != "-1")
		 {
			$html .= "</ul>";
		 }
  }
	$html = "";
	showmenu(-1,$html);
	echo $html;	
  ?>
  
  <li><a href="login.php?logout">Выход</a></li>
</ul>
