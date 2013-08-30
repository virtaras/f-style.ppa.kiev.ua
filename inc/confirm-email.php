<?
$user=execute_row_assoc("SELECT clients.* FROM clients WHERE confirmstr='".$_GET["hash"]."'");
function get_content(){
	global $user;
	if($user["id"]){
		if($user["confirmed"]){
			echo "Пользователь уже подтвержден!";
		}else{
			if(mysql_query("UPDATE clients SET confirmed=1 WHERE id='".$user["id"]."'"))
				echo "E-mail - ".$user["email"]." успешно подтверждён!";
		}
	}else{
		echo "Пользователь не найден!";
	}
}
?>