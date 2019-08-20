<?php


namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class CurlException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class CurlException extends ExceptionFather
{

    public function __construct(string $classNameRunException, $message)
    {
        parent::__construct('Class '.$classNameRunException. " " . $message, 0, null);
    }

}