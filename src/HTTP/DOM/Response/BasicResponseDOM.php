<?php


namespace Stenfrank\UBL21dian\HTTP\DOM\Response;

/**
 * Class BasicResponseDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Response
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
abstract class BasicResponseDOM
{

    protected $domDocument;


    protected $created;

    protected $expires;


    public function __construct(\DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
    }

    public function getDomDocument()
    {
        return $this->domDocument;
    }


}