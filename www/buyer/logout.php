<?php
include("../include/config.php");
	unset($_SESSION['account']);
	unset($_SESSION['cart']);
	$common->redirect(APP_URL."index.php");
?>
