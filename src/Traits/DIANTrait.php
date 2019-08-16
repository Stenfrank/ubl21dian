<?php
namespace Stenfrank\UBL21dian\Traits;

use Stenfrank\UBL21dian\Exceptions\CertificateNotFountException;
use Stenfrank\UBL21dian\Exceptions\FailedReadCertificateException;
use Stenfrank\UBL21dian\Exceptions\Failedx509ExportCertificateException;
use Stenfrank\UBL21dian\Exceptions\QueryNotFountException;
use Stenfrank\UBL21dian\Exceptions\TagNameNotFountException;

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
     * @throws FailedReadCertificateException
     * @throws CertificateNotFountException
     */
    protected function readCerts()
    {
        //Se virifica que el path del certificado y el password del ceriticado exista
        if (is_null($this->pathCertificate) || is_null($this->passwors)) {
            throw new CertificateNotFountException(get_class($this));
        }
        //Se compueba que se pueda leer el certificado con el password
        if (!openssl_pkcs12_read(file_get_contents($this->pathCertificate), $this->certs, $this->passwors)) {
            throw new FailedReadCertificateException(get_class($this));
        }
    }

    /**
     * X509 export.
     * @throws Failedx509ExportCertificateException
     */
    protected function x509Export()
    {
        if (!empty($this->certs)) {
            openssl_x509_export($this->certs['cert'], $stringCert);
            $stringCert = str_replace("-----BEGIN CERTIFICATE-----", "", $stringCert);
            $stringCert = str_replace("-----END CERTIFICATE-----", "", $stringCert);
            $stringCert = str_replace("\n", "", str_replace("\r", "", $stringCert));
            $stringCert = $this->prettify($stringCert);
            return $stringCert;
        }

        throw new Failedx509ExportCertificateException(get_class($this));
    }

    /**
     * Prettify
     * @param  string $input Input string
     * @return string        Multi-line resposne
     */
    protected function prettify($input) {
        return chunk_split($input, 76, "\n");
    }

    /**
     * Identifiers references.
     *
     */
    protected function identifiersReferences()
    {
        foreach ($this->ids as $key => $value) {
            $this->$key = mb_strtoupper("{$value}-".sha1(uniqid('', true)));
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
     * @param int $item
     * @return mixed
     * @throws TagNameNotFountException
     */
    protected function getTag($tagName, $item = 0)
    {
        $tag = $this->domDocument->documentElement->getElementsByTagName($tagName);

        if (is_null($tag->item(0))) {
            throw new TagNameNotFountException(get_class($this,$tagName));
        }

        return $tag->item($item);
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
            throw new QueryNotFountException(get_class($this));
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
