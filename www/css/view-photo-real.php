<?php include('include/config.php'); 

if(isset($_POST['addtocart']))
{
	$countcart = count($_SESSION['cart']);
	if( $countcart > 0 )
	{
		end($_SESSION['cart']); 
		$key = key($_SESSION['cart']);
		$_SESSION['cart'][$key+1]['type'] = $_POST['type'];
		$_SESSION['cart'][$key+1]['photo'] = $_POST['photo'];	
		$_SESSION['cart'][$key+1]['size'] = $_POST['size'];	
	}
	else
	{
		$_SESSION['cart'][0]['type'] = $_POST['type'];
		$_SESSION['cart'][0]['photo'] = $_POST['photo'];
		$_SESSION['cart'][0]['size'] = $_POST['size'];
	}
	$link = base64_encode($_POST['gallery']);
	if(!empty($_SESSION['app']['url']))
	{
		$common->redirect($_SESSION['app']['url']);
	}
	else
	{
		$common->redirect(APP_URL."photos.php?gallery=$link");
	}
}


if(isset($_GET['view']))
{	
	$id = base64_decode($_GET['view']);
	$conditions = array('id'=>$id);
	$view = $common->getrecord('pr_photos','*',$conditions);

	$conditionsseller = array('id'=>$view->seller);
	$sller = $common->getrecord('pr_seller','*',$conditionsseller);

	$conditionsnumber = array('gallery'=>$view->gallery);
	$number = $common->countrecords('pr_photos','*',$conditionsnumber);
}
else
{
	$common->redirect(APP_URL."photos.php");
}
if(empty($view))
{
	$common->redirect(APP_URL."photos.php");
}

$review = base64_decode($_GET['view']);
$conditions = array('photo'=>$review);
$reviews = $common->countreviews('pr_review',$conditions);

if(!empty($reviews->rating))
{
	$rating = $reviews->rating;
	$rows = $reviews->rows;
	$sum = $rating/$rows;

	$reviewcount = (int)$sum;
}

if(isset($_POST['printfile']))
{
	$conditions = array('id'=>$_POST['id']);
	$print = $common->getrecord('pr_photos','*',$conditions);
	?>
	<div id="divToPrint" style="display:none;">
		<img src="<?php echo APP_URL; ?>uploads/photos/real/<?php echo $print->printfile; ?>" style="width:100%; height:auto;" />  
	</div>
	<?php if($_POST['size'] == 'normal') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=800,height=800');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_FULL_URL; ?>";
				},4000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A3') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1000,height=1000');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_FULL_URL; ?>";
				},4000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A4') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1200,height=1200');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_FULL_URL; ?>";
				},4000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A5') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1400,height=1400');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_FULL_URL; ?>";
				},4000);
		</script>
	<?php } ?>
	<?php	
}
if(!empty($_GET['email']))
{
	$_SESSION['guast']['email'] = $_GET['email'];
}


?>

<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link href="css/dist/zoomify.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="efacts/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="efacts/demo.css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600' rel='stylesheet' type='text/css'>
	<script src="efacts/custom.js" type="text/javascript"></script>
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/style.css" />
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/css/jPages.css">
	<script src="pagination/js/jPages.js"></script>
	<script>
		function reloadPage()
		{
			setInterval(function(){
				location.href = "<?php echo APP_FULL_URL; ?>";
			},4000);
		}
	</script>
</head>
<body>
	<?php include('include/header.php'); ?>
<div class="facilities">
	<div class="space_header_view"></div>
	<div class="container">
		<div class="col-md-2 no-pading">
			<?php 
			if(!empty($sller->profilepicture))
			{
				?>
				<h3 class="tittle" style="margin-bottom: 12px; margin-top: 5px;"><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $sller->profilepicture; ?>" style="width:100px; height:80px; border-radius:50%;"/></h3> 
				<?php
			}
			else
			{
				?>
				<h3 class="tittle" style="margin-bottom: 12px; margin-top: 5px;"><img src="<?php echo APP_URL; ?>images/no-image.png" style="width:100px; height:80px; border-radius:50%;"/></h3> 
				<?php
			}
			?>	
		</div>
		<div class="col-md-2 no-pading">
			<h4 style="font-weight:bold; margin-top:25px; text-align:center">Photographer</h4>
			<h5 style="font-weight:bold; margin-top:5px; color:#00A2B5; text-align:center"><?php echo $sller->username; ?></h5>
		</div>
		<div class="col-md-5 no-pading">
			<div style="margin-top:25px; text-align:center;">
			<?php if(empty($reviewcount)) { ?>
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
			<?php } ?>
			<?php if($reviewcount == '1') { ?>
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
			<?php } ?>
			<?php if($reviewcount == '2') { ?>
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
			<?php } ?>
			<?php if($reviewcount == '3') { ?>
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
			<?php } ?>
			<?php if($reviewcount == '4') { ?>
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455617_Low rating.png" style="width:30px;" />
			<?php } ?>
			<?php if($reviewcount == '5') { ?>
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
				<img src="images/1457455623_Favourites.png" style="width:30px;" />
			<?php } ?>
			</div>
			<h5 style="font-weight:bold; margin-top:5px; color:#00A2B5; padding-left:0px; text-align:center">Reviews</h5>
		</div>
		<div class="col-md-3 no-pading" >
			<a href="photos.php"><h4 class="view_right" style="font-size:14px;" >View all Photos in </br>this Gallery</h4>
			<?php 
			$conditions = array('id'=>$view->gallery);
			$checklock = $common->getrecord('pr_galleries','*',$conditions) ;
			if(!empty($checklock->password)) 
			{
				if(!empty($_SESSION['guast']['email'])) 
				{
					?>
					<a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&lock=unlock&&email=<?php echo $_SESSION['guast']['email']; ?>"><h5 class="view_right1" style="text-align:center"><?php echo $number; ?> Photos</a></h5></a>
					<?php
				}
				else
				{
					?>
					<a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&lock=unlock"><h5 class="view_right1"><?php echo $number; ?> Photos</a></h5></a>
					<?php
				}
			}
			else
			{ 
				if(!empty($_SESSION['guast']['email'])) 
				{
					?>
					<a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&email=<?php echo $_SESSION['guast']['email']; ?>"><h5 class="view_right1"><?php echo $number; ?> Photos</a></h5></a>
					<?php
				}
				else
				{
					?>
					<a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>"><h5 class="view_right1"><?php echo $number; ?> Photos</a></h5></a>
					<?php
				}
			} 
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix" style="height:40px;"></div>
<div style="width:87%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
<div class="container">
	<?php 
	if(!empty($checklock->password)) 
	{ 
		if(!empty($_SESSION['guast']['email'])) 
		{
			?>
			<div style="margin-right:30px; margin-bottom:10px; float:right;"><a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&lock=unlock&&email=<?php echo $_SESSION['guast']['email']; ?>" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Go Back</a></div>
			<?php
		}
		else
		{
			?>
			<div style="margin-right:30px; margin-bottom:10px; float:right;"><a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&lock=unlock" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Go Back</a></div>
			<?php
		}
	}
	else
	{ 
		if(!empty($_SESSION['guast']['email'])) 
		{
			?>
			<div style="margin-right:30px; margin-bottom:10px; float:right;"><a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>&&email=<?php echo $_SESSION['guast']['email']; ?>" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Go Back</a></div>
			<?php 
		}
		else
		{
			?>
			<div style="margin-right:30px; margin-bottom:10px; float:right;"><a href="photos.php?gallery=<?php echo base64_encode($view->gallery); ?>" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Go Back</a></div>
			<?php
		}
	} 
	?>
	<div style="clear:both"></div>
	<div class="">
		<div class="col-md-6 no-pading" style="padding-top:15px;">
			<div class="row">
				<img src="<?php echo APP_URL; ?>uploads/photos/bigwatermark/<?php echo $view->webfile; ?>"id="img" alt="Curious Bino" class="img-thumbnail"> </div>
			<div style="float:left; padding:5px; font-weight:bold;">Photo Id : <?php echo $view->id; ?>,</div>
			<?php
			$date1 = $view->date;
			$date = explode(" ",$date1);
			?>
			<div style="padding:5px; font-weight:bold; margin-left:10px;">Upload Date : <?php echo $date[0]; ?></div>
			<div style="margin:12px;">
				<h4 style="font-weight:bold;"><?php echo $view->name; ?></h4>
			</div>
			<?php 
			if($view->webfileprice == '0.00' && $view->printfileprice == '0.00')
			{
				?>
				<div style="margin:15px;">
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">Web File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Free File For Download</h6>
					</div>
					<div>
						<form action="download.php"  method="post">
							<input type="hidden" name="id" value="<?php echo $view->id; ?>" />
							<button type="submit" name="downloadwebfile" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" onclick="reloadPage()";/>Download</button>
						</form>
					</div>
					<div style="height:15px;"></div>
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">Print File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Free File For Print</h6>
					</div>
					<div>
						<form action=""  method="post">
							<input type="hidden" name="id" value="<?php echo $view->id; ?>" />
							<input type="hidden" name="size" value="normal" />
							<button type="submit" name="printfile" style="padding:10px 45px 10px 45px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" onclick="PrintDiv();"/>Print File</button>
						</form>
					</div>
					<div style="height:15px;"></div>
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A3 Size Print File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Free File For Print</h6>
					</div>
					<div>
						<form action=""  method="post">
							<input type="hidden" name="id" value="<?php echo $view->id; ?>" />
							<input type="hidden" name="size" value="A3" />
							<button type="submit" name="printfile" style="padding:10px 45px 10px 45px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" onclick="PrintDiv();"/>Print File</button>
						</form>
					</div>
					<div style="height:15px;"></div>
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A4 Size Print File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Free File For Print</h6>
					</div>
					<div>
						<form action=""  method="post">
							<input type="hidden" name="id" value="<?php echo $view->id; ?>" />
							<input type="hidden" name="size" value="A4" />
							<button type="submit" name="printfile" style="padding:10px 45px 10px 45px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" onclick="PrintDiv();"/>Print File</button>
						</form>
					</div>
					<div style="height:15px;"></div>
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A5 SizePrint File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Free File For Print</h6>
					</div>
					<div>
						<form action=""  method="post">
							<input type="hidden" name="id" value="<?php echo $view->id; ?>" />
							<input type="hidden" name="size" value="A5" />
							<button type="submit" name="printfile" style="padding:10px 45px 10px 45px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" onclick="PrintDiv();"/>Print File</button>
						</form>
					</div>
					<div style="height:15px;"></div>
				</div>
				<?php
			}
			else
			{
				?>
				<div style="margin:10px;">
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">Web File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $<?php echo $view->webfileprice; ?> USD</h6>
					</div>
					<div>
						<?php if(!empty($_SESSION['account']['id'])) { ?>
						<form action=""  method="post">
							<input type="hidden" name="type" value="webfileprice" >
							<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
							<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
							<input type="hidden" name="size" value="nosize" >
							<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
						</form>
						<?php }elseif(!empty($_SESSION['guast']['email'])) { ?>
						<form action=""  method="post">
							<input type="hidden" name="type" value="webfileprice" >
							<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
							<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
							<input type="hidden" name="size" value="nosize" >
							<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
						</form>
						<?php }else{ ?>
							<div style="height:10px;"></div>
		<a href="<?php echo APP_URL; ?>log-in.php?redirecturl=view-photo.php?view=<?php echo base64_encode($view->id); ?>" style="padding:10px 20px 10px 20px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Buy Now</a>
						<?php } ?>
					</div>
				</div>
				<div style="clear:both; height:1px;"></div>
				<div style="margin:10px;">
					<div style="float:left; width:50%;">
						<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">Print File</h5>
						<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $<?php echo $view->printfileprice; ?> USD</h6>
					</div>
					<div>
						<?php if(!empty($_SESSION['account']['id'])) { ?>
						<form action=""  method="post">
							<input type="hidden" name="type" value="printfileprice" >
							<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
							<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
							<input type="hidden" name="size" value="nosize" >
							<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
						</form>
						<?php }elseif(!empty($_SESSION['guast']['email'])) { ?>
						<form action=""  method="post">
							<input type="hidden" name="type" value="printfileprice" >
							<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
							<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
							<input type="hidden" name="size" value="nosize" >
							<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
						</form>
						<?php }else{ ?>
							<div style="height:10px;"></div>
		<a href="<?php echo APP_URL; ?>log-in.php?redirecturl=view-photo.php?view=<?php echo base64_encode($view->id); ?>" style="padding:10px 20px 10px 20px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Buy Now</a>
						<?php } ?>
					</div>
				</div>
				<div style="clear:both; height:1px;"></div>
				<?php if($view->printfilepricea3 != '0.00') { ?>
					<div style="margin:10px;">
						<div style="float:left; width:50%;">
							<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A3 Size Print File</h5>
							<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $<?php echo $view->printfilepricea3; ?> USD</h6>
						</div>
						<div>
							<?php if(!empty($_SESSION['account']['id'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<input type="hidden" name="size" value="A3" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }elseif(!empty($_SESSION['guast']['email'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="size" value="A3" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }else{ ?>
								<div style="height:10px;"></div>
			<a href="<?php echo APP_URL; ?>log-in.php?redirecturl=view-photo.php?view=<?php echo base64_encode($view->id); ?>" style="padding:10px 20px 10px 20px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Buy Now</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<div style="clear:both; height:1px;"></div>
				<?php if($view->printfilepricea4 != '0.00') { ?>
					<div style="margin:10px;">
						<div style="float:left; width:50%;">
							<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A4 Size Print File</h5>
							<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $<?php echo $view->printfilepricea4; ?> USD</h6>
						</div>
						<div>
							<?php if(!empty($_SESSION['account']['id'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<input type="hidden" name="size" value="A4" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }elseif(!empty($_SESSION['guast']['email'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<input type="hidden" name="size" value="A4" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }else{ ?>
								<div style="height:10px;"></div>
			<a href="<?php echo APP_URL; ?>log-in.php?redirecturl=view-photo.php?view=<?php echo base64_encode($view->id); ?>" style="padding:10px 20px 10px 20px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Buy Now</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<div style="clear:both; height:1px;"></div>
				<?php if($view->printfilepricea5 != '0.00') { ?>
					<div style="margin:10px;">
						<div style="float:left; width:50%;">
							<h5 style="padding-left:10px;padding-bottom:5px; font-weight:bold;">A5 Size Print File</h5>
							<h6 style="padding-left:10px;padding-bottom:5px; color:red;">Price : $<?php echo $view->printfilepricea5; ?> USD</h6>
						</div>
						<div>
							<?php if(!empty($_SESSION['account']['id'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<input type="hidden" name="size" value="A5" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }elseif(!empty($_SESSION['guast']['email'])) { ?>
							<form action=""  method="post">
								<input type="hidden" name="type" value="printfileprice" >
								<input type="hidden" name="photo" value="<?php echo $view->id; ?>" >
								<input type="hidden" name="gallery" value="<?php echo $view->gallery; ?>" >
								<input type="hidden" name="size" value="A5" >
								<button type="submit" name="addtocart" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;"/>Buy Now</button>
							</form>
							<?php }else{ ?>
								<div style="height:10px;"></div>
			<a href="<?php echo APP_URL; ?>log-in.php?redirecturl=view-photo.php?view=<?php echo base64_encode($view->id); ?>" style="padding:10px 20px 10px 20px; background-color:#43ace5; color:#fff; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Buy Now</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php
			}
			?>
			
		</div>
		<div class="col-md-6 features-right" style="padding-top: 15px;">
			<?php
			$conditions = array('gallery'=>$view->gallery);
			$list = $common->getrecords('pr_photos','*',$conditions) ;
			if(!empty($list))
			{
				foreach($list as $list)
				{
					?>
					<div style="padding-left:10px; padding-right:10px; width:33%; float:left">
						<a href="view-photo.php?view=<?php echo base64_encode($list->id); ?>"><img src="uploads/photos/watermark/<?php echo $list->webfile; ?>" style="width:100%;"/></a>
						<?php 
						if($list->webfileprice == '0.00')
						{
							?>
							<div style="font-size:13px; margin-left:7px; font-weight:bold; margin-top:px; margin-bottom:10px;">$0.00 USD</div>
							<?php
						}
						else
						{
							?>
							<div style="font-size:13px; margin-left:7px; font-weight:bold; margin-top:px; margin-bottom:10px;">$<?php echo $list->webfileprice; ?> USD</div>
							<?php
						}
						?>
						<div style="clear:both"></div>
					</div>
					<?php
				}
	
			}
			?>
		</div>
		<div style="clear:both; height:10px;"></div>
		
		<div class="clearfix"></div>
	</div>

</div>
<div class="clearfix" style="height:40px;"></div>
	<?php include('include/footer.php') ?>
	<?php include('include/foot.php') ?>
	<script src="css/dist/zoomify.js"></script>
	<script>
		$('#img').zoomify();
	</script>
</body>
</html>
