<?php

namespace Stenfrank\UBL21dian;

use DOMDocument;
use Exception;
use Stenfrank\UBL21dian\Templates\Template;

/**
 * Client.
 */
class Client
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
    private $to;

    /**
     * Response.
     *
     * @var string
     */
    private $response;

    /**
     * Construct.
     *
     * @param \Stenfrank\UBL21dian\Templates\Template $template
     */
    public function __construct(Template $template)
    {
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->to = $template->To);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 180);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 180);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $template->xml);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/xml',
            'Content-type: application/soap+xml',
            'Content-length: '.strlen($template->xml),
        ]);

        $this->exec();

        return $this;
    }

    /**
     * Exec.
     */
    private function exec()
    {
        if (false === ($this->response = curl_exec($this->curl))) {
            throw new Exception('Class '.get_class($this).': '.curl_error($this->curl));
        }
    }

    /**
     * Get response.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get response to object.
     *
     * @return object
     */
    public function getResponseToObject()
    {
        try {
            $xmlResponse = new DOMDocument();
            $xmlResponse->loadXML($this->response);

            return $this->xmlToObject($xmlResponse);
        } catch (\Exception $e) {
            throw new Exception('Class '.get_class($this).': '.$this->to.' '.$this->response);
        }
    }

    /**
     * XML to object.
     *
     * @param mixed $root
     *
     * @return mixed
     */
    protected function xmlToObject($root)
    {
        $regex = '/.:/';
        $dataXML = [];

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;

            foreach ($attrs as $attr) {
                $dataXML['_attributes'][$attr->name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;

            if (1 == $children->length) {
                $child = $children->item(0);

                if (XML_TEXT_NODE == $child->nodeType) {
                    $dataXML['_value'] = $child->nodeValue;

                    return 1 == count($dataXML) ? $dataXML['_value'] : $dataXML;
                }
            }

            $groups = [];

            foreach ($children as $child) {
                if (!isset($dataXML[preg_replace($regex, '', $child->nodeName)])) {
                    $dataXML[preg_replace($regex, '', $child->nodeName)] = $this->xmlToObject($child);
                } else {
                    if (!isset($groups[preg_replace($regex, '', $child->nodeName)])) {
                        $dataXML[preg_replace($regex, '', $child->nodeName)] = array($dataXML[preg_replace($regex, '', $child->nodeName)]);
                        $groups[preg_replace($regex, '', $child->nodeName)] = 1;
                    }

                    $dataXML[preg_replace($regex, '', $child->nodeName)][] = $this->xmlToObject($child);
                }
            }
        }

        return (object) $dataXML;
    }

    public function __toString()
    {
        return json_encode($this->getResponseToObject());
    }
}
