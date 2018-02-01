<?php

error_reporting(E_ALL | E_STRICT);

require dirname(__DIR__).'/src/Client.php';

use Berysoft\Edigear;


$SECRET_KEY= getenv("EDIGEAR_SECRET_KEY");

$status_of_request_id = "CLI-ca67c79c-838a-4fa8-9a51-a451ec7e53d0";

$request = Edigear::createStatusRequest()->setId($status_of_request_id);

$response = Edigear::getInstance()->setSecretKey($SECRET_KEY)->send($request);
var_dump($response);

echo "\n", json_encode($response), "\n";