<?php

namespace Stenfrank\UBL21dian\Templates;

use Exception;
use Stenfrank\UBL21dian\Client;
use Stenfrank\UBL21dian\SOAPDIAN21;

/**
 * Template
 */
class Template extends SOAPDIAN21
{
    /**
     * To
     * @var string
     */
    public $To = 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc';
    
    /**
     * Template
     * @var string
     */
    protected $template;
    
    /**
     * Sign
     * @return \Stenfrank\UBL21dian\SOAPDIAN21
     */
    public function sign($string = null): SOAPDIAN21 {
        $this->requiredProperties();
        
        return parent::sign($this->createTemplate());
    }
    
    /**
     * Sign to send
     * @return \Stenfrank\UBL21dian\Client
     */
    public function signToSend(): Client {
        $this->requiredProperties();
        
        parent::sign($this->createTemplate());
        
        return new Client($this);
    }
    
    /**
     * Required properties
     * @return void
     */
    private function requiredProperties() {
        foreach ($this->requiredProperties as $requiredProperty) {
            if (is_null($this->$requiredProperty)) throw new Exception("The {$requiredProperty} property has to be defined");
        }
    }
}
