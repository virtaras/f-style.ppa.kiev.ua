<?php
global $db;
$arr = dbQuery("SELECT goods.id,goods.price,
r1193.id as color_id,r1193.name as color_name,r1193.code as color_code,r1193.showorder as color_order,
r1194.id as size_id,r1194.name as size_name,r1194.showorder as size_order
FROM goods 
LEFT JOIN r1193 ON r1193.id = goods.r1193
LEFT JOIN r1194 ON r1194.id = goods.r1194
WHERE goods.goodsid = " . $tovar["id"] . " ORDER BY r1193.name", $db);

$sizes = array();
$colors = array();

foreach ($arr as $current)
{
    $sizes[$current["size_id"]]["NAME"] = $current["size_name"];
    $sizes[$current["size_id"]]["ID"] = $current["size_id"];
    $sizes[$current["size_id"]]["COLORS"][] = array("ID" => $current["color_id"], "NAME" => $current["color_name"], "CODE" => $current["color_code"], "ORDER" => $current["color_order"]);


    $colors[$current["color_id"]]["NAME"] = $current["color_name"];
    $colors[$current["color_id"]]["ID"] = $current["color_id"];
    $colors[$current["color_id"]]["CODE"] = $current["color_code"];
    $colors[$current["color_id"]]["SIZES"][] = array("ID" => $current["size_id"], "NAME" => $current["size_name"], "ORDER" => $current["size_order"]);


}
?>
<div class="stuff-box">
    <div class="visual-preview-box">
<?php
                    $ind = 0;
    if (count($colors) > 0) {
        foreach ($colors as $k => $v) {
            $ind++;
            $cur_id = execute_scalar("SELECT id FROM goods WHERE goodsid = '" . $tovar['id'] . "' AND r1193 = $k");
            $images = getImages($cur_id, 3);
            ?>
            <div class="visual-preview-box-item <?=$ind == 1 ? "curent" : ""?>" data-color-index="<?=$k?>">
                <div class="visual-preview-box-slider" style="float:right;">
                    <ul>
                        <?
                        $ind = 0;
                        foreach ($images as $image) {
                            $img = getResizeImageById($image["id"], "h", array("height" => 367), $image["format"], $image);?>
                            <li><span class="vfix"></span><img src="<?=$img?>" alt="" style="height:525px;" /></li>
                            <? }?>
                    </ul>
                </div>
                <div class="visual-preview-box-thumbs">
                    <?
                    $ind = 0;
                    foreach ($images as $image) {
                        $img = getResizeImageById($image["id"], "w", array("width" => 72), $image["format"], $image);
                        $ind++;
                        ?>
                        <a data-slider-index="<?=$ind?>" href="#"><img src="<?=$img?>" alt=""/></a>
                        <? }?>
                </div>
            </div>
            <?
        }
    } else {
        $images = getImages($tovar['id'], 3);
        ?>
        <div class="visual-preview-box-item curent">
            <div class="visual-preview-box-slider" style="float:right;">
                <ul>
                    <?
                    foreach ($images as $image) {
                        $img = getResizeImageById($image["id"], "w", array("width" => 205), $image["format"], $image);?>
                        <li><span class="vfix"></span><img src="<?=$img?>" alt="" style="height:525px;"/></li>
                        <? }?>
                </ul>
            </div>
            <div class="visual-preview-box-thumbs">
                <?
                $ind = 0;
                foreach ($images as $image) {
                    $img = getResizeImageById($image["id"], "w", array("width" => 72), $image["format"], $image);
                    $ind++;
                    ?>
                    <a data-slider-index="<?=$ind?>" href="#" class="cloud-zoom"><img src="<?=$img?>" alt=""/></a>
                    <? }?>
            </div>
        </div>
        <? }?>
    </div>
    <div class="stuff-dscr">
        <div class="stuff-dscr-title">
            <?=$tovar["name"]?>
            <div class="stuff-dscr-article">
                арт <?=$tovar["code"]?>
            </div>
        </div>
        <div class="stuff-dscr-menu jNice">
            <div class="stuff-dscr-color-box" id="color_select">
                <div class="curent-color">
<?php
    $ind = 0;
    foreach ($colors as $k => $v) {
        $ind++;
        ?>
        <div class="curent-color-item<?php if ($ind == 1) { ?> curent<?php } ?>" data-color-index="<?=$k?>">
            <div class="curent-color-visual" style='background:<?=$v['CODE']?>;'>
            </div>
            <div class="curent-color-text">
                <?=$v["NAME"]?>
            </div>
        </div>
        <?php } ?>
                </div>
                <div class="stuff-color-menu">
<?php
                            $ind = 0;
    foreach ($colors as $k => $v) {
        $ind++;
        ?>
        <a title="<?=$v["NAME"]?>" <?=($ind == 1 ? "class='curent'" : "")?> data-color-index="<?=$k?>"
           style="background:<?=$v['CODE']?>" id="c<?=$k?>" href="#"></a>
        <?php } ?>


                </div>
                <div class="clear"></div>
            </div>
            <div class="stuff-dscr-menu-select" id="size_select_div">
			
			<!-- 
                <select id="size_select" class="size_select" onchange="set_colors(this)">
                    <option value="0">
                        Выбрать размер
                    </option>
<?php
                            $ind = 0;
                    foreach ($colors as $k => $v) {
                        $ind++;
                        if ($ind == 1) {
                            foreach ($v["SIZES"] as $key => $value)
                            {
                                ?>
                                <option value="<?=$value["ID"]?>"><?=$value["NAME"]?></option>
                                <?
                            }
                            break;
                        }
                    }
                    ?>
                </select> -->
				
				
				<div class="filter-listed1 size-master-box tovar" style="text-align: left">
								<input type="hidden"/>
								
									<a href="#" >S</a>
									<a href="#" >M</a>
									<a href="#" class="noitem">L</a>
									<a href="#" class="active">XL</a>
									<a href="#">XXL</a>
				
								
								
				</div>
            </div>
			
            
            <div class="clear"></div>
        </div>
        <div class="stuff-buy-box">
            <a class="button big" href="javascript:void(0);"
               onclick="checkSize();">Купить</a>
            <span class="price"><?=($price_action > 0 ? $price_action : $price)?>
                &nbsp;<span><?=$currency_symbol?></span></span>
				<div class="stuff-dscr-menu-button">
                <a class="button white bulb btn-tsize" href="#">Таблица размеров </a>
            </div>
        </div>
        <div class="stuff-dscr-text">
            <div class="ctext">
                <h3>Описание</h3>
                <?=htmlspecialchars_decode($tovar["full_description"])?>
                <h3>Детали</h3>
                <?=htmlspecialchars_decode($tovar["add_description"])?>
            </div>
        </div>
        <div class="stuff-dscr-socials jNice">
            <a class="button white star" href="javascript:addIzbrannoe('<?=$tovar["id"]?>')">В избранное</a><a class="button white question" href="#">Задать
            вопрос</a>
            <img src="/templates/images/socials.png" alt=""/>
        </div>
    </div>
    <div class="clear"></div>
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
</script>		