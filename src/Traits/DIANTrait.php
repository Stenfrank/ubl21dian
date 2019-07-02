<?php

namespace Stenfrank\UBL21dian\Traits;

use Exception;

/**
 * DIAN trait.
 */
trait DIANTrait
{
    /**
     * Version.
     *
     * @var string
     */
    public $version = '1.0';

    /**
     * Encoding.
     *
     * @var string
     */
    public $encoding = 'UTF-8';

    /**
     * Certs.
     *
     * @var array
     */
    protected $certs;

    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Read certs.
     */
    protected function readCerts()
    {
        if (is_null($this->pathCertificate) || is_null($this->passwors)) {
            throw new Exception('Class '.get_class($this).': requires the certificate path and password.');
        }
        if (!openssl_pkcs12_read(file_get_contents($this->pathCertificate), $this->certs, $this->passwors)) {
            throw new Exception('Class '.get_class($this).': Failure signing data: '.openssl_error_string());
        }
    }

    /**
     * X509 export.
     */
    protected function x509Export()
    {
        if (!empty($this->certs)) {
            openssl_x509_export($this->certs['cert'], $stringCert);

            return str_replace([PHP_EOL, '-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----'], '', $stringCert);
        }

        throw new Exception('Class '.get_class($this).': Error openssl x509 export.');
    }

    /**
     * Identifiers references.
     */
    protected function identifiersReferences()
    {
        foreach ($this->ids as $key => $value) {
            $this->$key = mb_strtoupper("{$value}-".sha1(uniqid()));
        }
    }

    /**
     * Remove child.
     *
     * @param string $tagName
     */
    protected function removeChild($tagName, $item = 0)
    {
        if (is_null($tag = $this->domDocument->documentElement->getElementsByTagName($tagName)->item($item))) {
            return;
        }

        $this->domDocument->documentElement->removeChild($tag);
    }

    /**
     * Get tag.
     *
     * @param string $tagName
     * @param int    $item
     *
     * @return mixed
     */
    protected function getTag($tagName, $item = 0)
    {
        $tag = $this->domDocument->documentElement->getElementsByTagName($tagName);

        if (is_null($tag->item(0))) {
            throw new Exception('Class '.get_class($this).": The tag name {$tagName} does not exist.");
        }

        return $tag->item($item);
    }

    /**
     * Get query.
     *
     * @param string $query
     * @param bool   $validate
     * @param int    $item
     *
     * @return mixed
     */
    protected function getQuery($query, $validate = true, $item = 0)
    {
        $tag = $this->domXPath->query($query);

        if (($validate) && (null == $tag->item(0))) {
            throw new Exception('Class '.get_class($this).": The query {$query} does not exist.");
        }
        if (is_null($item)) {
            return $tag;
        }

        return $tag->item($item);
    }

    /**
     * Join array.
     *
     * @param array  $array
     * @param bool   $formatNS
     * @param string $join
     *
     * @return string
     */
    protected function joinArray(array $array, $formatNS = true, $join = ' ')
    {
        return implode($join, array_map(function ($value, $key) use ($formatNS) {
            return ($formatNS) ? "{$key}=\"$value\"" : "{$key}=$value";
        }, $array, array_keys($array)));
    }

    /**
     * Set.
     *
     * @param any $name
     * @param any $value
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get.
     *
     * @param any $name
     *
     * @return any
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return;
    }
}
