<?php
if($_GET["id"] != "-1")
{
	?>
	
	<iframe frameborder='0'  id="cframe" style="width:100%;height:400px;" src="index.php?t=ordersrow&parent=<?=$_GET["id"]?>&noheader&nofooter" ></iframe>
	<iframe frameborder='0'  id="cframe" style="width:100%;height:400px;" src="index.php?t=orders_comments&parent=<?=$_GET["id"]?>&noheader&nofooter" ></iframe>
	<?
}

?>