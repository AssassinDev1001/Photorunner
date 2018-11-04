<?php include('include/config.php'); ?>
<?php

if(isset($_GET['galleryy']))
{
	$id = $_GET['gallery'];	
	//$conditions = array('id'=>$id, 'password'=>$_GET['password']);
	//$check = $common->getrecord('pr_galleries','*',$conditions);

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 16;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = " WHERE id = '".$id."' and password = '".$_GET['password']."'";						
	$statement = "pr_galleries" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$check = $common->getpagirecords('pr_galleries','*',$conditions);


	$id2 = base64_encode($id);
	if(!empty($check))	
	{
		$_SESSION['gallery']['id'] = $check->id;
		$common->redirect(APP_URL."photos.php?gallery=$id2&&lock=unlock");
	}
	else
	{
		$common->add('e', 'Password not matched.');	
		$common->redirect(APP_URL."photographers.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link rel="stylesheet" type="text/css" href="css/shade.css" />
	<link rel="stylesheet" type="text/css" href="css/shade/component.css" />
	<script src="css/shade/modernizr.custom.js"></script>
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/style.css" />
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#F3F3F3">
	<?php include('include/header.php'); ?>
<!-- banner-bottom -->
<div class="aaa111"></div>
<div class="banner-bottom">
	<div class="container">
		<div style="width:98%; margin:auto;">
		<?php
			if(!empty($_SESSION['flash_messages']))
			{	
				echo $msgs->display();
			}	
		?>
		</div>
		<div style="font-weight:bold; margin-bottom:30px;">Photgrapher List</div>
		<?php
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$limit = 10;
		$startpoint = ($page * $limit) - $limit;					
		$conditions = " WHERE status = '1' ";						
		$statement = "pr_seller" . $conditions;						
		$conditions .= " LIMIT {$startpoint} , {$limit}";	
		$seller = $common->getpagirecords('pr_seller','*',$conditions);

		if(!empty($seller))
		{
			$k = 1;
			foreach($seller as $seller)
			{
			?>
			<div class="">
				<div class="col-md-3">
					<div style="padding:8px;">
						<a class="fancybox" href="#inlin<?php echo $seller->id; ?>"><?php if(!empty($seller->profilepicture)) { ?><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->profilepicture; ?>" style="width:100px; height:100px;"><?php }else{ ?><img src="images/no-profile.png" style="width:100px; height:100px;"><?php } ?></a>
					</div>
				</div>
				<div class="col-md-6">
					<div style="padding:0px;">
						<div style="padding:0px;">
							<div><b>Name</b> : <?php echo $seller->firstname; ?></div>
							<div><b>Email</b> : <?php echo $seller->email; ?></div>
						</div>
						<div style="clear:both; height:5px;"></div>
						<div><b>Price in USD&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</b><?php if(!empty($seller->pricetext)) { echo $seller->pricetext; }else{ ?>Empty<?php } ?> $ <?php if(!empty($seller->price)) { echo number_format($seller->price,2); }else{ ?>0.00<?php } ?> USD </div>


						<div style="clear:both; height:5px;"></div>
						<div><b>Price in EURO&nbsp;:&nbsp;</b><?php if(!empty($seller->pricetext)) { echo $seller->pricetext; }else{ ?>Empty<?php } ?> &#8364 <?php if(!empty($seller->priceeuro)) { echo number_format($seller->priceeuro,2); }else{ ?>0.00<?php } ?> EURO </div>

						<div style="clear:both; height:5px;"></div>
						<a class="fancybox" href="#inlin<?php echo $seller->id; ?>"><button type="submit" class="log_bg" style="width:150px; border:0px;"><h4 style="color:#fff"><center>View Details</center></h4></button></a>
						
						
						
						
						<div id="inlin<?php echo $seller->id; ?>" style="display: none;">
							<div class="col-md-3">
								<div style="padding:8px;">
									<?php if(!empty($seller->profilepicture)) { ?><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->profilepicture; ?>" style="width:100px; height:100px;"><?php }else{ ?><img src="images/no-profile.png" style="width:100px; height:100px;"><?php } ?>
								</div>
								<div style="padding:8px;">
									<div><b>Name</b> : <?php echo $seller->firstname; ?></div>
									<div><b>Email</b> : <?php echo $seller->email; ?></div>
								</div>
							</div>
							<div class="col-md-6">
								<div style="padding:8px;">
									<div><b>About Us</b></div>
									<div><?php if(!empty($seller->about)) { echo $seller->about; }else{ ?>Empty<?php } ?></div>
									<div style="clear:both; height:5px;"></div>
									<div><b>Geographical Area</b></div>
									<div><?php if(!empty($seller->area)) { echo $seller->area; }else{ ?>Empty<?php } ?></div>
									<div style="clear:both; height:5px;"></div>
									<div><b>Price in USD&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</b><?php if(!empty($seller->pricetext)) { echo $seller->pricetext; }else{ ?>Empty<?php } ?> $ <?php if(!empty($seller->price)) { echo number_format($seller->price,2); }else{ ?>0.00<?php } ?> USD </div>


									<div style="clear:both; height:5px;"></div>
									<div><b>Price in EURO&nbsp;:&nbsp;</b><?php if(!empty($seller->pricetext)) { echo $seller->pricetext; }else{ ?>Empty<?php } ?> &#8364 <?php if(!empty($seller->priceeuro)) { echo number_format($seller->priceeuro,2); }else{ ?>0.00<?php } ?> EURO </div>
								</div>
							</div>
							<div class="col-md-3">
								<div><b>Collection</b></div>
								<div style="float:left">
									<?php if(!empty($seller->banner1)) { ?>
									<a class="fancybox" href="#banner1<?php echo $seller->id; ?>"><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner1; ?>" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"></a>
									<div id="banner1<?php echo $seller->id; ?>" style="display: none;">
										<img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner1; ?>" style="width:100%">
									</div>
									<?php }else{ ?>
									<img src="images/38752hd.jpg" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"><?php } ?>
									<?php if(!empty($seller->banner2)) { ?>
									<a class="fancybox" href="#banner2<?php echo $seller->id; ?>"><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner2; ?>" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"></a>
									<div id="banner2<?php echo $seller->id; ?>" style="display: none;">
										<img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner2; ?>" style="width:100%">
									</div>
									<?php }else{ ?>
									<img src="images/38752hd.jpg" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"><?php } ?>
								</div>
							</div>
							<div style="height:20px; clear:both"></div>
							<div class="col-md-12">
								<div><b>Galleries</b></div>
								<?php					
								$conditions = " WHERE seller = '".$seller->id."' limit 4 ";							
								$galleries = $common->getpagirecords('pr_galleries','*',$conditions);
								if(!empty($galleries))
								{
									foreach($galleries as $gallery)
									{
									?>
									<div style="padding: 10px; width:250px; float:left">
										<div class="">
											<div class="demo-3">
												<ul class="grid cs-style-3" style="padding: 0px 0px 0px;">
													<li style="padding: 0px; width:100%;">
														<?php if($_SESSION['gallery']['id'] == $gallery->id) { ?>
														<figure>
															<div class="tj_wrapper">
																<ul class="tj_gallery" style="margin-bottom: -5px;">
																	<li style="list-style:none; width:100%;"><a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>&&lock=unlock"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" class="image_width_photographers" alt="img06"></a></li>
																</ul>
															</div>
															<figcaption>
																	<a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>&&lock=unlock">View</a>
															</figcaption>
														</figure>
														<?php }else{ ?>
														<figure>
															<div class="tj_wrapper">
																<ul class="tj_gallery" style="margin-bottom: -5px;">
																<?php if(empty($gallery->password)) { ?>
																	<li style="list-style:none; width:100%;"><a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" class="image_width_photographers" alt="img06"></a></li>
																<?php }else{ ?>
																	<li style="list-style:none; width:100%;"><a class="fancybox" href="#inline<?php echo $gallery->id; ?>"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" class="image_width_photographers" alt="img06"></a></li>
																		<div id="inline<?php echo $gallery->id; ?>" style="width:98%; display: none; margin:auto; padding-top:15px;">
																			<form action="photographers.php" method="get">
																				<div style="font-weight:bold; padding-bottom:5px;">To unlock gallery Enter secure password</div>
																				<div style="margin-bottom:5px;"><input type="password" placeholder="Password" name="password" id="password" required="required" style="width:100%; border-radius:0px; border:1px solid #00A2B5; height:40px; padding-left:10px;"></div>

																				<input type="hidden" name="gallery" value="<?php echo $gallery->id; ?>" >
																				<button type="submit" style="" name="galleryy" class="btn btn-primary" >Submit</button>
																			</form>
																		</div>
																<?php } ?>
																</ul>
															</div>
															<figcaption>
																<?php if(empty($gallery->password)) { ?>
																	<a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>">View</a>
																<?php }else{ ?>
																	<a class="fancybox" href="#inline<?php echo $gallery->id; ?>">View</a>
																<?php } ?>
															</figcaption>
														</figure>
														<?php } ?>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<?php
									}
								}
								else
								{
									?>
										<img src="images/38752hd.jpg" style="min-width:250px; max-width:250px; min-height:140px; max-height:140px; margin:1%; border:4px solid #ccc;">
									<?php
								}
								?>
							</div>
							<div style="height:20px; clear:both"></div>
							<div style="height:1px; background-color:#00A2B5;"></div>
							<div style="height:20px; clear:both"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div><b>Collection</b></div>
					<div style="float:left">
						<?php if(!empty($seller->banner1)) { ?>
						<a class="fancybox" href="#banner1<?php echo $seller->id; ?>"><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner1; ?>" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"></a>
						<div id="banner1<?php echo $seller->id; ?>" style="display: none;">
							<img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner1; ?>" style="width:100%">
						</div>
						<?php }else{ ?>
						<img src="images/38752hd.jpg" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"><?php } ?>
						<?php if(!empty($seller->banner2)) { ?>
						<a class="fancybox" href="#banner2<?php echo $seller->id; ?>"><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner2; ?>" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"></a>
						<div id="banner2<?php echo $seller->id; ?>" style="display: none;">
							<img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner2; ?>" style="width:100%">
						</div>
						<?php }else{ ?>
						<img src="images/38752hd.jpg" style="min-width:46%; max-width:46%; min-height:120px; max-height:120px; margin:1%;"><?php } ?>
					</div>
				</div>
			</div>
			<div style="height:20px; clear:both"></div>
			<div style="height:1px; background-color:#00A2B5;"></div>
			<div style="height:20px; clear:both"></div>
			<?php
			}
		}
		?>
		<div style="float:right">
			<?php echo $common->pagination($statement,$limit,$page); ?>
		</div>
	</div>
</div>
<div style="height:35px; background-color:#F3F3F3;"></div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>
<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>

