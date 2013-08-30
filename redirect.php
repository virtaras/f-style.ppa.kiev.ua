<?php
session_start();
if(!empty($_GET["url"]))
{
    
	header("Location: ".$_GET["url"]);
    exit();
}
header("Location: ".$_SESSION["url"]);
?>
