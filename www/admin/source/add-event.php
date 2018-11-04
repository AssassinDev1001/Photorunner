<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->addevent($_POST,$_FILES))
		{
			$common->redirect(APP_URL."events.php");
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
			<h1>Add Events</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Add Events</li>
			</ol>
		</section>
		<section class="content">
			<div style="hight:10px;">&nbsp;</div>
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
						<label for="exampleInputEmail1">Events Bottom Heading</label>
						<input type="text" class="form-control" name="title" id="title" required="required">
					</div>
					<div class="form-group">
						<label for="exampleInputFile">Image Upload</label>
						<input type="file" name="image" id="image">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Events Description</label>
						<textarea id="editor" name="description" required="required"></textarea>
					</div>
				</div>				
				<div class="box-footer">
					<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>
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

