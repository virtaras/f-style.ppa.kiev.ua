
<html>
<head>
<title><?=$config_title?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/admin.css" />
<link rel="stylesheet" type="text/css" media="print" href="css/print.css" />
<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>	
<script language="JavaScript" type="text/javascript" src="js/jquery.selectboxes.js"></script>	

<script language="JavaScript" type="text/javascript" src="js/const.php"></script>	
<script language="JavaScript" type="text/javascript" src="js/global_function.js"></script>

<link rel="stylesheet" href="js/calendar/themes/aqua.css" />
<link rel="stylesheet" href="js/calendar/themes/layouts/tiny.css" />
<script type="text/javascript" src="js/calendar/utils/zapatec.js"></script>
<script type="text/javascript" src="js/calendar/src/calendar.js"></script>
<script type="text/javascript" src="js/calendar/lang/calendar-ru_win_.js"></script>

<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<?
if($_GET["t"] == "catalog" || $_GET["t"] == "goods")
{
	?>
	<script language="JavaScript" type="text/javascript" src="js/jquery.hotkeys.js"></script>	
	<script language="JavaScript" type="text/javascript" src="js/jquery.cookie.js"></script>	
	<script language="JavaScript" type="text/javascript" src="js/jquery.jstree.js"></script>	
	<link href="../js/smoothness/ui.jquery.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="../js/ui.jquery.js"></script>
	<script language="JavaScript">
	$().ready(function() {
			$("#select_tree").dialog({
			modal: true,
			autoOpen: false,
			minHeight:200,
			width:400,
			minWidth:400,
			buttons: {
				"Закрыть": function() {
					$(this).dialog('close');
				}
			}
		});
		});
	<?
	if($_GET["t"] == "goods")
	{
		?>
		
		$(function() {
		$("#stext").autocomplete({
			source: "udf/ajax.php?action=fast_search",
			minLength: 2,
			width:'300px',
			select: function(event, ui) {
				this.value = ui.item.value;
				document.location.href = 'http://<?=get_url(1,$config)?>&id='+ui.item.id;
				
			}
			
			});
		});
		<?
	}
	?>	
	</script>	
	<?
}
?>
<script language="JavaScript">

</script>
</head>
<body  >