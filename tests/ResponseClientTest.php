<?php
namespace Stenfrank\Tests;
use Stenfrank\UBL21dian\HTTP\DOM\Response\GetStatusZipResponseDOM;
use Stenfrank\UBL21dian\HTTP\DOM\Response\SendTestSetAsyncResponseDOM;

/**
 * Class ResponseClientTest
 * @package Stenfrank\Tests
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class ResponseClientTest extends TestCase
{

    /**
     * @test
     */
    public function extract_response_sendTestAsync_data_dom()
    {

        $domResponse = new \DOMDocument();
        $domResponse->load(__DIR__."/resources/responses/response_sendtestasys_with_error.xml");
        $responseObjectDom = new SendTestSetAsyncResponseDOM($domResponse,200);

        $this->assertTrue($responseObjectDom->getStatusHTTPCode() == 200);
        $this->assertEquals("2019-08-16T21:19:50.120Z",$responseObjectDom->getCreated());
        $this->assertEquals("2019-08-16T21:24:50.120Z",$responseObjectDom->getExpires());
        $this->assertEquals("604128a1bbf0a49098411bbcd6c7b059e4d35cd1",$responseObjectDom->getDocumentKey());
        $this->assertFalse($responseObjectDom->getSuccess());
        $this->assertEquals("invoiceSigned_sergiov2",$responseObjectDom->getXmlFileName());


    }

    /**
     * @test
     */
    public function extract_response_success_sendTestAsync_data_dom()
    {

        $domResponse = new \DOMDocument();
        $domResponse->load(__DIR__."/resources/responses/response_sendtestasync_success.xml");
        $responseObjectDom = new SendTestSetAsyncResponseDOM($domResponse,200);

        $this->assertTrue($responseObjectDom->getStatusHTTPCode() == 200);
        $this->assertEquals("ef9998bd-8081-4882-9762-3d7cc53ac497",$responseObjectDom->getZipKey());

    }

    /**
     * @test
     */
    public function extract_response_get_status_zip_with_errors()
    {
        $domResponse = new \DOMDocument();
        $domResponse->load(__DIR__."/resources/responses/response_getstatusZip_with_errors.xml");

        $responseObjectDom = new GetStatusZipResponseDOM($domResponse,200);

        $this->assertIsArray($responseObjectDom->getErrors());

        $errors = $responseObjectDom->getErrors();
        $this->assertTrue(count($errors) > 0);
        $this->assertContains("Regla: FAJ26, Rechazo: Responsabilidad informada por emisor no valida según lista",$errors[0]);
    }


}