<?
session_start();
$verstkaurl="http://fstyle-test.ppa.kiev.ua/tmptmp/";
require($_SERVER["DOCUMENT_ROOT"]."//inc/constant.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/connection.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/global.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/engine.php");
require($_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php");

if(isset($_POST["action"])){
	switch($_POST["action"]){
		//////////////////////////showGoodsPreview///////////////////////////////
		case "showTovarView":
			$goodsid=$_POST["goodsid"];
			$good=execute_row_assoc("SELECT * FROM goods WHERE id='$goodsid'");
			$maingoodsid=($good["goodsid"]>0) ? $good["goodsid"] : $good["id"];
			$maingood=execute_row_assoc("SELECT * FROM goods WHERE id='$maingoodsid'");
			$img = getResizeImageById($good["imid"],"h",array("height"=>"158"),$good["imformat"]);
			$g_colors=get_goods_filters($good,'r1193');
			$g_sizes=get_goods_filters($good,'r1194');
			$allGoods=getRowsFromDB("SELECT goods.* FROM goods WHERE goodsid='$maingoodsid'");
			if($good["id"]==$maingood["id"] && count($g_colors)) $good=execute_row_assoc("SELECT * FROM goods WHERE goodsid='$maingoodsid'");
			/////////////////////////////////////////////////////////////////////////////////////?>
			
				<div class="visual-preview-box">
			<?php
			$count=1;
			if(count($g_colors)){
			foreach($g_colors as $key=>$color){
				if($color["id"]==$good["r1193"]) $current="curent"; else $current="";
				$imagesOfColor=getRowsFromDB("SELECT images.* FROM images WHERE source=3 AND parentid IN (SELECT id FROM goods WHERE goodsid=$maingoodsid AND r1193='".$color["id"]."') LIMIT 0,5");
				if(count($imagesOfColor)==0){
					$imagesOfColor=getRowsFromDB("SELECT images.* FROM images WHERE source=3 AND parentid='$maingoodsid' LIMIT 0,5");
				}?>
				<div class="visual-preview-box-item <?=$current?>" data-color-index="<?=$color["id"]?>">
					<div class="visual-preview-box-slider" style="float:right;">
						<ul>
							<?$goodind=1;
							foreach($imagesOfColor as $goodimage){?>
								<li><span class="vfix"></span><img src="/images/files/<?=$goodimage["image"]?>" alt="<?=$goodimage["link"]?>" style="height:525px;" /></li>
							<?}?>
						</ul>
					</div>
					<div class="visual-preview-box-thumbs">
						<?$goodind=1;
						foreach($imagesOfColor as $goodimage){?>
							<a data-slider-index="<?=$goodind?>" href="javascript:void(0);"><img src="/image/frame/images/60/60/<?=$goodimage["id"]?>.jpg" alt=""/></a>
						<?}?>
					</div>
				</div>
			<?}
			}else{?>
				<?$current="curent";
				$imagesOfColor=getRowsFromDB("SELECT images.* FROM images WHERE source=3 AND parentid=$maingoodsid LIMIT 0,5");
				?>
				<div class="visual-preview-box-item <?=$current?>" data-color-index="<?=$color["id"]?>">
					<div class="visual-preview-box-slider" style="float:right;">
						<ul>
							<?$goodind=1;
							foreach($imagesOfColor as $goodimage){?>
								<li style="min-width:417px;"><span class="vfix"></span>
								<span class="visual-preview-box-slider-item ">
								<div id="wrap" style="top:0px;z-index:9999;position:relative;">
								<a href="/images/files/<?=$goodimage["image"]?>" class="cloud-zoom" style="position: relative; display: block;">
								<img src="/images/files/<?=$goodimage["image"]?>" alt="<?=$goodimage["image"]?>" style="height:525px;"></a>
								<div class="mousetrap" style=" z-index: 999; position: absolute; width: 415px; height: 193px; left: 0px; top: 0px; cursor: auto;">
								</div>
								</div>
								</span>
								
								
								<!--<img src="/images/files/<?=$goodimage["image"]?>" alt="<?=$goodimage["link"]?>" style="height:525px;" />--></li>
							<?}?>
						</ul>
					</div>
					<div class="visual-preview-box-thumbs">
						<?$goodind=1;
						foreach($imagesOfColor as $goodimage){?>
							<a data-slider-index="<?=$goodind?>" href="javascript:void(0);"><img src="/image/frame/images/60/60/<?=$goodimage["id"]?>.jpg" alt=""/></a>
						<?}?>
					</div>
				</div>
			<?}?>
				</div>
				<div class="stuff-dscr">
					<div class="stuff-dscr-title">
						<?=$good["name"]?>
						<div class="stuff-dscr-article">
							арт <?=$good["code"]?>
						</div>
					</div>
	<div class="stuff-dscr-menu jNice">
		<div class="stuff-dscr-color-box" id="color_select">
			<div class="curent-color">
			<?$ind=1;
			foreach($g_colors as $key=>$color){
				if($color["id"]==$good["r1193"]) $current="curent"; else $current="";?>
					<div class="curent-color-item <?=$current?>" data-color-index="<?=$color["id"]?>">
						<div class="curent-color-visual" style='background:<?=$color["code"]?>;'>
						</div>
						<div class="curent-color-text">
							<?=$color["code"]?>
						</div>
					</div>
				<?php ++$ind;
			} ?>
			</div>
			<div class="stuff-color-menu">
				<?foreach($g_colors as $key=>$color){
					if($color["id"]==$good["r1193"]) $class="curent"; else $class="";
					$current=getTovarByFieldsFromArr($allGoods,array("r1193"=>$color["id"]));?>
					<a title="<?=$color["code"]?>" class="<?=$class?>" data-color-index="<?=$color["id"]?>" style="background:<?=$color["code"]?>" href="javascript:showTovarView('<?=$current["id"]?>');"></a>
				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="stuff-dscr-menu-select" id="size_select_div">
			<div class="filter-listed1 size-master-box tovar" style="text-align: left">
				<?foreach($g_sizes as $key=>$size){
					$curent=getTovarByFieldsFromArr($allGoods,array("id"=>$good["id"],"r1193"=>$good["r1193"],"r1194"=>$size["id"]));
					$exists=getTovarByFieldsFromArr($allGoods,array("r1193"=>$good["r1193"],"r1194"=>$size["id"]));
					if($curent){
						$onclick="javascript:void(0);";
						$class="active";
					}else{
						if($exists){
							$onclick="javascript:showTovarView('".$exists["id"]."');";
							$class="";
						}else{
							$onclick="javascript:void(0);";
							$class="noitem";
						}
					}?>
					<a href="#" class="<?=$class?>" onclick="<?=$onclick?>"><?=$size["name"]?></a>
				<?}?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
					<div class="stuff-buy-box">
						<a class="button big" href="javascript:void(0);" onclick="javascript:send_to_basket('<?=$good["id"]?>');">Купить</a>
						<span class="price"><?=get_price($good["price_action"] > 0 ? $good["price_action"] : $good["price"],$base_currency,$good["id"],$good["currency"],true)?>
							&nbsp;<span><?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></span></span>
						<div class="stuff-dscr-menu-button" style="position:relative">
							<a class="style_table_toggle" onClick="$('#dropper').toggle();">Таблица размеров </a>
				<div  id="dropper" style="position:absolute; border: 4px solid #FFF; border-radius: 10px; z-index:111111; top: 35px; left: -550px; display: none">
					<div> <img style="border-radius: 5px" src="/templates/images/table-size-img.jpg" /> </div>
					<div style="position:absolute; top: -15px; right:-15px; cursor: pointer"><a onClick="$('#dropper').toggle();"><img style="border-radius: 5px" src="/templates/images/close_size.png" /></a></div>
				</div>
							</div>
						</div>
					</div>
					<div class="stuff-dscr-text">
						<div class="ctext">
							<h3>Описание</h3>
							<?=$good["description"]?>
							<h3>Детали</h3>
							<?=$good["full_description"]?>
						</div>
					</div>
					<div class="stuff-dscr-socials jNice">
						<a class="button white star" href="javascript:addIzbrannoe('<?=$tovar["id"]?>')">В избранное</a>
						<!--<a class="button white question" href="#">Задать вопрос</a>-->
						<div class="" style="height:15px;"></div>
						<img src="/templates/images/socials.png" alt=""/>
					</div>
				</div>
				<div class="clear"></div>
				<script>
					var l = 0;
					$('.stuff-box img').bind('load', function(){
					  l++;
					  if (l == $('.stuff-box img').length) {
						 $.when(handleLoad()).then($("#preloader-page").hide());
					  }
					});			
				</script>
			<?/////////////////////////////////////////////////////////////////////////////
			break;
	}
}