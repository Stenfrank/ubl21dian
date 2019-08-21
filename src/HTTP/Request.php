<?php


namespace Stenfrank\UBL21dian\HTTP;
use Stenfrank\UBL21dian\Exceptions\CurlException;

/**
 * Class Request
 * @package Stenfrank\UBL21dian\HTTP
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class Request
{

    /**
     * Curl.
     *
     * @var resource
     */
    private $curl;


    /**
     * to.
     *
     * @var string
     */
    private $url;


    /**
     * to.
     *
     * @var string
     */
    private $body;


    /**
     * @var
     */
    private $response;



    /**
     * Client constructor.
     * @param string $url
     * @param string $content
     * @throws CurlException
     */
    public function __construct(string $url,string $content)
    {

        $this->url = $url;
        $this->body = $content;

        $this->init();


    }

    /**
     * Inicaliza la peticion
     */
    public function init()
    {
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 180);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 180);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/xml',
            'Content-type: application/soap+xml',
            'Content-length: '.strlen($this->body),
        ]);
    }

    /**
     * @throws CurlException
     */
    public function send($classResponse = null)
    {
        $response = null;
        if (false === ($response = curl_exec($this->curl))) {
            throw new CurlException(get_class($this),curl_error($this->curl));
        }
        $this->response = new Response($response,$this->curl,$classResponse);
    }


}