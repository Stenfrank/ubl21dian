<?php

namespace Stenfrank\UBL21dian\Templates\SOAP;

use Stenfrank\UBL21dian\HTTP\DOM\Request\SendBillAsyncRequestDOM;
use Stenfrank\UBL21dian\Templates\Template;
use Stenfrank\UBL21dian\Templates\CreateTemplate;

/**
 * Send bill async.
 */
class SendBillAsync extends Template implements CreateTemplate
{
    /**
     * Action.
     *
     * @var string
     */
    public $Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/SendBillAsync';

    /**
     * Required properties.
     *
     * @var array
     */
    protected $requiredProperties = [
        'fileName',
        'contentFile',
    ];

    /**
     * Construct.
     *
     * @param string $pathCertificate
     * @param string $passwors
     */
    public function __construct($pathCertificate, $passwors)
    {
        parent::__construct($pathCertificate, $passwors);
    }

    /**
     * Create template.
     *
     * @return string
     */
    public function createTemplate()
    {
        $domRequest = new SendBillAsyncRequestDOM($this->fileName,$this->contentFile);
        return $domRequest->getString();
    }
}
