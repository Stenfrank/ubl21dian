<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\Client;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatus;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatusZip;
use Stenfrank\UBL21dian\Templates\SOAP\SendTestSetAsync;

/**
 * Client test.
 */
class ClientTest extends TestCase
{
    /** @test */
    public function can_consume_web_service_dian()
    {

        $getStatusZip = new GetStatusZip($this->pathCert, $this->passwordCert);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign
        $getStatusZip->sign();

        $client = new Client($getStatusZip);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($client->getResponse()));
        $this->assertContains('TrackId no existe en los registros de la DIAN.', $client->getResponse());
    }

    /** @test */
    public function a_soap_template_can_consume_web_service_dian()
    {
        $getStatusZip = new GetStatus($this->pathCert, $this->passwordCert);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign to send
        $client = $getStatusZip->signToSend();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($client->getResponse()));
        $this->assertContains('TrackId no existe en los registros de la DIAN.', $client->getResponse());
    }

    /**
     * @test
     */
    public function sendInovice()
    {



        $pathInvoice = __DIR__ . "/resources/zips/ws_f08300448580000000003.zip";
        $base64Inovice = base64_encode(file_get_contents($pathInvoice));
        $sendBillAsync = new SendTestSetAsync($this->pathCert, $this->passwordCert);

        $sendBillAsync->fileName = 'ws_f08300448580000000003.zip';

        $sendBillAsync->contentFile = $base64Inovice;
        $sendBillAsync->testSetId = "8d0dcb81-e085-49e9-9b37-a5310811848a";



        // Sign
        $client = $sendBillAsync->signToSend();

        file_put_contents(__DIR__. "/outputs/request_invoice_firmado_sergio.xml",$sendBillAsync->xml);
        $object = $client->getResponse();

        $document = new DOMDocument();
        $document->loadXML($object);
        $document->formatOutput = true;
        $document->save( __DIR__ . "/outputs/testenvio_invoice_firmado_sergio.xml");
        var_dump($object);
    }
}
