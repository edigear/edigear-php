<?php
error_reporting(E_ALL | E_STRICT);
// Outbound call from our call center to number to verify
// then user received call which must not answer it, 
// to verify user must take last 4 digits of phone number which called him

require dirname(__DIR__).'/src/Client.php';
use Berysoft\Edigear;


$SECRET_KEY= getenv("EDIGEAR_SECRET_KEY");

$number_to_call = 96170424018;

$request = Edigear::createOutboundCallRequest()->setPhoneNumber($number_to_call);


$response = Edigear::getInstance()->setSecretKey($SECRET_KEY)->send($request);
var_dump($response);

echo "\n", json_encode($response), "\n";