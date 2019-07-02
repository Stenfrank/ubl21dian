<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\BinarySecurityToken\SOAP;

/**
 * Sign soap test.
 */
class SignSoapTest extends TestCase
{
    /**
     * XML Template.
     *
     * @var string
     */
    private $xmlString = <<<XML
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:wcf="http://wcf.dian.colombia">
<soap:Header/>
<soap:Body>
  <wcf:GetStatus>
     <!--Optional:-->
     <wcf:trackId>123456666</wcf:trackId>
  </wcf:GetStatus>
</soap:Body>
</soap:Envelope>
XML;

    /** @test */
    public function it_generates_signature_soap_with_string()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $soap21 = new SOAP($pathCertificate, $passwors, $this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($soap21->xml));
    }

    /** @test */
    public function it_generates_signature_soap_with_instance_of_dom_document()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $domDocument = new DOMDocument();
        $domDocument->loadXML($this->xmlString);

        $soap21 = new SOAP($pathCertificate, $passwors);
        $soap21->Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatus';
        $soap21->To = 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc';
        $soap21->CurrentTime = time();
        $soap21->TimeToLive = 60000;

        $soap21->sign($domDocument->saveXML());

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($soap21->xml));
    }
}
