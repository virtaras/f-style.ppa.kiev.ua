<?php
if($_GET["id"] != "-1")
{
	?>
	<iframe frameborder="0" width="100%" src="index.php?t=requestitem&parent=<?=$_GET["id"]?>&noheader&nofooter" height="500px;"  ></iframe>
	<?
}
?>