<?php


namespace Stenfrank\UBL21dian\HTTP\DOM\Request;

/**
 * Class BasicRequestDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Request
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
abstract class BasicRequestDOM
{
    /**
     * @var \DOMDocument
     */
    protected $domDocument;

    protected $pathTemplate;

    /**
     * BasicRequestDOM constructor.
     * @param $sessionID
     */
    public function __construct()
    {
        $this->domDocument = new \DOMDocument();
        $this->pathTemplate =  dirname(__DIR__,4) . "/resources/templates/soap/";
        $this->load();
        $this->build();
    }

    /**
     * Se encarga de cargar la plantilla
     */
    private function load()
    {
        $this->domDocument->load($this->pathTemplate . $this->getNameTemplate() . ".xml");
    }

    /**
     * Devuelve el nombre de la plantilla XML
     * @return String
     */
    public abstract function getNameTemplate(): string;


    /**
     *  Se encarga de llenar el XML con los datos
     */
    protected abstract function build();

    /**
     * Devuelve la clase response que va a tener el request
     * @return mixed
     */
    public abstract function getClassResponse();




    /**
     * @return \DOMDocument
     */
    public function getDomDocument():\DOMDocument
    {
        return $this->domDocument;
    }

    /**
     * Devuelve el documento en formato String
     * @return string
     */
    public function getString()
    {
        $element = $this->domDocument->firstChild;
        return $this->domDocument->saveXML($element);

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }

}