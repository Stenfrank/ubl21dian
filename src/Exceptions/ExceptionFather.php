<?php
namespace Stenfrank\UBL21dian\Exceptions;
use Exception;


/**
 * Class ExceptionFather
 * Basica para la ejecucion de excepcion con un solo mensaje
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class ExceptionFather extends Exception
{

    public function __construct(string $classNameRunException, $message)
    {
        parent::__construct('Class '.$classNameRunException. " " . $message, 0, null);
    }



}