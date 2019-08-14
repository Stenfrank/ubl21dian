<?php


namespace Stenfrank\UBL21dian\Exceptions;

/**
 * Class TagNameNotFountException
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class TagNameNotFountException extends ExceptionFather
{

    public function __construct(string $classNameRunException,$tagName)
    {
        parent::__construct($classNameRunException, ": The tag name {$tagName} does not exist.");
    }

}