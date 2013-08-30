<?php
global $db;
if(isset($_GET["catalog"]))
{
		$c_array = array($_GET["catalog"]);
		get_child_id($_GET["catalog"],&$c_array);
		$brandsLetter = dbQuery("SELECT SUBSTR(brands.name,1,1) as l 
		FROM brands 
		INNER JOIN goods ON goods.brand = brands.id AND goods.parentid IN (".implode(",",$c_array).") 
		GROUP BY brands.name
		ORDER BY brands.name",$db);

}
else
{
	$brandsLetter = dbQuery("SELECT SUBSTR(name,1,1) as l FROM brands ORDER BY name",$db);
}
?>
<div class="wsbw">
			<div class="side-barr">
				<div class="side-barr-list">
					<div class="side-barr-list-title">
						Категории
					</div>
					<ul>
					<?php
					$arr = get_catalog_items_all();
					foreach($arr as $key=>$value)
					{
						$subarr = get_catalog_items_all($key);
						foreach($subarr as $k=>$v)
						{
							?>
							<li <? if($_GET["catalog"] == $k) { ?> class="active"  <?php } ?> ><a  href="/<?=$v["url"]?>/brands/"    ><?=$v["name"]?></a></li>
							<?
							$subarr2 = get_catalog_items_all($k);
							foreach($subarr2 as $id=>$item)
							{
								?>
								<li <? if($_GET["catalog"] == $id) { ?> class="active"  <?php } ?> >&nbsp;&nbsp;<a  href="/<?=$item["url"]?>/brands/"><?=$item["name"]?></a></li>
								<?   
							}
						}
					}
					?>
						
					</ul>
				</div>
				<div class="main-sep"></div>
			</div>
			
			<div class="side-content">	
				<div class="main-sep special"></div>
				<div class="letter-menu">
					<?php
					foreach($brandsLetter as $letter)
					{
						?>
						<a href="#<?=urlencode($letter["l"])?>"><?=$letter["l"]?></a>
						<?
					}
					?>
				</div>
				<?php
					foreach($brandsLetter as $letter)
					{
				?>
				<div class="letter-result-item">
					<div class="letter-result-item-title">
						<a name="<?=urlencode($letter["l"])?>"></a><?=$letter["l"]?>
					</div>
					<?php
					if(isset($_GET["catalog"]))
				{
						$brands = dbQuery("SELECT brands.id,brands.name,brands.urlname
						FROM brands 
						INNER JOIN goods ON goods.brand = brands.id AND goods.parentid IN (".implode(",",$c_array).") 
						WHERE SUBSTR(brands.name,1,1) = '".$letter["l"]."'
						GROUP BY brands.name
						ORDER BY brands.name",$db);
						

				}
				else
				{
					$brands = dbQuery("SELECT id,name,urlname FROM brands WHERE SUBSTR(name,1,1) = '".$letter["l"]."' ORDER BY name",$db);
					
				}	
					$b = array_chunk($brands,ceil(count($brands)/3),TRUE);
					foreach($b as $l) {
					?>
					<div class="letter-result-item-coll">
					<?php foreach($l as $current) {					?>
						<a href="/<?=$current["urlname"]?>-m<?=$current["id"]?>/"><?=$current["name"]?></a><br/> 
					<?php } ?>	
					</div>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="main-sep nm"></div>
				<?php } ?>
				
			</div>
			<div class="clear"></div>
			
		</div>
		<script type="text/javascript">
			$(".letter-menu").find("a").click(function() {    
				$(".letter-menu").find("a").removeClass("active");
				$(this).addClass("active");
			}  );
			
		</script>