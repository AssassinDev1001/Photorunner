<?php include('include/config.php'); 

$conditions = array();
$gallery = $common->getrecords('pr_gallery','*',$conditions) ;

?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link rel="stylesheet" type="text/css" href="css/shade.css" />
	<link rel="stylesheet" type="text/css" href="css/shade/component.css" />
	<script src="css/shade/modernizr.custom.js"></script>
	<style>
		.img-height:{}
	</style>
</head>
<body>
<?php include('include/header.php'); ?>
<div class="banner-bottom"  style="background-color:#F3F3F3">
	<div class="banner-info" style="margin-top:75px;">
		<div id="custom-search-input">
			<div class="input-group col-md-12" style="padding:0px;">
				<input type="text" class="search-query form-control " placeholder="Find the perfect Photos,vector and more...." style="color:#333; border-radius:0px; height:60px;"/>
				<span class="input-group-btn">
					<button class="btn btn-danger" type="button" style="padding: 19px 22px !important; border-radius:0px;">
						<span class=" glyphicon glyphicon-search"></span>
					</button>
				</span>
			</div>
		</div>
	</div>
	<div class="container">
		<div style="height:20px;"></div>
		<div class="col-md-3" style="padding: 0px 10px 0px 10px; margin-bottom:8px;">
			<div class="bottom-grids">
				<?php
				if(!empty($gallery))
				{
					foreach($gallery as $gallery)
					{
					?>
					<div class="demo-3">
						<ul class="grid cs-style-3" style="padding: 0px 0px 0px;">
							<li style="padding: 0px; border-bottom:35px solid #fff; border-top:15px solid #fff; border-left:5px solid #fff; border-right:5px solid #fff;">
								<figure>
									<div class="tj_wrapper">
										<ul class="tj_gallery" style="margin-bottom: -5px;">
											<li style="list-style:none;"><a href="#"><img src="uploads/gallery/<?php echo $gallery->image; ?>" style="width:100%; min-height:200px; max-height:200px;" alt="img06"></a></li>
										</ul>
									</div>
									<figcaption>
										<span><?php echo $gallery->name; ?></span>
										<a href="">Click</a>
									</figcaption>
								</figure>
							</li>
						</ul>
					</div>
					<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>
