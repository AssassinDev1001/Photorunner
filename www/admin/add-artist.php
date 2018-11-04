<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");
if(isset($_POST['add']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->addatirst($_POST, $_FILES))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
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
			<h1>Add Signature Artist Story</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Add Signature Artist Story</li>
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
					<div class="form-group">
						<label>Select Photographer</label>
						<select class="form-control" name="seller" id="seller" required="required">
							<option value="">Select Photographer</option>
							<?php
							$conditions = array();
							$seller = $common->getrecords('pr_seller','*',$conditions);
							if(!empty($seller))
							{
								$k=1;
								foreach($seller as $seller)
								{
									?>
									<option value="<?php echo $seller->id; ?>"><?php echo $seller->firstname; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Heading</label>
						<input type="text" class="form-control" name="heading" id="heading" required="required" value="<?php echo html_entity_decode($pages->heading); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputFile">Story Banner</label>
						<input type="file" name="banner" id="banner" >
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Sub Headning</label>
						<input type="text" class="form-control" name="subheadning" id="subheadning" required="required" value="<?php echo html_entity_decode($pages->subheadning); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Story</label>
						<textarea id="editor" name="story" required="required"><?php echo html_entity_decode($pages->story); ?></textarea>
					</div>
				</div>				
				<div class="box-footer">
					<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
					<button type="submit" name="add" class="btn btn-primary">Add Artist</button>
				</div>
				</form>
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

