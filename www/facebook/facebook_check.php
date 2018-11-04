<?php
include('config/config.php');


$check=mysql_query("select * from schol_registration where email='".$_SESSION['fb_email']."'");
$fetch=mysql_fetch_assoc($check);
if($fetch['status']=='0')
{
echo"<script>alert('Your Account is Deactivate.Please check your Email.')</script>";
}
else if($fetch['login_status']=='1')
{
echo"<script>alert('Your Account is logged in from another device.Please logout from that device and try again.')</script>";    
}
else
{

if(mysql_num_rows($check)>=1)
{
    $_SESSION['user_id']=$fetch['reg_id'];
    if(!empty($_GET['rurl']))
    {
        header('Location:'.$_GET['rurl']);
    }
    else
    {
        
            if(($_SESSION['course_idi'] != "") && ($_SESSION['course_namei'] != ""))
            {
                mysql_query("update schol_registration set login_status = '1' where reg_id = '".$fetch['reg_id']."'");
                header('Location:simpleuser_show_video.php');
            }
            else
            {
                mysql_query("update schol_registration set login_status = '1' where reg_id = '".$fetch['reg_id']."'");
                header('Location:dashmyacountpage.php');
            }
        
    }
}
else
{
//echo"<script>alert('Invalid Email and Password.')</script>";

        header('Location:signin.php?gog_err=errr');
}
}

?>
                                            
