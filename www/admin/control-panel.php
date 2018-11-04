<?php
	include('include/config.php'); 
	include('include/check-login.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include('include/head.php') ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini" style="font-weight:none !important;">
		<?php include('include/header.php') ?>
		<?php include('include/left.php') ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Dashboard<small>Control panel</small></h1>
				<ol class="breadcrumb">
					<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				</ol>
				<div style="margin-top:15px;"></div>
				<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}					
				?>
			</section>
			 <section class="content">
				<div class="row">
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
<?php
	$conditions1 = array();
	$Buyers = $common->countrecords('pr_members','*',$conditions1);
?>
								<h3><?php echo $Buyers; ?></h3>
								<p>Buyers</p>
							</div>
							<div class="icon">
								<i class="ion ion-person-add"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-green">
							<div class="inner">
<?php
	$conditions2 = array();
	$photographer = $common->countrecords('pr_seller','*',$conditions2);
?>
								<h3><?php echo $photographer; ?></h3>
								<p>photographer</p>
							</div>
							<div class="icon">
								<i class="ion ion-person-add"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
<?php
	$conditions3 = array();
	$Photos = $common->countrecords('pr_photos','*',$conditions3);
?>
								<h3><?php echo $Photos; ?></h3>
								<p>Photos</p>
							</div>
							<div class="icon">
								<i class="fa fa-fw fa-photo"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
<?php
	$conditions4 = array();
	$Galleries = $common->countrecords('pr_galleries','*',$conditions4);
?>
								<h3><?php echo $Galleries; ?></h3>
								<p>Galleries</p>
							</div>
							<div class="icon">
								<i class="fa fa-fw fa-photo"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-blue">
							<div class="inner">
<?php
	$conditions5 = array();
	$Newsletter = $common->countrecords('pr_newsletter','*',$conditions5);
?>
								<h3><?php echo $Newsletter; ?></h3>
								<p>Newsletter</p>
							</div>
							<div class="icon">
								<i class="fa fa-fw fa-file"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-4 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
<?php
	$conditions6 = array();
	$Payments = $common->countrecords('pr_payments','*',$conditions6);
?>
								<h3><?php echo $Payments; ?></h3>
								<p>Payments</p>
							</div>
							<div class="icon">
								<i class="fa fa-fw fa-dollar"></i>
							</div>
							<a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</section>
			</div>
		</div>
		<div>
	</section>
</div>
<!-- header-section-starts -->
<?php include('include/footer.php') ?>
<!-- header-section-end -->
