<div class="slider gidslider">
	<ul id="slider2">
		<?$images=getImages($head["id"],1);
		foreach($images as $image){?>
			<li>
				<div class="slide-block">
					<?if($image["href"]){?>
						<div class="button-style position-style1"><a class="button1 arrow-right" href="<?=$image["link"]?>"><?=$image["href"]?></a></div>
					<?}?>
					<img src="/images/files/<?=$image["image"]?>" alt=""/>
				</div>
			</li>
		<?}?>
	</ul>
</div>
<div class="mr_gid">
<div class="articles">
	<?$articles=getRowsFromDB("SELECT content.* FROM content WHERE ispublish=1 AND parentid='".$head["id"]."' ORDER BY showorder");
	$article_cnt=1;
	$in_row=3;
	foreach($articles as $article){
		$image=execute_row_assoc("SELECT images.* FROM images WHERE parentid='".$article["id"]."' AND source=1 AND is_main=1");
		if($article_cnt%$in_row==0) $last="last"; else $last="";?>
		<div class="article <?=$last?>" >
				<img src="/image/frame/images/300/480/<?=$image["id"]?>.jpg" />
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
</div>
<div class="clear"></div>