<?php

namespace Stenfrank\UBL21dian\XAdES;

/**
 * Sign Credit Note.
 */
class SignCreditNote extends SignInvoice
{
    /**
     * NS.
     *
     * @var array
     */
    public $ns = [
        'xmlns:ext' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
        'xmlns:cac' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'xmlns:cbc' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'xmlns:sts' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures',
        'xmlns' => 'urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        'xmlns:xades141' => 'http://uri.etsi.org/01903/v1.4.1#',
        'xmlns:xades' => 'http://uri.etsi.org/01903/v1.3.2#',
        'xmlns:ds' => SignInvoice::XMLDSIG,
    ];
}
