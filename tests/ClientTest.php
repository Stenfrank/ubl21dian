<?php
namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\Client;
use Stenfrank\UBL21dian\HTTP\DOM\Request\GetNumberingRangeRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\GetStatusRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\SendTestSetAsyncRequestDOM;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatus;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatusZip;

/**
 * Client test.
 */
class ClientTest extends TestCase
{
    /** @test
     * @throws \Stenfrank\UBL21dian\Exceptions\CurlException
     */
    public function can_consume_web_service_dian()
    {

        $getStatusZip = new GetStatusRequestDOM($this->pathCert, $this->passwordCert);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign
        $getStatusZip->sign();

        $client = new Client($getStatusZip->To,$getStatusZip->xml);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($client->getResponse()));
        $this->assertContains('TrackId no existe en los registros de la DIAN.', $client->getResponse());
    }

    /** @test */
    public function a_soap_template_can_consume_web_service_dian()
    {
        $getStatusZip = new GetStatusRequestDOM($this->pathCert, $this->passwordCert);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign to send
        $responseDOM = $getStatusZip->signToSend();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $responseString = $responseDOM->getDomDocument()->saveXML();
        $this->assertSame(true, $domDocumentValidate->loadXML($responseString));
        $this->assertContains('TrackId no existe en los registros de la DIAN.', $responseString);
    }

    /**
     * @test
     */
    public function send_sing_template_dom_request_send_test_bill_async()
    {
        $domRequest = new SendTestSetAsyncRequestDOM($this->pathCert,$this->passwordCert);
        $domRequest->fileName = "invoice_signed_dian_v2.zip";
        $domRequest->contentFile = base64_encode(file_get_contents(__DIR__."/resources/zips/invoice_signed_dian_v2.zip"));
        $domRequest->testSetId = "xxxxxxxxxxxxxxxxxxx";
        $responseDom = $domRequest->signToSend();

        $stringResponse = $responseDom->getDomDocument()->saveXML();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;
        $this->assertSame(true, $domDocumentValidate->loadXML($stringResponse));

        $this->assertContains("Set de prueba con identificador xxxxxxxxxxxxxxxxxxx es incorrecto.",$stringResponse);
    }

    /**
     * @test
     */
    public function send_numbering_range_faild()
    {
        $domRequest = new GetNumberingRangeRequestDOM($this->pathCert,$this->passwordCert);
        $domRequest->accountCode = "1111111111111";
        $domRequest->accountCodeT = "222222222222";
        $domRequest->softwareCode = "fc8eac422eba16e2sasdadadasda";

        $responseDOM = $domRequest->signToSend();

        $stringResponse = $responseDOM->getDomDocument()->saveXML();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;
        $this->assertSame(true, $domDocumentValidate->loadXML($stringResponse));

        $this->assertContains("no autorizado para consultar rangos de numeraci&#xF3;n del NIT: 1111111111111",$stringResponse);
        $this->assertContains("NIT: 8300448582 no autorizado para consultar rangos de numeración del NIT: 1111111111111",$responseDOM->getOperationDescription());

    }

    /**
     * @test
     */
    public function send_numbering_range_sucess()
    {
        $domRequest = new GetNumberingRangeRequestDOM($this->pathCert,$this->passwordCert);
        $domRequest->accountCode = getenv('ACCOUNT_CODE');
        $domRequest->accountCodeT = getenv('ACCOUNT_CODE');
        $domRequest->softwareCode = getenv("SOFTWARE_IDENTIFICATION");

        $responseDOM = $domRequest->signToSend();

        $stringResponse = $responseDOM->getDomDocument()->saveXML();

       //file_put_contents(__DIR__. "/outputs/response_getnumering_ok.xml",$stringResponse);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;
        $this->assertSame(true, $domDocumentValidate->loadXML($stringResponse));

        //$this->assertContains("no autorizado para consultar rangos de numeraci&#xF3;n del NIT: 1111111111111",$stringResponse);
        //$this->assertContains("NIT: 8300448582 no autorizado para consultar rangos de numeración del NIT: 1111111111111",$responseDOM->getOperationDescription());

    }


}
