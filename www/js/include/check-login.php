<?php
if(empty($_SESSION['account']['id']) && !isset($_SESSION['account']['id']))
{		
	$common->redirect(APP_URL.'index.php');
}
?>
