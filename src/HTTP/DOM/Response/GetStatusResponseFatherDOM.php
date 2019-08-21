<?php


namespace Stenfrank\UBL21dian\HTTP\DOM\Response;

/**
 * Class GetStatusResponseFatherDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Response
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class GetStatusResponseFatherDOM extends BasicResponseDOM
{

    /**
     * Se encarga de registrar los namespaces del response
     */
    protected function registerNS($namespacese = array())
    {
        $namesapces = [
            "b" => "http://schemas.datacontract.org/2004/07/DianResponse",
            "i" => "http://www.w3.org/2001/XMLSchema-instance",
            "c" => "http://schemas.microsoft.com/2003/10/Serialization/Arrays"
        ];

        parent::registerNS($namesapces);
    }


    /**
     * @return bool
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getIsValid()
    {

        $value = $this->getQuery("//b:DianResponse/b:IsValid")->nodeValue;
        if($value == "false"){
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getStatusCode()
    {
        return $this->getQuery("//b:DianResponse/b:StatusCode")->nodeValue;
    }

    /**
     * @return mixed
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getStatusDescription()
    {
        return $this->getQuery("//b:DianResponse/b:StatusDescription")->nodeValue;
    }

    /**
     * @return mixed
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getXmlBase64Bytes()
    {
        return $this->getQuery("//b:DianResponse/b:XmlBase64Bytes")->nodeValue;
    }

    /**
     * @return mixed
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getXmlDocumentKey()
    {
        return $this->getQuery("//b:DianResponse/b:XmlDocumentKey")->nodeValue;
    }

    /**
     * @return mixed
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getXmlFileName()
    {
        return $this->getQuery("//b:DianResponse/b:XmlFileName")->nodeValue;
    }

    /**
     * @return array
     * @throws \Stenfrank\UBL21dian\Exceptions\QueryNotFountException
     */
    public function getErrors()
    {
        $elementMessage = $this->getQuery("//b:ErrorMessage/c:string",true,null);
        $strings = [];
        foreach ($elementMessage as $element){
            $strings[] = $element->nodeValue;
        }
        return $strings;

    }
}