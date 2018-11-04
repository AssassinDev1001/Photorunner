<?php
if(!empty($_SESSION['admin']['id']) && isset($_SESSION['admin']['id']))
{
	$common->redirect(APP_URL.'control-panel.php');
}
?>