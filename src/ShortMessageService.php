<?php

trait ShortMessageService 
{
    abstract function setId(string $id);
    abstract protected function isValid() : bool;
    
    public function SMSValidation()
    {
        $this->channel = EdiChannel::Message;
        if ($this->compiled())
        {
            $prepare = ['number' => $this->number, 'type' => $this->channel, 'platform' => $this->platform];
            echo json_encode($prepare), "\n";
        }        
    }
}