<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['remove']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->delete('pr_members',$conditions))
	{
		$common->redirect(APP_URL."users.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activate('pr_members',$conditions))
	{
		$common->redirect(APP_URL."users.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['deactivate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->deactivate('pr_members',$conditions))
	{
		$common->redirect(APP_URL."users.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}


if(isset($_POST['search']))
{
	$email = $_POST['email'];
	$search_from = $_POST['search_from'];
	$username = $_POST['username'];
	$search_to = $_POST['search_to'];

	$new_search_from = $search_from.' '.'00:00:00';
	$new_search_to = $search_to.' '.'00:00:00';

	if(!empty($email))
	{
		$conditions['email'] = $email;
		$ref_sql = 'AND type="buyer"';
	}
	if(!empty($username))
	{
		$conditions['username'] = $username;
		$ref_sql = 'AND type="buyer"';
	}

	if((!empty($search_from)) && (!empty($search_to)))
	{
		if((empty($email)) && (empty($username)))
		{
			$endsql = "WHERE date BETWEEN '".$new_search_from."' AND '".$new_search_to."'";
			$ref_sql = 'AND type="buyer"';
		}
		else
		{
			$endsql = "AND entered BETWEEN '".$new_search_from."' AND '".$new_search_to."'";
			$ref_sql = 'AND user_type="buyer"';
		}
		
	}
	if((empty($search_from)) && (empty($search_to)) && (empty($email)) && (empty($username)))
	{
		$ref_sql = 'where type="buyer"';
	}

	$tables = array('pr_members');
	$members = $common->searchuser_byadmin($tables,'*',$conditions,$endsql,$ref_sql);

}
else
{

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 10;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = "";						
	$statement = "pr_members" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$members = $common->getpagirecords('pr_members','*',$conditions);
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
		<h1 style="padding-left:20px; padding-top:20px;">Manage Buyers</h1>
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Buyers</li>
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
							<label for="exampleInputEmail1">Email Adress</label>
							<input type="email" class="form-control" style="width:90%;" name="email" id="email" placeholder="Enter Email Adress">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Register From</label>
							<i class="fa fa-calendar"></i><input type="text" class="form-control" style="width:90%;" name="search_from" id="datepicker1" placeholder="01-01-2011">
						</div>
					</div>
					<div style="width:50%; float:right">
						<div class="form-group">
							<label for="exampleInputEmail1">Username</label>
							<input type="text" class="form-control" style="width:90%;" name="username" id="username" placeholder="Username">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Register To</label>
							<i class="fa fa-calendar"></i><input type="text" class="form-control" style="width:90%;" name="search_to" id="datepicker2" placeholder="01-01-2011">
						</div>
						<button type="submit" name="search" class="btn btn-primary" style="padding-left:25px; padding-right:25px;">Search</button>
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
									<!--th class="center"><input type="checkbox" id="checkAll" name="all_del"/></th-->
									<th style="text-align:center">S. No</th>
									<th style="text-align:center">First Name</th>
									<th style="text-align:center">Last Name</th>
									<th style="text-align:center">Email</th>
									<th style="text-align:center">Username</th>
									<th style="text-align:center">Created date</th>
									<th style="text-align:center">Status</th>
									<th style="text-align:center">Action</th>
									<th style="text-align:center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(!empty($members))
								{
									$k=$startpoint+1;
									foreach($members as $members)
									{
										$conditions = array('member'=>$members->id);							
										$info = $common->getrecord('pr_memberinfo','*',$conditions);
										?>
										<tr>
											<!--td class="center"><input type="checkbox" name="chk_del[]" value="<?php //echo $users->id; ?>"/></td-->
											<td style="text-align:center"><?php echo $k; ?>.</td>
											<td style="text-align:center"><?php echo $members->firstname; ?></td>
											<td style="text-align:center"><?php echo $members->lastname; ?></td>
											<td style="text-align:center"><?php echo $members->email; ?></td>
											<td style="text-align:center"><?php echo $members->username; ?></td>
											<td style="text-align:center">
												<?php 
													$data1 = $members->date; 
													$data2 = explode(" ",$data1);
													echo $data2[0]; 
												?></td>
											<td>
												<?php if($members->status==1) {?>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $members->id; ?>"/>
													<button type="submit" name="deactivate" class="btn btn-primary" style="width:100%">Activate</button>	
												</form>
												<?php } else{ ?>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $members->id; ?>"/>
													<button type="submit" name="activate" class="btn btn-danger" style="width:100%">Deactivate</button>	
												</form>
												<?php } ?>
											</td>
											<td>
												<form role="form" action="" method="post">
													<input type="hidden" name="id" value="<?php echo $members->id; ?>"/>
													<button class="btn btn-primary" style="width:100%" type="submit" name="remove">Remove</button>
												</form>
											</td>
											<td>
												<a class="fancybox" href="#inline<?php echo $members->id; ?>"><div style="color:#00A2B5; font-size:16px; text-align:center; padding-top:5px;">View Detail</div></a>
												<div id="inline<?php echo $members->id; ?>" style="width:100%; display: none; margin:auto; padding-top:5px;">
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">Address First</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->address1)) { echo $info->address1; } else {  ?>No Results Found<?php } ?></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">Address First</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->address2)) { echo $info->address2; } else {  ?>No Results Found<?php } ?></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">Address Second</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->postalcode)) { echo $info->postalcode; } else {  ?>No Results Found<?php } ?></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">Country</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->country)) { echo $info->country; } else {  ?>No Results Found<?php } ?></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">State</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->state)) { echo $info->state; } else {  ?>No Results Found<?php } ?></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px; font-weight:bold;">City</div>
													<div style="width:50%; float:right; text-align:center; font-size:16px; padding:5px;"><?php if(!empty($info->city)) { echo $info->city; } else {  ?>No Results Found<?php } ?></div>
												</div>  

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
<script type="text/javascript" src="../fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="../fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="../fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
