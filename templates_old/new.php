
<div class="discount-promo-box">
			<div class="discount-promo-box-item">
				<a href="#">
					<div class="discount-promo-box-img">
						<img src="/templates/images/t_img31.png" alt=""/>
					</div>
					<div class="discount-promo-box-item-text">
						<span style="color:#393937; font-size:20px; font-family: 'officinasansbookcregular';"><b style="font-family: 'officinasansboldcregular';">Платья и</b> костюмы</span><br/>
						<span style="color:#686868; font-size:12px; font-family: 'officinasansbookcregular';">сезонные скидки до <span style="color:#d7523e;">50%</span></span>
					</div>
				</a>
			</div>
			<div class="discount-promo-box-item border">
				<a href="#">
					<div class="discount-promo-box-img">
						<img src="/templates/images/t_img41.png" alt=""/>
					</div>
					<div class="discount-promo-box-item-text">
						<span style="color:#393937; font-size:20px; font-family: 'officinasansbookcregular';"><b style="font-family: 'officinasansboldcregular';">Платья и</b> костюмы</span><br/>
						<span style="color:#686868; font-size:12px; font-family: 'officinasansbookcregular';">сезонные скидки до <span style="color:#d7523e;">50%</span></span>
					</div>
				</a>
			</div>
			<div class="discount-promo-box-item last border">
				<a href="#">
					<div class="discount-promo-box-img">
						<img src="/templates/images/t_img51.png" alt=""/>
					</div>
					<div class="discount-promo-box-item-text">
						<span style="color:#393937; font-size:20px; font-family: 'officinasansbookcregular';"><b style="font-family: 'officinasansboldcregular';">Платья и</b> костюмы</span><br/>
						<span style="color:#686868; font-size:12px; font-family: 'officinasansbookcregular';">сезонные скидки до <span style="color:#d7523e;">50%</span></span>
					</div>
				</a>
			</div>
			<div class="clear"></div>
		</div>
		<div class="wsbw">
			<div class="side-barr">
				<div class="side-barr-list">
					<div class="side-barr-list-title">
						Категории
					</div>
					<ul>
					<?php
					global $db;
					global $catalog_array;
					$arr = dbQuery("SELECT catalog.id,catalog.name FROM goods 
					INNER JOIN catalog ON catalog.id = goods.parentid
					WHERE goods.r1197 = 1 GROUP BY catalog.id,catalog.name ORDER BY catalog.name  ",$db);
					foreach($arr as $current) {
					?>
					<li <?=(isset($_GET["category"]) && $_GET["category"] == $current["id"] ? " class='active' " : "")?> ><a href="/novinki/?category=<?=$current["id"]?>"><?=$current["name"]?></a></li>
					<?
					}
					?>
					</ul>
				</div>
				<div class="side-barr-list">
					<div class="side-barr-list-title">
						Дизайнеры
					</div>
					<div class="scroll-pane">
						<ul>
							<?php
							$arr = dbQuery("SELECT brands.id,brands.name FROM goods 
					INNER JOIN brands ON brands.id = goods.brand
					WHERE goods.r1197 = 1 GROUP BY brands.id,brands.name ORDER BY brands.name  ",$db);
					foreach($arr as $current) {
					?>
					<li <?=(isset($_GET["brand"]) && $_GET["brand"] == $current["id"] ? " class='active' " : "")?>><a href="/novinki/?brand=<?=$current["id"]?>"><?=$current["name"]?></a></li>
					<?
					}
							?>
						</ul>
					</div>
				</div>
				<div class="main-sep"></div>
			</div>
			<div class="side-content">	
				<div class="main-sep"></div>
				<div class="catalog-box">
				
				<?php
				$arr = get_products(0,FALSE,1000000," AND goods.r1197 = 1".(isset($_GET["brand"]) ? " AND goods.brand = " . intval($_GET["brand"]) : "").(isset($_GET["category"]) ? " AND goods.parentid = " . intval($_GET["category"]) : "")," ORDER BY goods.price ASC");
				$i = 0;
				foreach($arr as $current) { $i++;
					$img =  getResizeImageById($current["imid"],"h",array("height"=>163),$current["imformat"]);
				?>
				
					<div class="catalog-box-item<?php if($i == 4) { ?> last<?php } ?>">
						<a href="<?=$current["tovar_url"]?>">
							<div class="catalog-box-item-img">
								<span class="vfix"></span><img src="<?=$img?>" alt=""/>
							</div>
							<div class="catalog-box-item-title">
								<span class="vfix"></span><span><?=$current["name"]?></span> 
							</div>
							<div class="catalog-box-item-dscr">
								<?=$current["description"]?>
							</div>
							<div class="catalog-box-item-price">
								<?=$current["show_price"]?>&nbsp;<?=$current["currency_symbol"]?>
							</div>
						</a>
						<div class="main-sep"></div>
					</div>
					<?php if($i == 4) { ?> 
					<div class="clear"></div>
					<?php $i = 0; }  ?>
				<?php } ?>	
				</div>
				<div class="clear"></div>
				<div class="page-dscr">
					<div class="ctext">
						<?=htmlspecialchars_decode($head["info"],ENT_QUOTES)?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			
		</div>