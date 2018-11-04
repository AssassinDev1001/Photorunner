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
		if($common->updatepage($_POST))
		{
			$common->redirect(APP_URL."pages.php");
		}
		else
		{
			$common->redirect(APP_URL."pages.php");
		}
	}
}

if(isset($_POST['submit']))
{
	$conditions = array('id'=>$_POST['id']);
	$pages = $common->getrecord('pr_pages','*',$conditions);	
}
else
{
	$common->redirect(APP_URL."pages.php");
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
			<h1>Edit Page</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Edit Page</li>
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
						<label for="exampleInputEmail1">Page Title</label>
						<input type="text" class="form-control" name="title" id="title" required="required" value="<?php echo html_entity_decode($pages->title); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Page Headning</label>
						<input type="text" class="form-control" name="headning" id="headning" required="required" value="<?php echo html_entity_decode($pages->headning); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Page Description</label>
						<input type="hidden" name="id" value="<?php echo $pages->id; ?>"/>
						<textarea id="editor" name="description" required="required"><?php echo html_entity_decode($pages->description); ?></textarea>
					</div>
				</div>				
				<div class="box-footer">
					<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
					<button type="submit" name="update" class="btn btn-primary">Update Page</button>
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

