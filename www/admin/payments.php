<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

if(isset($_POST['remove']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->delete('pr_gallery',$conditions))
	{
		$common->redirect(APP_URL."gallery.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['activate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->activategallery('pr_gallery',$conditions))
	{
		$common->redirect(APP_URL."gallery.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}
if(isset($_POST['deactivate']))
{
	$conditions = array('id'=>$_POST['id']);
	if($common->deactivategallery('pr_gallery',$conditions))
	{
		$common->redirect(APP_URL."gallery.php");
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
	}
	if(!empty($username))
	{
		$conditions['username'] = $username;
	}

	if((!empty($search_from)) && (!empty($search_to)))
	{
		if((empty($email)) && (empty($username)))
		{
			$endsql = "WHERE date BETWEEN '".$new_search_from."' AND '".$new_search_to."'";
		}
		
	}

	$tables = array('pr_payments');
	$payments = $common->searchuser_byadmin($tables,'*',$conditions,$endsql,$ref_sql);

}
else
{
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 10;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = "";						
	$statement = "pr_payments" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$payments = $common->getpagirecords('pr_payments','*',$conditions);
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
		<h1 style="padding-left:20px; padding-top:20px;">Manage Payments</h1>
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Payments</li>
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
							<label for="exampleInputEmail1">Payment From</label>
							<i class="fa fa-calendar"></i><input type="text" class="form-control" style="width:90%;" name="search_from" id="datepicker1" placeholder="01-01-2011">
						</div>
					</div>
					<div style="width:50%; float:right">
						<div class="form-group">
							<label for="exampleInputEmail1">Payment To</label>
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
									<th style="text-align:center">S. No</th>
									<th style="text-align:center">Buyer</th>
									<th style="text-align:center">Photographer</th>
									<th style="text-align:center">TXN Id</th>
									<th style="text-align:center">Amount</th>
									<th style="text-align:center">Type</th>
									<th style="text-align:center">Status</th>
									<th style="text-align:center">Date</th>
									<th style="text-align:center">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if(!empty($payments))
								{
									$k=$startpoint+1;
									foreach($payments as $payments)
									{

									$conditionsseller = array('id'=>$payments->buyer);
									$buyer = $common->getrecord('pr_members','*',$conditionsseller);

									$conditionsseller = array('id'=>$payments->photographer);
									$seller = $common->getrecord('pr_seller','*',$conditionsseller);
										?>
										<tr>
											<td style="text-align:center"><?php echo $k; ?>.</td>
											<td style="text-align:center"><?php if(!empty($buyer->username)) { echo $buyer->username; }else{ ?>No Detail<?php } ?></td>
											<td style="text-align:center"><?php echo $seller->username; ?></td>
											<td style="text-align:center"><?php echo $payments->txnid; ?></td>
											<td style="text-align:center">$ <?php echo $payments->amount; ?> USD</td>
											<td style="text-align:center"><?php echo $payments->type; ?></td>
											<td style="text-align:center"><?php echo $payments->payment; ?></td>
											<td style="text-align:center">
											<?php 
												$data1 = $payments->date; 
												$data2 = explode(" ",$data1);
												echo $data2[0]; 
											?></td>
											<td>
												<a class="fancybox" href="#inline<?php echo $payments->id; ?>"><div style="color:#00A2B5; font-size:14px; text-align:center;">View Detail</div></a>
												<div id="inline<?php echo $payments->id; ?>" style="width:100%; display: none; margin:auto; padding-top:5px;">
													<div style="width:100%; text-align:center; font-weight:bold; font-size:18px;">Buyer</div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Username</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php if(!empty($buyer->username)) { echo $buyer->username; }else{ ?>No Detail<?php } ?></div>
													<div style="clear:both"></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Email Address</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php if(!empty($buyer->email)) { echo $buyer->email; }else{ ?>No Detail<?php } ?></div>
													<div style="clear:both; height:10px;"></div>


													<div style="width:100%; text-align:center; font-weight:bold; font-size:18px;">Photographer</div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Username</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php echo $seller->username; ?></div>
													<div style="clear:both"></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Email Address</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php echo $seller->email; ?></div>
													<div style="clear:both"></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Bank Name</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php echo $seller->bankname; ?></div>
													<div style="clear:both"></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Bank Owner Name</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php echo $seller->owner_name; ?></div>
													<div style="clear:both"></div>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Bank Number</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;"><?php echo $seller->banknumber; ?></div>
													<div style="clear:both"></div>
											<?php
											$payment1 = $payments->amount;
											$payment2 = $payment1/100;
											$payment3 = $payment2*90;
											?>
													<div style="width:50%; float:left; text-align:center; font-size:16px; padding:5px;">Amount 90%</div>
													<div style="width:50%; float:right; text-align:center; font-size:15px; padding:5px;">$ <?php echo $payment3; ?> USD</div>
													<div style="clear:both; height:10px;"></div>
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
										<td colspan="8">No records found</td>  
									</tr>
									<?php
								}
								?>
							</tfoot>
							
						</table>
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
				</div>
			</section>
		</div>
	</section>
</div>      
<?php include('include/footer.php') ?>
</body>
</html>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="../fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="../fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
