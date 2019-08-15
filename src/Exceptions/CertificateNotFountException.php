<?php
namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class CertificateNotFountException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class CertificateNotFountException extends ExceptionFather
{

    public function __construct(string $classNameRunException)
    {
        parent::__construct($classNameRunException, "requires the certificate path and password.");
    }

}