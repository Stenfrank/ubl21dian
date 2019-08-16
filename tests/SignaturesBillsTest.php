<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\XAdES\SignInvoice;

/**
 * Signatures Bills Test.
 */
class SignaturesBillsTest extends TestCase
{

    /**
     * Dom del archivo de la factura sin firmar
     * @var
     */
    private $domInvoiceUnsigned;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->domInvoiceUnsigned = new DOMDocument();
        $this->domInvoiceUnsigned->load(__DIR__ . "/resources/invoices/invoice_unsigned_dian_v2.xml");

    }


    /**
     * @return mixed
     */
    private function getStringInvoiceUnsigned()
    {
        return $this->domInvoiceUnsigned->saveXML();

    }



    /** @test */
    public function it_generates_signature_XAdES_sha1()
    {



        $signInvoice = new SignInvoice($this->pathCert, $this->passwordCert, $this->getStringInvoiceUnsigned(), SignInvoice::ALGO_SHA1);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha1"', $signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha256()
    {

        $signInvoice = new SignInvoice($this->pathCert, $this->passwordCert,  $this->getStringInvoiceUnsigned());

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"', $signInvoice->xml);

        file_put_contents(__DIR__."/outputs/invoiceSignedv2.xml",$signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha512()
    {


        $signInvoice = new SignInvoice($this->pathCert, $this->passwordCert, $this->getStringInvoiceUnsigned(), SignInvoice::ALGO_SHA512);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha512"', $signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_software_security_code()
    {

        $signInvoice = new SignInvoice($this->pathCert, $this->passwordCert);

        // Software security code
        $signInvoice->softwareID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        $signInvoice->pin = '12345';

        // Sign
        $signInvoice->sign($this->getStringInvoiceUnsigned());

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('54d15940890b5ed395e7476d294a972cee650200942adb7a899de35cb0cf22f98831e7c9a95d90b961c6845340b09efd', $signInvoice->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_calculate_cufe()
    {

        $signInvoice = new SignInvoice($this->pathCert, $this->passwordCert);

        // CUFE
        $signInvoice->technicalKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        // Sign
        $signInvoice->sign($this->getStringInvoiceUnsigned());

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('d48db2461fc229b54a1b92df388fc66844d23cddd120bf0cc952dda6243c9db9046b23ca3cea0185aac2a985d1fb6c7c', $signInvoice->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

}
