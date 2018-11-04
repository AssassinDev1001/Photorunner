<?php  include('../include/config.php'); include(APP_ROOT.'include/check-seller.php');

if(isset($_POST['profile_picture_submit']))
{
	if(!empty($_FILES['profilepicture']['name']))
	{
		if($common->updateprofilepictureseller($_FILES))
		{
			$common->redirect(APP_URL."seller/seller-account.php");
		}
		else
		{
			$common->redirect(APP_URL."seller/seller-account.php");
		}
	}	
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}

if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->updateseller($_POST))
		{
			$common->redirect(APP_URL."seller/account.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

$conditions = array('id'=>$_SESSION['seller']['id']);
$seller = $common->getrecord('pr_seller','*',$conditions) ;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<script>
	function getstate(country){
		$.ajax({
			url:"<?php echo APP_URL; ?>buyer/state-ajax.php",
			type:"GET",
			data:"&country="+country,
			success:function(res)
			{
			document.getElementById('show_state').innerHTML=res;
			}
		
		})
	}
	</script>
	<script>
	function getcity(state){
		$.ajax({
			url:"<?php echo APP_URL; ?>buyer/city-ajax.php",
			type:"GET",
			data:"&state="+state,
			success:function(res)
			{
			document.getElementById('show_city').innerHTML=res;
			}
		
		})
	}
	</script>
	<style>
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	</style>
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div style="height:124px;"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div style="width:100%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
	<div class="col-md-3 no-pading" style="background-color:#fff; height:auto;margin:20px 0;">
		<?php 
		if(!empty($seller->profilepicture))
		{
			?>
			<div style="margin:15px;">
				<img src="<?php echo APP_URL; ?>uploads/<?php echo $seller->profilepicture; ?>" style="width:100%; min-height:200px; max-height:200px;"/>
			</div>
			<?php
		}else{
			?>
			<div style="margin:15px;">
				<img src="<?php echo APP_URL; ?>images/banner10.jpg" style="width:100%; min-height:200px; max-height:200px;"/>
			</div>
			<?php
		}
		?>
		<a class="fancybox" href="#inline"><div style="margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;">Upload Profile Picture</div></a>
		<div id="inline" style="width:250px; display: none; margin:auto; padding-top:15px;">
			<form action="" method="post" enctype='multipart/form-data' style="height: 200px;">	
				<h4>Upload Profile Picture</h4>
				<input type="file" name="profilepicture" id="profilepicture" required="required">
				<span style="color:red">Profile Picture Size <b>250px * 250px</b></span><br/>
				<button type="submit" style="margin-top:10px;" name="profile_picture_submit" class="btn btn-primary" >Upload Profile Picture</button>
			</form>
		</div>
		<a href="<?php echo APP_URL; ?>seller/create-gallery.php"><div class="line1">Create Gallery</div></a>
		<a href="<?php echo APP_URL; ?>seller/add-photo.php"><div  class="line1">Add Photo</div></a>
		<a href="<?php echo APP_URL; ?>seller/change-password.php"><div  class="line1">Change Password</div></a>
		<a href="<?php echo APP_URL; ?>seller/logout.php"><div class="line2">Sign Out</div></a>
	</div>
	<div class="col-md-9 features features-right" style="margin:20px 0; padding:0 0 0 20px">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<form  action=""  method="post" id="register-form" style="margin:30px;"> 
				<h2 style="text-align:center">Update Your Profile</h2>
				<div class="col-md-6">
					<lable style="padding:5px;">Category</lable>
					<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="category" id="category" required="required">
						<option value="">Select Category</option>
						<?php
						$conditions = array();
						$categories = $common->getrecords('pr_categories','*',$conditions);
						if(!empty($categories))
						{
							$k=1;
							foreach($categories as $categories)
							{
								?>
								<option value="<?php echo $categories->id; ?>"<?php if($seller->category == $categories->id) { echo"selected='selected'"; }  ?>><?php echo $categories->category; ?></option>
								<?php
							}
						}
						?>
					</select>
					<lable style="padding:5px;">Email Address</lable>
					<input type="email" placeholder="Email Address" name="email" id="email" value="<?php echo $seller->email; ?>" disabled />
					<lable style="padding:5px;">Username</lable>
					<input type="text" placeholder="Username" name="username" id="username" value="<?php echo $seller->username; ?>" disabled />
					<lable style="padding:5px;">First Name</lable>
					<input type="text" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $seller->firstname; ?>" />
					<lable style="padding:5px;">Last Name</lable>
					<input type="text" placeholder="Last Name" name="lastname" id="lastname" value="<?php echo $seller->lastname; ?>" />
					<lable style="padding:5px;">Zip Code</lable>
					<input type="text" placeholder="Zip Code"  id="zip_code" name="zip_code" value="<?php echo $seller->zip_code; ?>" />
				</div>
				<div class="col-md-6">
					<lable style="padding:5px;">Business Name</lable>
					<input type="text" placeholder="Business Name" name="business_name" id="business_name" value="<?php echo $seller->business_name; ?>"/>
					<lable style="padding:5px;">Street Address2</lable>
					<input type="text" placeholder="Business Phone number" name="phone_number" id="phone_number" class="numeric" value="<?php echo $seller->phone_number; ?>"/>
					<lable style="padding:5px;">Country</lable>
					<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="country" id="country" required="required" onchange="getstate(this.value);">
						<option value="">Select Country</option>
						<?php
						$conditions = array();
						$country = $common->getrecords('pr_country','*',$conditions);
						if(!empty($country))
						{
							$k=1;
							foreach($country as $country)
							{
								?>
								<option value="<?php echo $country->id; ?>"<?php if($seller->country == $country->id) { echo"selected='selected'"; }  ?>><?php echo $country->country; ?></option>
								<?php
							}
						}
						?>
					</select>
					<div id="show_state">
						<lable style="padding:5px;">State</lable>
						<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="state" id="state" required="required" onchange="getcity(this.value);">
							<option value="">Select State</option>
							<?php
							$conditions = array();
							$state = $common->getrecords('pr_state','*',$conditions);
							if(!empty($state))
							{
								$k=1;
								foreach($state as $state)
								{
									?>
									<option value="<?php echo $state->id; ?>"<?php if($seller->state == $state->id) { echo"selected='selected'"; }  ?>><?php echo $state->state; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
					<div id="show_city">
						<lable style="padding:5px;">City</lable>
						<select class="form-control" style="border-radius:0px; margin: 0 0 20px;"  name="city" id="city" required="required">
							<option value="">Select City</option>
							<?php
							$conditions = array();
							$city = $common->getrecords('pr_city','*',$conditions);
							if(!empty($city))
							{
								$k=1;
								foreach($city as $city)
								{
									?>
									<option value="<?php echo $city->id; ?>"<?php if($seller->city == $city->id) { echo"selected='selected'"; }  ?>><?php echo $city->city; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
				<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
				<input type="hidden" name="id" value="<?php echo $_SESSION['seller']['id']; ?>" >
				<button type="submit" name="submit" id="btnSubmit"/>Update Profile</button>
			</form>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#register-form input[type="text"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "2px solid red",
                });
            }
            else {
                $(this).css({
					"border": "1px solid #3cb371",
                });
            }
        });
        
       $('#register-form select').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "2px solid red",
                });
            }
            else {
                $(this).css({
					"border": "1px solid #3cb371",
                });
            }
        });

        $('#register-form input[type="email"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "2px solid red",
                });
            }
            else {
				var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if(!pattern.test($.trim($(this).val())))
				{
					isValid = false;
					$(this).css({
						"border": "2px solid red",
					});
				}
				else
				{
					$(this).css({
						"border": "1px solid #3cb371",
					});
				}
                
            }
        });
        if (isValid == false) 
            e.preventDefault();
        else 
            ;
    });
});
</script>
<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
