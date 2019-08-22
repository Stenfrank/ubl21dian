<?php


namespace Stenfrank\UBL21dian\HTTP;
use DOMDocument;
use Stenfrank\UBL21dian\HTTP\DOM\Response\BasicResponseDOM;

/**
 * Class Response
 * @package Stenfrank\UBL21dian\HTTP
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class Response
{

    /**
     * @var
     */
    private $responseRAW;

    /**
     * @var
     */
    private $curlClient;

    /**
     * @var
     */
    private $classResonse;

    public function __construct($response, $curlClient, $classResponse = null)
    {
        $this->responseRAW = $response;
        $this->curlClient = $curlClient;
        $this->classResonse = $classResponse;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return (int)curl_getinfo($this->curlClient,CURLINFO_HTTP_CODE);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatusCode() == 200 ? true : false;
    }

    /**
     * @return string
     */
    public function getBodyRAW()
    {
        return $this->responseRAW;
    }


    /**
     * @return string|null|BasicResponseDOM
     */
    public function getBody()
    {

        if($this->classResonse == null){
            return $this->getBodyRAW();
        }

        try{
            $xmlResponse = new DOMDocument();
            $xmlResponse->loadXML($this->responseRAW);
            $classResponse = $this->classResonse;
            return new $classResponse($xmlResponse);
        }catch (\Exception $e){
            return null;
        }

    }







}