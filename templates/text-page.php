<div class="page-holder">	<p class="text_h1"><?=$head["name"]?></p>	<div class="mr_ctext">		<?=htmlspecialchars_decode($head["info"])?>	</div>	<div style="clear: both"></div>	<!-- Товары -->	<?if(trim($head["goods"])!=""){?>	<div class="goods-block mr" style="display:inline-block; width: 970px; border-top: 1px solid #eef1f2; padding-top: 25px">	<?	$certificates=getRowsFromDB("SELECT goods.* FROM goods WHERE id IN (".$head["goods"].") ORDER BY price");	foreach($certificates as $certificate){		$image=getTovarImage($certificate);		$url=get_product_url($certificate);?>		<div class="item" style="float:left;">			<a href="<?=$url?>">				<img src="/images/files/<?=$image["image"]?>" />			</a>		</div>	<?}?>	</div>	<?}?>	<!-- Товары --></div><div class="clear"></div>