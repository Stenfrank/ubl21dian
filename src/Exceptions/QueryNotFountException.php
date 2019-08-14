<?php


namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class QueryNotFountException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class QueryNotFountException extends ExceptionFather
{

    public function __construct(string $classNameRunException, $query)
    {
        parent::__construct($classNameRunException, ": The query {$query} does not exist.");
    }

}