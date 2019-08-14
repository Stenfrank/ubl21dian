<?php

namespace Stenfrank\UBL21dian;

use Stenfrank\UBL21dian\Traits\DIANTrait;

/**
 * Sign.
 * @property \DOMDocument domDocument
 * @property null|string xmlString
 * @property null|string passwors
 * @property null|string pathCertificate
 */
abstract class Sign
{
    use DIANTrait;

    /**
     * Abstract loadXML.
     *
     * @var void
     */
    abstract protected function loadXML();

    /**
     * Construct.
     *
     * @param string $pathCertificate
     * @param string $passwors
     * @param string $xmlString
     * @throws \Exception
     */
    public function __construct($pathCertificate = null, $passwors = null, $xmlString = null)
    {
        $this->pathCertificate = $pathCertificate;
        $this->passwors = $passwors;
        $this->xmlString = $xmlString;

        $this->readCerts();
        $this->identifiersReferences();

        if (!is_null($xmlString)) {
            $this->sign();
        }
    }

    /**
     * Get document.
     *
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->domDocument;
    }

    /**
     * Sign.
     *
     * @param string $string
     *
     * @return Sign
     */
    public function sign($string = null)
    {
        if (null != $string) {
            $this->xmlString = $string;
        }

        if (!is_null($this->xmlString)) {
            $this->loadXML();
            $this->xml = $this->domDocument->saveXML();
        }

        return $this;
    }
}
