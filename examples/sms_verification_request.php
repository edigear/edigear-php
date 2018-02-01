<?php

error_reporting(E_ALL | E_STRICT);


require dirname(__DIR__).'/src/Client.php';
use Berysoft\Edigear;


$SECRET_KEY= getenv("EDIGEAR_SECRET_KEY");

$number_sending_to = 96170424018;

$request = Edigear::createSMSRequest()->setPhoneNumber($number_sending_to);


$response = Edigear::getInstance()->setSecretKey($SECRET_KEY)->send($request);
var_dump($response);

echo "\n", json_encode($response), "\n";
