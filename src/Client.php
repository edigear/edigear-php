<?php

namespace Berysoft;
include_once 'abstracts.php';
include_once 'Request.php';


class Edigear
{
    const GEAR_URL      = "https://rest.edigear.com";
    const GEAR_VERSION  = "v1";
    const GEAR_AGENT    = "edigear api client";
    const VERSION       = "1.0";
      
    private $userAgent  = null;
    
    private $secretKey;
    
    private static $instance;
    
    
    private function __construct()
    {                
    }
    
    
    public static function getInstance() : Edigear
    {
        if (Edigear::$instance==null)
        {
            Edigear::$instance = new Edigear();            
        }
        return Edigear::$instance;
    }
               
    
    public function setSecretKey(string $secret)
    {
        $this->secretKey = $secret;
        return $this;
    }
    
        
    
    public function send(EdigearRequest $request)
    {
        $result = ['status'=>0, 'data'=>[]];
        if (!extension_loaded('curl')) 
        {
            $result['data']=['error'=>'cURL library is not loaded'];
            return $result;
        }
        
        if ((!isset($this->secretKey)) || (!$this->secretKey))
        {
            $result['data']=['error'=>'no secret key is specified'];
            return $result;
        }
        
        if (!$request->isValid())
        {
            $result['data']=['error'=>'Invalid request payload. '.$request->getLastError()];            
            return $result;
        }
        
        
        if ($this->userAgent==NULL)
        {
            $this->userAgent = 'Edigear-PHP/' . self::VERSION . ' (+https://github.com/berysoft/edigear-php)';
            $this->userAgent .= ' PHP/' . PHP_VERSION;
            $curl_version = curl_version();
            $this->userAgent .= ' curl/' . $curl_version['version'];
        }
        
        $options = [
            CURLOPT_URL => $request->getURL(),
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HEADER => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_VERBOSE => FALSE,
            CURLOPT_SSL_VERIFYPEER => TRUE];
        
        $ch = curl_init();
        $headers = array('Authorization: '.$this->secretKey);
              
        try
        {
            curl_setopt($ch, CURLOPT_URL, $request->getUrl());
            
            switch ($request->getMethod()) 
            {
                case EGMethod::POST:
                    curl_setopt($ch, CURLOPT_POST, true);
                    $jsonPayload = $request->getPayload();
                    //error_log($jsonPayload);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
                    array_push($headers, "Content-Type: application/json");
                    array_push($headers, 'Content-Length: '.strlen($jsonPayload));
                    break;

                default:
                    break;
            }
            $options[CURLOPT_HTTPHEADER] = $headers;
            curl_setopt_array($ch, $options);
            
            $response = \curl_exec($ch);

            //error_log($response);
            
            //Retrieve Response Status
            $result['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            $is_json = is_string($response) && is_array(json_decode($response, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
            if ($is_json)
            {                
                $result['data'] = json_decode($response, TRUE);
            }
            
        } 
        catch (Exception $ex) 
        {
            throw new EdigearError ($ex->getMessage());
        }
        finally 
        {
            if (is_resource($ch)) 
            {
                curl_close($ch);
            }
        }
        
        return $result;
    }
        
}




       

class EGResponse
{
    private $result = [];
    
    public function getAsJson()
    {
        return json_decode($this->result);
    }
    
    public function getAsArray()
    {
        return $this->result;
    }
}





trait ShortMessageService 
{
    abstract function setId(string $id);
    abstract protected function isValid() : bool;
    
    public function SMSValidation()
    {
        $this->channel = EdiChannel::Message;
        if ($this->compiled())
        {
            $prepare = 
                    [
                        'number' => $this->number, 
                        'type' => $this->channel, 
                        'platform' => $this->platform
                    ];
            echo json_encode($prepare), "\n";
        }        
    }
}


class EdigearError extends \Exception
{
}



