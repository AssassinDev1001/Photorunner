<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");
	
if(isset($_POST['create']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->state($_POST))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}
if(isset($_POST['update']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->updatestate($_POST))
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
	$state = $common->getrecord('pr_state','*',$conditions);	
}
if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activatestate('pr_state',$conditions))
	{
		$common->redirect(APP_URL."manage-state.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['deactivate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->deactivatestate('pr_state',$conditions))
	{
		$common->redirect(APP_URL."manage-state.php");
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
			<h1>Manage States</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Manage States</li>
			</ol>
		</section>
		<section class="content">
			<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}					
			?>
			<div class="box">
				<form action="" method="post" id="page-form" enctype='multipart/form-data'>
				<div class="box-body">
					<label>Select Country</label>
					<select class="form-control" name="country" id="country" required="required">
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
								<option value="<?php echo $country->id; ?>"<?php if($state->country == $country->id) { echo"selected='selected'"; }  ?>><?php echo $country->country; ?></option>
								<?php
							}
						}
						?>
					</select>
					<div style="height:10px;"></div>
					<div class="form-group">
						<label for="exampleInputEmail1">Enter State</label>
						<input type="text" class="form-control" value="<?php echo $state->state; ?>" name="state" id="state" required="required">
					</div>
				</div>
				<?php
				if(isset($_POST['submit']))
				{
					?>
					<div style="padding-left:10px; padding-bottom:10px;">
						<input type="hidden" name="id" value="<?php echo $state->id; ?>"/>
						<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
						<button type="submit" name="update" style="padding-left:30px; padding-right:30px;" class="btn btn-primary">Update State</button>
					</div>
					<?php
				}
				else
				{
					?>
					<div style="padding-left:10px; padding-bottom:10px;">
						<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
						<button type="submit" name="create" style="padding-left:30px; padding-right:30px;" class="btn btn-primary">Add State</button>
					</div>
					<?php
				}
				?>
				</form>
			</div>
		</section>
		<div class="col-md-12 register-top-grid" style="background-color:#fff;">
		<section class="content">
			<div class="box">
				<div class="box-body">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="text-align:center">S. No</th>
								<th style="text-align:center">State</th>
								<th style="text-align:center">Country</th>
								<th style="text-align:center">Action</th>
								<th style="text-align:center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
							$limit = 10;
							$startpoint = ($page * $limit) - $limit;				
							$conditions ="";						
							$statement = "pr_state" . $conditions;	
							$conditions .= " LIMIT {$startpoint} , {$limit}";	

							$state = $common->getpagirecords('pr_state','*',$conditions);
				
							if(!empty($state))
							{
								$k=$startpoint+1;
								foreach($state as $state)
								{

									$conditions = array('id'=>$state->country);
									$country = $common->getrecord('pr_country','*',$conditions);	
									?>
									<tr>
										<td style="text-align:center"><?php echo $k; ?>.</td>
										<td style="text-align:center"><?php echo $state->state; ?></td>
										<td style="text-align:center"><?php echo $country->country; ?></td>
										<td style="width:20%">
											<?php if($state->status==1) {?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $state->id; ?>"/>
												<button type="submit" name="deactivate" class="btn btn-block btn-warning" style="width:100%">Activate</button>	
											</form>
											<?php } else{ ?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $state->id; ?>"/>
												<button type="submit" name="activate" class="btn btn-danger" style="width:100%">Deactivate</button>	
											</form>
											<?php } ?>
										</td>
										<td style="width:20%">
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $state->id; ?>"/>
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
									<td colspan="5">No records found</td>  
								</tr>
								<?php
							}
							?>
						</tfoot>
					</table>
					<div style="float:right; margin-right:30px;">
						<?php echo $common->pagination($statement,$limit,$page); ?>
					</div>
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

