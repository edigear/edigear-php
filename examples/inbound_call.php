<?php

error_reporting(E_ALL | E_STRICT);
// Inbound call from user number to one phone number which we choose randomly 
// to verify: just when we received the call user number become verified

require dirname(__DIR__).'/src/Client.php';
use Berysoft\Edigear;


$SECRET_KEY= getenv("EDIGEAR_SECRET_KEY");

$caller_number = 96170424018;

$request = Edigear::createInboundCallRequest()->setPhoneNumber($caller_number);


$response = Edigear::getInstance()->setSecretKey($SECRET_KEY)->send($request);
var_dump($response);

echo "\n", json_encode($response), "\n";
