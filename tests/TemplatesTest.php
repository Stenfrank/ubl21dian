<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\HTTP\DOM\Request\GetNumberingRangeRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\GetStatusRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\GetStatusZipRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\SendBillAsyncRequestDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Request\SendTestSetAsyncRequestDOM;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatus;
use Stenfrank\UBL21dian\Templates\SOAP\GetStatusZip;
use Stenfrank\UBL21dian\Templates\SOAP\SendBillAsync;
use Stenfrank\UBL21dian\Templates\SOAP\SendTestSetAsync;

/**
 * Templates test.
 */
class TemplatesTest extends TestCase
{

    /**
     * @test
     */
    public function generate_template_dom_request_send_test_bill_async()
    {
        $domRequest = new SendTestSetAsyncRequestDOM($this->pathCert,$this->passwordCert);
        $domRequest->fileName = "Test.xml";
        $domRequest->contentFile = "base64_fileZIP";
        $domRequest->testSetId = "xxxxxxxxxxxxxxxxxxx";


        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($domRequest->getTemplate()));
        $this->assertContains('<wcf:fileName>Test.xml</wcf:fileName>', $domRequest->getTemplate());
        $this->assertContains('<wcf:contentFile>base64_fileZIP</wcf:contentFile>', $domRequest->getTemplate());
        $this->assertContains('<wcf:contentFile>base64_fileZIP</wcf:contentFile>', $domRequest->getTemplate());
        $this->assertContains('<wcf:testSetId>xxxxxxxxxxxxxxxxxxx</wcf:testSetId>', $domRequest->getTemplate());
    }


    /**
     * Test de la generacion de la plantilla de envio asincronico
     * @test
     */
    public function it_generates_template_send_bill_async()
    {

        $sendBillAsync = new SendBillAsyncRequestDOM($this->pathCert, $this->passwordCert);
        $sendBillAsync->fileName = 'Test.xml';
        $sendBillAsync->contentFile = 'base64_fileZIP';

        // Sign
        $sendBillAsync->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($sendBillAsync->xml));
        $this->assertContains('<wcf:fileName>Test.xml</wcf:fileName>', $sendBillAsync->xml);
        $this->assertContains('<wcf:contentFile>base64_fileZIP</wcf:contentFile>', $sendBillAsync->xml);

        file_put_contents(__DIR__. '/outputs/SENDBILLASYNC.xml', $sendBillAsync->xml);
    }

    /**
     * tesrt para la generacion de template de envio asincronico
     * @test
     */
    public function it_generates_template_send_test_set_async()
    {
        $sendTestSetAsync = new SendTestSetAsyncRequestDOM($this->pathCert, $this->passwordCert);
        $sendTestSetAsync->fileName = 'Test.xml';
        $sendTestSetAsync->contentFile = 'base64_fileZIP';
        $sendTestSetAsync->testSetId = 'xxxxxxxxxxxxxxxxxxx';

        // Sign
        $sendTestSetAsync->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($sendTestSetAsync->xml));
        $this->assertContains('<wcf:fileName>Test.xml</wcf:fileName>', $sendTestSetAsync->xml);
        $this->assertContains('<wcf:contentFile>base64_fileZIP</wcf:contentFile>', $sendTestSetAsync->xml);
        $this->assertContains('<wcf:testSetId>xxxxxxxxxxxxxxxxxxx</wcf:testSetId>', $sendTestSetAsync->xml);

        file_put_contents(__DIR__. '/outputs/SENDTESTSETASYNC.xml', $sendTestSetAsync->xml);
    }

    /**
     * test para el status del zip
     * @test
     */
    public function it_generates_template_get_status_zip()
    {


        $getStatusZip = new GetStatusZipRequestDOM($this->pathCert, $this->passwordCert);
        $getStatusZip->trackId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        // Sign
        $getStatusZip->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($getStatusZip->xml));
        $this->assertContains('<wcf:trackId>xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</wcf:trackId>', $getStatusZip->xml);

        file_put_contents(__DIR__. '/outputs/GETSTATUSZIP.xml', $getStatusZip->xml);

    }

    /**
     * @test
     */
    public function it_generates_template_get_status()
    {

        $getStatus = new GetStatusRequestDOM($this->pathCert, $this->passwordCert);
        $getStatus->trackId = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        // Sign
        $getStatus->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($getStatus->xml));
        $this->assertContains('<wcf:trackId>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</wcf:trackId>', $getStatus->xml);

        file_put_contents(__DIR__. '/outputs/GETSTATUS.xml', $getStatus->xml);
    }

    /**
     * @test
     */
    public function generate_tamplate_get_numbering_range()
    {
        $getNumbering = new GetNumberingRangeRequestDOM($this->pathCert,$this->passwordCert);
        $getNumbering->accountCode = "ACOUNT_CODE";
        $getNumbering->accountCodeT = "ACOUNT_CODE_PROVIDER";
        $getNumbering->softwareCode = "SOFTWARE_CODE";

        $getNumbering->sign();

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertSame(true, $domDocumentValidate->loadXML($getNumbering->xml));
        $this->assertContains("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary",$getNumbering->xml);
        $this->assertContains("<wcf:accountCode>ACOUNT_CODE</wcf:accountCode>",$getNumbering->xml);
        $this->assertContains("<wcf:accountCodeT>ACOUNT_CODE_PROVIDER</wcf:accountCodeT>",$getNumbering->xml);
        $this->assertContains("<wcf:softwareCode>SOFTWARE_CODE</wcf:softwareCode>",$getNumbering->xml);

    }
}
