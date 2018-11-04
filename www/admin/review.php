<?php 
include('include/config.php');
include(APP_ROOT."include/check-login.php");

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

	$tables = array('pr_review');
	$review = $common->searchuser_byadmin($tables,'*',$conditions,$endsql,$ref_sql);

}
else
{
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 10;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = "";						
	$statement = "pr_review" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$review = $common->getpagirecords('pr_review','*',$conditions);
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
		<h1 style="padding-left:20px; padding-top:20px;">Reviews</h1>
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Reviews</li>
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
							<label for="exampleInputEmail1">Review From</label>
							<i class="fa fa-calendar"></i><input type="text" class="form-control" style="width:90%;" name="search_from" id="datepicker1" placeholder="01-01-2011">
						</div>
					</div>
					<div style="width:50%; float:right">
						<div class="form-group">
							<label for="exampleInputEmail1">Review To</label>
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
									<th style="text-align:center">Buyer Email Address</th>
									<th style="text-align:center">Photo</th>
									<th style="text-align:center">Rating Star</th>
									<th style="text-align:center">Buyer Comment</th>
									<th style="text-align:center">Date</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if(!empty($review))
								{
									$k=$startpoint+1;
									foreach($review as $review)
									{

									$conditionsseller = array('id'=>$review->photo);
									$photo = $common->getrecord('pr_photos','*',$conditionsseller);

										?>
										<tr>
											<td style="text-align:center"><?php echo $k; ?>.</td>
											<td style="text-align:center"><?php echo $review->buyer_email; ?></td>
											<td style="text-align:center"><?php echo $photo->name; ?></td>
											<td style="text-align:center"><?php echo $review->rating; ?> Star</td>
											<td style="text-align:center"><?php echo $review->review; ?></td>
											<td style="text-align:center">
											<?php 
												$data1 = $review->date; 
												$data2 = explode(" ",$data1);
												echo $data2[0]; 
											?></td>
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
