<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');


$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 10;
$startpoint = ($page * $limit) - $limit;					
$conditions = " WHERE member = '".$_SESSION['account']['id']."' ORDER by id DESC ";			
$statement = "pr_favourite" . $conditions;						
$conditions .= " LIMIT {$startpoint} , {$limit}";	
$favourite = $common->getpagirecords('pr_favourite','*',$conditions);

?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<script>
		function dels(idd)
		{
		if(confirm("Are you sure you want to delete this record?"))
			{
				
				$.ajax({
					type: "GET",
					url: "deletefavourite_ajax.php",
					data:"delid="+idd,
					success: function(data){
						$('.favourite1'+idd).fadeOut("slow");	
					 	$( '#show_stared132' ).html( data );
						return false;
					},
				 
				});

			}
		}
	</script>
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div style="width:100%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
	<div class="col-md-12 features features-right" style="margin:20px 0; padding:0 0 0 20px;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="margin:20px;">
				<?php
				if(!empty($favourite))
				{
					foreach($favourite as $favourite)
					{
					$conditionsphoto = array('id'=>$favourite->photo);
					$photo = $common->getrecord('pr_photos','*',$conditionsphoto);

					$conditionsseller = array('id'=>$photo->seller);
					$seller = $common->getrecord('pr_seller','*',$conditionsseller);
					?>
						<div class="favourite1<?php echo $favourite->id; ?>" id=""style="border:2px solid #33b5e5; float:left; width:100%;">
							<div>
								<div class="col-md-3" style="padding:10px;"><img src="<?php echo APP_URL; ?>uploads/photos/bigwatermark/<?php echo $photo->webfile; ?>" style="width:200px; height:180px;" /></div>
								<div class="col-md-7" style="padding:10px;">
									<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Product Name</div>
									<div style="font-size:15px; padding:5px;">: <?php echo $photo->name; ?></div>
									<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Qty</div>
									<div style="font-size:15px; padding:5px;">: 1</div>
									<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Price</div>
									<div style="font-size:15px; padding:5px;">: $ <?php echo $photo->webfileprice; ?> USD</div>
									<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">photographer</div>
									<div style="font-size:15px; padding:5px;">: <?php echo $seller->username; ?></div>
								</div>
								<div class="col-md-2" style="padding:10px;">
									<div style="color:#00A2B5; text-align:center; padding-top:38px;"><a href="javascript:void();" onclick="dels('<?php echo $favourite->id; ?>')"><img src="<?php echo APP_URL; ?>images/delete.png" style="width:100px; height:100px;" /></a></div>
								</div>
							</div>
						</div>
						<div style="clear:both; height:20px;"></div>
					<?php
					}
				}
				else
				{
				?>
				<div style="margin:30px;">
					<div style="width:150px; margin:auto;"><img src="<?php echo APP_URL; ?>images/noproduct.png" style="width:100%; height:auto;" /></div>
					<div style="text-align:center;font-size: 18px;font-weight: bold;">No Any Photo Available in Your Favorite List</div>
				</div>
				<?php
				}
				?>
				<div style="clear:both; height:20px;"></div>
			</div>
			<div style="float:right">
				<?php echo $common->pagination($statement,$limit,$page); ?>
			</div>
		</div>
		
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>

