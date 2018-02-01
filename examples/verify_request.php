<?php
// Applicable for SMS and Outbound call requests
error_reporting(E_ALL | E_STRICT);

require dirname(__DIR__).'/src/Client.php';

use Berysoft\Edigear;


$SECRET_KEY= getenv("EDIGEAR_SECRET_KEY");

$request_id_to_verify = "SMS-96955952-7539-45ae-b3ec-56609f6637f6";
$pin_code = 6374;
$request = Edigear::createVerifyRequest($request_id_to_verify, $pin_code);

$response = Edigear::getInstance()->setSecretKey($SECRET_KEY)->send($request);
var_dump($response);

echo "\n", json_encode($response), "\n";