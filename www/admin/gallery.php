<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['remove']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->delete('pr_galleries',$conditions))
	{
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activategallery('pr_galleries',$conditions))
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
	if($common->deactivategallery('pr_galleries',$conditions))
	{
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}


if(isset($_POST['search']))
{
	$email = $_POST['email'];

	if(!empty($email))
	{
		$conditions['name'] = $email;
	}

	$gallery = $common->getrecords('pr_galleries','*',$conditions);

}
else
{

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 10;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = "";						
	$statement = "pr_galleries" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$gallery = $common->getpagirecords('pr_galleries','*',$conditions);
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php include('include/head.php') ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php include('include/header.php') ?>
<?php include('include/left.php') ?>
<div class="content-wrapper" style="background-color:#fff;">
	<section class="content-header" style="padding:0px;">
		<h1 style="padding-left:20px; padding-top:20px;">Manage Gallery</h1>
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Gallery</li>
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
							<label for="exampleInputEmail1">Gallery Name</label>
							<input type="text" class="form-control" style="width:90%;" name="email" id="email" placeholder="Gallery Name">
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
			<section class="content">
				<div class="box">
					<div class="box-body">
						<table id="example2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th style="text-align:center">S. No</th>
									<th style="text-align:center">Gallery Name</th>
									<th style="text-align:center">Photographer Name</th>
									<th style="text-align:center">Gallery</th>
									<th style="text-align:center">Created date</th>
									<th style="text-align:center">Status</th>
									<th style="text-align:center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!empty($gallery))
								{
									$k=$startpoint+1;
									foreach($gallery as $gallery)
									{
									$conditionsseller = array('id'=>$gallery->seller);
									$seller = $common->getrecord('pr_seller','*',$conditionsseller);
										?>
										<tr>
											<td style="text-align:center"><?php echo $k; ?>.</td>
											<td style="text-align:center"><?php echo $gallery->name; ?></td>
											<td style="text-align:center"><?php if(!empty($seller->username)) { ?><?php echo $seller->username; ?><?php }else{ ?>No Record<?php } ?></td>
											<td style="text-align:center"><?php if(!empty($gallery->password)) { ?>Private<?php }else{ ?>Public<?php } ?></td>
											<td style="text-align:center">
												<?php 
													$data1 = $gallery->date; 
													$data2 = explode(" ",$data1);
													echo $data2[0]; 
												?></td>
											<td>
												<?php if($gallery->status==1) {?>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $gallery->id; ?>"/>
													<button type="submit" name="deactivate" class="btn btn-primary" style="width:100%">Activate</button>	
												</form>
												<?php } else{ ?>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $gallery->id; ?>"/>
													<button type="submit" name="activate" class="btn btn-danger" style="width:100%">Deactivate</button>	
												</form>
												<?php } ?>
											</td>
											<td>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $gallery->id; ?>"/>
													<button class="btn btn-primary" style="width:100%" type="submit" name="remove">Remove</button>
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
		</div>
	</section>
</div>      
<?php include('include/footer.php') ?>
</body>
</html>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
