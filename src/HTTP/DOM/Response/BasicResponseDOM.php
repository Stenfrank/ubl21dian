<?php
namespace Stenfrank\UBL21dian\HTTP\DOM\Response;
use Stenfrank\UBL21dian\Exceptions\QueryNotFountException;

/**
 * Class BasicResponseDOM
 * @package Stenfrank\UBL21dian\HTTP\DOM\Response
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
abstract class BasicResponseDOM
{

    /**
     * @var \DOMDocument
     */
    protected $domDocument;

    protected $domXPath;

    /**
     * @var
     */
    protected $created;

    /**
     * @var
     */
    protected $expires;

    /**
     * @var
     */
    protected $statusHTTPCode;


    public function __construct(\DOMDocument $domDocument, $statusCode)
    {
        $this->domDocument = $domDocument;
        $this->domXPath = new \DOMXPath($this->domDocument);
        $this->registerNS();
        $this->statusHTTPCode = $statusCode;
        $this->descompose();
    }


    /**
     * Se encarga de registrar los namespaces del response
     */
    protected function registerNS($namespacese = array())
    {

        $namespaces = [
            "s" => "http://www.w3.org/2003/05/soap-envelope",
            "a" => "http://www.w3.org/2005/08/addressing",
            "u" => "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd",
            "o" => "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
        ];

        $namespaces = array_merge($namespaces,$namespacese);

        foreach ($namespaces as $key => $value){
            $this->domXPath->registerNamespace($key,$value);
        }


    }



    public function getDomDocument()
    {
        return $this->domDocument;
    }


    /**
     * Get query.
     *
     * @param string $query
     * @param bool $validate
     * @param int $item
     *
     * @return mixed
     * @throws QueryNotFountException
     */
    protected function getQuery($query, $validate = true, $item = 0)
    {
        $tag = $this->domXPath->query($query);

        if (($validate) && (null == $tag->item(0))) {
            throw new QueryNotFountException(get_class($this),$query);
        }
        if (is_null($item)) {
            return $tag;
        }

        return $tag->item($item);
    }

    /**
     * Se encarga de extraer los datos del dom para asignarlos a propiedades de la clase
     * @throws QueryNotFountException
     */
    protected function descompose()
    {
        $this->created = $this->getQuery("//o:Security/u:Timestamp/u:Created",false)->nodeValue;
        $this->expires = $this->getQuery("//o:Security/u:Timestamp/u:Expires",false)->nodeValue;;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return mixed
     */
    public function getStatusHTTPCode()
    {
        return $this->statusHTTPCode;
    }







}