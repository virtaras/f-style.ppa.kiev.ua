<?
include _DIR."templates/account/menu.php";
$orders_cnt=execute_scalar("SELECT COUNT(*) FROM orders WHERE userid = '".$_SESSION["login_user"]["id"]."'");
$izbrannoe_arr=isset($_COOKIE["izbrannoe"]) ? unserialize(stripslashes($_COOKIE["izbrannoe"])) : array();
$izbrannoe_cnt=count($izbrannoe_arr);
?>
<div class="mr_bar-right">
	<div class="blocky">
		<img src="/templates/images/lk1.jpg">
		<div class="abs"><p>Персональная информация</p></div>
		<a href="<?=_SITE?>account/account_info.html"></a>
	</div>
	<div class="blocky">
		<img src="/templates/images/lk1.jpg">
		<div class="abs"><p>История заказов</p></div>
		<div class="abs_corn"><?=$orders_cnt?></div>
		<a href="#"></a>
	</div>
	<div class="blocky">
		<img src="/templates/images/lk1.jpg">
		<div class="abs"><p>Избранное</p></div>
		<div class="abs_corn"><?=$izbrannoe_cnt?></div>
		<a href="<?=_SITE?>account/izbrannoe.html"></a>
	</div>
</div>
<div style="clear: both"></div>
<?
include _DIR."templates/account/footer.php";
?>