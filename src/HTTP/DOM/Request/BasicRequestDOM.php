<?php
namespace Stenfrank\UBL21dian\HTTP\DOM\Request;
use Stenfrank\UBL21dian\BinarySecurityToken\SOAP;
use Stenfrank\UBL21dian\Client;
use Stenfrank\UBL21dian\Exceptions\RequiredPropertyTemplateRequest;
use Stenfrank\UBL21dian\HTTP\DOM\Response\BasicResponseDOM;
use Stenfrank\UBL21dian\HTTP\Request;
use Stenfrank\UBL21dian\HTTP\Response;

/**
 * Class BasicRequestDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Request
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
abstract class BasicRequestDOM extends SOAP
{

    /**
     * Path donde se encuentra la plantoilla
     * @var string
     */
    protected $pathTemplate;

    /**
     * Documento de la plantilla sin firmar
     * @var \DOMDocument
     */
    protected $domTemplate;


    /**
     * Cliente de la peticion
     * @var
     */
    protected $client;

    /**
     * To.
     *
     * @var string
     */
    public $To = 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc?wsdl';


    /**
     * BasicRequestDOM constructor.
     * @param $pathCertificate
     * @param $passwors
     */
    public function __construct($pathCertificate, $passwors)
    {
        parent::__construct($pathCertificate, $passwors);
        $this->domTemplate = new \DOMDocument();
        $this->pathTemplate =  dirname(__DIR__,4) . "/resources/templates/soap/";
        $this->load();
    }

    /**
     * Se encarga de cargar la plantilla
     */
    private function load()
    {
        $this->domTemplate->load($this->pathTemplate . $this->getNameTemplate() . ".xml");
    }

    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    protected abstract function getNameTemplate(): string;


    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected abstract function build();

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    protected abstract function getClassResponse();



    /**
     * Required properties.
     * @throws RequiredPropertyTemplateRequest
     */
    private function requiredProperties()
    {
        foreach ($this->requiredProperties as $requiredProperty) {
            if (is_null($this->$requiredProperty)) {
                throw new RequiredPropertyTemplateRequest($requiredProperty);
            }
        }
    }

    /**
     * Devuelve el documento en formato String
     * @return string
     */
    public function getTemplate()
    {
        $this->requiredProperties();
        $this->build();
        $element = $this->domTemplate->firstChild;
        return $this->domTemplate->saveXML($element);

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTemplate();
    }



    /**
     * Sign.
     *
     * @return \Stenfrank\UBL21dian\BinarySecurityToken\SOAP
     */
    public function sign($string = null): SOAP
    {
        return parent::sign($this->getTemplate());
    }


    /**
     * @return Response
     * @throws \Stenfrank\UBL21dian\Exceptions\CurlException
     */
    public function signToSend(): Response
    {
        parent::sign($this->getTemplate());

        $this->client = new Request($this->To,$this->xml);
        $this->client->send($this->getClassResponse());
        return $this->client->getResponse();

    }





}