<?php


namespace Stenfrank\UBL21dian\HTTP\DOM\Response;

/**
 * Class GetNumberingRangeResponseDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Response
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class GetNumberingRangeResponseDOM extends BasicResponseDOM
{

    protected function registerNS($namespacese = array())
    {
        $namespacesenew = [
          'b' => 'http://schemas.datacontract.org/2004/07/NumberRangeResponseList',
          'i' => 'http://www.w3.org/2001/XMLSchema-instance',
          'c' => 'http://schemas.datacontract.org/2004/07/NumberRangeResponse'
        ];
        parent::registerNS($namespacesenew); // TODO: Change the autogenerated stub
    }

    public function getOperationCode()
    {
        return $this->getQuery("//b:OperationCode")->nodeValue;
    }

    public function getOperationDescription()
    {
        return $this->getQuery("//b:OperationDescription")->nodeValue;
    }

}