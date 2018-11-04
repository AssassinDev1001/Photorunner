<?php
if(empty($_SESSION['admin']['id']) && !isset($_SESSION['admin']['id']))
{
	$msgs->add('e', 'Please login to access your account.');	
	$common->redirect(APP_URL.'index.php');
}
?>