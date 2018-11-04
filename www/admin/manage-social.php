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
		if($common->updatesocial($_POST))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}
if(isset($_POST['submit']))
{
	$conditions = array('id'=>$_POST['id']);
	$social = $common->getrecord('pr_social','*',$conditions);	
}
if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activatesocial('pr_social',$conditions))
	{
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['deactivate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->deactivatesocial('pr_social',$conditions))
	{
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head.php') ?>
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/js/sample.js"></script>	
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<?php include('include/header.php') ?>
	<?php include('include/left.php') ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Manage Social Network</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Manage Social Network</li>
			</ol>
		</section>
		<div style="margin:20px;">
			<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}					
			?>
		</div>
		<?php
		if(isset($_POST['submit']))
		{
			?>
			<section class="content">
				<div class="box">
					<form action="" method="post" id="page-form" enctype='multipart/form-data'>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputEmail1">Enter Name</label>
							<input type="text" class="form-control" name="name" value="<?php echo $social->name; ?>" id="name" required="required">
						</div>
					</div>	
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputEmail1">Enter URL</label>
							<input type="text" class="form-control" name="url" value="<?php echo $social->url; ?>" id="url" required="required">
						</div>
					</div>
					<div style="padding-left:10px; padding-bottom:10px;">
						<input type="hidden" name="id" value="<?php echo $social->id; ?>"/>
						<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
						<button type="submit" name="update" style="padding-left:30px; padding-right:30px;" class="btn btn-primary">Update Network</button>
					</div>
			
					</form>
				</div>
			</section>
			<?php
		}
		?>

		<div class="col-md-12 register-top-grid" style="background-color:#fff;">
		<section class="content">
			<div class="box">
				<div class="box-body">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="text-align:center">S. No</th>
								<th style="text-align:center">Name</th>
								<th style="text-align:center">URL</th>
								<th style="text-align:center">Action</th>
								<th style="text-align:center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php				
							$conditions =array();						
							$social = $common->getpagirecords('pr_social','*',$conditions);
							if(!empty($social))
							{
								$k=$startpoint+1;
								foreach($social as $social)
								{
									?>
									<tr>
										<td style="text-align:center"><?php echo $k; ?>.</td>
										<td style="text-align:center"><?php echo $social->name; ?></td>
										<td style="text-align:center"><?php echo $social->url; ?></td>
										<td style="width:20%">
											<?php if($social->status==1) {?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $social->id; ?>"/>
												<button type="submit" name="deactivate" class="btn btn-block btn-warning" style="width:100%">Activate</button>	
											</form>
											<?php } else{ ?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $social->id; ?>"/>
												<button type="submit" name="activate" class="btn btn-danger" style="width:100%">Deactivate</button>	
											</form>
											<?php } ?>
										</td>
										<td style="width:20%">
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $social->id; ?>"/>
												<button class="btn btn-block btn-info" style="width:100%" type="submit" name="submit">Edit</button>
											</form>
										</td>
									</tr>
									<?php
									$k = $k+1;
								}
							}
							else
							{
								?>
								<tr style="background-color:#E4F4F5; color:#000;">
									<td colspan="4">No records found</td>  
								</tr>
								<?php
							}
							?>
						</tfoot>
					</table>
				</div>
			</div>
		</section>
	</div>
<?php include('include/footer.php') ?>
<div style="clear:both"></div>
<div>
<script>
	initSample();
</script>
<script src="js/jquery.validate.min.js"></script>
<script>	
	$(document).ready(function(){
		$("#page-form").validate();
	});
</script>

