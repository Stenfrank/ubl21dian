<?php
namespace Stenfrank\UBL21dian\Exceptions;
use Exception;


/**
 * Class RequirestPropertyTemplateRequest
 * @package Stenfrank\UBL21dian\Exceptions
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class RequiredPropertyTemplateRequest extends Exception
{

    public function __construct(string $proiertiname)
    {
        parent::__construct("The {$proiertiname} property has to be defined", 0, null);
    }

}