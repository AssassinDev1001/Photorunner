<?php
if(empty($_SESSION['seller']['id']) && !isset($_SESSION['seller']['id']))
{		
	$common->redirect(APP_URL.'index.php');
}
?>
