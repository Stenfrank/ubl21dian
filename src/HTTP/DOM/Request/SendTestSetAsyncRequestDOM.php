<?php
namespace Stenfrank\UBL21dian\HTTP\DOM\Request;
use Stenfrank\UBL21dian\HTTP\DOM\Response\SendTestSetAsyncResponseDOM;

/**
 * Class SendTestSetAsyncRequestDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Request
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class SendTestSetAsyncRequestDOM extends BasicRequestDOM
{

    /**
     * Action.
     *
     * @var string
     */
    public $Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/SendTestSetAsync';

    /**
     * Required properties.
     *
     * @var array
     */
    protected $requiredProperties = [
        'fileName',
        'contentFile',
        'testSetId',
    ];


    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected function build()
    {
        $this->domTemplate->getElementsByTagName("fileName")->item(0)->nodeValue = $this->fileName;
        $this->domTemplate->getElementsByTagName("contentFile")->item(0)->nodeValue = $this->contentFile;
        $this->domTemplate->getElementsByTagName("testSetId")->item(0)->nodeValue = $this->testSetId;
    }

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    protected function getClassResponse()
    {
        return SendTestSetAsyncResponseDOM::class;
    }


    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    protected function getNameTemplate(): string
    {
        return "SendTestSetAsync";
    }
}