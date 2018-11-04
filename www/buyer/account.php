<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');

if(isset($_POST['profile_picture_submit']))
{
	if(!empty($_FILES['profilepicture']['name']))
	{
		if($common->updateprofilepicture($_FILES))
		{
			$common->redirect(APP_URL."buyer/account.php");
		}
		else
		{
			$common->redirect(APP_URL."buyer/account.php");
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
		if($common->editpersonal($_POST))
		{
			$common->redirect(APP_URL."buyer/account.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

$conditions = array('member'=>$_SESSION['account']['id']);
$personal = $common->getrecord('pr_memberinfo','*',$conditions);

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
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div class="space_account"></div>
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
	<?php include(APP_ROOT.'include/buyer-left.php') ?>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<form  action=""  method="post" id="register-form" style="margin:30px;"> 
				<div class="col-md-6">
					<h2 style="text-align:center">Personal Information</h2>
					<lable style="padding:5px;">First Name</lable>
					<input type="text" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $members->firstname; ?>" />
					<lable style="padding:5px;">Last Name</lable>
					<input type="text" placeholder="Last Name" name="lastname" id="lastname" value="<?php echo $members->lastname; ?>" />
					<lable style="padding:5px;">Email Address</lable>
					<input type="email" placeholder="Email Address" name="email" id="email" value="<?php echo $members->email; ?>" disabled />
					<lable style="padding:5px;">Mobile Number</lable>
					<input type="text" placeholder="Phone Number"  id="mobile" name="mobile" class="numeric" value="<?php echo $members->mobile; ?>" />
				</div>
				<div class="col-md-6">
					<h2 style="text-align:center">Address</h2>
					<lable style="padding:5px;">Street Address1</lable>
					<input type="text" placeholder="Street Address1" name="address1" id="address1" value="<?php echo $personal->address1; ?>"/>
					<lable style="padding:5px;">Street Address2</lable>
					<input type="text" placeholder="Street Address2" name="address2" id="address2" value="<?php echo $personal->address2; ?>"/>
					<lable style="padding:5px;">Postal Code</lable>
					<input type="text" placeholder="Postal Code" name="postalcode" id="postalcode" value="<?php echo $personal->postalcode; ?>"/>


					<lable style="padding:5px;">Country</lable>
					<input type="text" placeholder="Country" name="country" id="country" value="<?php echo $personal->country; ?>"/>
					<lable style="padding:5px;">State</lable>
					<input type="text" placeholder="State" name="state" id="state" value="<?php echo $personal->state; ?>"/>

					<lable style="padding:5px;">City</lable>
					<input type="text" placeholder="City" name="city" id="city" value="<?php echo $personal->city; ?>"/>

					<?php /*<lable style="padding:5px;">Country</lable>
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
								<option value="<?php echo $country->id; ?>"<?php if($personal->country == $country->id) { echo"selected='selected'"; }  ?>><?php echo $country->country; ?></option>
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
									<option value="<?php echo $state->id; ?>"<?php if($personal->state == $state->id) { echo"selected='selected'"; }  ?>><?php echo $state->state; ?></option>
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
									<option value="<?php echo $city->id; ?>"<?php if($personal->city == $city->id) { echo"selected='selected'"; }  ?>><?php echo $city->city; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>*/ ?>
				</div>
				<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
				<input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>" >
				<button type="submit" name="submit" id="btnSubmit"/>Edit Personal Information</button>
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
