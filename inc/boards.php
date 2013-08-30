<?
if(isset($_GET["id"]))
{
	$board = execute_row_assoc("SELECT *,DATE_FORMAT(create_date,'%d.%m.%Y') as bdate FROM boards WHERE id = $_GET[id]");	
	$title = $board["title"];
}
function get_content()
{
	if(isset($_GET["id"]))
	{
		global $board;
		?>
		<h2><?=$board["title"]?></h2>
		<div><?=$board["bdate"]?></div>
		<p>
		<?=htmlspecialchars_decode($board["info"])?>
		</p>
		<?
	}
	else
	{
	
	}
}
?>