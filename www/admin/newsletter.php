<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");


if(isset($_POST['send']))
{					
	$conditions = "";							
	$letter = $common->getrecords('pr_newsletter','*',$conditions);
	if(!empty($letter))
	{
		foreach($letter as $letter)
		{
			$email = $letter->email;
			$common->snewsletter($_POST, $email);
		}
		$common->add('s', 'Newaletter has been sent successfully.');
		$common->redirect(APP_FULL_URL);
	}
}

if(isset($_POST['search']))
{
	$email = $_POST['email'];

	if(!empty($email))
	{
		$conditions['email'] = $email;
	}

	$newsletter = $common->getrecords('pr_newsletter','*',$conditions);

}
else
{

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 10;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = "";						
	$statement = "pr_newsletter" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$newsletter = $common->getpagirecords('pr_newsletter','*',$conditions);
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
<div class="content-wrapper" style="background-color:#fff;">
	<section class="content-header" style="padding:0px;">
		<h1 style="padding-left:20px; padding-top:20px;">Manage Newsletter</h1>
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Newsletter</li>
		</ol>
		<div style="width:100%; margin-left:auto; margin-right:auto;">
			<div style="padding:20px;">
				<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}					
				?>
				<form method="POST">
					<div style="width:50%; float:left">
						<div class="form-group">
							<label for="exampleInputEmail1">Email Address</label>
							<input type="text" class="form-control" style="width:90%;" name="email" id="email" placeholder="Email Address">
						</div>
					</div>
					<div style="width:50%; float:right">			
						<div class="form-group" style="margin-top:25px;">
							<button type="submit" name="search" class="btn btn-primary" style="padding-left:25px; padding-right:25px;">Search</button>
						
						</div>
					</div>
				</form>
				<div style="clear:both"></div>
			</div>
		</div>
		<div class="col-md-12 register-top-grid" style="background-color:#fff;">
			<section class="content" style="min-height:175px;">
				<div class="box">
					<div class="box-body">
						<table id="example2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th style="text-align:center">S. No</th>
									<th style="text-align:center">Email Address</th>
									<th style="text-align:center">Date</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!empty($newsletter))
								{
									$k=$startpoint+1;
									foreach($newsletter as $newsletter)
									{
										?>
										<tr>
											<td style="text-align:center"><?php echo $k; ?>.</td>
											<td style="text-align:center"><?php echo $newsletter->email; ?></td>
											<td style="text-align:center"><?php echo $newsletter->date; ?></td>
										</tr>
										<?php
									$k = $k+1;
									}
								}
								else
								{
									?>
									<tr style="background-color:#E4F4F5; color:#000;">
										<td colspan="12">No records found</td>  
									</tr>
									<?php
								}
								?>
							</tfoot>
							
						</table>
					</div>
				</div>
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
			</section>
			<div style="margin-left:15px;margin-right:15px;">
				<form action="" method="post" id="page-form" enctype='multipart/form-data'>
					<div class="box-body">
						<div class="form-group">
							<label for="exampleInputEmail1">Create Newsletter</label>
							<textarea id="editor" name="newsletter" required="required"></textarea>
						</div>
					</div>				
					<div class="box-footer">
						<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
						<button type="submit" name="send" class="btn btn-primary">Send Newsletter</button>
					</div>
				</form>
			</div>
		</div>
	</section>
	
</div>      
<?php include('include/footer.php') ?>
</body>
</html>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
	initSample();
</script>
