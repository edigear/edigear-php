<?php

error_reporting(E_ALL | E_STRICT);
require dirname(__DIR__) . '/vendor/autoload.php';


use Berysoft\Edigear;

// Set channel to test
$channel = "cli"; /* check, cli, rvc, sms*/
$verification_request_id = "SMS-b35893ee-204a-4687-9c4b-d487c2cf443f";

if (PHP_SAPI=='cli')
{
    $test = $argv[1] ?? "s";
    
    switch ($channel) 
    {
        case "sms":
            switch ($test) 
            {
                case "s":
                    $request = EdigearRequest::Create()->setAction(EGAction::Status)->setId($verification_request_id);
                    break;
                case "v":
                    $request = EdigearRequest::Create()->setAction(EGAction::Verify)->setId($verification_request_id)->setPin("2022");
                    break;
                case "r":
                    $request = EdigearRequest::Create()->
                        setAction(EGAction::Request)->
                        setChannel(EGChannel::Message)->
                        setPlatform(EGPlatform::Website)->
                        setSender("mourjan")->
                        setPhoneNumber(353830399895);
                    break;
            }
            break;

        case "cli":
            switch ($test) 
            {
                case "s":               
                    $request = EdigearRequest::Create()->setAction(EGAction::Status)->setId("CLI-6d64637a-0414-416f-a068-228a26fb9d3c");
                    break;

                case "v":
                    $request = EdigearRequest::Create()->setAction(EGAction::Verify)->setId("CLI-6d64637a-0414-416f-a068-228a26fb9d3c")->setPin(1790);                
                    break;

                case "r":
                    $request = EdigearRequest::Create()->
                        setAction(EGAction::Request)->
                        setChannel(EGChannel::Inbound)->
                        setPlatform(EGPlatform::IOS)->
                        setPhoneNumber(353871985414);
                    break;
            }

            break;

        case "rvc":
            switch ($test) 
            {
                case "s":
                    $request = EdigearRequest::Create()->setAction(EGAction::Status)->setId("RVC-5f119886-6599-4a96-80bd-623a86a99cd9");
                    break;
                case "v":
                    $request = EdigearRequest::Create()->
                        setAction(EGAction::Verify)->
                        setId("RVC-5f119886-6599-4a96-80bd-623a86a99cd9")
                        ->setPin(4077); 
                    break;
                case "r":
                    $request = EdigearRequest::Create()->
                        setAction(EGAction::Request)->
                        setChannel(EGChannel::Outbound)->
                        setPlatform(EGPlatform::Website)->
                        setPhoneNumber(353830399895);
                    break;
            }
            break;
    }        

    $response = Edigear::getInstance()->setSecretKey("SECRET-KEY")->send($request);
    var_dump($response);
}
