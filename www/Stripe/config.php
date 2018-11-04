<?php
require_once('lib/Stripe.php');

$stripe = array(
  "secret_key"      => "sk_test_4Mw6s5C3whYw9dQBQDTO0YPB",
  "publishable_key" => "pk_test_4Mw6Nlh2oewHxD9HRgcAfVSa"
);

Stripe::setApiKey($stripe['secret_key']);
?>

