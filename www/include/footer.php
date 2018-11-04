<!-- footer -->
<div class="footer">
	<div class="container">
		<div class="footer-grids">
			<div class="col-md-3 footer-grid">
				<h3>Company</h3>
				<ul>
					<li><a href="<?php echo APP_URL; ?>about-us.php">About us</a></li>
					<li><a href="<?php echo APP_URL; ?>press.php">Press</a></li>
					<li><a href="<?php echo APP_URL; ?>careers.php">Careers</a></li>
					<li><a href="<?php echo APP_URL; ?>affiliates.php">Affiliates</a></li>
				</ul>
			</div>
			<div class="col-md-3 footer-grid">
				<h3>What we Sell </h3>
				<ul>
					<li><a href="<?php echo APP_URL; ?>plans-pricing.php">Plans and Pricing</a></li>
					<li><a href="<?php echo APP_URL; ?>corporate-accounts.php">Corporate accounts</a></li>
					<li><a href="#">promo codes</a></li>
				</ul>
			</div>
			<div class="col-md-3 footer-grid">
				<h3>Need Help</h3>
				<ul>
					<li style="background:none;padding-left:0px;"><a href="#">Customer support: 0047 98844016</a></li>
					<li style="background:none;padding-left:0px;"><a href="mailto:info@photorunner.no" >E:info@photorunner.no</a></li>
					<li style="background:none;padding-left:0px;"><a href="mailto:post@photorunner.no" >E:post@photorunner.no</a></li>
				</ul>
			</div>
			<div class="col-md-3 footer-grid">
				<h3>Newsletter</h3>
				<div id="custom-search-input">
					<div class="input-group col-md-12">
						<form action="" method="GET" id="compose-newsletter">
							<input class=" search-query form-control" name="subemail" id="subemail" type="email" style="color:#333; width:50%;" placeholder="Enter Email.,," required="required">
							<span class="input-group-btn">
								<button class="btn btn-danger" type="submit" name="newsletter" id="sub_submit">
									<span class="glyphicon glyphicon-">Signup</span>
								</button>
							</span>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div class="footer1" style="">
	<div class="container">
		<p style="text-align:center"> &copy; 2016 Photorunner. All Rights Reserved </p>
		<p style="text-align:center; font-size:14px;"> Designed and Developed by Anaad IT Solutions (P) Ltd</p>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {;
		$("#sub_submit").click(function(){
		var sub_email1 = $("#subemail").val();

		if ($.trim(sub_email1).length == 0) {
			alert('Please Enter Subscription Email First');
			e.preventDefault();
		}
		if (!validateEmail(sub_email1)) {
				alert('Invalid Email Address');
				e.preventDefault();
			}
		var dataString = '&sub_email1='+ sub_email1;
		$.ajax({
		type: "GET",
		url: "newsletterajax.php",
		
		data: dataString,
		success: function(resi){
			if(resi == 1)
			{
				alert('You Have Successfully Subscribed To Our Newsletter');
				return true;
			}
			if(resi == 2)
			{
				alert('You Are Already Subscribed To Our Newsletter');
				return false;
			}
		}
		});
		return false;
	});
    });
    function validateEmail(sub_email1) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sub_email1)) {
        return true;
    }
    else {
        return false;
    }
}
</script>
