<?php
namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\Client;
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


}
