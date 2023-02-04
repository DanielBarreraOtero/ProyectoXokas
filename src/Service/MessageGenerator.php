<?php

namespace App\Service;

class MessageGenerator
{
    private string $message;

    public function __construct()
    {
        $this->message = 'Soy un servicio! :D';
    }

    public function getMessage()
    {
        return $this->message;
    }
    
}
