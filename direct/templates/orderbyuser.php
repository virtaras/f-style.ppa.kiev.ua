<?
if(isset($_GET["id"]) && $_GET["id"] != "-1")
{
	?>
	<iframe frameborder='0' id="cframe" height="1000" style="width:100%;height:1000px;" src="index.php?t=orders&parent=<?=$_GET["id"]?>&noheader&nofooter" ></iframe>
	<?
}

?>