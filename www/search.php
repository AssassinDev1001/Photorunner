<?php
include('searchdb.php');
if($_POST)
{
$q=$_POST['search'];
$sql_res=mysql_query("select id,name from pr_photos where name like '%$q%' order by id LIMIT 5");
while($row=mysql_fetch_array($sql_res))
{
$username=$row['name'];

?>
<div style="font-size:16px; padding:20px 5px 5px 5px; background-color:#fff; border-bottom:1px solid #00A5B2;">
	<div class="show" align="left" >
	<span class="name" ><?php echo $username; ?></span>
	</div>
</div>
<?php
}
}
?>
