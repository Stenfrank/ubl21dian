<?php

namespace Stenfrank\SoapDIAN;

use Stenfrank\SoapDIAN\Traits\TraitMagic;
use Carbon\Carbon;
use DOMDocument,
    Exception;

/**
 * SOAP DIAN 21 - Web Service of tests and production
 */
class SOAPDIAN21
{
    use TraitMagic;
    
    /**
     * Version
     * @var string
     */
    public $version = '1.0';
    
    /**
     * Encoding
     * @var string
     */
    public $encoding = 'UTF-8';
    
    /**
     * IDS
     * @var array
     */
    private $ids = [
        'wsuBinarySecurityTokenID' => 'SOENAC',
        'securityTokenReferenceID' => 'STR',
        'signatureID' => 'SIG',
        'timestampID' => 'TS',
        'keyInfoID' => 'KI',
        'wsuIDTo' => 'ID'
    ];
    
    /**
     * To NS
     * @var array
     */
    protected $toNS = [
        'xmlns:wsa' => 'http://www.w3.org/2005/08/addressing',
        'xmlns:soap' => 'http://www.w3.org/2003/05/soap-envelope',
        'xmlns:wcf' => 'http://wcf.dian.colombia'
    ];
    
    /**
     * Signed info NS
     * @var array
     */
    protected $signedInfoNS = [
        'xmlns:ds' => 'http://www.w3.org/2000/09/xmldsig#',
        'xmlns:wsa' => 'http://www.w3.org/2005/08/addressing',
        'xmlns:soap' => 'http://www.w3.org/2003/05/soap-envelope',
        'xmlns:wcf' => 'http://wcf.dian.colombia'
    ];
    
    /**
     * Certs
     * @var array
     */
    protected $certs;
    
    public function __construct($pathCertificate = null, $passwors = null, $xmlString = null) {
        $this->pathCertificate = $pathCertificate;
        $this->passwors = $passwors;
        $this->xmlString = $xmlString;
        $this->CurrentTime = time();
        
        $this->readCerts();
        $this->identifiersReferences();
        
        $this->startNodes();
        
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
    public function startNodes($string = null) {
        if ($string != null) $this->xmlString = $string;
        
        if (!is_null($this->xmlString)) {
            $this->loadXML();
            $this->soap = $this->domDocument->saveXML();
        }
    }
    
    /**
     * Load XML
     * @return void
     */
    private function loadXML() {
        if ($this->xmlString instanceof DOMDocument) $this->xmlString = $this->xmlString->saveXML();
        
        $this->domDocument = new DOMDocument($this->version, $this->encoding);
        $this->domDocument->loadXML($this->xmlString);
        
        $this->header = $this->domDocument->createElement('soap:Header');
        $this->header->setAttribute('xmlns:wsa', 'http://www.w3.org/2005/08/addressing');
        $this->domDocument->documentElement->insertBefore($this->header, $this->domDocument->documentElement->firstChild);
        
        $this->security = $this->domDocument->createElementNS('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'wsse:Security');
        $this->security->setAttribute('xmlns:wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');
        $this->header->appendChild($this->security);
        
        $this->action = $this->domDocument->createElement('wsa:Action', $this->Action ?? 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatus');
        $this->header->appendChild($this->action);
        
        $this->to = $this->domDocument->createElement('wsa:To', $this->To ?? 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc');
        $this->to->setAttribute('wsu:Id', $this->wsuIDTo);
        $this->to->setAttribute('xmlns:wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');
        $this->header->appendChild($this->to);
        
        $this->timestamp = $this->domDocument->createElement('wsu:Timestamp');
        $this->timestamp->setAttribute('wsu:Id', $this->timestampID);
        $this->security->appendChild($this->timestamp);
        
        $this->created = $this->domDocument->createElement('wsu:Created', gmdate("Y-m-d\TH:i:s\Z", $this->CurrentTime));
        $this->timestamp->appendChild($this->created);
        
        $this->expire = $this->domDocument->createElement('wsu:Expires', gmdate("Y-m-d\TH:i:s\Z", $this->CurrentTime + $this->TimeToLive ?? 60000));
        $this->timestamp->appendChild($this->expire);
        
        $this->x509Export();
        
        $this->signature = $this->domDocument->createElement('ds:Signature');
        $this->signature->setAttribute('Id', $this->signatureID);
        $this->signature->setAttribute('xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $this->security->appendChild($this->signature);
        
        $this->signedInfo = $this->domDocument->createElement('ds:SignedInfo');
        $this->signature->appendChild($this->signedInfo);
        
        $this->canonicalizationMethod = $this->domDocument->createElement('ds:CanonicalizationMethod');
        $this->canonicalizationMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        $this->signedInfo->appendChild($this->canonicalizationMethod);
        
        $this->inclusiveNamespaces1 = $this->domDocument->createElement('ec:InclusiveNamespaces');
        $this->inclusiveNamespaces1->setAttribute('PrefixList', 'wsa soap wcf');
        $this->inclusiveNamespaces1->setAttribute('xmlns:ec', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        $this->canonicalizationMethod->appendChild($this->inclusiveNamespaces1);
        
        $this->signatureMethod = $this->domDocument->createElement('ds:SignatureMethod');
        $this->signatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256');
        $this->signedInfo->appendChild($this->signatureMethod);
        
        $this->reference1 = $this->domDocument->createElement('ds:Reference');
        $this->reference1->setAttribute('URI', "#{$this->wsuIDTo}");
        $this->signedInfo->appendChild($this->reference1);
        
        $this->transforms = $this->domDocument->createElement('ds:Transforms');
        $this->reference1->appendChild($this->transforms);
        
        $this->transform = $this->domDocument->createElement('ds:Transform');
        $this->transform->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        $this->transforms->appendChild($this->transform);
        
        $this->inclusiveNamespaces2 = $this->domDocument->createElement('ec:InclusiveNamespaces');
        $this->inclusiveNamespaces2->setAttribute('PrefixList', 'soap wcf');
        $this->inclusiveNamespaces2->setAttribute('xmlns:ec', 'http://www.w3.org/2001/10/xml-exc-c14n#');
        $this->transform->appendChild($this->inclusiveNamespaces2);
        
        $this->digestMethod = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
        $this->reference1->appendChild($this->digestMethod);
        
        /** DigestValue */;
        $this->digestValue();
        
        /** SignatureValue */
        $this->signature();
        
        $this->keyInfo = $this->domDocument->createElement('ds:KeyInfo');
        $this->keyInfo->setAttribute('Id', $this->keyInfoID);
        $this->signature->appendChild($this->keyInfo);
        
        $this->securityTokenReference = $this->domDocument->createElement('wsse:SecurityTokenReference');
        $this->securityTokenReference->setAttribute('wsu:Id', $this->securityTokenReferenceID);
        $this->keyInfo->appendChild($this->securityTokenReference);
        
        $this->reference2 = $this->domDocument->createElement('wsse:Reference');
        $this->reference2->setAttribute('URI', "#{$this->wsuBinarySecurityTokenID}");
        $this->reference2->setAttribute('ValueType', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3');
        $this->securityTokenReference->appendChild($this->reference2);
    }
    
    /**
     * Read certs
     * @return void
     */
    private function readCerts() {
        if (is_null($this->pathCertificate) || is_null($this->passwors)) throw new Exception('Class '.get_class($this).' requires the certificate path and password.');
        
        if (!openssl_pkcs12_read(file_get_contents($this->pathCertificate), $this->certs, $this->passwors)) throw new Exception('Failure signing data: '.openssl_error_string());
    }
    
    /**
     * X509 export
     * @return void
     */
    private function x509Export() {
        if (!empty($this->certs)) {
            openssl_x509_export($this->certs['cert'], $stringCert);
            $stringCert = str_replace(["\r", "\n", "-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----"], '', $stringCert);
        }
        
        $this->token = $this->domDocument->createElement('wsse:BinarySecurityToken', $stringCert ?? null);
        $this->token->setAttribute('EncodingType', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary');
        $this->token->setAttribute('ValueType', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3');
        $this->token->setAttribute('wsu:Id', $this->wsuBinarySecurityTokenID);
        $this->security->appendChild($this->token);
    }
    
    /**
     * Identifiers references
     * @return void
     */
    private function identifiersReferences() {
        foreach ($this->ids as $key => $value) {
            $this->$key = "{$value}-".md5(Carbon::now()->format('YmdHisu'));
        }
    }
    
    /**
     * Digest value
     * @param  string $string
     * @return string
     */
    public function digestValue($string = null) {
        $toNS = implode(' ', array_map(function($value, $key) {
            return "{$key}=\"$value\"";
        }, $this->toNS,  array_keys($this->toNS)));
        
        $domDocument = new DOMDocument($this->version, $this->encoding);
        $domDocument->loadXML(str_replace('<wsa:To ', "<wsa:To {$toNS} ", $string ?? $this->domDocument->saveXML($this->to)));
        
        $digestValue = base64_encode(hash('sha256', $domDocument->C14N(), true));
        
        $this->digestValue = $this->domDocument->createElement('ds:DigestValue', $digestValue);
        $this->reference1->appendChild($this->digestValue);
        
        $this->soap = $this->domDocument->saveXML();
        
        return $digestValue;
    }
    
    /**
     * Signature
     * @param  string $string
     * @return string
     */
    public function signature($string = null) {
        $signedInfoNS = implode(' ', array_map(function($value, $key) {
            return "{$key}=\"$value\"";
        }, $this->signedInfoNS,  array_keys($this->signedInfoNS)));
        
        $domDocument = new DOMDocument($this->version, $this->encoding);
        $domDocument->loadXML(str_replace('<ds:SignedInfo', "<ds:SignedInfo {$signedInfoNS}", $string ?? $this->domDocument->saveXML($this->signedInfo)));
        
        if (!openssl_sign($domDocument->C14N(), $sing, $this->certs['pkey'], 'RSA-SHA256')) die('Failure Signing Data: '.openssl_error_string().' - RSA-SHA256');
        
        $sing = base64_encode($sing);
        
        $this->signatureValue = $this->domDocument->createElement('ds:SignatureValue', $sing);
        $this->signature->appendChild($this->signatureValue);
        
        $this->soap = $this->domDocument->saveXML();
        
        return $sing;
    }
}
