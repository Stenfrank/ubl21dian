<?php

namespace Stenfrank\SoapDIAN;

use Stenfrank\SoapDIAN\Traits\DIANTrait;

/**
 * 
 */
abstract class Sing
{
    use DIANTrait;
    
    /**
     * Abstract loadXML
     * @var void
     */
    abstract protected function loadXML();
    
    /**
     * Construct
     * @param string $pathCertificate
     * @param string $passwors
     * @param string $xmlString
     */
    public function __construct($pathCertificate = null, $passwors = null, $xmlString = null) {
        $this->pathCertificate = $pathCertificate;
        $this->passwors = $passwors;
        $this->xmlString = $xmlString;
        
        $this->readCerts();
        $this->identifiersReferences();
        
        $this->sign();
        
        return $this;
    }
    
    /**
     * Get document
     * @return DOMDocument
     */
    public function getDocument() {
        return $this->domDocument;
    }
    
    /**
     * Start nodes
     * @param  string $string
     * @return void
     */
    public function sign($string = null) {
        if ($string != null) $this->xmlString = $string;
        
        if (!is_null($this->xmlString)) {
            $this->loadXML();
            $this->xml = $this->domDocument->saveXML();
        }
    }
}
