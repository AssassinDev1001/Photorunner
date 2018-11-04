<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activateartist('pr_artist',$conditions))
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
	if($common->deactivateartist('pr_artist',$conditions))
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
			<h1>Manage Category</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Manage Category</li>
			</ol>
		</section>
		<div class="col-md-12 register-top-grid" style="background-color:#fff;">
		<section class="content">
			<?php
			if(!empty($_SESSION['flash_messages']))
			{	
				echo $msgs->display();
			}					
			?>
			<div class="box">
				<div class="box-body">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="text-align:center">S. No</th>
								<th style="text-align:center">Headning</th>
								<th style="text-align:center">Action</th>
								<th style="text-align:center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
							$limit = 10;
							$startpoint = ($page * $limit) - $limit;					
							$conditions = "";						
							$statement = "pr_artist" . $conditions;						
							$conditions .= " LIMIT {$startpoint} , {$limit}";	
							$artist = $common->getpagirecords('pr_artist','*',$conditions);
				
							if(!empty($artist))
							{
								$k=$startpoint+1;
								foreach($artist as $artist)
								{
									?>
									<tr>
										<td style="text-align:center"><?php echo $k; ?>.</td>
										<td style="text-align:center"><?php echo $artist->heading; ?></td>
										<td style="width:20%">
											<?php if($artist->status==1) {?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $artist->id; ?>"/>
												<button type="submit" name="deactivate" class="btn btn-block btn-warning" style="width:100%">Activate</button>	
											</form>
											<?php } else{ ?>
											<form role="form" action="" method="post">
												<input type="hidden" name="id" value="<?php echo $artist->id; ?>"/>
												<button type="submit" name="activate" class="btn btn-danger" style="width:100%">Deactivate</button>	
											</form>
											<?php } ?>
										</td>
										<td style="width:20%">
											<form role="form" action="edit-artist.php" method="post">
												<input type="hidden" name="id" value="<?php echo $artist->id; ?>"/>
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
					<?php
					if(isset($_POST['search']))
					{
						?>
						<div></div>
						<?php
					}
					else
					{
						?>
						<div style="float:right">
							<?php echo $common->pagination($statement,$limit,$page); ?>
						</div>
						<?php
					}
					?>
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

