<?php
$conditions = array('id'=>$_SESSION['account']['id']);
$members = $common->getrecord('pr_members','*',$conditions) ;
?>
<div class="col-md-3 no-pading" style="background-color:#fff; height:auto;margin:20px 0;">
	<?php 
	if(!empty($members->profilepicture))
	{
		?>
		<div style="margin:15px;">
			<img src="<?php echo APP_URL; ?>uploads/buyer/<?php echo $members->profilepicture; ?>" style="width:100%; min-height:200px; max-height:200px;"/>
		</div>
		<?php
	}else{
		?>
		<div style="margin:15px;">
			<img src="<?php echo APP_URL; ?>images/no-image.png" style="width:100%; min-height:200px; max-height:200px;"/>
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
	<a href="<?php echo APP_URL; ?>buyer/purchase-list.php"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">My Purchase List</div></a>
	<a href="<?php echo APP_URL; ?>buyer/favorite-list.php"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">My Favorite List</div></a>
	<a href="<?php echo APP_URL; ?>buyer/account-setting.php"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">Account Setting</div></a>
	<a href="<?php echo APP_URL; ?>buyer/change-password.php"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">Change Password</div></a>
	<a href="<?php echo APP_URL; ?>buyer/logout.php"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;">Sign Out</div></a>
</div>
