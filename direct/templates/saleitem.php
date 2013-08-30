<?php
if($_GET["id"] != "-1")
{
	?>
	
	<iframe frameborder='0'  id="cframe" style="width:1000px;height:500px;" src="index.php?t=goods&parent=<?=$_GET["parentid"]?>&goods=<?=$_GET["id"]?>&noheader&nofooter" ></iframe> 
	<?
}

?>