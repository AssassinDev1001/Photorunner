<?php
$conditions = array('member'=>$_SESSION['account']['id']);
$check2 = $common->getrecord('pr_memberinfo','*',$conditions);
if(!empty($check2->member))
{
	$common->redirect(APP_URL."buyer/account.php");
}
?>

