<?php

namespace Stenfrank\UBL21dian\BinarySecurityToken;

use Exception;
use DOMDocument;
use Stenfrank\UBL21dian\Sign;

/**
 * SOAP.
 */
class SOAP extends Sign
{
    /**
     * ADDRESSING.
     *
     * @var string
     */
    const ADDRESSING = 'http://www.w3.org/2005/08/addressing';

    /**
     * SOAP_ENVELOPE.
     *
     * @var string
     */
    const SOAP_ENVELOPE = 'http://www.w3.org/2003/05/soap-envelope';

    /**
     * DIAN_COLOMBIA.
     *
     * @var string
     */
    const DIAN_COLOMBIA = 'http://wcf.dian.colombia';

    /**
     * XMLDSIG.
     *
     * @var string
     */
    const XMLDSIG = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     * WSS_WSSECURITY.
     *
     * @var string
     */
    const WSS_WSSECURITY = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    /**
     * WSS_WSSECURITY_UTILITY.
     *
     * @var string
     */
    const WSS_WSSECURITY_UTILITY = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';

    /**
     * EXC_C14N.
     *
     * @var string
     */
    const EXC_C14N = 'http://www.w3.org/2001/10/xml-exc-c14n#';

    /**
     * RSA_SHA256.
     *
     * @var string
     */
    const RSA_SHA256 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';

    /**
     * SHA256.
     *
     * @var string
     */
    const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';

    /**
     * X509V3.
     *
     * @var string
     */
    const X509V3 = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3';

    /**
     * BASE64BINARY.
     *
     * @var string
     */
    const BASE64BINARY = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

    /**
     * IDS.
     *
     * @var array
     */
    protected $ids = [
        'wsuBinarySecurityTokenID' => 'SOENAC',
        'securityTokenReferenceID' => 'STR',
        'signatureID' => 'SIG',
        'timestampID' => 'TS',
        'keyInfoID' => 'KI',
        'wsuIDTo' => 'ID',
    ];

    /**
     * To NS.
     *
     * @var array
     */
    protected $toNS = [
        'xmlns:wsa' => self::ADDRESSING,
        'xmlns:soap' => self::SOAP_ENVELOPE,
        'xmlns:wcf' => self::DIAN_COLOMBIA,
    ];

    /**
     * Signed info NS.
     *
     * @var array
     */
    protected $signedInfoNS = [
        'xmlns:ds' => self::XMLDSIG,
        'xmlns:wsa' => self::ADDRESSING,
        'xmlns:soap' => self::SOAP_ENVELOPE,
        'xmlns:wcf' => self::DIAN_COLOMBIA,
    ];

    public function __construct($pathCertificate = null, $passwors = null, $xmlString = null)
    {
        $this->CurrentTime = time();

        parent::__construct($pathCertificate, $passwors, $xmlString);

        return $this;
    }

    /**
     * Load XML.
     */
    protected function loadXML()
    {
        if ($this->xmlString instanceof DOMDocument) {
            $this->xmlString = $this->xmlString->saveXML();
        }

        $this->domDocument = new DOMDocument($this->version, $this->encoding);
        $this->domDocument->loadXML($this->xmlString);

        $this->removeChild('Header');

        $this->header = $this->domDocument->createElement('soap:Header');
        $this->header->setAttribute('xmlns:wsa', self::ADDRESSING);
        $this->domDocument->documentElement->insertBefore($this->header, $this->domDocument->documentElement->firstChild);

        $this->security = $this->domDocument->createElementNS(self::WSS_WSSECURITY, 'wsse:Security');
        $this->security->setAttribute('xmlns:wsu', self::WSS_WSSECURITY_UTILITY);
        $this->header->appendChild($this->security);

        $this->action = $this->domDocument->createElement('wsa:Action', $this->Action ?? 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatus');
        $this->header->appendChild($this->action);

        $this->to = $this->domDocument->createElement('wsa:To', $this->To ?? 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc');
        $this->to->setAttribute('wsu:Id', $this->wsuIDTo);
        $this->to->setAttribute('xmlns:wsu', self::WSS_WSSECURITY_UTILITY);
        $this->header->appendChild($this->to);

        $this->timestamp = $this->domDocument->createElement('wsu:Timestamp');
        $this->timestamp->setAttribute('wsu:Id', $this->timestampID);
        $this->security->appendChild($this->timestamp);

        $this->created = $this->domDocument->createElement('wsu:Created', gmdate("Y-m-d\TH:i:s\Z", $this->CurrentTime));
        $this->timestamp->appendChild($this->created);

        $this->expire = $this->domDocument->createElement('wsu:Expires', gmdate("Y-m-d\TH:i:s\Z", $this->CurrentTime + ($this->TimeToLive ?? 60000)));
        $this->timestamp->appendChild($this->expire);

        // x509Export
        $this->token = $this->domDocument->createElement('wsse:BinarySecurityToken', $this->x509Export());
        $this->token->setAttribute('EncodingType', self::BASE64BINARY);
        $this->token->setAttribute('ValueType', self::X509V3);
        $this->token->setAttribute('wsu:Id', $this->wsuBinarySecurityTokenID);
        $this->security->appendChild($this->token);

        $this->signature = $this->domDocument->createElement('ds:Signature');
        $this->signature->setAttribute('Id', $this->signatureID);
        $this->signature->setAttribute('xmlns:ds', self::XMLDSIG);
        $this->security->appendChild($this->signature);

        $this->signedInfo = $this->domDocument->createElement('ds:SignedInfo');
        $this->signature->appendChild($this->signedInfo);

        $this->canonicalizationMethod = $this->domDocument->createElement('ds:CanonicalizationMethod');
        $this->canonicalizationMethod->setAttribute('Algorithm', self::EXC_C14N);
        $this->signedInfo->appendChild($this->canonicalizationMethod);

        $this->inclusiveNamespaces1 = $this->domDocument->createElement('ec:InclusiveNamespaces');
        $this->inclusiveNamespaces1->setAttribute('PrefixList', 'wsa soap wcf');
        $this->inclusiveNamespaces1->setAttribute('xmlns:ec', self::EXC_C14N);
        $this->canonicalizationMethod->appendChild($this->inclusiveNamespaces1);

        $this->signatureMethod = $this->domDocument->createElement('ds:SignatureMethod');
        $this->signatureMethod->setAttribute('Algorithm', self::RSA_SHA256);
        $this->signedInfo->appendChild($this->signatureMethod);

        $this->reference1 = $this->domDocument->createElement('ds:Reference');
        $this->reference1->setAttribute('URI', "#{$this->wsuIDTo}");
        $this->signedInfo->appendChild($this->reference1);

        $this->transforms = $this->domDocument->createElement('ds:Transforms');
        $this->reference1->appendChild($this->transforms);

        $this->transform = $this->domDocument->createElement('ds:Transform');
        $this->transform->setAttribute('Algorithm', self::EXC_C14N);
        $this->transforms->appendChild($this->transform);

        $this->inclusiveNamespaces2 = $this->domDocument->createElement('ec:InclusiveNamespaces');
        $this->inclusiveNamespaces2->setAttribute('PrefixList', 'soap wcf');
        $this->inclusiveNamespaces2->setAttribute('xmlns:ec', self::EXC_C14N);
        $this->transform->appendChild($this->inclusiveNamespaces2);

        $this->digestMethod = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethod->setAttribute('Algorithm', self::SHA256);
        $this->reference1->appendChild($this->digestMethod);

        // DigestValue
        $this->digestValue();

        // SignatureValue
        $this->signature();

        $this->keyInfo = $this->domDocument->createElement('ds:KeyInfo');
        $this->keyInfo->setAttribute('Id', $this->keyInfoID);
        $this->signature->appendChild($this->keyInfo);

        $this->securityTokenReference = $this->domDocument->createElement('wsse:SecurityTokenReference');
        $this->securityTokenReference->setAttribute('wsu:Id', $this->securityTokenReferenceID);
        $this->keyInfo->appendChild($this->securityTokenReference);

        $this->reference2 = $this->domDocument->createElement('wsse:Reference');
        $this->reference2->setAttribute('URI', "#{$this->wsuBinarySecurityTokenID}");
        $this->reference2->setAttribute('ValueType', self::X509V3);
        $this->securityTokenReference->appendChild($this->reference2);
    }

    /**
     * Digest value.
     *
     * @param string $string
     *
     * @return string
     */
    public function digestValue($string = null)
    {
        $domDocument = new DOMDocument($this->version, $this->encoding);
        $domDocument->loadXML(str_replace('<wsa:To ', "<wsa:To {$this->joinArray($this->toNS)} ", $string ?? $this->domDocument->saveXML($this->to)));

        $digestValue = base64_encode(hash('sha256', $domDocument->C14N(), true));

        $this->digestValue = $this->domDocument->createElement('ds:DigestValue', $digestValue);
        $this->reference1->appendChild($this->digestValue);

        $this->xml = $this->domDocument->saveXML();

        return $digestValue;
    }

    /**
     * Signature.
     *
     * @param string $string
     *
     * @return string
     */
    public function signature($string = null)
    {
        $domDocument = new DOMDocument($this->version, $this->encoding);
        $domDocument->loadXML(str_replace('<ds:SignedInfo', "<ds:SignedInfo {$this->joinArray($this->signedInfoNS)}", $string ?? $this->domDocument->saveXML($this->signedInfo)));

        if (!openssl_sign($domDocument->C14N(), $sing, $this->certs['pkey'], 'RSA-SHA256')) {
            throw new Exception('Class '.get_class($this).': Failure Signing Data: '.openssl_error_string().' - RSA-SHA256');
        }

        $sing = base64_encode($sing);

        $this->signatureValue = $this->domDocument->createElement('ds:SignatureValue', $sing);
        $this->signature->appendChild($this->signatureValue);

        $this->xml = $this->domDocument->saveXML();

        return $sing;
    }
}
