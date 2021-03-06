<?php

namespace Berysoft;


abstract class EGChannel
{
    const Undefined         = 0;
    const Message           = 1;
    const Inbound           = 2;
    const Outbound          = 3;  
    const TextMessage       = 4;
    const RealTimePassword  = 5;
    
    public static function validate(array $data) {
        if (!isset($data['channel'])) {
            return 'Channel is not set [EdigearRequest->setChannel( EGChannel::Message | EGChannel::Inbound | EGChannel::Outbound )]';
        }
        
        if ($data['channel']!==EGChannel::Message 
            && $data['channel']!==EGChannel::Inbound 
            && $data['channel']!==EGChannel::Outbound 
            && $data['channel']!==EGChannel::TextMessage
            && $data['channel']!==EGChannel::RealTimePassword) {
            return 'Invalid channel value [EdigearRequest->setChannel( EGChannel::Message | EGChannel::Inbound | EGChannel::Outbound | EGChannel::TextMessage | EGChannel::RealTimePassword )]';
        }
        
        return TRUE;
    }
}