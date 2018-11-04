<?php
include('include/search_ajax_config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) 
{
	$member = $_SESSION['account']['id'];
	$date = @date('Y-m-d H:i:s');

	$result = mysql_query("SELECT * FROM pr_favourite WHERE photo = '".$_GET['id']."' and member='".$member."'");
	if(mysql_num_rows($result) > 0)
	{
		mysql_query("Delete from pr_favourite where photo = '".$_GET['id']."' and member='".$member."'");
		$goal = mysql_query("select * from pr_favourite where member = '".$member."'");
		$goal_count = mysql_num_rows($goal);

		echo  $ajax1 = '<i class="chnge fa fa-heart-o merced" style="font-size:20px; color:#ed4e6e; padding:5px;"></i></p>'.'@=@'.$goal_count;
	} 
	else
	{
		mysql_query("insert into pr_favourite set photo='".$_GET['id']."',member='".$member."',date='".$date."'");
		$goal1 = mysql_query("select * from pr_favourite where member = '".$member."'");
		$goal_count1 = mysql_num_rows($goal1);
		echo  $ajax1 =  '<i class="chnge fa fa-heart merced" style="font-size:20px; color:#ed4e6e; padding:5px;
"></i></p>'.'@=@'.$goal_count1;
	}
}
?>

