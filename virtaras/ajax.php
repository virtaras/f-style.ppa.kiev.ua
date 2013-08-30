<?
session_start();
ini_set("display_errors","On");
include $_SERVER["DOCUMENT_ROOT"]."//inc/protection.php";
include $_SERVER["DOCUMENT_ROOT"]."//inc/constant.php";
include $_SERVER["DOCUMENT_ROOT"]."//inc/connection.php";
include $_SERVER["DOCUMENT_ROOT"]."//inc/global.php";
include $_SERVER["DOCUMENT_ROOT"]."//inc/engine.php";
include $_SERVER["DOCUMENT_ROOT"]."//virtaras/functions.php";
if(isset($_POST["action"])){
	switch($_POST["action"]){
	
		case "addToBasket":
			$values=prepareArray($_POST);
			$id=$values["id"];
			$quantity=max((int)($values["quantity"]),1);
			$params=$values["params"];
			echo addToBasket($id,$quantity,$params);
			break;
			
		case "removeFromBasket":
			$values=prepareArray($_POST);
			$id=$values["id"];
			echo removeFromBasket($id);
			break;
			
		case "setSessionKeyValue":
			$post=prepareArray($_POST);
			$key=$post["key"];
			$value=$post["value"];
			$_SESSION[$key]=$value;
			echo $value;
			break;
			
		case "getIdByGoodsidFilters":
			$id=555;
			echo $id;
			break;
	}
}
?>