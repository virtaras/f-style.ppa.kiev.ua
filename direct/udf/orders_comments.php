<?php
function send_mail($id)
{
	$userid = execute_scalar("SELECT userid FROM orders WHERE id = " . $_POST["orderid"]);
	
	$email = execute_scalar("SELECT email FROM clients WHERE id = '$userid'");
		
		
		
		include_once($_SERVER['DOCUMENT_ROOT']."/inc/emarket.php");
		
		send_mime_mail($_SERVER['HTTP_HOST'], "no_reply@".str_replace("www.","",$_SERVER['HTTP_HOST']), "",$email, "UTF-8", "UTF-8", "Ответ по Вашему комментарию к заказу №" . $_POST["orderid"], "<p>
			" . htmlspecialchars($_POST["comment"]) . "
			</p><p>
			<a href='http://technofishka.com.ua/account_history.html#".$_POST["orderid"]."' target='_blank' >Перейти к заказу</a>
			</p>" );
		
	
}
function after_insert($id)
{
	send_mail($id);
}



function after_update($id)
{
	send_mail($id);
}


?>