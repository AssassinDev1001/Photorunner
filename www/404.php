<?php include('include/config.php'); 


if(!empty($_GET['email']))
{
	if(empty($_SESSION['account']['id']))
	{
		$_SESSION['guast']['email'] = $_GET['email'];
	}
}
if(!empty($_GET['email']))
{
	$_SESSION['app']['url'] = APP_FULL_URL;
}

if(isset($_POST['open']))
{
	$id = $_POST['gallery1'];	
	$conditions = array('id'=>$id, 'password'=>$_POST['password']);
	$check = $common->getrecord('pr_galleries','*',$conditions);

	$id2 = base64_encode($id);
	if(!empty($check))	
	{
		$_SESSION['gallery']['id'] = $id;
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->add('e', 'Password not matched.');	
		$common->redirect(APP_FULL_URL);
	}
}

elseif(isset($_GET['gallery']))
{
	if(isset($_GET['gallery']) && isset($_GET['lock']))
	{
		$gallery = base64_decode($_GET['gallery']);
		$conditions = array('gallery'=>$gallery, 'status'=>'1');
		$photo1 = $common->getrecords('pr_photos','*',$conditions) ;
	}
	else
	{
		$gallery = base64_decode($_GET['gallery']);
		$conditions = array('gallery'=>$gallery, 'status'=>'1');
		$photo = $common->getrecords('pr_photos','*',$conditions) ;
	}
}
elseif(isset($_GET['search']))
{
	$conditions = array('name'=>$_GET['searchinput'], 'status'=>'1');
	$photo = $common->getsearch('pr_photos','*',$conditions) ;
}
else
{
	$conditions = array('status'=>'1');
	$photo = $common->getrecords('pr_photos','*',$conditions) ;
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
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
</head>
<body>
<?php include('include/header.php'); ?>
<div style="background-color:#f2f2f2">
	<div class="banner-bottom" style="background-color:#f2f2f2">
		<div class="container">
			<div class="banner-info space_for_photo">
				<div id="custom-search-input">
				</div>
			</div>
		</div>
		<div class="container">
			<div style="text-align:center;"><img src="http://www.photorunner.no/images/Not-Found-404-Orange.png" /></div>	
		</div>
	</div>
</div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>
