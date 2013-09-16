<?include _DIR."templates/account/menu.php";?>
<div class="mr_bar-right">
	<?$articles=getRowsFromDB("SELECT content.* FROM content WHERE ispublish=1 AND parentid=70 ORDER BY showorder");
	$article_cnt=1;
	$in_row=3;
	foreach($articles as $article){
		$image=execute_row_assoc("SELECT images.* FROM images WHERE parentid='".$article["id"]."' AND source=1 AND is_main=1");
		if($article_cnt%$in_row==0) $last="last"; else $last="";?>
		<div class="article <?=$last?>" >
				<img src="/image/frame/images/200/200/<?=$image["id"]?>.jpg" />
				<div class="text">
					<h2><?=$article["name"]?></h2>
					<div class="previev"><?=$article["preview"]?></div>
				</div>
			<a href="/<?=$head["urlname"]?>/<?=$article["urlname"]?>">
			</a>
		</div>
		<?++$article_cnt;
	}?>
</div>
<?include _DIR."templates/account/footer.php";?>