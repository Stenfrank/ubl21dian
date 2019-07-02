<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\Client;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatus;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatusZip;

/**
 * Client test.
 */
class ClientTest extends TestCase
{
    /** @test */
    public function can_consume_web_service_dian()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $getStatusZip = new GetStatusZip($pathCertificate, $passwors);
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
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $getStatusZip = new GetStatus($pathCertificate, $passwors);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign to send
        $client = $getStatusZip->signToSend();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($client->getResponse()));
        $this->assertContains('TrackId no existe en los registros de la DIAN.', $client->getResponse());
    }
}
