<?php

namespace Stenfrank\UBL21dian\XAdES;

use DOMXPath;
use DOMDocument;
use Carbon\Carbon;
use Stenfrank\UBL21dian\Sign;

/**
 * Sign Invoice.
 */
class SignInvoice extends Sign
{
    /**
     * XMLDSIG.
     *
     * @var string
     */
    const XMLDSIG = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     * POLITICA_FIRMA_V2.
     *
     * @var string
     */
    const POLITICA_FIRMA_V2 = 'https://facturaelectronica.dian.gov.co/politicadefirma/v2/politicadefirmav2.pdf';

    /**
     * POLITICA_FIRMA_V2_VALUE.
     *
     * @var string
     */
    const POLITICA_FIRMA_V2_VALUE = 'dMoMvtcG5aIzgYo0tIsSQeVJBDnUnfSOfBpxXrmor0Y=';

    /**
     * C14N.
     *
     * @var string
     */
    const C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';

    /**
     * ENVELOPED_SIGNATURE.
     *
     * @var string
     */
    const ENVELOPED_SIGNATURE = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';

    /**
     * SIGNED_PROPERTIES.
     *
     * @var string
     */
    const SIGNED_PROPERTIES = 'http://uri.etsi.org/01903#SignedProperties';

    /**
     * ALGO_SHA1.
     *
     * @var array
     */
    const ALGO_SHA1 = [
        'rsa' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha1',
        'algorithm' => 'http://www.w3.org/2001/04/xmlenc#sha1',
        'sign' => OPENSSL_ALGO_SHA1,
        'hash' => 'sha1',
    ];

    /**
     * ALGO_SHA256.
     *
     * @var array
     */
    const ALGO_SHA256 = [
        'rsa' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
        'algorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
        'sign' => OPENSSL_ALGO_SHA256,
        'hash' => 'sha256',
    ];

    /**
     * ALGO_SHA512.
     *
     * @var array
     */
    const ALGO_SHA512 = [
        'rsa' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512',
        'algorithm' => 'http://www.w3.org/2001/04/xmlenc#sha512',
        'sign' => OPENSSL_ALGO_SHA512,
        'hash' => 'sha512',
    ];

    /**
     * IDS.
     *
     * @var array
     */
    protected $ids = [
        'SignedPropertiesID' => 'SIGNED-PROPS',
        'SignatureValueID' => 'SIG-VALUE',
        'SignatureID' => 'SOENAC',
        'KeyInfoID' => 'KEY-INFO',
        'ReferenceID' => 'REF',
    ];

    /**
     * NS.
     *
     * @var array
     */
    public $ns = [
        'xmlns:cac' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'xmlns:ext' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
        'xmlns:cbc' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'xmlns:sts' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures',
        'xmlns' => 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        'xmlns:xades141' => 'http://uri.etsi.org/01903/v1.4.1#',
        'xmlns:xades' => 'http://uri.etsi.org/01903/v1.3.2#',
        'xmlns:ds' => self::XMLDSIG,
    ];

    /**
     * Result signature.
     *
     * @var mixed
     */
    public $resultSignature;

    /**
     * Group of totals.
     *
     * @var string
     */
    public $groupOfTotals = 'LegalMonetaryTotal';

    /**
     * Extra certs.
     *
     * @var array
     */
    private $extracerts = [];

    public function __construct($pathCertificate = null, $passwors = null, $xmlString = null, $algorithm = self::ALGO_SHA256)
    {
        $this->algorithm = $algorithm;

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

        // DOMX path
        $this->domXPath = new DOMXPath($this->domDocument);

        // Software security code
        $this->softwareSecurityCode();

        // UUID
        $this->setUUID();

        // Digest value xml clean
        $this->digestValueXML();

        $this->extensionContentSing = $this->domDocument->documentElement->getElementsByTagName('ExtensionContent')->item(1);

        $this->signature = $this->domDocument->createElement('ds:Signature');
        $this->signature->setAttribute('xmlns:ds', self::XMLDSIG);
        $this->signature->setAttribute('Id', $this->SignatureID);
        $this->extensionContentSing->appendChild($this->signature);

        // Signed info
        $this->signedInfo = $this->domDocument->createElement('ds:SignedInfo');
        $this->signature->appendChild($this->signedInfo);

        // Signature value not value
        $this->signatureValue = $this->domDocument->createElement('ds:SignatureValue', 'ERROR!');
        $this->signatureValue->setAttribute('Id', $this->SignatureValueID);
        $this->signature->appendChild($this->signatureValue);

        // Key info
        $this->keyInfo = $this->domDocument->createElement('ds:KeyInfo');
        $this->keyInfo->setAttribute('Id', $this->KeyInfoID);
        $this->signature->appendChild($this->keyInfo);

        $this->X509Data = $this->domDocument->createElement('ds:X509Data');
        $this->keyInfo->appendChild($this->X509Data);

        $this->X509Certificate = $this->domDocument->createElement('ds:X509Certificate', $this->x509Export());
        $this->X509Data->appendChild($this->X509Certificate);

        // Object
        $this->object = $this->domDocument->createElement('ds:Object');
        $this->signature->appendChild($this->object);

        $this->qualifyingProperties = $this->domDocument->createElement('xades:QualifyingProperties');
        $this->qualifyingProperties->setAttribute('Target', "#{$this->SignatureID}");
        $this->object->appendChild($this->qualifyingProperties);

        $this->signedProperties = $this->domDocument->createElement('xades:SignedProperties');
        $this->signedProperties->setAttribute('Id', $this->SignedPropertiesID);
        $this->qualifyingProperties->appendChild($this->signedProperties);

        $this->signedSignatureProperties = $this->domDocument->createElement('xades:SignedSignatureProperties');
        $this->signedProperties->appendChild($this->signedSignatureProperties);

        $this->signingTime = $this->domDocument->createElement('xades:SigningTime', Carbon::now()->format('Y-m-d\TH:i:s.vT:00'));
        $this->signedSignatureProperties->appendChild($this->signingTime);

        $this->signingCertificate = $this->domDocument->createElement('xades:SigningCertificate');
        $this->signedSignatureProperties->appendChild($this->signingCertificate);

        // Cert
        $this->cert = $this->domDocument->createElement('xades:Cert');
        $this->signingCertificate->appendChild($this->cert);

        $this->certDigest = $this->domDocument->createElement('xades:CertDigest');
        $this->cert->appendChild($this->certDigest);

        $this->digestMethodCert = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethodCert->setAttribute('Algorithm', $this->algorithm['algorithm']);
        $this->certDigest->appendChild($this->digestMethodCert);

        $this->DigestValueCert = base64_encode(openssl_x509_fingerprint($this->certs['cert'], $this->algorithm['hash'], true));

        $this->digestValueCert = $this->domDocument->createElement('ds:DigestValue', $this->DigestValueCert);
        $this->certDigest->appendChild($this->digestValueCert);

        $this->issuerSerialCert = $this->domDocument->createElement('xades:IssuerSerial');
        $this->cert->appendChild($this->issuerSerialCert);

        $this->X509IssuerNameCert = $this->domDocument->createElement('ds:X509IssuerName', $this->joinArray(array_reverse(openssl_x509_parse($this->certs['cert'])['issuer']), false, ','));
        $this->issuerSerialCert->appendChild($this->X509IssuerNameCert);

        $this->X509SerialNumberCert = $this->domDocument->createElement('ds:X509SerialNumber', openssl_x509_parse($this->certs['cert'])['serialNumber']);
        $this->issuerSerialCert->appendChild($this->X509SerialNumberCert);

        // Extracerts
        foreach ($this->certs['extracerts'] as $key => $extracert) {
            $this->extracerts['Cert'][$key] = $this->domDocument->createElement('xades:Cert');
            $this->signingCertificate->appendChild($this->extracerts['Cert'][$key]);

            $this->extracerts['CertDigest'][$key] = $this->domDocument->createElement('xades:CertDigest');
            $this->extracerts['Cert'][$key]->appendChild($this->extracerts['CertDigest'][$key]);

            $this->extracerts['DigestMethod'][$key] = $this->domDocument->createElement('ds:DigestMethod');
            $this->extracerts['DigestMethod'][$key]->setAttribute('Algorithm', $this->algorithm['algorithm']);
            $this->extracerts['CertDigest'][$key]->appendChild($this->extracerts['DigestMethod'][$key]);

            $this->extracerts['DigestValue'][$key] = $this->domDocument->createElement('ds:DigestValue', base64_encode(openssl_x509_fingerprint($extracert, $this->algorithm['hash'], true)));
            $this->extracerts['CertDigest'][$key]->appendChild($this->extracerts['DigestValue'][$key]);

            $this->extracerts['IssuerSerial'][$key] = $this->domDocument->createElement('xades:IssuerSerial');
            $this->extracerts['Cert'][$key]->appendChild($this->extracerts['IssuerSerial'][$key]);

            $this->extracerts['X509IssuerName'][$key] = $this->domDocument->createElement('ds:X509IssuerName', $this->joinArray(array_reverse(openssl_x509_parse($extracert)['issuer']), false, ','));
            $this->extracerts['IssuerSerial'][$key]->appendChild($this->extracerts['X509IssuerName'][$key]);

            $this->extracerts['X509SerialNumber'][$key] = $this->domDocument->createElement('ds:X509SerialNumber', openssl_x509_parse($extracert)['serialNumber']);
            $this->extracerts['IssuerSerial'][$key]->appendChild($this->extracerts['X509SerialNumber'][$key]);
        }

        $this->signaturePolicyIdentifier = $this->domDocument->createElement('xades:SignaturePolicyIdentifier');
        $this->signedSignatureProperties->appendChild($this->signaturePolicyIdentifier);

        $this->signaturePolicyId = $this->domDocument->createElement('xades:SignaturePolicyId');
        $this->signaturePolicyIdentifier->appendChild($this->signaturePolicyId);

        $this->sigPolicyId = $this->domDocument->createElement('xades:SigPolicyId');
        $this->signaturePolicyId->appendChild($this->sigPolicyId);

        $this->identifier = $this->domDocument->createElement('xades:Identifier', self::POLITICA_FIRMA_V2);
        $this->sigPolicyId->appendChild($this->identifier);

        $this->sigPolicyHash = $this->domDocument->createElement('xades:SigPolicyHash');
        $this->signaturePolicyId->appendChild($this->sigPolicyHash);

        $this->digestMethodPolicy = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethodPolicy->setAttribute('Algorithm', $this->algorithm['algorithm']);
        $this->sigPolicyHash->appendChild($this->digestMethodPolicy);

        $this->digestValuePolicy = $this->domDocument->createElement('ds:DigestValue', self::POLITICA_FIRMA_V2_VALUE);
        $this->sigPolicyHash->appendChild($this->digestValuePolicy);

        $this->signerRole = $this->domDocument->createElement('xades:SignerRole');
        $this->signedSignatureProperties->appendChild($this->signerRole);

        $this->claimedRoles = $this->domDocument->createElement('xades:ClaimedRoles');
        $this->signerRole->appendChild($this->claimedRoles);

        $this->claimedRole = $this->domDocument->createElement('xades:ClaimedRole', 'supplier');
        $this->claimedRoles->appendChild($this->claimedRole);

        // Signed info nodes
        $this->canonicalizationMethod = $this->domDocument->createElement('ds:CanonicalizationMethod');
        $this->canonicalizationMethod->setAttribute('Algorithm', self::C14N);
        $this->signedInfo->appendChild($this->canonicalizationMethod);

        $this->signatureMethod = $this->domDocument->createElement('ds:SignatureMethod');
        $this->signatureMethod->setAttribute('Algorithm', $this->algorithm['rsa']);
        $this->signedInfo->appendChild($this->signatureMethod);

        $this->referenceXML = $this->domDocument->createElement('ds:Reference');
        $this->referenceXML->setAttribute('Id', $this->ReferenceID);
        $this->referenceXML->setAttribute('URI', '');
        $this->signedInfo->appendChild($this->referenceXML);

        $this->transformsXML = $this->domDocument->createElement('ds:Transforms');
        $this->referenceXML->appendChild($this->transformsXML);

        $this->transformXML = $this->domDocument->createElement('ds:Transform');
        $this->transformXML->setAttribute('Algorithm', self::ENVELOPED_SIGNATURE);
        $this->transformsXML->appendChild($this->transformXML);

        $this->digestMethodXML = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethodXML->setAttribute('Algorithm', $this->algorithm['algorithm']);
        $this->referenceXML->appendChild($this->digestMethodXML);

        $this->digestValueXML = $this->domDocument->createElement('ds:DigestValue', $this->DigestValueXML);
        $this->referenceXML->appendChild($this->digestValueXML);

        $this->domDocumentReferenceKeyInfoC14N = new DOMDocument($this->version, $this->encoding);
        $this->domDocumentReferenceKeyInfoC14N->loadXML(str_replace('<ds:KeyInfo ', "<ds:KeyInfo {$this->joinArray($this->ns)} ", $this->domDocument->saveXML($this->keyInfo)));

        $this->DigestValueKeyInfo = base64_encode(hash($this->algorithm['hash'], $this->domDocumentReferenceKeyInfoC14N->C14N(), true));

        $this->referenceKeyInfo = $this->domDocument->createElement('ds:Reference');
        $this->referenceKeyInfo->setAttribute('URI', "#{$this->KeyInfoID}");
        $this->signedInfo->appendChild($this->referenceKeyInfo);

        $this->digestMethodKeyInfo = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethodKeyInfo->setAttribute('Algorithm', $this->algorithm['algorithm']);
        $this->referenceKeyInfo->appendChild($this->digestMethodKeyInfo);

        $this->digestValueKeyInfo = $this->domDocument->createElement('ds:DigestValue', $this->DigestValueKeyInfo);
        $this->referenceKeyInfo->appendChild($this->digestValueKeyInfo);

        $this->referenceSignedProperties = $this->domDocument->createElement('ds:Reference');
        $this->referenceSignedProperties->setAttribute('Type', self::SIGNED_PROPERTIES);
        $this->referenceSignedProperties->setAttribute('URI', "#{$this->SignedPropertiesID}");
        $this->signedInfo->appendChild($this->referenceSignedProperties);

        $this->digestMethodSignedProperties = $this->domDocument->createElement('ds:DigestMethod');
        $this->digestMethodSignedProperties->setAttribute('Algorithm', $this->algorithm['algorithm']);
        $this->referenceSignedProperties->appendChild($this->digestMethodSignedProperties);

        $this->domDocumentSignedPropertiesC14N = new DOMDocument($this->version, $this->encoding);
        $this->domDocumentSignedPropertiesC14N->loadXML(str_replace('<xades:SignedProperties ', "<xades:SignedProperties {$this->joinArray($this->ns)} ", $this->domDocument->saveXML($this->signedProperties)));

        $this->DigestValueSignedProperties = base64_encode(hash($this->algorithm['hash'], $this->domDocumentSignedPropertiesC14N->C14N(), true));

        $this->digestValueSignedProperties = $this->domDocument->createElement('ds:DigestValue', $this->DigestValueSignedProperties);
        $this->referenceSignedProperties->appendChild($this->digestValueSignedProperties);

        // Signature set value
        $this->domDocumentSignatureValueC14N = new DOMDocument($this->version, $this->encoding);
        $this->domDocumentSignatureValueC14N->loadXML(str_replace('<ds:SignedInfo', "<ds:SignedInfo {$this->joinArray($this->ns)} ", $this->domDocument->saveXML($this->signedInfo)));

        openssl_sign($this->domDocumentSignatureValueC14N->C14N(), $this->resultSignature, $this->certs['pkey'], $this->algorithm['sign']);

        $this->signatureValue->nodeValue = base64_encode($this->resultSignature);
    }

    /**
     * Digest value XML.
     */
    private function digestValueXML()
    {
        $this->DigestValueXML = base64_encode(hash($this->algorithm['hash'], $this->domDocument->C14N(), true));
    }

    /**
     * Software security code.
     */
    private function softwareSecurityCode()
    {
        if (is_null($this->softwareID) || is_null($this->pin)) {
            return;
        }

        $this->getTag('SoftwareSecurityCode', 0)->nodeValue = hash('sha384', "{$this->softwareID}{$this->pin}{$this->getTag('ID', 0)->nodeValue}");
    }

    /**
     * set UUID.
     */
    private function setUUID()
    {
        // Register name space
        foreach ($this->ns as $key => $value) {
            $this->domXPath->registerNameSpace($key, $value);
        }

        if ((!is_null($this->pin)) && (is_null($this->technicalKey))) {
            $this->cude();
        }
        if (!is_null($this->technicalKey)) {
            $this->cufe();
        }
    }

    /**
     * CUFE.
     */
    private function cufe()
    {
        $this->getTag('UUID', 0)->nodeValue = hash('sha384', "{$this->getTag('ID', 0)->nodeValue}{$this->getTag('IssueDate', 0)->nodeValue}{$this->getTag('IssueTime', 0)->nodeValue}{$this->getQuery("cac:{$this->groupOfTotals}/cbc:LineExtensionAmount")->nodeValue}01".($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=01]/cbc:TaxAmount', false)->nodeValue ?? '0.00').'04'.($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=04]/cbc:TaxAmount', false)->nodeValue ?? '0.00').'03'.($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=03]/cbc:TaxAmount', false)->nodeValue ?? '0.00')."{$this->getQuery("cac:{$this->groupOfTotals}/cbc:PayableAmount")->nodeValue}{$this->getQuery('cac:AccountingSupplierParty/cac:Party/cac:PartyTaxScheme/cbc:CompanyID')->nodeValue}{$this->getQuery('cac:AccountingCustomerParty/cac:Party/cac:PartyTaxScheme/cbc:CompanyID')->nodeValue}{$this->technicalKey}{$this->getTag('ProfileExecutionID', 0)->nodeValue}");
    }

    /**
     * Cude.
     */
    private function cude()
    {
        $this->getTag('UUID', 0)->nodeValue = hash('sha384', "{$this->getTag('ID', 0)->nodeValue}{$this->getTag('IssueDate', 0)->nodeValue}{$this->getTag('IssueTime', 0)->nodeValue}{$this->getQuery("cac:{$this->groupOfTotals}/cbc:LineExtensionAmount")->nodeValue}01".($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=01]/cbc:TaxAmount', false)->nodeValue ?? '0.00').'04'.($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=04]/cbc:TaxAmount', false)->nodeValue ?? '0.00').'03'.($this->getQuery('cac:TaxTotal[cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID=03]/cbc:TaxAmount', false)->nodeValue ?? '0.00')."{$this->getQuery("cac:{$this->groupOfTotals}/cbc:PayableAmount")->nodeValue}{$this->getQuery('cac:AccountingSupplierParty/cac:Party/cac:PartyTaxScheme/cbc:CompanyID')->nodeValue}{$this->getQuery('cac:AccountingCustomerParty/cac:Party/cac:PartyTaxScheme/cbc:CompanyID')->nodeValue}{$this->pin}{$this->getTag('ProfileExecutionID', 0)->nodeValue}");
    }
}
