<?php include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information1.php');
if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->personal($_POST))
		{
			$common->redirect(APP_URL."buyer/account.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

$conditions = array('id'=>$_SESSION['account']['id']);
$members = $common->getrecord('pr_members','*',$conditions) ;
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
<body style="background-color:#F3F3F3">
	<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_header_join">&nbsp;</div>
<div style="width:48%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
<div class="module form-module">
	<div class="form"></div>
	<div class="form">
		<h2 style="text-align:center">Personal Information</h2>
		<form  action=""  method="post" id="register-form"> 
			<div style="float:left; width:48%;"><input type="text" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $members->firstname; ?>" disabled /></div><div style="width:4%; float:left">&nbsp;</div><div style="width:48%; float:right;"><input type="text" placeholder="Last Name" name="lastname" id="lastname" value="<?php echo $members->lastname; ?>" disabled /></div>
			<div style="clear:both;"></div>
			<input type="email" placeholder="Email Address" name="email" id="email" value="<?php echo $members->email; ?>" disabled />
			<input type="text" placeholder="Phone Number"  id="mobile" name="mobile" class="numeric" value="<?php echo $members->mobile; ?>" />
			<h2 style="text-align:center">Address</h2>
			<input type="text" placeholder="Street Address1" name="address1" id="address1"/>
			<input type="text" placeholder="Street Address2" name="address2" id="address2"/>
			<input type="text" placeholder="Postal Code" name="postalcode" id="postalcode"/>


			<input type="text" placeholder="Country" name="country" id="country"/>
			<input type="text" placeholder="State" name="state" id="state"/>
			<input type="text" placeholder="City" name="city" id="city"/>
			<?php /*<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="country" id="country" required="required" onchange="getstate(this.value);">
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
						<option value="<?php echo $country->id; ?>"<?php if($country->state == $country->id) { echo"selected='selected'"; }  ?>><?php echo $country->country; ?></option>
						<?php
					}
				}
				?>
			</select>
			<div id="show_state">
				<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="state" id="state" required="required">
					<option value="">Select State</option>
					<?php
					$rim = $cityy->country;
					$conditions = array(country => $rim);
					$state = $common->getrecords('pr_state','*',$conditions);
					if(!empty($state))
					{
						$k=1;
						foreach($state as $state)
						{
							?>
							<option value="<?php echo $state->id; ?>"<?php if($cityy->state == $state->id) { echo"selected='selected'"; }  ?>><?php echo $state->state; ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<div id="show_city">
				<select class="form-control" style="border-radius:0px; margin: 0 0 20px;"  name="city" id="city" required="required">
					<option value="">Select City</option>
					<?php
					$rim = $cityy->country;
					$conditions = array(country => $rim);
					$state = $common->getrecords('pr_city','*',$conditions);
					if(!empty($state))
					{
						$k=1;
						foreach($state as $state)
						{
							?>
							<option value="<?php echo $state->id; ?>"<?php if($cityy->state == $state->id) { echo"selected='selected'"; }  ?>><?php echo $state->state; ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>*/ ?>
			<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
			<input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>" >
			<button type="submit" name="submit" id="btnSubmit"/>Submit</button>
		</form>
	</div>
</div>
<div style="height:100px;">&nbsp;</div>
	<?php include(APP_ROOT.'include/footer.php') ?>
	<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	var specialKeys = new Array();
	specialKeys.push(8);
	$(function () {
		$(".numeric").bind("keypress", function (e) {
			var keyCode = e.which ? e.which : e.keyCode
			var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
			$(".error").css("display", ret ? "none" : "inline");
			return ret;
		});
	});
</script>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#register-form input[type="text"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else {
                $(this).css({
					"border": "",
                });
            }
        });
        
       $('#register-form select').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else {
                $(this).css({
					"border": "",
                });
            }
        });

        $('#register-form input[type="email"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else {
				var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if(!pattern.test($.trim($(this).val())))
				{
					isValid = false;
					$(this).css({
						"border": "1px solid red",
					});
				}
				else
				{
					$(this).css({
						"border": "",
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
