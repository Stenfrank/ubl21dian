<?php


namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class Failedx509ExportCertificateException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class Failedx509ExportCertificateException extends ExceptionFather
{
    public function __construct(string $classNameRunException)
    {
        parent::__construct($classNameRunException,  ': Error openssl x509 export.');
    }
}