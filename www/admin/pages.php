<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");
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
			<h1>All Pages</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active"> All Pages</li>
			</ol>
			<div style="hight:10px;">&nbsp;</div>
			<?php
			if(!empty($_SESSION['flash_messages']))
			{	
				echo $msgs->display();
			}					
			?>
			<div class="box">	        
				<div class="box-body no-padding">	  
					<table class="table table-striped">
						<tr>
							<th style="width: 10px">#</th>
							<th>Page Title</th>
							<th>Page Headning</th>
							<td style="width:100px; text-align:center">Action</td>
						</tr>
						<?php
						$conditions = array();
						$pages = $common->getrecords('pr_pages','*',$conditions);
						if(!empty($pages))
						{
							$k=1;
							foreach($pages as $pages)
							{
								?>
								<tr style="background-color:#E4F4F5; color:#000;">
									<td><?php echo $k; ?>.</td>
									<td><?php echo $pages->title; ?></td>
									<td><?php echo $pages->headning; ?></td>
									<form role="form" action="edit-page.php" method="post">
										<input type="hidden" name="id" value="<?php echo $pages->id; ?>"/>
										<td style="text-align:center"><button style="border:none" type="submit" name="submit"><i class="fa fa-fw fa-edit"></i></button></td>
									</form>
								</tr>
								<?php
								$k = $k+1;
							}
						}
						else
						{
							?>
							<tr style="background-color:#E4F4F5; color:#000;">
								<td colspan="3">No records found</td>  
							</tr>
							<?php
						}
						?>
						<div style="clear:both"></div>
					</table>
				</div>
			</div>
		</section>
	</div>
<!-- footer-section-starts -->
<?php include('include/footer.php') ?>


