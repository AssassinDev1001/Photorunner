<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['update']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->adminchangepassword($_POST))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head.php') ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php include('include/header.php') ?>
<?php include('include/left.php') ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Change Password</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Change Password</li>
			</ol>
		</section>
		<section class="content">
			<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}
			?>
			<div class="row">
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Required Information</h3>
								</div>
								<form action="" method="post" id="change-password-form">
									<div class="box-body">
										<div class="form-group">
											<label for="exampleInputEmail1" style="color:black;">Current Password:</label>
											<input type="password" class="form-control" value="" name="oldpassword" id="oldpassword" placeholder="Enter Old Password" style="width:70%;" required="required">
										</div>
										<div class="form-group">
											<label for="exampleInputPassword1" style="color:black;">New Password:</label>
											<input type="password" class="form-control" value="" name="password" id="password" placeholder="Enter New Password" style="width:70%;" required="required">
										</div>
										<div class="form-group">
											<label for="exampleInputPassword1" style="color:black;">Confirm Password:</label>
											<input type="password" class="form-control" value="" name="cpassword" id="cpassword" placeholder="Enter New Password" style="width:70%;" required="required">
										</div>
									</div>	
									<div class="box-footer">
										<input type="hidden" name="id" value="<?php echo $_SESSION['admin']['id']; ?>"/>
										<button type="submit" class="btn btn-primary" name="update">Change Password</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	<?php include('include/footer.php') ?>
<script src="js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function(){
		$("#change-password-form").validate({
			rules: {
				password: {
					required: true,
				},
				cpassword: {
					required: true,
					equalTo: "#password"
				}
			},

			messages: {           
				password: {
					required: "Please provide a password",
				},
				cpassword: {
					required: "Please provide a confirm password",
					equalTo: "Password must be match."
				},	
			},
        
			submitHandler: function(form) {
				form.submit();
			}
		
		});		
	});	
</script>
