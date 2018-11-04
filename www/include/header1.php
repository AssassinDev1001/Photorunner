<?php
	$conditions = array();
	$home = $common->getrecord('pr_home','*',$conditions);	
?>
<div class="header">
	<div class="header_toping">
		<div class="container">
			<div class="col-md-6">
				<a href="skype:<?php echo html_entity_decode($home->number); ?>?chat" style="color:#333;">
				<i class="fa fa-skype"></i> 
				<?php echo html_entity_decode($home->number); ?> 
				</a>

				<span class="evlop"><i class="fa  fa-envelope"></i>
				<?php echo html_entity_decode($home->email); ?><span>
			</div>
			<div class="col-md-6" class="mediaa_soc" style="padding-right:0px">
				<ul class="media_socila">
					<?php if(!empty($_SESSION['guast']['email'])) { ?>
					<li style="font-weight:bold;font-size: 15px;font-weight: bold;"><a href="<?php echo APP_URL; ?>success.php" style="color:#333;text-decoration:none;">My Purchase List</a></li>
					<li style="font-weight:bold;font-size: 15px;font-weight: bold;"><a href="<?php echo APP_URL; ?>guast-payment.php" style="color:#333;text-decoration:none;">Your Cart (<?php echo count($_SESSION['cart']);?>)</a></li>
					<?php }else{ ?>
					<li style="font-weight:bold;font-size: 15px;font-weight: bold;"><a href="<?php echo APP_URL; ?>payment.php" style="color:#333;text-decoration:none;">Your Cart (<?php echo count($_SESSION['cart']);?>)</a></li>
					<?php } ?>
				<?php				
				$conditions =array('status'=>'1');						
				$social = $common->getrecords('pr_social','*',$conditions);
				if(!empty($social))
				{
					$k=$startpoint+1;
					foreach($social as $social)
					{
					?>
				<li><a href="<?php echo $social->url ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo $social->name ?>"><i class="fa fa-<?php echo $social->icon ?>"></i></a></li>
					<?php
					}
				}
				?>
				</ul>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
	<div class="container">
		<div class="header-nav">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div><img src="<?php echo APP_URL; ?>images/logo.png" style="max-width:100%"></div>
					<div>fxgds</div>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
	<li style="text-align:center; color:#000;"><a class="hvr-overline-from-center button2 active" href="<?php echo APP_URL; ?>index.php" style="color:#000;">Home</a></li>
	<li style="text-align:center; color:#000;"><a class="hvr-overline-from-center button2"style="color:#000;" href="<?php echo APP_URL; ?>photos.php">Photos</a></li>
	<li style="text-align:center; color:#000;"><a class="hvr-overline-from-center button2"style="color:#000;" href="<?php echo APP_URL; ?>galleries.php">Galleries</a></li>
	<li style="text-align:center; color:#000;"><a class="hvr-overline-from-center button2"style="color:#000;" href="<?php echo APP_URL; ?>about-us.php">About Us</a></li>
	<li style="text-align:center; color:#000;"><a class="hvr-overline-from-center button2"style="color:#000;" href="<?php echo APP_URL; ?>photographers.php">Photographers</a></li>
						<?php
						if(!empty($_SESSION['seller']['id']))
						{
							?>
							<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>seller/index.php">My Account</a></li>
							<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>seller/logout.php">Log Out</a></li>
							<?php
						}
						else
						{
							if(!empty($_SESSION['account']['id']))
							{
								?>
								<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>buyer/account.php">My Account</a></li>
								<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>buyer/logout.php">Log Out</a></li>
								<?php
							}
							else
							{
								?>
								<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>join-us.php">Join</a></li>
								<li><a class="hvr-overline-from-center button2 log_bg" href="<?php echo APP_URL; ?>log-in.php">Log in</a></li>
								<?php
							}
						}
						?>
						<?php
						
						?>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</div>
