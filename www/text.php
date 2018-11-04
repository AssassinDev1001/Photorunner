<?php
$post = array();
$post["mp3"] = '@bell-ringing-01.mp3';
$post["title"] = 'Amandeep test first';
$post["description"] = 'Amandeep test first';
$post["status"] = 'Amandeep1';


$url = 'https://upload.clyp.it/upload';
$handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post);
curl_exec($handle);

//$res = json_decode($handle);
echo"<pre>";
print_r($handle);
echo"</pre>";
?>
