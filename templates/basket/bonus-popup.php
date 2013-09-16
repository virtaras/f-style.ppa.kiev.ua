<?session_start();
require($_SERVER["DOCUMENT_ROOT"]."//inc/constant.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/connection.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/global.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/emarket.php");
require($_SERVER["DOCUMENT_ROOT"]."//inc/engine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php");
if(checkLogged()){
$user=execute_row_assoc("SELECT clients.* FROM clients WHERE id='".$_SESSION["login_user"]["id"]."'");
$bonus=(isset($user["id"]) && $user["id"]>0) ? $user["bonus"] : 0;?>
<div class="w-ajax">
	<div class="ajax-info2">
		<div class="close-btn style1"></div>
		<h2 style="font-family: 'officinasansboldcregular'; font-size: 18px ; text-align: center">Использовать бонусы</h2>
		<div><p style="text-align: left"><span>Всего <span class="sp_bold">доступно</span> бонусных баллов:</span><span class="sp_bold_pr"><?=$bonus?></span></p></div>
		<div><span><p><span class="sp_bold">Использовать</span> бонусных баллов:</span><input id="use_bonus" type="text" name="use_bonus" value="" /><span>баллов</span></p></div>
		<div style="text-align: center"> 
		<a href="javascript:confirmBonus($('#use_bonus').val());">Применить</a>
		</div>
	</div>
</div>
<?}else{?>
<div class="w-ajax">
	<div class="ajax-info2">
		Авторизируйтесь, чтобы использовать бонус!
	</div>
</div>
<?}?>