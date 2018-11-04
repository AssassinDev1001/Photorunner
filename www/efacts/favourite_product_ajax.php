<?php
include('include/search_ajax_config.php');

 if (isset($_GET['id']) && !empty($_GET['id'])) {
  $babo_id = $_SESSION['account']['id'];
  $babo_email1 = $_SESSION['language'];
  $fav_date = @date('Y-m-d H:i:s');

  $result = mysql_query("SELECT * FROM bz_favourite_product WHERE product_id = '".$_GET['id']."' and user_id='".$babo_id."'");
  //if result found
  if(mysql_num_rows($result) > 0)
  {
    
      mysql_query("Delete from bz_favourite_product where product_id = '".$_GET['id']."' and user_id='".$babo_id."'");

     $goal = mysql_query("select * from bz_favourite_product where user_id = '".$babo_id."'");
      
      $goal_count = mysql_num_rows($goal);

  echo  $ajax1 = '<p class="tun" style="padding: 0em 0; margin-left:5px; margin-top:-245px;"><i class="chnge fa fa-heart-o merced" style="font-size:20px;"></i></p>'.'@=@'.$goal_count;
  } 
  else
   {
       mysql_query("insert into bz_favourite_product set language_id='".$babo_email1."',product_id='".$_GET['id']."',user_id='".$babo_id."',date_time='".$fav_date."'");

        $goal1 = mysql_query("select * from bz_favourite_product where user_id = '".$babo_id."'");
      
      $goal_count1 = mysql_num_rows($goal1);

  echo  $ajax1 =  '<p class="tun" style="padding: 0em 0; margin-left:5px; margin-top:-245px;"><i class="chnge fa fa-heart merced" style="font-size:20px;
  "></i></p>'.'@=@'.$goal_count1;

  }
  
}
?>
