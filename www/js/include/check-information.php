<?php

$conditions = array('member'=>$_SESSION['account']['id']);
$check1 = $common->getrecord('pr_memberinfo','*',$conditions);
if(empty($check1->member))
{
	$common->redirect(APP_URL."buyer/personal-information.php");
}
?>

