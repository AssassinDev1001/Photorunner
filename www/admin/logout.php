<?php 
include('include/config.php');
unset($_SESSION['admin']['id']);
$common->redirect(APP_URL);
?>
