<?php
session_start();
require("inc/constant.php");
unset($_SESSION["login_user"]);
unset($_SESSION["current_user"]);
header("Location: "._SITE);
?>