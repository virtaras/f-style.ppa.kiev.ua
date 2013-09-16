<? $page=execute_row_assoc("SELECT content.* FROM content WHERE id=65");?>
<div class="seo_text">
	<?=htmlspecialchars_decode($page["info"])?>
</div>