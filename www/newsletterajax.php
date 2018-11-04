<?php
include('include/newsletter.php');
if (isset($_GET['sub_email1']) && !empty($_GET['sub_email1']))
{
	$sub_remote = mysql_query("select * from pr_newsletter where email = '".$_GET['sub_email1']."'");
	$sub_count = mysql_num_rows($sub_remote);
	if($sub_count == 0)
	{
		$sub_date = @date('Y-m-d');
		mysql_query("insert into pr_newsletter set email='".$_GET['sub_email1']."',date='".$sub_date."'");
		echo '1';
	}
	else
	{
		echo '2';
	}	
}
?>

