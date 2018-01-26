<?php
namespace Berysoft;


class EdigearRequest 
{
    use ShortMessageService;
    
    protected $action;
    protected $method;    
    protected $payload;
    
    private $error = null;
    
    private function __construct() 
    {
        $this->method = EGMethod::POST;
        $this->payload = ['number'=>0, 'id'=>'', 'pin'=>'', 'channel'=>EGChannel::Undefined, 'platform'=>EGPlatform::Undefined];
    }
    
    
    public static function Create() : EdigearRequest
    {
        $_instance = new EdigearRequest();
        return $_instance;
    }

    
    public function getURL() : string
    {
        $url = Edigear::GEAR_URL . '/' . Edigear::GEAR_VERSION;
        switch ($this->action) 
        {
            case EGAction::Request:
                $url.='/validation/request';
                break;
            
            case EGAction::Verify:
                $url.='/validation/verify';
                break;
            
            case EGAction::Status:
                $url.="/validation/status/{$this->payload['id']}";               
                break;

            default:
                throw new EdigearError("no action key is specified (request, verify, status)");
        }
               
        return $url;
    }
    
    
    public function setChannel(int $channel) : EdigearRequest
    {
        $this->payload['channel'] = $channel;        
        return $this;
    }
    
    
    public function setAction(int $action) : EdigearRequest
    {
        $this->action = $action;
        
        if ($this->action === EGAction::Request)
        {
            $this->setMethod(EGMethod::POST);
        }
        else if ($this->action=== EGAction::Status)
        {
            $this->setMethod(EGMethod::GET);
        }
        
        return $this;
    }
    
    
    public function setPlatform(int $platform) : EdigearRequest
    {
        $this->payload['platform'] = $platform;
        return $this;
    }

    
    public function setSender(string $sender) : EdigearRequest
    {
        if ($sender)
        {
            $this->payload['sender'] = $sender;
        }
        else
        {
            unset($this->payload['sender']);
        }
        return $this;
    }
    
    protected function setMethod(string $method) : EdigearRequest
    {
        $this->method = $method;
        return $this;
    }
    
    
    public function getMethod() : string
    {
        return $this->method;
    }
   
    
    public function setPhoneNumber(int $phoneNumber) : EdigearRequest
    {
        $this->payload['number'] = $phoneNumber;
        return $this;                
    }
       
    
    public function setId(string $id) : EdigearRequest
    {
        $this->payload['id'] = $id;
        return $this;
    }
    
    public function setPin(string $pin) : EdigearRequest
    {
        $this->payload['pin'] = $pin;
        return $this;
    }
    
    
    public function getPayload()
    {
        switch ($this->action) 
        {
            case EGAction::Request:
                if ($this->payload['channel']==EGChannel::Message && isset($this->payload['sender']) && !empty($this->payload['sender']))
                {
                    $payl = [
                        'number'=>$this->payload['number'], 
                        'channel'=>$this->payload['channel'], 
                        'platform'=>$this->payload['platform'], 
                        'sender'=> $this->payload['sender']];
                    if (isset($this->payload['pin']) && $this->payload['pin'])
                    {
                        $payl['code']=$this->payload['pin'];
                    }
                    return json_encode($payl);
                }
                return json_encode([
                        'number'=>$this->payload['number'], 
                        'channel'=>$this->payload['channel'], 
                        'platform'=>$this->payload['platform']]);                

            case EGAction::Verify:
                return json_encode(['id'=>$this->payload['id'], 'pin'=>$this->payload['pin']]);                

            default:
                break;
        }
        return json_encode($this->payload);
    }
    
    
    public function isValid() : bool
    {
        switch ($this->action) 
        {
            case EGAction::Request:
                if ($this->payload['number']<99999)
                {
                    return FALSE;
                }
                
                if (($error = EGChannel::validate($this->payload))!==TRUE)
                {
                    echo $error, "[",__LINE__, "]\n";
                    return FALSE;
                }
                break;

            case EGAction::Verify:
//                if (($error = EGChannel::validate($this->payload))!==TRUE)
//                {
//                    echo $error, "\n";
//                    return FALSE;
//                }
//                
                if ($this->payload['id']==null || empty($this->payload['id']))
                {
                    $this->error = "Invalid request id!";
                    return FALSE;
                }
                
               
                if ($this->payload['channel']!==EGChannel::Inbound && 
                        (!isset($this->payload['pin']) ||
                        $this->payload['pin']==NULL || 
                        $this->payload['pin']<"0000" || 
                        $this->payload['pin']>"9999"))
                {
                    $this->error = "Invalid pin code!";
                    return FALSE;
                }
                break;

                
            case EGAction::Status:
                if (!isset($this->payload['id']) || $this->payload['id']==null || empty($this->payload['id']))
                {
                    return FALSE;
                }
                break;
            
            default:
                return FALSE;
        }
        
        return TRUE;
    }
    
    
    public function getLastError() : string
    {
        return $this->error ?? '';
    }
        
}