<?
//$pages=array("account_info"=>"Персональная информация","account_history"=>"История заказов","izbrannoe"=>"Избранное","predlozhenia"=>"Предложения от магазина");
$pages=array("account_info"=>"Персональная информация","account_history"=>"История заказов","izbrannoe"=>"Избранное");
?>
<div class="mr_topline"><p>ЛИЧНЫЙ КАБИНЕТ <span>пользователя</span> <?=$_SESSION["login_user"]["r129"]?></p></div>
<div class="mr_bar-left">
		<h2>ЗАКЛАДКИ</h2>
		<?foreach($pages as $page=>$name){?>
			<a href="<?=_SITE?>account/<?=$page?>.html"><?=$name?></a>
		<?}?>
</div>