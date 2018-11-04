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
		if($common->homepage($_POST, $_FILES))
		{
			$common->redirect(APP_URL."control-panel.php");
		}
		else
		{
			$common->redirect(APP_URL."control-panel.php");
		}
	}
}

$conditions = array();
$home = $common->getrecord('pr_home','*',$conditions);	
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
			<h1>Edit Home Page</h1>
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Edit Home Page</li>
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
					<div style="float:left; width:48%;">
						<div class="form-group">
							<label for="exampleInputEmail1">Phone Number</label>
							<input type="text" class="form-control" name="number" id="number" required="required" value="<?php echo html_entity_decode($home->number); ?>">
						</div>
					</div>
					<div style="float:right; width:48%;">
						<div class="form-group">
							<label for="exampleInputEmail1">Email Address</label>
							<input type="text" class="form-control" name="email" id="email" required="required" value="<?php echo html_entity_decode($home->email); ?>">
						</div>
					</div>
					<div style="float:left; width:50%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Website Logo</label>
								<input type="file" name="image1" id="image1" >
								<br/>
								<img src="../uploads/<?php echo $home->image1; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:48%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Banner</label>
								<input type="file" name="image2" id="image2" >
								<br/>
								<img src="../uploads/<?php echo $home->image2; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
<div style="clear:both"></div>

					<div class="form-group">
						<label for="exampleInputEmail1">Logo Bottom Text</label>
						<input type="text" class="form-control" name="logotext" id="logotext" required="required" value="<?php echo html_entity_decode($home->logotext); ?>">
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Banner Bottom Heading</label>
						<input type="text" class="form-control" name="bannerheading" id="bannerheading" required="required" value="<?php echo html_entity_decode($home->bannerheading); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Heading</label>
						<input type="text" class="form-control" name="facilitiesheading" id="facilitiesheading" required="required" value="<?php echo html_entity_decode($home->facilitiesheading); ?>">

					</div>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputFile">Our Facilities First Image</label>
							<input type="file" name="image3" id="image3" >
							<br/>
							<img src="../uploads/<?php echo $home->image3; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities First Image Title</label>
						<input type="text" class="form-control" name="firstimagetitle" id="firstimagetitle" required="required" value="<?php echo html_entity_decode($home->firstimagetitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities First Image Sub Title</label>
						<input type="text" class="form-control" name="firstimagesubtitle" id="firstimagesubtitle" required="required" value="<?php echo html_entity_decode($home->firstimagesubtitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities First Image Description</label>
						<input type="text" class="form-control" name="firstdescription" id="firstdescription" required="required" value="<?php echo html_entity_decode($home->firstdescription); ?>">
					</div>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputFile">Our Facilities Second Image</label>
							<input type="file" name="image4" id="image4" >
							<br/>
							<img src="../uploads/<?php echo $home->image4; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Second Image Title</label>
						<input type="text" class="form-control" name="secondimagetitle" id="secondimagetitle" required="required" value="<?php echo html_entity_decode($home->secondimagetitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Second Image Sub Title</label>
						<input type="text" class="form-control" name="secondimagesubtitle" id="secondimagesubtitle" required="required" value="<?php echo html_entity_decode($home->secondimagesubtitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Second Image Description</label>
						<input type="text" class="form-control" name="seconddescription" id="seconddescription" required="required" value="<?php echo html_entity_decode($home->seconddescription); ?>">
					</div>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputFile">Our Facilities Third Image</label>
							<input type="file" name="image5" id="image5">
							<br/>
							<img src="../uploads/<?php echo $home->image5; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Third Image Title</label>
						<input type="text" class="form-control" name="thirdimagetitle" id="thirdimagetitle" required="required" value="<?php echo html_entity_decode($home->thirdimagetitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Third Image Sub Title</label>
						<input type="text" class="form-control" name="thirdimagesubtitle" id="thirdimagesubtitle" required="required" value="<?php echo html_entity_decode($home->thirdimagesubtitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Third Image Description</label>
						<input type="text" class="form-control" name="thirddescription" id="thirddescription" required="required" value="<?php echo html_entity_decode($home->thirddescription); ?>">
					</div>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputFile">Our Facilities Fourth Image</label>
							<input type="file" name="image6" id="image6">
							<br/>
							<img src="../uploads/<?php echo $home->image6; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fourth Image Title</label>
						<input type="text" class="form-control" name="fourtimagetitle" id="fourtimagetitle" required="required" value="<?php echo html_entity_decode($home->fourtimagetitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fourth Image Sub Title</label>
						<input type="text" class="form-control" name="fourtimagesubtitle" id="fourtimagesubtitle" required="required" value="<?php echo html_entity_decode($home->fourtimagesubtitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fourth Image Description</label>
						<input type="text" class="form-control" name="fourthdescription" id="fourthdescription" required="required" value="<?php echo html_entity_decode($home->fourthdescription); ?>">
					</div>
					<div class="box-body"> 
						<div class="form-group">
							<label for="exampleInputFile">Our Facilities Fifth Image</label>
							<input type="file" name="image7" id="image7">
							<br/>
							<img src="../uploads/<?php echo $home->image7; ?>" style="width:10%;border:1px solid #d2d6de;padding:5px;" />
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fifth Image Title</label>
						<input type="text" class="form-control" name="fifthimagetitle" id="fifthimagetitle" required="required" value="<?php echo html_entity_decode($home->fifthimagetitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fifth Image Sub Title</label>
						<input type="text" class="form-control" name="fifthimagesubtitle" id="fifthimagesubtitle" required="required" value="<?php echo html_entity_decode($home->fifthimagesubtitle); ?>">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Our Facilities Fifth Image Description</label>
						<input type="text" class="form-control" name="fifthdescription" id="fifthdescription" required="required" value="<?php echo html_entity_decode($home->fifthdescription); ?>">
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step First</label>
								<input type="file" name="image8" id="image8">
								<br/>
								<img src="../uploads/<?php echo $home->image8; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Second</label>
								<input type="file" name="image9" id="image9">
								<br/>
								<img src="../uploads/<?php echo $home->image9; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Third</label>
								<input type="file" name="image10" id="image10">
								<br/>
								<img src="../uploads/<?php echo $home->image10; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Fourth</label>
								<input type="file" name="image11" id="image11" >
								<br/>
								<img src="../uploads/<?php echo $home->image11; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>

					<div style="clear:both"></div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step First Text</label>
								<input type="text" name="image8text" id="image8text" class="form-control" value="<?php echo html_entity_decode($home->image8text); ?>">
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Second Text</label>
								<input type="text" name="image9text" id="image9text" class="form-control" value="<?php echo html_entity_decode($home->image9text); ?>">
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Third Text</label>
								<input type="text" name="image10text" id="image10text" class="form-control" value="<?php echo html_entity_decode($home->image10text); ?>">
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Register Step Fourth Text</label>
								<input type="text" name="image11text" id="image11text" class="form-control" value="<?php echo html_entity_decode($home->image11text); ?>">
							</div>
						</div>
					</div>


					<div style="clear:both"></div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Bottom Image First</label>
								<input type="file" name="image12" id="image12" >
								<br/>
								<img src="../uploads/<?php echo $home->image12; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Bottom Image Second</label>
								<input type="file" name="image13" id="image13">
								<br/>
								<img src="../uploads/<?php echo $home->image13; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Bottom Image Third</label>
								<input type="file" name="image14" id="image14">
								<br/>
								<img src="../uploads/<?php echo $home->image14; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="float:left; width:25%;">
						<div class="box-body"> 
							<div class="form-group">
								<label for="exampleInputFile">Bottom Image Fourth</label>
								<input type="file" name="image15" id="image15">
								<br/>
								<img src="../uploads/<?php echo $home->image15; ?>" style="width:50%;border:1px solid #d2d6de;padding:5px;" />
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div class="form-group">
						<label for="exampleInputEmail1">Company  Description</label>
						<textarea id="editor" name="companydescription" required="required"><?php echo html_entity_decode($home->companydescription); ?></textarea>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Bottom Copyright</label>
						<input type="text" class="form-control" name="copyright" id="copyright" required="required" value="<?php echo html_entity_decode($home->copyright); ?>">
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

