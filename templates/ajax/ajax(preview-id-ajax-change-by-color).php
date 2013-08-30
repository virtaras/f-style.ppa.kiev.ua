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
		case "showGoodsPreview":
			$goodsid=$_POST["goodsid"];
			$good=execute_row_assoc("SELECT * FROM goods WHERE id='$goodsid'");
			$maingoodsid=($good["goodsid"]>0) ? $good["goodsid"] : $good["id"];
			$maingood=execute_row_assoc("SELECT * FROM goods WHERE id='$maingoodsid'");
			$img = getResizeImageById($good["imid"],"h",array("height"=>"158"),$good["imformat"]);
			$g_colors=get_goods_filters($good,'r1193');
			$g_sizes=get_goods_filters($good,'r1194');
			$allGoods=getRowsFromDB("SELECT goods.* FROM goods WHERE goodsid='$maingoodsid'");
			?>
			<div class="imagehandler">
				<div class="nextprev-box">
					<a class="prev" href="#">Пред. товар</a>
					<a class="next" href="#">След. товар</a>
				</div>
				<div class="visual-preview-box">
					<?$count=1;
					//if(!count($g_colors)) //if there is no good with color
					foreach($g_colors as $key=>$color){
						//if($count==1) $current="curent"; else $current="";
						if($color["id"]==$good["r1193"] || $good["id"]==$maingoodsid) $current="curent"; else $current="";
						//$goodsOfColor=getRowsFromDB("SELECT goods.* FROM goods WHERE goodsid='$maingoodsid' AND r1193='".$color["id"]."'");
						$imagesOfColor=getRowsFromDB("SELECT images.* FROM images WHERE source=3 AND parentid IN (SELECT id FROM goods WHERE goodsid=$maingoodsid AND r1193='".$color["id"]."') LIMIT 0,5");
						if(count($imagesOfColor)==0){
							$imagesOfColor=getRowsFromDB("SELECT images.* FROM images WHERE source=3 AND parentid='$maingoodsid' LIMIT 0,5");
						}
						?>
						<div class="visual-preview-box-item <?=$current?>" data-color-index="<?=$color["id"]?>">
							<div class="visual-preview-box-thumbs">
								<div class="vfix"></div>
								<div class="thumbs-vfix">
									<?$goodind=1;
									foreach($imagesOfColor as $goodimage){?>
										<!--<a data-slider-index="<?=$goodind?>" href="#"><img src="/images/files/<?=$goodimage["image"]?>" alt=""/></a>-->
										<a data-slider-index="<?=$goodind?>" href="#"><img src="/image/frame/images/60/60/<?=$goodimage["id"]?>.jpg" alt=""/></a>
										<?++$goodind;
									}?>
								</div>
							</div>
							<div class="visual-preview-box-slider">
								<ul>
									<?$goodind=1;
									foreach($imagesOfColor as $goodimage){?>
										<li><span class="vfix"></span><img src="/images/files/<?=$goodimage["image"]?>" alt="<?=$goodimage["link"]?>" style="height:525px;" /></li>
										<?++$goodind;
									}?>
								</ul>
							</div>
						</div>
						<?++$count;
					}?>
				</div>
				<div class="stuff-dscr">
					<div class="stuff-dscr-title">
						<?=$good["name"]?>
						<div class="stuff-dscr-article">
							арт <?=$good["code"]?>
						</div>
					</div>
					<div class="stuff-dscr-menu jNice">
						<div class="stuff-dscr-color-box">
							<div class="curent-color">
								<?$ind=1;
								foreach($g_colors as $key=>$color){
									if($color["id"]==$good["r1193"]) $current="curent"; else $current="";?>
									<div class="curent-color-item <?=$current?>" data-color-index="<?=$color["id"]?>" >
										<div class="curent-color-visual" style='background:<?=$color["code"]?>;'>
										</div>
										<div class="curent-color-text">
											<?=$color["code"]?>
										</div>
									</div>
									<?++$ind;
								}?>
							</div>
							<div class="stuff-color-menu">
								<?foreach($g_colors as $key=>$color){
									$current=getTovarByFieldsFromArr($allGoods,array("r1193"=>$color["id"]));?>
									<a style="background:<?=$color["code"]?>" data-color-index="<?=$color["id"]?>" href="javascript:void(0);" onclick="showGoodsPreview('<?=$current["id"]?>')"></a>
								<?}?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="stuff-dscr-menu-select">
							<div class="filter-listed1 size-master-box size-box-style">
								<?foreach($g_sizes as $key=>$size){?>
									<a href="#"><?=$size["name"]?></a>
								<?}?>
								<!--<a class="noitem" href="#">XS</a><a href="#">S</a><a href="#">M</a><a class="active" href="#">L</a><a class="noitem" href="#">XL</a>-->
							</div>
						</div>
						<div class="stuff-dscr-menu-button" style="position: relative">
	
							<a class="style_table_toggle" onClick="$('#dropper').toggle();">Таблица размеров </a>
							<div  id="dropper" style="position:absolute; border: 4px solid #FFF; border-radius: 10px; z-index:111111; top: 35px; left: -550px; display: none">
	
	<div>
		<img style="border-radius: 5px" src="/templates/images/table-size-img.jpg" />
	</div>
	
	<div style="position:absolute; top: -15px; right:-15px"><a onClick="$('#dropper').toggle();"><img style="border-radius: 5px" src="/templates/images/close_size.png" /></a></div>
</div>
						</div>
						
						<div class="clear"></div>
					</div>
					<div class="stuff-buy-box">
						<a class="button big" href="javascript:void(0);" onclick="javascript:send_to_basket('<?=$goodsid?>');">Купить</a>
						<span class="price"><?=get_price($good["price_action"] > 0 ? $good["price_action"] : $good["price"],$base_currency,$good["id"],$good["currency"],true)?> <span><?=$currency_array[_DISPLAY_CURRENCY]["shortname"]?></span></span>
					</div>
					<div class="stuff-dscr-text">
						<div class="ctext">
							<h3>Описание</h3>
							<p><?=$good["description"]?></p>
							<h3>Детали</h3>
							<?=$good["full_description"]?>
						</div>
					</div>
					<div class="stuff-dscr-socials jNice">
						<a class="button white star margin-style1" href="javascript:addIzbrannoe('<?=$goodsid?>')">В избранное</a><a class="button white question margin-style1" href="#">Задать вопрос</a>
						<img src="<?=$verstkaurl?>images/socials.png" alt=""/>
					</div>
				</div>
			</div>
			<script>
				var l = 0;
				$('.imagehandler img').bind('load', function(){
				  l++;
				  if (l == $('.imagehandler img').length) {
					 handleLoad();
				  }
				});			
			</script>
			<div class="clear"></div>
			<?
			break;
			
		case "showIzbrannoe":
			$izbrannoe=isset($_COOKIE["izbrannoe"]) ? unserialize(stripslashes($_COOKIE["izbrannoe"])) : array();
			print_r($izbrannoe);
			break;

		case "addIzbrannoe":
			$izbrannoe=isset($_COOKIE["izbrannoe"]) ? unserialize(stripslashes($_COOKIE["izbrannoe"])) : array();
			if(array_search($_POST["goodsid"],$izbrannoe)===false) $izbrannoe[]=$_POST["goodsid"];
			$expire = time() + 60*60*24*30;
			setcookie("izbrannoe", serialize($izbrannoe), $expire, '/');
			break;
			
		case "getGoodsView":
			$goodsid=(int)($_POST["goodsid"]);
			$tovarid=(int)($_POST["tovarid"]);
			$filters=array();
			$filters_sql="";
			$filters_post=$_POST["filters"];
			foreach($filters_post as $item){
				$filter=explode("=",$item);
				if((int)($filter[1])>0) $filters_sql.=" AND ".prepare($filter[0])."='".prepare($filter[1])."'";
				$filters[$filter[0]]=$filter[1];
			}
			$sql_text="SELECT goods.* FROM goods WHERE goodsid='".$goodsid."'".$filters_sql;
			//echo $sql_text;
			$r=mysql_fetch_assoc(mysql_query($sql_text));
			if(isset($filters["r1193"]) && $filters["r1193"]>0) $r1193_where=" AND r1193='".$filters["r1193"]."'"; else $r1193_where="";
			$g_sizes_active=get_goods_filters($r,'r1194',$r1193_where);
			?>		
			<div id="<?=$r["id"]?>-preloader" style="display:none;position:absolute;background:white;opacity:0.6;background-size:100%;width:200px;height:380px;"></div>
			<?
			//$img = getResizeImageById($r["imid"],"h",array("height"=>"232"),$r["imformat"]);
			$image = getTovarImage($r);
			$img="/images/files/".$image["image"];
			$g_colors=get_goods_filters($r,'r1193');
			$g_sizes=get_goods_filters($r,'r1194');
			$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
			$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
			$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
			$tovar_url = get_product_url($r);
			?>
				<a href="<?=$tovar_url?>">
							<div class="catalog-box-item-img">
								<span class="vfix"></span><img src="<?=$img?>" alt="<?=$r["name"]?>" style="vertical-align:top;height:232px;"/>
							</div>
							<div class="catalog-box-item-title">
								<span class="vfix"></span><span><?=$r["name"]?></span> 
							</div>
							<!--<div class="catalog-box-item-dscr">
							<?=$tovar["description"]?>
							</div>-->
							<div class="catalog-box-item-dscr">
								<?=$price?> <?=$currency_symbol?>
							</div>
						</a>
						<div class="hide-menu">
							<div class="filter-listed1 colorType">
								<input type="hidden"/>
								<?foreach($g_colors as $key=>$color){?>
									<a style="background:<?=$color["code"]?>" data-filter-id="<?=$color["id"]?>" href="javascript:void(0);" onclick="javascript:updateGoodsFields('<?=$goodsid?>','<?=$tovarid?>','r1193','<?=$color["id"]?>');"></a>
								<?}?>
							</div>
							<div class="filter-listed1 size-master-box">
								<input type="hidden"/>
								<?foreach($g_sizes as $key=>$size){
									if(!inArray($size,$g_sizes_active)){
										$class="noitem";
										$href="javascript:void('0');";
									}else{
										if($size["id"]==$r["r1194"]) $class="active"; else $class="";
										$href="javascript:updateGoodsFields('".$goodsid."','".$tovarid."','r1194','".$size["id"]."');";
									}?>
									<a class="<?=$class?>" data-filter-id="<?=$size["id"]?>" href="#" onclick="<?=$href?>"><?=$size["name"]?></a>
								<?}?>
								<!--<a  class="noitem" href="#">XS</a><a data-filter-id="1" href="#">S</a><a data-filter-id="1" href="#">M</a><a data-filter-id="1" class="active" href="#">L</a><a class="noitem" href="#">XL</a>-->
							</div>
							<div class="hide-menu-item">
								<a href="javascript:send_to_basket('<?=$r["id"]?>');"><span class="cart"></span>В корзину</a>
								<a href="#stuff-box" class="fancybox1" onclick="javascript:showGoodsPreview('<?=$r["id"]?>');">Быстрый просмотр</a>
								<a href="javascript:toIzbrannoe('<?=$r["id"]?>');">В избранное</a>
							</div>
						</div>
			<input type="hidden" id="<?=$goodsid?>goodsid" value="<?=$goodsid?>" />
			<input type="hidden" id="<?=$r["id"]?>tovarid" value="<?=$r["id"]?>" />
			<input type="hidden" id="<?=$goodsid?>r1193" value="<?=$filters['r1193']?>" />
			<input type="hidden" id="<?=$goodsid?>r1194" value="<?=$filters['r1194']?>" />
			<?
			break;
	}
}