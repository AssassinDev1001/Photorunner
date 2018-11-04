<title>Photo Runner</title>
	<!-- for-mobile-apps -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
	function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- //for-mobile-apps -->
	<link href="<?php echo APP_URL; ?>css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo APP_URL; ?>css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/swipebox.css">

	<link rel="icon" href="<?php echo APP_URL; ?>images/pfavicon.png" type="image/png" sizes="16x16">

	<script src="<?php echo APP_URL; ?>js/jquery-1.11.1.min.js"></script>
	<script src="<?php echo APP_URL; ?>js/modernizr.custom.js"></script>
	<link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
	<!-- start-smoth-scrolling -->
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/move-top.js"></script>
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/easing.js"></script>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>search-input/base.css" />
	<link rel="stylesheet" href="<?php echo APP_URL; ?>search-input/style.css" />
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
			});
		});
	</script>
	
<!-- start-smoth-scrolling -->
<script>
	$(window).on("scroll", function() {
	if ($(this).scrollTop() > 10) {
	   $(".header").css("background","#fff");
		$(".navbar-default .navbar-nav > li > a").css("color","#333");
		 $(".header_toping").css("border-bottom","1px solid #868686");
		
	}
	else {
	   $(".header").css("background","#fff");
		$(".navbar-default .navbar-nav > li > a").css("color","#333");
		 $(".header_toping").css("border-bottom","1px solid #868686");
		
	}
	});

	$(document).ready(function(){


	$("[data-toggle=tooltip]").tooltip();
	});
</script>
<style>
.abcd {
    background-image: url("images/logo.png");
    background-size:100%;
    width:100%;
}
</style>
<?php
	if(isset($_POST['currency']))
	{
		$_SESSION['currency'] = $_POST['currency'];	
	}
	if(empty($_SESSION['currency']))
	{
		$_SESSION['currency'] = 'USD';
	}
?>
<style>
.dropbtn {
	color: #000000;
	font-size: 14px;
	border: none;
	cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.dropdown-content-button{
	color: black;
	padding: 12px 16px;
	text-decoration: none;
	display: block;
	border:0px;
	background-color:#fff;
	width:100%;
}

.dropdown:hover .dropdown-content {
    display: block;
}

</style>

