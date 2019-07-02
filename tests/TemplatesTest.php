<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatus;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatusZip;
use Stenfrank\UBL21dian\Templates\SOAP\SendBillAsync;
use Stenfrank\UBL21dian\Templates\SOAP\SendTestSetAsync;

/**
 * Templates test.
 */
class TemplatesTest extends TestCase
{
    /** @test */
    public function it_generates_template_send_bill_async()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $sendBillAsync = new SendBillAsync($pathCertificate, $passwors);
        $sendBillAsync->fileName = 'Test.xml';
        $sendBillAsync->contentFile = 'base64';

        // Sign
        $sendBillAsync->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($sendBillAsync->xml));
        $this->assertContains('<wcf:fileName>Test.xml</wcf:fileName>', $sendBillAsync->xml);
        $this->assertContains('<wcf:contentFile>base64</wcf:contentFile>', $sendBillAsync->xml);

        // file_put_contents('/home/frank/public_html/Project/ubl-21-dian/SENDBILLASYNC.xml', $sendBillAsync->xml);
    }

    /** @test */
    public function it_generates_template_send_test_set_async()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $sendTestSetAsync = new SendTestSetAsync($pathCertificate, $passwors);
        $sendTestSetAsync->fileName = 'Test.xml';
        $sendTestSetAsync->contentFile = 'base64';
        $sendTestSetAsync->testSetId = 'xxxxxxxxxxxxxxxxxxx';

        // Sign
        $sendTestSetAsync->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($sendTestSetAsync->xml));
        $this->assertContains('<wcf:fileName>Test.xml</wcf:fileName>', $sendTestSetAsync->xml);
        $this->assertContains('<wcf:contentFile>base64</wcf:contentFile>', $sendTestSetAsync->xml);
        $this->assertContains('<wcf:testSetId>xxxxxxxxxxxxxxxxxxx</wcf:testSetId>', $sendTestSetAsync->xml);

        // file_put_contents('/home/frank/public_html/Project/ubl-21-dian/SENDTESTSETASYNC.xml', $sendTestSetAsync->xml);
    }

    /** @test */
    public function it_generates_template_get_status_zip()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $getStatusZip = new GetStatusZip($pathCertificate, $passwors);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign
        $getStatusZip->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($getStatusZip->xml));
        $this->assertContains('<wcf:trackId>xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</wcf:trackId>', $getStatusZip->xml);

        // file_put_contents('/home/frank/public_html/Project/ubl-21-dian/GETSTATUSZIP.xml', $getStatusZip->xml);
    }

    /** @test */
    public function it_generates_template_get_status()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $getStatus = new GetStatus($pathCertificate, $passwors);
        $getStatus->trackId = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        // Sign
        $getStatus->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($getStatus->xml));
        $this->assertContains('<wcf:trackId>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</wcf:trackId>', $getStatus->xml);

        // file_put_contents('/home/frank/public_html/Project/ubl-21-dian/GETSTATUS.xml', $getStatus->xml);
    }
}
