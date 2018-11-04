<?php include('include/config.php');

$conditions = array('id'=>'2');
$press = $common->getrecord('pr_pages','*',$conditions) ;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head.php'); ?>
</head>
<body>
	<?php include('include/header.php'); ?>
<div class="banner-about">
	<div class="container">
		<div style="text-align:center; font-size:70px; color:#fff; font-weight:bold; margin-top:220px;"><?php echo $press->title; ?></div>
		<div style="text-align:center; font-size:16px; color:#fff;"><?php echo $press->headning; ?></div>
	</div>
</div>
<div class="facilities">
	<div class="container">
		<div class="col-md-12 no-pading">
			<div style="width:100%; margin-left:auto; margin-right:auto;">
				<div style="font-size:16px; margin-top:50px;"><?php echo html_entity_decode($press->description); ?></div>
				<div style="height:30px; clear:both">&nbsp;</div>
			</div>
		<div class="clearfix"></div>
	</div>
</div>
<div style="height:15px;">&nbsp;</div>
	<?php include('include/footer.php') ?>
	<?php include('include/foot.php') ?>
</body>
</html>
