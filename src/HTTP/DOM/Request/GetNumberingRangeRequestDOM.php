<?php
namespace Stenfrank\UBL21dian\HTTP\DOM\Request;
use Stenfrank\UBL21dian\HTTP\DOM\Response\GetNumberingRangeResponseDOM;

/**
 * Class GetNumberingRangeRequestDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Request
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class GetNumberingRangeRequestDOM extends BasicRequestDOM
{

    /**
     * Action.
     *
     * @var string
     */
    public $Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetNumberingRange';

    /**
     * Required properties.
     *
     * @var array
     */
    protected $requiredProperties = [
        'accountCode', //Numero de identificacion tributaria, sin punto ni separaciones (NIT)
        'accountCodeT', //numero de identificacion tributaria del duseño del software (NIT)
        'softwareCode' //numero de identificacion del software que genera las facturas electronicas alfanumerica de 16 caracteres
    ];

    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    protected function getNameTemplate(): string
    {
        return "GetNumberingRange";
    }

    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected function build()
    {
        $this->domTemplate->getElementsByTagName("accountCode")->item(0)->nodeValue = $this->accountCode;
        $this->domTemplate->getElementsByTagName("accountCodeT")->item(0)->nodeValue = $this->accountCodeT;
        $this->domTemplate->getElementsByTagName("softwareCode")->item(0)->nodeValue = $this->softwareCode;
    }

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    protected function getClassResponse()
    {
        return GetNumberingRangeResponseDOM::class;
    }
}