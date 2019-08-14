<?php


namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class FailedReadCertificateException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class FailedReadCertificateException extends ExceptionFather
{

    public function __construct(string $classNameRunException)
    {
        parent::__construct($classNameRunException, ': Failure signing data: '.openssl_error_string());
    }


}