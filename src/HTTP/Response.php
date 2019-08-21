<?php


namespace Stenfrank\UBL21dian\HTTP;
use DOMDocument;

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

    private $classResonse;

    public function __construct($response, $curlClient, $classResponse)
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
     * @return mixed
     */
    public function getBodyRAW()
    {
        return $this->responseRAW;
    }

    /**
     * @return null
     */
    public function getBody()
    {
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