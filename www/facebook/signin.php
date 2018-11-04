<?php
include('config/config.php');
include('code/signin.code.php');


$_SESSION['gog_email'] = $_SESSION['google_data']['email'];
$gog_email = mysql_fetch_assoc(mysql_query("select * from schol_registration where email = '".$_SESSION['gog_email']."'"));

if($_GET['confirm_id']!="")
{
	mysql_query("update schol_book_online_courses set confirm = '1' where id='".$_GET['confirm_id']."'");
}

$lang_arabic = mysql_fetch_assoc(mysql_query("select * from db_login_form where lang_id='".$_SESSION['flag_id']."' and status='1'"));
unset($_SESSION['google_data']);
unset($_SESSION['token']);
unset($_SESSION['gog_email']);

unset($_SESSION['fb_id']);
unset($_SESSION['fb_username']);
unset($_SESSION['fb_email']);
unset($_SESSION['fb_locale']);
unset($_SESSION['fb_picture']);


include('google_index1.php');

if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'facebook') {
        header("Location: check-facebook.php");
    }
}

?>
<!DOCTYPE html>
<html>
	<head>
	
	   <?php include('include/head.php'); ?>
	 <script>
		$(document).ready(function(){
		$(".close1").click(function(){
        $(".close_green1").hide(700);
    });
	});
	</script>
<style type="text/css">
        @import url(http://fonts.googleapis.com/earlyaccess/droidarabickufi.css);

        .value_wrapper
        {
        	height: 125px !important;
        }
        .niceselect p
        {
        	padding: 22px !important;
        }
        .select_style
		{
			border: none !important;box-shadow: none !important;
		}
    </style>

<script src="js/nice_select.js"></script>
<link href="css/nice_select.css" rel="stylesheet" type="text/css"/>
<link href="css/nice_select.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" >
$('document').ready(function(){
    $('.change').niceselect();
})
</script>

		</head>
		<body>
			<div id="warpper">
				<?php include('include/header.php'); ?>

<div id="main_container">
<div class="fg_pswrd">

	<div class="reset_ur_pswrd arabic_direction" <?php if($_SESSION['flag_id']=='12') { echo "dir='rtl'"; }?>>
			<?php
        $chmk25 = mysql_fetch_assoc(mysql_query("select * from page_topic where heading='25' and language_id='".$_SESSION['flag_id']."' and status='1'"));
        echo html_entity_decode($chmk25['topic']);
      ?>
	</div>


		<div style="clear:both"></div>
		<?php
			if($_GET['gog_err'] != "")
			{
				$mug='<div class="code_lt_bck close_green1">
		<div class="code_lt close1">
			<img src="images/went.png" title="Close" class="wid_100"/>
		</div>
		<div class="code_wrg_ps">
			Please Sign Up First. <span class="code_bck_col">And Try again</span>
		</div>
		<div class="code_lt close1">
			<img src="images/went23.png" title="Close" class="wid_100"/>
		</div>
			<div style="clear:both;"></div>
	</div>
		<div class="code_lt_ht"></div>';
		
			}
		?>

		<?php
			if($_GET['signup_err'] != "")
			{
				$mug='<div class="code_lt_bck close_green1">
		<div class="code_lt close1">
			<img src="images/went.png" title="Close" class="wid_100"/>
		</div>
		<div class="code_wrg_ps">
			Already Have An Account. <span class="code_bck_col">Please Sign in From Below</span>
		</div>
		<div class="code_lt close1">
			<img src="images/went23.png" title="Close" class="wid_100"/>
		</div>
			<div style="clear:both;"></div>
	</div>
		<div class="code_lt_ht"></div>';
		
			}
		?>
		<?php echo $mug; ?>
	<div class="wid_57_auto">
<div class="wid_100">
	<div class="wid_100">
		<div><?php //echo $mug; ?></div>

					<!-- Start Formoid form-->
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>/formoid_files/formoid1/formoid-solid-green.css" type="text/css" />
	<script type="text/javascript" src="<?php echo SITE_URL; ?>/formoid_files/formoid1/jquery.min.js"></script>
	<form class="formoid-solid-green" style="width:100%;" method="post">
		<?php //echo $msg; ?>
		<?php// echo $msg1; ?>
		<input type="hidden" name="montreal" id="montreal" value="<?php echo $_REQUEST['brampton']; ?>" />
		<div class="element-input">
			<label class="title"></label>
			<div class="item-cont">
				<select class="large arabic_input change rever_arab_txt"  name="user_type" style="height:70px;" required="required">
					<option value=""><?php echo $lang_arabic['utype']; ?></option>
					<option value="simple_user" <?php if($gog_email['user_type']=='simple_user') { echo "selected='selected'"; }?>>
					<?php echo $lang_arabic['student']; ?>
					</option>
					<option value="tutor">
					<?php echo $lang_arabic['tutor']; ?>
					</option>
				</select>
					<span class="icon-place"></span>
			</div>
		</div>
		<div class="element-input" style="margin-top:-8px;">
			<label class="title"></label>
			<div class="item-cont">
				<input class="large arabic_input" type="email" name="email" style="height:70px;background-color:#fff;" value="<?php echo $gog_email['email']; ?>" required="required" placeholder="<?php echo $lang_arabic['email']; ?>" />
					<span class="icon-place"></span>
			</div>
		</div>
		<div class="element-Email" style="margin-top:-15px;">
			<label class="title"></label>
				<div class="item-cont">
					<input class="large arabic_input" type="password" name="password" style="height:70px;" value="" required="required" placeholder="<?php echo $lang_arabic['password']; ?>"/>
						<span class="icon-place"></span>
				</div>
		</div>
		<div style="width:92%;" class="schol_res21">
			<div class="wid_100">
				<!--div style="width:30%;float:left;">
					<div class="wid_100">
						<div style="width:10%;float:left;border:1px solid">
							<input type="checkbox" name="" value="" style="width:100%"/>
						</div>
						<div style="width:80%;float:left;color:#02adef;margin-left:10px;">
							Remember me
						</div>
							<div style="clear:both"></div>
				</div>
					</div>
						<div style="clear:both"></div>
				</div-->
				
				
					<div class="signin_30 arabic_direction" <?php if($_SESSION['flag_id']=='12') { echo "dir='rtl'"; }?>>
						<a href="forgot_password.php" style="text-decoration:underline;color:#4c70b7;">	
					<?php
        $chmk26 = mysql_fetch_assoc(mysql_query("select * from page_topic where heading='26' and language_id='".$_SESSION['flag_id']."' and status='1'"));
        echo html_entity_decode($chmk26['topic']);
      ?>
						</a>
					</div>
					

					
			
	

						<div style="clear:both"></div>
			</div>
		</div>
		
		
		<!-- a href="dashmyacountpage" --><div class="submit" style="width:92%;margin:auto;"><input style="font-family: "Droid Arabic Kufi";" type="submit" name="submit" value="<?php echo $lang_arabic['btn_name']; ?>" class="fg_pswrd_sbt"/></div>
	</form>
	
	<p class="frmd"><a href="http://formoid.com/v28">bootstrap form</a> Formoid.com 2.8</p><script type="text/javascript" src="<?php echo SITE_URL; ?>/formoid_files/formoid1/formoid-solid-green.js"></script>

		
</div>
	<div style="clear:both"></div>
<div class="schol_res22 dushman4">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- uder-dashboard -->
<ins class="adsbygoogle schol_res23"
     data-ad-client="ca-pub-4390970243552938"
     data-ad-slot="1327917802"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<div class="wid_100">
	<div class="wid_92_auto">
		<div class="signin_or">
			<div class="signin_line"></div>
			<div class="signin_ortxt">
				<?php
        $chmk27 = mysql_fetch_assoc(mysql_query("select * from page_topic where heading='27' and language_id='".$_SESSION['flag_id']."' and status='1'"));
        echo html_entity_decode($chmk27['topic']);
      ?>
			</div>
			<div class="signin_line"></div>
				<div style="clear:both"></div>
		</div>
			<div style="clear:both"></div>
		<div class="wid_100_mar_20">
			<?php
        $immk28 = mysql_fetch_assoc(mysql_query("select * from social_link_images where country='signin_facebook' and language_id='".$_SESSION['flag_id']."' and status='1'"));
      ?>
				<a href="?login&oauth_provider=facebook">
				
					<img alt="facebook scholme registration" src="<?php echo SITE_URL; ?>/images/<?php echo $immk28['pic']; ?>" class="course_vid_img">
				
					
				
				</a>
			
			
				<!--a href="?login&oauth_provider=twitter">
				<?php
					//if($_SESSION['flag_id']=='12')
					//{
				?>
					<img alt="twitter scholme registration" src="<?php echo SITE_URL; ?>/images/SIGNIN-T.png" class="course_vid_img">
				<?php
					//}
					//else
					//{
				?>
					<img alt="twitter scholme registration" src="<?php echo SITE_URL; ?>/images/SIGNIN-T.png" class="course_vid_img">
				<?php
					//}
				?>
				</a-->
			
			
		<?php
        $immk27 = mysql_fetch_assoc(mysql_query("select * from social_link_images where country='signin_google_plus' and language_id='".$_SESSION['flag_id']."' and status='1'"));
      ?>					
	<a href="<?php echo $authUrl; ?>">
		<img alt="google scholme registration" src="images/<?php echo $immk27['pic']; ?>" class="wid_100 ht_70"/>
	</a>
	
	
		
		
	</div>	
			<div style="clear:both"></div>
		<div class="fg_pswrd_img">
			<img alt="scholme dashboard logo" src="images/sign-in-pages-logo.png" class="wid_100"/>
		</div>
	</div>
		<div style="clear:both"></div>
</div>

</div>
			<div style="clear:both"></div>
	</div>
</div>




	   
	</div>

	
										<?php include('include/footer.php'); ?>
										
					</div>
			</div>
		</body>
	
</html>