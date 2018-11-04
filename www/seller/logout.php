<?php
include("../include/config.php");
	unset($_SESSION['seller']);
	$common->redirect(APP_URL."index.php");
?>
