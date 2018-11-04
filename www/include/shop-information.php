<?php

$conditions = array('id'=>$_SESSION['shop']['id']);
$check4 = $common->getrecord('bz_users','*',$conditions);

if($check4->shop != '0')
{
	$common->redirect(APP_URL."shop-owner/shop-account.php");
	
}

?>
