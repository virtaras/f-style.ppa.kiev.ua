<?
if(!isset($sourceid))
{
	global $sourceid;
}
echo $sourceid;
echo $_GET["id"];
?>
<script language="JavaScript">
function load_goods()
{
	show_wait("images_area");
	$("#images_area").load('templates/img.php',{load_images:true,source:<?=$sourceid?>,parent:<?=$_GET["id"]?>})
	$("#images").empty();
	document.getElementById("goods_img1").value = "";
	$("#images_buttons").show();
}
</script>