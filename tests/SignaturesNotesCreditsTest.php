<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\XAdES\SignCreditNote;

/**
 * Signatures Notes Credits.
 */
class SignaturesNotesCreditsTest extends TestCase
{
    /**
     * XML Template.
     *
     * @var string
     */
    private $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<CreditNote
    xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
    xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
    xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures"
    xmlns:xades="http://uri.etsi.org/01903/v1.3.2#"
    xmlns:xades141="http://uri.etsi.org/01903/v1.4.1#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2     http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-CreditNote-2.1.xsd">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
                    <sts:InvoiceSource>
                        <cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.1">CO</cbc:IdentificationCode>
                    </sts:InvoiceSource>
                    <sts:SoftwareProvider>
                        <sts:ProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeID="9" schemeName="31">9000000</sts:ProviderID>
                        <sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)"></sts:SoftwareID>
                    </sts:SoftwareProvider>
                    <sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)"/>
                    <sts:AuthorizationProvider>
                        <sts:AuthorizationProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</sts:AuthorizationProviderID>
                    </sts:AuthorizationProvider>
                    <sts:QRCode>https://muisca.dian.gov.co/WebFacturaelectronica/paginas/VerificarFacturaElectronicaExterno.faces?TipoDocumento=1NroDocumento=PRUE980078932NITFacturador=890101815NumIdentAdquiriente=9000000Cufe=bf8b43bc09bdf01204dd2f1dcae2b51ea02e849f29257140fc00dc9b65aa953e</sts:QRCode>
                </sts:DianExtensions>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>01</cbc:CustomizationID>
    <cbc:ProfileID>DIAN 2.0</cbc:ProfileID>
    <cbc:ProfileExecutionID>2</cbc:ProfileExecutionID>
    <cbc:ID>NC1000</cbc:ID>
    <cbc:UUID schemeID="2" schemeName="CUDE-SHA384"/>
    <cbc:IssueDate>2019-06-27</cbc:IssueDate>
    <cbc:IssueTime>08:00:20-05:00</cbc:IssueTime>
    <cbc:CreditNoteTypeCode>91</cbc:CreditNoteTypeCode>
    <cbc:Note>NC10002018-09-1312:53:23-05:0075600.38010.00020.00030.0075600.3890000009000000dd85db55545bd6566f36b0fd3be9fd8555c36e2:ambienteDePruebas</cbc:Note>
    <cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>1</cbc:LineCountNumeric>
    <cac:AccountingSupplierParty>
        <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>FACTURADOR DE EJEMPLO</cbc:Name>
            </cac:PartyName>
            <cac:PhysicalLocation>
                <cac:Address>
                    <cbc:ID>05001</cbc:ID>
                    <cbc:CityName>Medellín</cbc:CityName>
                    <cbc:CountrySubentity>Antioquia</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>05</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name>Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>FACTURADOR DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="9" schemeName="31">9000000</cbc:CompanyID>
                <cbc:TaxLevelCode listName="05">O-99</cbc:TaxLevelCode>
                <cac:RegistrationAddress>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>Bogotá DC</cbc:CityName>
                    <cbc:CountrySubentity>Districto Capital</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name>Colombia</cbc:Name>
                    </cac:Country>
                </cac:RegistrationAddress>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>FACTURADOR DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="9" schemeName="31">9000000</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:Name>12345</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
            <cac:Contact>
                <cbc:Telephone>9712311</cbc:Telephone>
                <cbc:ElectronicMail>sd_fistrib_facturaelectronica@dian.gov.co</cbc:ElectronicMail>
            </cac:Contact>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyName>
                <cbc:Name>ADQUIRIENTE DE EJEMPLO</cbc:Name>
            </cac:PartyName>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">9000000</cbc:CompanyID>
                <cbc:TaxLevelCode listName="05">O-99</cbc:TaxLevelCode>
                <cac:RegistrationAddress>
                    <cbc:ID>76001</cbc:ID>
                    <cbc:CityName>Cali</cbc:CityName>
                    <cbc:CountrySubentity>Valle del Cauca</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>76</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CALLE 0 0C 0</cbc:Line>
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
                <cbc:CompanyID schemeID="3" schemeName="31">9000000</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:Name>81248</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
            <cac:Contact>
                <cbc:Telephone>9712311</cbc:Telephone>
                <cbc:ElectronicMail>sd_fistrib_facturaelectronica@dian.gov.co</cbc:ElectronicMail>
            </cac:Contact>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:PaymentMeans>
        <cbc:ID>1</cbc:ID>
        <cbc:PaymentMeansCode>10</cbc:PaymentMeansCode>
        <cbc:PaymentID>1</cbc:PaymentID>
    </cac:PaymentMeans>
    <cac:PaymentTerms>
        <cbc:ReferenceEventCode>1</cbc:ReferenceEventCode>
    </cac:PaymentTerms>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="COP">0.00</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="COP">0.00</cbc:TaxExclusiveAmount>
        <cbc:TaxInclusiveAmount currencyID="COP">0.00</cbc:TaxInclusiveAmount>
        <cbc:AllowanceTotalAmount currencyID="COP">0.00</cbc:AllowanceTotalAmount>
        <cbc:ChargeTotalAmount currencyID="COP">0.00</cbc:ChargeTotalAmount>
        <cbc:PayableAmount currencyID="COP">0.00</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:CreditNoteLine>
        <cbc:ID>1</cbc:ID>
        <cbc:CreditedQuantity unitCode="EA">1.000000</cbc:CreditedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">0.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>false</cbc:FreeOfChargeIndicator>
        <cac:Item>
            <cbc:Description>Base para TV</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeAgencyName="195">6543542313534</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">0.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:CreditNoteLine>
</CreditNote>
XML;

    /** @test */
    public function it_generates_signature_XAdES_sha1()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignCreditNote($pathCertificate, $passwors, $this->xmlString, SignCreditNote::ALGO_SHA1);

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

        $signCreditNote = new SignCreditNote($pathCertificate, $passwors, $this->xmlString);

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

        $signCreditNote = new SignCreditNote($pathCertificate, $passwors, $this->xmlString, SignCreditNote::ALGO_SHA512);

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

        $signCreditNote = new SignCreditNote($pathCertificate, $passwors);

        // Software security code
        $signCreditNote->softwareID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        $signCreditNote->pin = '12345';

        // Sign
        $signCreditNote->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('eb7ff57a48c4bc5840e846382346e2a5546a3903c150fdf34bec4e7e8fb29c6437ef9c99575f0f78f80f7dfcc97d3e02', $signCreditNote->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_calculate_cude()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signCreditNote = new SignCreditNote($pathCertificate, $passwors);

        // CUDE
        $signCreditNote->pin = 'xxxxx';

        // Sign
        $signCreditNote->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('60a024139af65a986830609895271ad17f4d5d51effc4d8ad9db09096bce5786bba43003fd47211d72417866bc61642c', $signCreditNote->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signCreditNote->xml));
    }
}
