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


    private $fileName;
    private $contentFile;
    private $testSetId;

    /**
     * SendTestSetAsyncRequestDOM constructor.
     * @param $fileName
     * @param $contentFile
     * @param $testSetId
     */
    public function __construct($fileName, $contentFile, $testSetId)
    {
        $this->fileName = $fileName;
        $this->contentFile = $contentFile;
        $this->testSetId = $testSetId;
        parent::__construct();
    }


    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected function build()
    {
        $this->domDocument->getElementsByTagName("fileName")->item(0)->nodeValue = $this->fileName;
        $this->domDocument->getElementsByTagName("contentFile")->item(0)->nodeValue = $this->contentFile;
        $this->domDocument->getElementsByTagName("testSetId")->item(0)->nodeValue = $this->testSetId;
    }

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    public function getClassResponse()
    {
        return SendTestSetAsyncResponseDOM::class;
    }


    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    public function getNameTemplate(): string
    {
        return "SendTestSetAsync";
    }
}