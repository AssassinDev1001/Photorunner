<?php /*<div style="margin:15px;">
	<img src="<?php echo APP_URL; ?>images/banner10.jpg" style="width:100%; min-height:200px; max-height:200px;"/>
</div>
<a href="<?php echo APP_URL; ?>seller/add-gallery.php"><div class="line1">Add Gallery</div></a>
<a href="<?php echo APP_URL; ?>seller/add-photo.php"><div  class="line1">Add Photo</div></a>
<a href="<?php echo APP_URL; ?>seller/change-password.php"><div  class="line1">Change Password</div></a>
<a href="<?php echo APP_URL; ?>seller/logout.php"><div class="line2">Sign Out</div></a>*/ 

$conditions = array('id'=>$_SESSION['seller']['id']);
$seller = $common->getrecord('pr_seller','*',$conditions) ;

if(isset($_POST['profile_picture_submit']))
{
	if(!empty($_FILES['profilepicture']['name']))
	{
		if($common->updateprofilepictureseller($_FILES))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}	
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
?>
<?php 
if(!empty($seller->profilepicture))
{
	?>
	<div style="margin:15px;">
		<img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->profilepicture; ?>" style="width:100%; min-height:200px; max-height:200px;"/>
	</div>
	<?php
}else{
	?>
	<div style="margin:15px;">
		<img src="<?php echo APP_URL; ?>images/no-image.png" style="width:100%; min-height:200px; max-height:200px; border:1px solid #ccc"/>
	</div>
	<?php
}
?>
<a class="fancybox" href="#inline"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">Upload Profile Picture</div></a>
<div id="inline" style="width:250px; display: none; margin:auto; padding-top:15px;">
	<form action="" method="post" enctype='multipart/form-data' style="height: 200px;">	
		<h4>Upload Profile Picture</h4>
		<input type="file" name="profilepicture" id="profilepicture" required="required">
		<button type="submit" style="margin-top:10px;" name="profile_picture_submit" class="btn btn-primary" >Upload Profile Picture</button>
	</form>
</div>
<a href="<?php echo APP_URL; ?>seller/galleries.php"><div class="line1">Galleries</div></a>
<a href="<?php echo APP_URL; ?>seller/add-gallery.php"><div class="line1">Add Gallery</div></a>
<a href="<?php echo APP_URL; ?>seller/photos.php"><div class="line1">Photos</div></a>
<a href="<?php echo APP_URL; ?>seller/add-photo.php"><div  class="line1">Add Photo</div></a>
<a href="<?php echo APP_URL; ?>seller/sold-photos.php"><div  class="line1">Sold Photos</div></a>
<a href="<?php echo APP_URL; ?>seller/change-password.php"><div  class="line1">Change Password</div></a>
<a href="<?php echo APP_URL; ?>seller/logout.php"><div class="line2">Sign Out</div></a>
<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
