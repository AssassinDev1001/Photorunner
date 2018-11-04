<?php include('include/config.php'); ?>
<?php					
	$conditions = " WHERE status = '1' ";							
	$artist = $common->getrecordssss('pr_artist','*',$conditions);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head.php'); ?>
	<style>
	.background_index{

	}
	</style>
</head>
<body>
	<?php include('include/header.php'); ?>
<div class="banner background_index_1" style="background: url(/images/81835805_3200x1200.jpg) no-repeat 0px 0px;background-size:cover;
-webkit-background-size: cover;
-o-background-size: cover;
-ms-background-size: cover;
-moz-background-size: cover;
min-height: 420px;
">
	<div style="font-size:60px; color:#fff; font-weight:bold; text-align:center;padding-top:220px;">SIGNATURE ARTIST</div>
</div>

<div class="banner-bottom">
	<div class="container">
	<?php
		if(!empty($artist))
		{
			foreach($artist as $artist)
			{
			?>
				<div style="color: #ffd047;font-size: 60px;font-weight: bold;line-height: 60px;margin-bottom: 40px;"><?php echo html_entity_decode($artist->heading); ?></div>
				<div style="color: #333333;font-size: 16px;line-height: 20px; width:80%;"><?php echo html_entity_decode($artist->subheadning); ?></div>
				<div><img src="../uploads/artist/<?php echo $artist->banner; ?>" style="width:100%; height:auto; margin-top:50px;" /></div>
				<div style="color:#000; text-align:right; font-size:9px;">PHOTORUNNER SIGNATURE ARTISTS</div>
				<div style="margin-top:50px;"><?php echo html_entity_decode($artist->story); ?></div>
				<div style="clear:both; height:50px;"></div>
			<?php
			}
		}
	?>
	</div>
	<div style="clear:both"></div>
	<?php /*<div style="float:right">
		<?php echo $common->pagination($statement,$limit,$page); ?>
	</div>*/ ?>
</div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>

