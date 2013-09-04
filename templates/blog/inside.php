<?$parent=execute_row_assoc("SELECT content.* FROM content WHERE id ='".$head["parentid"]."'");
global $currency_array;?>
<div class="autor-box">
	<a href="#" class="gonext">Следующая запись</a>
</div>
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
<div style="height:30px;"></div>
<div class="wrap-floats">
	<div class="float-left">
		<div class="ctext">
			<h2><?=$head["name"]?></h2>
			<p><?=htmlspecialchars_decode($head["info"])?></p>
		</div>
		<div class="clear-line"></div>
		<!------------------------------Товары------------------------------------>
		<?$goods=$head["goods"];
		if($goods!=""){
		$base_currency=_DISPLAY_CURRENCY;
		$sql_text = "SELECT goods.*,IF(catalog.one_name != '',catalog.one_name,catalog.name) as cname,brands.name as brandname,brands.urlname as brandurl,goods.id as gid,goods.currency as gcurrency,
				IF(goods.price_action > 0,goods.price_action,goods.price) * (SELECT course FROM currency_course WHERE from_currency = gcurrency AND to_currency = $base_currency) as vprice,
				(SELECT id FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imid,
				(SELECT format FROM images WHERE source = 3 AND parentid = goods.id AND is_main = 1  LIMIT 0,1) as imformat
			FROM goods
			INNER JOIN catalog ON catalog.id = goods.parentid
			LEFT JOIN brands ON brands.id = goods.brand
			WHERE goods.id IN (".$goods.")";
		$result_sql=mysql_query($sql_text);
		?>
		<div class="last-wards-box jcarousel-mini-style">
			<h2 class="subheading">Последние поступления</h2>
			<ul id="mycarousel" class="jcarousel-skin-tango">
				<?while($r = mysql_fetch_assoc($result_sql)){
					$ind++;
					$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
					$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
					$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
					$tovar_url = get_product_url($r);?>
					<li>
						<div class="last-wards-box-item box-item-style">
							<a href="<?=$tovar_url?>">
								<div class="last-wards-box-item-img">
									<img src="/image/frame/images/122/163/<?=$r["imid"]?>.jpg" alt=""/>
								</div>
								<div class="last-wards-box-item-title">
									<?=$r["name"]?>
								</div>
								<div class="last-wards-box-item-dscr">
									<?=$r["description"]?>
								</div>
								<div class="last-wards-box-item-type">
									<b><?=$price?> <?=$currency_symbol?></b>
								</div>
							</a>
						</div>
					</li>
				<?}?>
			</ul>
		</div>
		<?}?>
		<!------------------------------Товары------------------------------------>
		<div class="opt-block">
			<div class="socials">
				Рассказать друзьям:<a href="#"><img src="/templates/images/icon-fb.png" /></a><a href="#"><img src="/templates/images/icon-tw.png" /></a><a href="#"><img src="/templates/images/icon-gp.png" /></a>
			</div>
			<div class="to-up-page">
				<a href="#" id="toTop">Вернуться вверх<img src="/templates/images/arrow-up.png" /></a>
			</div>
		</div>
	</div>
	<?$article_connected_sql="SELECT content.*,images.id as imid, images.image FROM content JOIN images ON images.parentid=content.id WHERE content.id ='".$head["content_id"]."' AND images.source=1";
	$article_connected=execute_row_assoc($article_connected_sql);
	if(isset($article_connected["id"]) && $article_connected["id"]>0){?>
	<div class="float-right">
		<div class="bg-padd">
			<div class="prev-info">
				<img src="/image/frame/images/265/265/<?=$article_connected["imid"]?>.jpg" />
				<div class="button-style"><a class="button1" href="/<?=$parent["urlname"]?>/<?=$article_connected["urlname"]?>">Узнать больше</a></div>
			</div>
		</div>
	</div>
	<?}?>
	<div class="clear"></div>
</div>