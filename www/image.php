<?php include('include/config.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link href="css/dist/zoomify.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php include('include/header.php'); ?>
<!-- our facilities -->
<div class="facilities">
	<div style="height:120px;"></div>
	<div class="container">
		<div class="col-md-2 no-pading">
			<h3 class="tittle" style="margin-bottom: 12px; margin-top: 5px;"><img src="images/11.png" style="width:100px; height:80px; border-radius:50%;"/></h3> 	
		</div>
		<div class="col-md-2 no-pading">
			<h4 style="font-weight:bold; margin-top:25px;">Photographer</h4>
			<h5 style="font-weight:bold; margin-top:5px; color:#00A2B5;">Ptere son</h5>
		</div>
		<div class="col-md-3 no-pading">
			<div style="margin-top:25px;"><img src="images/Entertainment.png" style="width:30px;" /><img src="images/Entertainment.png" style="width:30px;" /><img src="images/Entertainment.png" style="width:30px;" /><img src="images/Entertainment.png" style="width:30px;" /></div>
			<h5 style="font-weight:bold; margin-top:5px; color:#00A2B5; padding-left:10px;">View Reviews</h5>
		</div>
		<div class="col-md-5 no-pading">
			<h4 style="font-weight:bold; margin-top:25px; color:#00A2B5; text-align:right; padding-right:20px;">View all Photo</h4>
			<h5 style="font-weight:bold; margin-top:5px; color:#00A2B5; text-align:right; padding-right:100px;">1000</h5>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix" style="height:40px;"></div>
<div class="container">
	<div class="">
		<div class="col-md-6 no-pading">
			<div class="row">
				<img src="https://unsplash.it/1200/800?image=882"id="img" alt="Curious Bino" class="img-thumbnail"> </div>
				<div style="float:left; padding:5px; font-weight:bold;">Photo Id : 987654321</div>
				<div style="float:right; padding:5px; font-weight:bold;">Uploade Date : 18 March, 2016</div>
			</div>
			
		</div>
		<div class="col-md-6 features-right" style="padding-top: 35px;">
			<div style="margin:20px;">
				<h4>Graduation Convocation photo sant joesph Collega London 2016</h4>
			</div>
			<div style="margin:20px;">
				<h4 style="padding-left:10px;padding-bottom:5px;">Web File</h4>
				<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $2 USD</h6>
				<h4 class="log_bg" style="color:#fff; width:36%; font-weight:0px;"><center>Buy Now</center></h4>
			</div>
			<div style="margin:20px;">
				<h4 style="padding-left:10px;padding-bottom:5px;">Print File</h4>
				<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $2 USD</h6>
				<h4 class="log_bg" style="color:#fff; width:36%; font-weight:0px;"><center>Buy Now</center></h4>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix" style="height:40px;"></div>
	<?php include('include/footer.php') ?>
	<?php include('include/foot.php') ?>
	<script src="css/dist/zoomify.js"></script>
	<script>
		$('#img').zoomify();
	</script>
</body>
</html>
