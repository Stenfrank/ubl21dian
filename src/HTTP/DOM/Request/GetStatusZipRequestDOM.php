<?php
namespace Stenfrank\UBL21dian\HTTP\DOM\Request;
use Stenfrank\UBL21dian\HTTP\DOM\Response\GetStatusZipResponseDOM;

/**
 * Class GetStatusZipRequestDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Request
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class GetStatusZipRequestDOM extends BasicRequestDOM
{

    /**
     * Action.
     *
     * @var string
     */
    public $Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatusZip';

    /**
     * Required properties.
     *
     * @var array
     */
    protected $requiredProperties = [
        'trackId',
    ];


    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    protected function getNameTemplate(): string
    {
        return "GetStatusZip";
    }

    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected function build()
    {
        $this->domTemplate->getElementsByTagName("trackId")->item(0)->nodeValue = $this->trackId;
    }

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    protected function getClassResponse()
    {
        return GetStatusZipResponseDOM::class;
    }
}