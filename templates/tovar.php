<div class="stuff-box">
</div>

			<div class="stuff-thumbs-box">
				<div class="stuff-thumbs-box-control">
					<div class="stuff-thumbs-box-control-item active" data-box-id="1">
						Похожие
					</div>
					<div class="stuff-thumbs-box-control-item" data-box-id="2">
						Просматриваемые
					</div>
				</div>
				<div class="stuff-thumbs-box-show">
					<div class="stuff-thumbs-box-show-item active" data-box-id="1">
						<div class="catalog-box">
							<?
							$arr = get_products($tovar["parentid"], TRUE, 5, "AND goods.id != " . $tovar["id"]);

							foreach ($arr as $current) {
								$ind++;
								$img = getResizeImageById($current["imid"], "h", array("height" => 163), $current["imformat"]);
								?>

								<div class="catalog-box-item<?=($ind == 5 ? " last" : "")?>">
									<a href="<?=$current["tovar_url"]?>">
										<div class="catalog-box-item-img">
											<span class="vfix"></span><img src="<?=$img?>" alt="<?=$current["name"]?>"/>
										</div>
										<div class="catalog-box-item-title">
											<span class="vfix"></span><span><?=$current["name"]?></span>
										</div>
										<div class="catalog-box-item-dscr">
											<?=$tovar["description"]?>
										</div>
										<div class="catalog-box-item-price">
											<?=$current["show_price"]?>&nbsp;<?=$current["currency_symbol"]?>
										</div>
									</a>

								</div>
								<?php } ?>
							<div class="clear"></div>

						</div>
					</div>
					<div class="stuff-thumbs-box-show-item" data-box-id="2">
						<div class="catalog-box">

			<?php
									$arr = get_viewed_products(5, $tovar["id"]);
				$ind = 0;
				foreach ($arr as $current) {
					$ind++;
					$img = getResizeImageById($current["imid"], "h", array("height" => 163), $current["imformat"]);
					?>

					<div class="catalog-box-item<?=($ind == 5 ? " last" : "")?>">
						<a href="<?=$current["tovar_url"]?>">
							<div class="catalog-box-item-img">
								<span class="vfix"></span><img src="<?=$img?>" alt="<?=$current["name"]?>"/>
							</div>
							<div class="catalog-box-item-title">
								<span class="vfix"></span><span><?=$current["name"]?></span>
							</div>
							<div class="catalog-box-item-dscr">
								<?=$tovar["description"]?>
							</div>
							<div class="catalog-box-item-price">
								<?=$current["show_price"]?>&nbsp;<?=$current["currency_symbol"]?>
							</div>
						</a>

					</div>
					<?php } ?>
				<div class="clear"></div>

						</div>
					</div>
				</div>
			</div>
<script type="text/javascript">
    var sizes = <?php print json_encode($sizes) . ";"; ?>
    var colors = <? print json_encode($colors) . ";"; ?>

    function checkSize() {
        if ($(".size_select").val() == 0) {
            alert("Не указан размер");
        } else {
        chosen_color = $('.curent-color .curent').attr('data-color-index');
        chosen_size = $(".size_select").val();

        send_to_cart(<?=$tovar["id"]?>,<?=$tovar["id"]?>,chosen_color,chosen_size);
        }
    }
	function afterShowTovarView(res){
		$(".stuff-box").html(res);
	}
	function showTovarView(goodsid){
		$("#preloader-page").show();
		$.post(baseurl+"templates/ajax/tovar.php",{action:"showTovarView",goodsid:goodsid},afterShowTovarView);
	}
	$(".stuff-box").ready(function(){
		showTovarView('<?=$tovar["id"]?>');
	});
</script>		