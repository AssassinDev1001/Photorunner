<?php
include('../include/search_ajax_config.php');
if (isset($_GET['delid']) && !empty($_GET['delid']))
{
	$member = $_SESSION['account']['id'];
	mysql_query("delete from pr_favourite where id = '".$_REQUEST['delid']."' and member='".$member."'");
	$fmm_cnt = mysql_query("select * from pr_favourite where member = '".$member."'");
	$fmm_cnt1 = mysql_num_rows($fmm_cnt);
	echo $fmm_cnt1;
}
?>
