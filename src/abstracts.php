<?php

abstract class EGChannel
{
    const Undefined = 0;
    const Message   = 1;
    const Inbound   = 2;
    const Outbound  = 3;    
    
    public static function validate(array $data)
    {
        if (!isset($data['channel']))
        {
            return 'Channel is not set [EdigearRequest->setChannel( EGChannel::Message | EGChannel::Inbound | EGChannel::Outbound )]';
        }
        
        if ($data['channel']!==EGChannel::Message && $data['channel']!==EGChannel::Inbound && $data['channel']!==EGChannel::Outbound)
        {
            return 'Invalid channel value [EdigearRequest->setChannel( EGChannel::Message | EGChannel::Inbound | EGChannel::Outbound )]';
        }
        
        return TRUE;
    }
}


abstract class EGAction
{
    const Request   = 1;
    const Verify    = 2;
    const Status    = 3;
}


abstract class EGPlatform
{
    const Undefined = 0;
    const IOS       = 1;
    const Android   = 2;
    const Website   = 3;
    const Desktop   = 4;
}


abstract class EGMethod
{
    const GET       = "GET";
    const POST      = "POST";
}
