<?php


namespace Stenfrank\UBL21dian\HTTP\DOM\Response;

/**
 * Class SendBillResponseFather
 * @package Stenfrank\UBL21dian\HTTP\DOM\Response
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class SendBillResponseFatherDOM extends BasicResponseDOM
{

    public function getDocumentKey()
    {
        return $this->getQuery("//b:ErrorMessageList/c:XmlParamsResponseTrackId/c:DocumentKey")->nodeValue;
    }

    public function getProcessedMessage()
    {
        return $this->getQuery("//b:ErrorMessageList/c:XmlParamsResponseTrackId/c:ProcessedMessage")->nodeValue;
    }

    public function getSuccess()
    {
        $nodeValue = $this->getQuery("//b:ErrorMessageList/c:XmlParamsResponseTrackId/c:Success")->nodeValue;

        if($nodeValue == "false"){
            return false;
        }
        return true;

    }

    public function getXmlFileName()
    {
        return $this->getQuery("//b:ErrorMessageList/c:XmlParamsResponseTrackId/c:XmlFileName")->nodeValue;
    }

    public function getZipKey()
    {
        return $this->getQuery("//b:ZipKey")->nodeValue;
    }



}