<?php include('include/config.php'); 


if(isset($_GET['galleryy']))
{
	$id = $_GET['gallery'];	
	//$conditions = array('id'=>$id, 'password'=>$_GET['password']);
	//$check = $common->getrecord('pr_galleries','*',$conditions);

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 16;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = " WHERE id = '".$id."' and password = '".$_GET['password']."'";						
	$statement = "pr_galleries" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$check = $common->getpagirecords('pr_galleries','*',$conditions);


	$id2 = base64_encode($id);
	if(!empty($check))	
	{
		$_SESSION['gallery']['id'] = $check->id;
		$common->redirect(APP_URL."photos.php?gallery=$id2&&lock=unlock");
	}
	else
	{
		$common->add('e', 'Password not matched.');	
		$common->redirect(APP_URL."galleries.php");
	}
}


elseif(isset($_GET['search']))
{
	//$conditions = array('name'=>$_GET['searchinput'], 'status'=>'1');
	//$galleries = $common->getsearch('pr_galleries','*',$conditions) ;

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 16;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = " WHERE status = '1' and name = '".$_GET['searchinput']."' and password = ''";						
	$statement = "pr_galleries" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$galleries = $common->getpagirecords('pr_galleries','*',$conditions);

}
else
{
	//$conditions = array('status'=>'1');
	//$galleries = $common->getrecords('pr_galleries','*',$conditions) ;

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 16;
	$startpoint = ($page * $limit) - $limit;					
	$conditions = " WHERE status = '1' and password = ''";						
	$statement = "pr_galleries" . $conditions;						
	$conditions .= " LIMIT {$startpoint} , {$limit}";	
	$galleries = $common->getpagirecords('pr_galleries','*',$conditions);
}

if(!empty($_GET['email']))
{
	$_SESSION['guast']['email'] = $_GET['email'];
}
if(!empty($_GET['email']))
{
	if(empty($_SESSION['account']['id']))
	{
		$_SESSION['guast']['email'] = $_GET['email'];
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link rel="stylesheet" type="text/css" href="css/shade.css" />
	<link rel="stylesheet" type="text/css" href="css/shade/component.css" />
	<script src="css/shade/modernizr.custom.js"></script>
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/style.css" />
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/css/jPages.css">
	<script src="pagination/js/jPages.js"></script>
	<script>
	$(function(){
		$("div.holder").jPages({
		containerID  : "itemContainer",
		perPage      : 16,
		startPage    : 1,
		startRange   : 1,
		midRange     : 2,
		endRange     : 1
		});

		});
	</script>
	<style>
		.img-height:{}
	</style>
</head>
<body>
<?php include('include/header.php'); ?>
<div class="banner-bottom"  style="background-color:#F3F3F3">
	<div class="container">
		<div class="banner-info space_for_photo">
			<div id="custom-search-input">
				<form  action=""  method="get" style="width:100%">
					<div class="input-group col-md-12" style="padding:0px;">
						<?php /*<input type="text" class="search-query form-control " placeholder="Find the perfect Photos,vector and more...." style="color:#333; border-radius:0px; height:60px;" required="required" name="searchinput"/>
						<span class="input-group-btn">
							<button class="btn btn-danger" type="submit" style="padding: 19px 22px !important; border-radius:0px;" name="search">
								<span class=" glyphicon glyphicon-search"></span>
							</button>
						</span>*/ ?>
						<div style="50px;"></div>
					</div>
				</form>
			</div>
		</div>
		<div style="width:98%; margin:auto;">
			<?php
				if(!empty($_SESSION['flash_messages']))
				{	
					echo $msgs->display();
				}	
			?>
		</div>
		<div style="height:20px;"></div>
		<div class="blog-section">
		<div class="blog-posts">
		<div class="blog-top" id="itemContainer">
		<?php
		if(!empty($galleries))
		{
			foreach($galleries as $gallery)
			{
				$conditions = array('gallery'=>$gallery->id, 'status'=>'1');
				$photo = $common->getrecord('pr_photos','*',$conditions) ;
				//echo"<pre>";
				//print_r($photo);
				//echo"</pre>";
			?>
				<div class="col-md-3" style="padding: 0px 10px 0px 10px; margin-bottom:8px;">
					<div class="bottom-grids">
						<div class="demo-3">
							<ul class="grid cs-style-3" style="padding: 0px 0px 0px;">
								<li style="padding: 0px; border-bottom:35px solid #fff; border-top:15px solid #fff; border-left:5px solid #fff; border-right:5px solid #fff;" class="full_width_image">
									<?php if($_SESSION['gallery']['id'] == $gallery->id) { ?>
									<figure>
										<div class="tj_wrapper">
											<ul class="tj_gallery" style="margin-bottom: -5px;">
												<?php if(!empty($photo)) { ?>
													<li style="list-style:none; width:100%;"><a href="view-photo-test.php?view=<?php echo base64_encode($photo->id); ?>"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" style="width:100%; min-height:200px; max-height:200px;" alt="img06"></a></li>
												<?php }else{ ?>
													<li style="list-style:none; width:100%;"><a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>&&lock=unlock"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" style="width:100%; min-height:200px; max-height:200px;" alt="img06"></a></li>
												<?php } ?>
											</ul>
										</div>
										<figcaption>
											<span><?php echo $gallery->name; ?></span>
											<?php if(!empty($photo)) { ?>
												<a href="view-photo-test.php?view=<?php echo base64_encode($photo->id); ?>">View</a>
											<?php }else{ ?>
												<a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>&&lock=unlock">View</a>
											<?php } ?>
										</figcaption>
									</figure>
									<?php }else{ ?>
									<figure>
										<div class="tj_wrapper">
											<ul class="tj_gallery" style="margin-bottom: -5px;">
												<?php if(!empty($photo)) { ?>
													<li style="list-style:none; width:100%;"><a href="view-photo-test.php?view=<?php echo base64_encode($photo->id); ?>"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" style="width:100%; min-height:200px; max-height:200px;" alt="img06"></a></li>
												<?php }else{ ?>
													<li style="list-style:none; width:100%;"><a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>"><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gallery->image; ?>" style="width:100%; min-height:200px; max-height:200px;" alt="img06"></a></li>
												<?php } ?>
											</ul>
										</div>
										<figcaption>
											<span><?php echo $gallery->name; ?></span>
												<?php if(!empty($photo)) { ?>
													<a href="view-photo-test.php?view=<?php echo base64_encode($photo->id); ?>">View</a>
												<?php }else{ ?>
													<a href="photos.php?gallery=<?php echo base64_encode($gallery->id); ?>">View</a>
												<?php } ?>
										</figcaption>
									</figure>
										<?php 
										}
									?>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<?php
			}
		}
		else
		{
			?>
			<div style="width:100%">
				<div style="margin:auto; width:12%;"><img src="images/no-record.png" style="width:100%"/></div>
				<div style="font-size:18px; font-weight:bold; text-align:center">Not Found any Result</div>
			</div>
			<?php
		}
		?>
		</div></div</div>
		<div style="clear:both"></div>
		<div style="float:right">
			<?php echo $common->pagination($statement,$limit,$page); ?>
		</div>
		</div>
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
