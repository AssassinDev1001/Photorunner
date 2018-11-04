<?php
include('config/config.php');
    
    $chek=mysql_query("select * from schol_registration where email='".$_SESSION['fb_email']."'");
    $ff=mysql_fetch_assoc($chek);

    
    $profileimage = $_SESSION['fb_picture']['data']['url']; //image URL
    $ch = curl_init($profileimage);
    $rands1=rand();
    $image_name="user".$rands1.".gif";
    $fp = fopen("profile_pic/".$image_name, "wb");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    if(mysql_num_rows($chek)>=1)
    {
       
        mysql_query("update schol_registration set language = '".$_SESSION['flag_id']."',user_type = 'simple_user',fname = '".$_SESSION['fb_username']."',email='".$_SESSION['fb_email']."',address = '".$_SESSION['fb_locale']."',profile_pic = '".$image_name."',cur_date = CURDATE(),cur_time = CURTIME(), status='1' where email = '".$_SESSION['fb_email']."'");
        $ss=mysql_fetch_assoc(mysql_query("select * from schol_registration where email='".$_SESSION['fb_email']."'"));
    //$_SESSION['user_id']=$ff['reg_id'];
        header('location:signin.php?signup_err=signuperror');
    }
    else
    {
       
        $rand=rand();
        mysql_query("insert into schol_registration set language = '".$_SESSION['flag_id']."',user_type = 'simple_user',fname = '".$_SESSION['fb_username']."',email='".$_SESSION['fb_email']."',password = '".$rand."',address = '".$_SESSION['fb_locale']."',profile_pic = '".$image_name."',cur_date = CURDATE(),cur_time = CURTIME(), status='1'");

        $ss=mysql_fetch_assoc(mysql_query("select * from schol_registration where email='".$_SESSION['fb_email']."'"));
       $_SESSION['user_id']=$ss['reg_id'];
      
        $un = $ss['fname'];
        $pr = $ss['profile_pic'];
        $ln = base64_encode($ss['email']);
        
        $invd = mysql_fetch_assoc(mysql_query("select * from schol_contactus_emaildesign where title='Student Registration' and language_id='".$_SESSION['flag_id']."' and status='1'"));

        $search  = array('$uname' ,'$profile', '$link');
        $replace = array($un,$pr,$ln);

        //$message = str_replace($search,$replace,$invd['description']);

        $from = 'Scholme S.A.R.L';
        $to = $ss['email'];
        $subject = $invd['subject'];

        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-type: text/html; charset=UTF-8\r\n"; 
        $headers .= "From: The Sending Name is <$from>\r\n";
        
        $message="Hello User,<br/><br/>Your Registration has been done successfully.<br/>Your account password is ".$ss['password']."<br/><br/>If you need any help, please feel free to contact us at info@scholme.com<br/><br/>Thanks<br/>Support Team<br/>SCHOL ME.";


       mail($to, $subject, $message, $headers)or die("mail error");


       $ins_date = date("Y-m-d H:i:s");

       mysql_query("insert into schol_manage_email set email_from='".$from."',email_to='".$to."',content='".$subject."',category='student_registration',date_time='".$ins_date."',status='1'");

        mysql_query("update schol_registration set login_status = '1' where reg_id = '".$ss['reg_id']."'");
        header('location:dashmyacountpage.php?fbi_log=facebook'); 
    }

 
?>
