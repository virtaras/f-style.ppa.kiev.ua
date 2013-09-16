<style>

li.bjqs-slide:first-child {display:block!important};
</style>


<a href="javascript:$('#izbrannoe-goods').toggle();" style="float: left;display:block;width:21px;height:239px; background: url('/templates/images/chosen.png'); color: #ffffff; padding: 12px 0 0 15px; text-decoration: none"><?$izbrannoe_arr=isset($_COOKIE["izbrannoe"]) ? unserialize(stripslashes($_COOKIE["izbrannoe"])) : array();
if(count($izbrannoe_arr)){
	echo count($izbrannoe_arr);?></a>
	<div style="display: inline-block; border-top: 2px solid #2f2f2f; border-bottom: 2px solid #2f2f2f; height: 247px; background: #ffffff">
	<div id="izbrannoe-goods" style="display:none;width:270px; text-align: center">	
		
		<div id="banner-fade">
        <ul class="bjqs">		
			
			<?
			$izbrannoes_sql="SELECT goods.* FROM goods WHERE id IN (".implode(",",$izbrannoe_arr).")";
			$izbrannoes=mysql_query($izbrannoes_sql);
			while($r=mysql_fetch_assoc($izbrannoes)){
			$price = get_price($r["price"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
			$price_action = get_price($r["price_action"],_DISPLAY_CURRENCY,$r["id"],$r["currency"]);
			$currency_symbol = $currency_array[_DISPLAY_CURRENCY]["shortname"];
			$tovar_url = get_product_url($r);
			$image = getTovarImage($r);
			$img = getResizeImageById($image["id"],"w",array("width"=>"155"),$image["format"]);?>
			<li>
			<div class="item">
				<!--<a href="javascript:removeIzbrannoe('<?=$r["id"]?>');">удалить</a>-->
				<a style="display:inline-block; height: 205px; position: relative; text-align: center; color: #1e1e1e; font-size: 13px; font-family: 'officinasansboldcregular'" href="<?=$tovar_url?>">
					<img style="height: 195px; margin-top: 10px" src="<?=$img?>" alt="<?=$r["name"]?>" />
					<div style="position: absolute; bottom: 0px; background: rgba(255,255,255,0.7); width: 100%">
					<div class="name"><?=$r["name"]?></div>
					<div class="price"><?=$price?> <?=$currency_symbol?></div>
					</div>
				</a>
				<input type="button" value="все избранные товары" />
			</div>
					</li>
		<?}?>	

		
		</ul>
		</div>
<script>
var cnt = 0;
$('#izbrannoe-goods img').bind('load', function(){
	cnt++;
	if (cnt == $('#izbrannoe-goods img').length) {
		handleLoadIzbrannoe();
	}
});	
function handleLoadIzbrannoe(){
    $('#banner-fade').bjqs({
        height : 248,
        width : 270,
        showmarkers : false,
		automatic : false,
		animtype : 'slide',

    });
}
</script>
		
		
		
	</div>
	</div>
<?}?>