<?php
require_once('lib/Stripe.php');

$stripe = array(
  "secret_key"      => "sk_live_f0Zb65LFbH3jQV4zGbzb1AMV",
  "publishable_key" => "pk_live_obp9GmiNdWLl0DWA5wYW6rCv"


  //"secret_key"      => "sk_test_xitA2poC7TfjnP1IGD0FT6rp",
  //"publishable_key" => "pk_test_inM99ehBADdrzRTf3wa3ggu2"
);

Stripe::setApiKey($stripe['secret_key']);
?>

