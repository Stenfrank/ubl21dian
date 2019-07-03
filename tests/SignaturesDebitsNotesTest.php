<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\XAdES\SignDebitNote;

/**
 * Signatures Notes Credits.
 */
class SignaturesDebitsNotesTest extends TestCase
{
    /**
     * XML Template.
     *
     * @var string
     */
    private $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<DebitNote
    xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
    xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
    xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
    xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures"
    xmlns:xades="http://uri.etsi.org/01903/v1.3.2#"
    xmlns:xades141="http://uri.etsi.org/01903/v1.4.1#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2    http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-DebitNote-2.1.xsd">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
                    <sts:InvoiceSource>
                        <cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.1">CO</cbc:IdentificationCode>
                    </sts:InvoiceSource>
                    <sts:SoftwareProvider>
                        <sts:ProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</sts:ProviderID>
                        <sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">56f2ae4e-9812-4fad-9255-08fcfcd5ccb0</sts:SoftwareID>
                    </sts:SoftwareProvider>
                    <sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)"/>
                    <sts:AuthorizationProvider>
                        <sts:AuthorizationProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</sts:AuthorizationProviderID>
                    </sts:AuthorizationProvider>
                    <sts:QRCode>NroFactura=ND1 NitFacturador=800197268 NitAdquiriente=900108281 FechaFactura=2019-06-21 ValorTotalFactura=14994.07 CUFE=9d9ab37faf74a9d2e978e9f62221a7c07da4426588c7acf388938774448a93f6d36b49f3cfab564bdecaed0bed853d28
                        URL=https://catalogo-vpfe-hab.dian.gov.co/Document/FindDocument?documentKey=9d9ab37faf74a9d2e978e9f62221a7c07da4426588c7acf388938774448a93f6d36b49f3cfab564bdecaed0bed853d28&amp;partitionKey=co|06|9d&amp;emissionDate=20190621</sts:QRCode>
                </sts:DianExtensions>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>11</cbc:CustomizationID>
    <cbc:ProfileID>DIAN 2.1</cbc:ProfileID>
    <cbc:ProfileExecutionID>2</cbc:ProfileExecutionID>
    <cbc:ID>ND1</cbc:ID>
    <cbc:UUID schemeID="2" schemeName="CUDE-SHA384"/>
    <cbc:IssueDate>2019-06-21</cbc:IssueDate>
    <cbc:IssueTime>09:15:23-05:00</cbc:IssueTime>
    <cbc:Note>ND12019-06-2109:15:23-05:0012600.06012394.01040.00030.0014994.07900508908900108281201912</cbc:Note>
    <cbc:DocumentCurrencyCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listID="ISO 4217 Alpha">USD</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID>SETP990000015</cbc:ReferenceID>
        <cbc:ResponseCode>1</cbc:ResponseCode>
        <cbc:Description>Factura anterior</cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:OrderReference>
        <cbc:ID>13.09.2018--GMO</cbc:ID>
    </cac:OrderReference>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>SETP990000015</cbc:ID>
            <cbc:UUID schemeName="CUFE-SHA384">e988c01fe2901d1bbf37911758961f7083b6e5c58400552b309549d6fbaa6402c8ff67b51d3c6f4ed478dc94b0c8b0fc</cbc:UUID>
            <cbc:IssueDate>2019-06-21</cbc:IssueDate>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    <cac:DespatchDocumentReference>
        <cbc:ID>8124167214 DA</cbc:ID>
    </cac:DespatchDocumentReference>
    <cac:ReceiptDocumentReference>
        <cbc:ID>12314129 GR</cbc:ID>
    </cac:ReceiptDocumentReference>
    <cac:AccountingSupplierParty>
        <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>Nombre Tienda</cbc:Name>
            </cac:PartyName>
            <cac:PartyName>
                <cbc:Name>Establecimiento Principal</cbc:Name>
            </cac:PartyName>
            <cac:PartyName>
                <cbc:Name>FACTURADOR DE EJEMPLO</cbc:Name>
            </cac:PartyName>
            <cac:PhysicalLocation>
                <cac:Address>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá, D.c.
                    </cbc:CityName>
                    <cbc:CountrySubentity>Bogotá</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>Av. #97 - 13</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>FACTURADOR DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</cbc:CompanyID>
                <cbc:TaxLevelCode listName="05">O-99</cbc:TaxLevelCode>
                <cac:RegistrationAddress>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá, D.c.
                    </cbc:CityName>
                    <cbc:CountrySubentity>Bogotá</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>Av. Jiménez #7 - 13</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:RegistrationAddress>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>FACTURADOR DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:ID>ND</cbc:ID>
                    <cbc:Name>10181</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
            <cac:Contact>
                <cbc:Name>Carlos Rosas</cbc:Name>
                <cbc:Telephone>9784563</cbc:Telephone>
                <cbc:ElectronicMail>crosas12@servicios.com</cbc:ElectronicMail>
                <cbc:Note>descripcion</cbc:Note>
            </cac:Contact>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>ADQUIRIENTE DE EJEMPLO</cbc:Name>
            </cac:PartyName>
            <cac:PhysicalLocation>
                <cac:Address>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá, D.c.
                    </cbc:CityName>
                    <cbc:CountrySubentity>Bogotá</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CARRERA 8 No 20-14/40</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">900108281</cbc:CompanyID>
                <cbc:TaxLevelCode listName="04">O-99</cbc:TaxLevelCode>
                <cac:RegistrationAddress>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá, D.c.
                    </cbc:CityName>
                    <cbc:CountrySubentity>Bogotá</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:RegistrationAddress>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">900108281</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:Name>90518</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
            <cac:Contact>
                <cbc:Name>Carlos Rosas</cbc:Name>
                <cbc:Telephone>9784563</cbc:Telephone>
                <cbc:ElectronicMail>crosas12@servicios.com</cbc:ElectronicMail>
                <cbc:Note>descripcion</cbc:Note>
            </cac:Contact>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxRepresentativeParty>
        <cac:PartyIdentification>
            <cbc:ID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">989123123</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name/>
        </cac:PartyName>
    </cac:TaxRepresentativeParty>
    <cac:Delivery>
        <cac:DeliveryAddress>
            <cbc:ID>11001</cbc:ID>
            <cbc:CityName>Bogotá, D.c.
            </cbc:CityName>
            <cbc:CountrySubentity>Bogotá, D.c. 11</cbc:CountrySubentity>
            <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
            <cac:AddressLine>
                <cbc:Line>CARRERA 8 No 20-14/40</cbc:Line>
            </cac:AddressLine>
            <cac:Country>
                <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                <cbc:Name languageID="es">Colombia</cbc:Name>
            </cac:Country>
        </cac:DeliveryAddress>
        <cac:DeliveryParty>
            <cac:PartyName>
                <cbc:Name>Empresa de transporte</cbc:Name>
            </cac:PartyName>
            <cac:PhysicalLocation>
                <cac:Address>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá, D.c.
                    </cbc:CityName>
                    <cbc:CountrySubentity>Bogotá</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>Av. #17 - 193</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>Empresa de transporte</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="1" schemeName="31">981223983</cbc:CompanyID>
                <cbc:TaxLevelCode listName="04">O-99</cbc:TaxLevelCode>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>Empresa de transporte</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="1" schemeName="31">981223983</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:Name>75433</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
            <cac:Contact>
                <cbc:Name>Carlos Rosas</cbc:Name>
                <cbc:Telephone>9784563</cbc:Telephone>
                <cbc:ElectronicMail>crosas12@servicios.com</cbc:ElectronicMail>
                <cbc:Note>descripcion</cbc:Note>
            </cac:Contact>
        </cac:DeliveryParty>
    </cac:Delivery>
    <cac:DeliveryTerms>
        <cbc:SpecialTerms>Portes Pagados</cbc:SpecialTerms>
        <cbc:LossRiskResponsibilityCode>CFR</cbc:LossRiskResponsibilityCode>
        <cbc:LossRisk>Costo y Flete</cbc:LossRisk>
    </cac:DeliveryTerms>
    <cac:PaymentMeans>
        <cbc:ID>2</cbc:ID>
        <cbc:PaymentMeansCode>41</cbc:PaymentMeansCode>
        <cbc:PaymentDueDate>2019-06-30</cbc:PaymentDueDate>
        <cbc:PaymentID>1234</cbc:PaymentID>
    </cac:PaymentMeans>
    <cac:PaymentExchangeRate>
        <cbc:SourceCurrencyCode>USD</cbc:SourceCurrencyCode>
        <cbc:SourceCurrencyBaseRate>1.00</cbc:SourceCurrencyBaseRate>
        <cbc:TargetCurrencyCode>COP</cbc:TargetCurrencyCode>
        <cbc:TargetCurrencyBaseRate>1.00</cbc:TargetCurrencyBaseRate>
        <cbc:CalculationRate>3100</cbc:CalculationRate>
        <cbc:Date>2019-06-21</cbc:Date>
    </cac:PaymentExchangeRate>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="USD">2394.01</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="USD">12600.06</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="USD">2394.01</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:Percent>19.00</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="USD">0.00</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="USD">0.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="USD">0.00</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:Percent>0.000</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>03</cbc:ID>
                    <cbc:Name>ICA</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="USD">0.00</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="USD">0.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="USD">0.00</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:Percent>0.00</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>04</cbc:ID>
                    <cbc:Name>INC</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:RequestedMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="USD">12600.06</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="USD">12600.06</cbc:TaxExclusiveAmount>
        <cbc:TaxInclusiveAmount currencyID="USD">14994.07</cbc:TaxInclusiveAmount>
        <cbc:PayableAmount currencyID="USD">14994.07</cbc:PayableAmount>
    </cac:RequestedMonetaryTotal>
    <cac:DebitNoteLine>
        <cbc:ID>1</cbc:ID>
        <cbc:DebitedQuantity unitCode="EA">1.000000</cbc:DebitedQuantity>
        <cbc:LineExtensionAmount currencyID="USD">12600.06</cbc:LineExtensionAmount>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="USD">2394.01</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="USD">12600.06</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="USD">2394.01</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>19.00</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>01</cbc:ID>
                        <cbc:Name>IVA</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:AllowanceCharge>
            <cbc:ID>1</cbc:ID>
            <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReason>Descuento por cliente frecuente</cbc:AllowanceChargeReason>
            <cbc:MultiplierFactorNumeric>33.33</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="USD">6299.94</cbc:Amount>
            <cbc:BaseAmount currencyID="USD">18900.00</cbc:BaseAmount>
        </cac:AllowanceCharge>
        <cac:Item>
            <cbc:Description>Articulo 1 Prueba</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:InformationContentProviderParty>
                <cac:PowerOfAttorney>
                    <cac:AgentParty>
                        <cac:PartyIdentification>
                            <cbc:ID schemeAgencyID="195" schemeID="3" schemeName="31">900108281</cbc:ID>
                        </cac:PartyIdentification>
                    </cac:AgentParty>
                </cac:PowerOfAttorney>
            </cac:InformationContentProviderParty>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="USD">18900.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:DebitNoteLine>
</DebitNote>
XML;

    /** @test */
    public function it_generates_signature_XAdES_sha1()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignDebitNote($pathCertificate, $passwors, $this->xmlString, SignDebitNote::ALGO_SHA1);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha1"', $signCreditNote->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha256()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignDebitNote($pathCertificate, $passwors, $this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"', $signCreditNote->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha512()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignDebitNote($pathCertificate, $passwors, $this->xmlString, SignDebitNote::ALGO_SHA512);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha512"', $signCreditNote->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_software_security_code()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignDebitNote($pathCertificate, $passwors);

        // Software security code
        $signCreditNote->softwareID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        $signCreditNote->pin = '12345';

        // Sign
        $signCreditNote->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('31e48a81d2d90d1cd81d386d9c4fc5c0030ef582b401b3825bcd0d1d17e1d6441b5b7b95e10d11d5c65861daa2bada67', $signCreditNote->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_calculate_cude()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignDebitNote($pathCertificate, $passwors);

        // CUDE
        $signCreditNote->pin = 'xxxxx';

        // Sign
        $signCreditNote->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('17ef520059982ca6443c543a7f3b2af2c836af88bf7479e2eb4cf538ef1b2957ce77a3ba3bff80df3c8aef4dabebdfed', $signCreditNote->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }
}
