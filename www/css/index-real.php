<?php include('include/config.php'); ?>
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
	<div class="banner-bottom">
		<div class="container">
			<h2 class="tittle">Register for Selling Photos</h2> 
			<div class="bottom-grids">
				<div class="col-md-3  bottom-grid">
					<div class="bottom-text" >
						<img src="uploads/<?php echo $home->image8; ?>">
					</div>
					<div class="bottom-spa"></div>
				</div>
				<div class="col-md-3 bottom-grid">
					<div class="bottom-text">
						<img src="uploads/<?php echo $home->image9; ?>">
					</div>
					<div class="bottom-spa"></div>
				</div>
				<div class="col-md-3 bottom-grid">
					<div class="bottom-text">
						<img src="uploads/<?php echo $home->image10; ?>">
					</div>
					<div class="bottom-spa"></div>
				</div>
				<div class="col-md-3 bottom-grid">
					<div class="bottom-text">
						<img src="uploads/<?php echo $home->image11; ?>">
					</div>
					<div class="bottom-spa"></div>
				</div>
			<div class="clearfix"></div>
			</div>
		</div>
	</div>

<?php /*<div class="banner background_index_1" style="background: url(../uploads/<?php echo $home->image2; ?>) no-repeat 0px 0px;background-size:cover;
-webkit-background-size: cover;
-o-background-size: cover;
-ms-background-size: cover;
-moz-background-size: cover;
min-height: 420px;
">
	<div class="container">
		<div  id="top" class="callbacks_container">
			<ul class="rslides" id="slider3">
				<li>
					<div class="banner-info">
						<div id="custom-search-input">
							<form  action="photos.php"  method="get" style="width:100%">
								<?php /*<div class="input-group col-md-12">
									<div class="hide_search_abc">
										<input type="text" class="  search-query form-control" placeholder="Find the perfect Photos,vector and more...." style="color:#333; height:50px;" required="required" name="searchinput"/>
									</div>
									<div class="height_for_search"></div>
									<span class="input-group-btn">
										<div class="hide_search_abc">
											<button class="btn btn-danger" type="submit" style="padding: 14px 22px;" name="search">
												<span class=" glyphicon glyphicon-search"></span>
											</button>
										</div>
									</span>
								</div>*/ ?>
								<div style="height:100px;"></div>
							</form>
						</div>
					</div>
					<div class="yourClass">
						<div class="col-md-12" style="text-align:center; padding:0px;">
							<a href="<?php echo APP_URL; ?>log-in.php"><img src="images/login.png" style="width:200px;" ></a>
							<a href="<?php echo APP_URL; ?>registration.php"><img src="images/reg.png" style="width:200px;"></a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="slide_bgg">
		<p><?php echo html_entity_decode($home->bannerheading); ?>
			<?php /*<a href="#">
				<span class="label label-default" style="float:right;background:#333;line-height:31px;">Read More</span>
			</a>*/ ?>
		</p>
	</div>
</div>
<!-- //banner -->
<!-- our facilities -->
<div class="facilities">
	<div class="container">
		<h3 class="tittle">"<?php echo html_entity_decode($home->facilitiesheading); ?>"</h3> 	
		<div class="col-md-8 no-pading">
			<div class="view view-seventh" >
				<a href="photos.php" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image3; ?>" alt="" style="min-height:240px;max-height:240px;-webkit-filter: brightness(60%); /* Chrome, Safari, Opera */
    filter: brightness(50%);" >
					<div class="mask">
						<h4>PHOTORUNNER</h4>
						<p><?php echo html_entity_decode($home->firstdescription); ?></p>
					</div>
				</a>
				<div class="text_on_image_first"><?php echo substr("$home->firstimagetitle",0,16);  ?></div>
				<div class="text_on_image_second"><?php echo substr("$home->firstimagesubtitle",0,30);  ?></div>
			</div>
			<div class="view view-seventh">
				<a href="photos.php" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image4; ?>" alt="" style="min-height:240px;max-height:240px;-webkit-filter: brightness(60%); /* Chrome, Safari, Opera */
    filter: brightness(50%);">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
						<p><?php echo html_entity_decode($home->seconddescription); ?></p>
					</div>
				</a>
				<div class="text_on_image_first"><?php echo substr("$home->secondimagetitle",0,16);  ?></div>
				<div class="text_on_image_second"><?php echo substr("$home->secondimagesubtitle",0,30);  ?></div>
			</div>
			<div class="view view-seventh">
				<a href="photos.php" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image5; ?>" alt="" style="min-height:240px;max-height:240px;-webkit-filter: brightness(60%); /* Chrome, Safari, Opera */
    filter: brightness(50%);">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
						<p><?php echo html_entity_decode($home->thirddescription); ?></p>
					</div>
				</a>
				<div class="text_on_image_first"><?php echo substr("$home->thirdimagetitle",0,16);  ?></div>
				<div class="text_on_image_second"><?php echo substr("$home->thirdimagesubtitle",0,30);  ?></div>
			</div>
			<div class="view view-seventh">
				<a href="photos.php" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image6; ?>" alt="" style="min-height:240px;max-height:240px;-webkit-filter: brightness(60%); /* Chrome, Safari, Opera */
    filter: brightness(50%);">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
						<p><?php echo html_entity_decode($home->fourthdescription); ?></p>
					</div>
				</a>
				<div class="text_on_image_first"><?php echo substr("$home->fourtimagetitle",0,16);  ?></div>
				<div class="text_on_image_second"><?php echo substr("$home->fourtimagesubtitle",0,30);  ?></div>
			</div>
		</div>
		<div class="col-md-4 no-pading">
			<div class="view view-seventh" style="width:100%">
				<a href="photos.php" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image7; ?>" alt=""style="min-height:520px;max-height:520px;-webkit-filter: brightness(60%); /* Chrome, Safari, Opera */
    filter: brightness(50%);">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
						<p><?php echo html_entity_decode($home->fifthdescription); ?></p>                        
					</div>
				</a>
				<div class="text_on_image_first_real"><?php echo substr("$home->fifthimagetitle",0,16);  ?></div>
				<div class="text_on_image_second_real"><?php echo substr("$home->fifthimagesubtitle",0,30);  ?></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //our facilities -->
<!-- banner-bottom -->

<div class="features">
	<div class="container">
		<div class="col-md-6 no-pading">
			<div class="view view-seventh">
				<a href="" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image12; ?>" alt="" style="min-height:150px;max-height:150px;">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
					</div>
				</a>
			</div>
			<div class="view view-seventh">
				<a href="" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image13; ?>" alt="" style="min-height:150px;max-height:150px;" >
					<div class="mask">
						<h4>PHOTORUNNER</h4>
					</div>
				</a>
			</div>
			<div class="view view-seventh">
				<a href="" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image14; ?>" alt="" style="min-height:150px;max-height:150px;">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
					</div>
				</a>
			</div>
			<div class="view view-seventh">
				<a href="" class="b-link-stripe b-animate-go  swipebox"  title="Image Title"><img src="uploads/<?php echo $home->image15; ?>" alt="" style="min-height:150px;max-height:150px;">
					<div class="mask">
						<h4>PHOTORUNNER</h4>
					</div>
				</a>
			</div>
		</div>
		<div class="col-md-6 features-right ">
			<?php echo html_entity_decode($home->companydescription); ?>
			<form  action="signature-artist.php"  method="post" style="width:100%">
				<button type="submit" class="log_bg" style="width:80%; border:0px;"><h4 style="color:#fff"><center>Meet Photorunner Signature Artist</center></h4></button>
			</form>
		</div>
	<div class="clearfix"></div>
	</div>
</div>
	<?php include('include/footer.php') ?>
	<?php include('include/foot.php') ?>
</body>
</html>

