<?php

namespace Stenfrank\Tests;

use DOMDocument;
use Stenfrank\UBL21dian\XAdES\SignInvoice;

/**
 * Signatures Bills Test.
 */
class SignaturesBillsTest extends TestCase
{
    /**
     * XML Template.
     *
     * @var string
     */
    private $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<Invoice
    xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
    xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
    xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures"
    xmlns:xades="http://uri.etsi.org/01903/v1.3.2#"
    xmlns:xades141="http://uri.etsi.org/01903/v1.4.1#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2     http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
                    <sts:InvoiceControl>
                        <sts:InvoiceAuthorization>18760000001</sts:InvoiceAuthorization>
                        <sts:AuthorizationPeriod>
                            <cbc:StartDate>2019-01-19</cbc:StartDate>
                            <cbc:EndDate>2030-01-19</cbc:EndDate>
                        </sts:AuthorizationPeriod>
                        <sts:AuthorizedInvoices>
                            <sts:Prefix>SETP</sts:Prefix>
                            <sts:From>990000000</sts:From>
                            <sts:To>995000000</sts:To>
                        </sts:AuthorizedInvoices>
                    </sts:InvoiceControl>
                    <sts:InvoiceSource>
                        <cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.1">CO</cbc:IdentificationCode>
                    </sts:InvoiceSource>
                    <sts:SoftwareProvider>
                        <sts:ProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">901210113</sts:ProviderID>
                        <sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">aae35b45-6f9a-4da5-a32c-78d63da52364</sts:SoftwareID>
                    </sts:SoftwareProvider>
                    <sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">e43e2e7204b6e1d308eccb5d99e1c84c1ff7e0939c931ec576d4bac1ee5ecd3a323fb850cfb7f29c9b6d8fec82bb93c8</sts:SoftwareSecurityCode>
                    <sts:AuthorizationProvider>
                        <sts:AuthorizationProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="4" schemeName="31">800197268</sts:AuthorizationProviderID>
                    </sts:AuthorizationProvider>
                    <sts:QRCode>https://muisca.dian.gov.co/WebFacturaelectronica/paginas/VerificarFacturaElectronicaExterno.faces?TipoDocumento=1NroDocumento=PRUE980078932NITFacturador=890101815NumIdentAdquiriente=901210113Cufe=bf8b43bc09bdf01204dd2f1dcae2b51ea02e849f29257140fc00dc9b65aa953e</sts:QRCode>
                </sts:DianExtensions>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>01</cbc:CustomizationID>
    <cbc:ProfileID>DIAN 2.1</cbc:ProfileID>
    <cbc:ProfileExecutionID>2</cbc:ProfileExecutionID>
    <cbc:ID>SETP990000008</cbc:ID>
    <cbc:UUID schemeID="2" schemeName="CUFE-SHA384">e633d09279badff1414e7e00f0c9322c01879754c0349e2ea8acd357a474962e17729d393a8bb4094ff3f29dbf0e3af1</cbc:UUID>
    <cbc:IssueDate>2019-06-12</cbc:IssueDate>
    <cbc:IssueTime>12:53:36-05:00</cbc:IssueTime>
    <cbc:DueDate>2019-04-04</cbc:DueDate>
    <cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
    <cbc:Note>SETP9900000082019-06-1212:53:36-05:001720000.00010.00020.00030.002023060.009012101133901210113dd85db55545bd6566f36b0fd3be9fd8555c36e2</cbc:Note>
    <cbc:TaxPointDate>2019-04-30</cbc:TaxPointDate>
    <cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>6</cbc:LineCountNumeric>
    <cac:OrderReference>
        <cbc:ID>546326432432</cbc:ID>
    </cac:OrderReference>
    <cac:DespatchDocumentReference>
        <cbc:ID>DA53452416721</cbc:ID>
    </cac:DespatchDocumentReference>
    <cac:ReceiptDocumentReference>
        <cbc:ID>GR23847134</cbc:ID>
    </cac:ReceiptDocumentReference>
    <cac:AdditionalDocumentReference>
        <cbc:ID>GT191231912</cbc:ID>
        <cbc:DocumentTypeCode>99</cbc:DocumentTypeCode>
    </cac:AdditionalDocumentReference>
    <cac:AccountingSupplierParty>
        <cbc:AdditionalAccountID schemeAgencyID="195">1</cbc:AdditionalAccountID>
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
                    <cbc:ID>05001</cbc:ID>
                    <cbc:CityName>Medellín</cbc:CityName>
                    <cbc:CountrySubentity>Antioquia</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>05</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>FACTURADOR DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">901210113</cbc:CompanyID>
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
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">901210113</cbc:CompanyID>
                <cac:CorporateRegistrationScheme>
                    <cbc:ID>SETP</cbc:ID>
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
            <cac:PhysicalLocation>
                <cac:Address>
                    <cbc:ID>11001</cbc:ID>
                    <cbc:CityName>BOGOTA</cbc:CityName>
                    <cbc:CountrySubentity>Districto Capital</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                    <cac:AddressLine>
                        <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                        <cbc:Name languageID="es">Colombia</cbc:Name>
                    </cac:Country>
                </cac:Address>
            </cac:PhysicalLocation>
            <cac:PartyTaxScheme>
                <cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">901210113</cbc:CompanyID>
                <cbc:TaxLevelCode listName="05">O-99</cbc:TaxLevelCode>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>ADQUIRIENTE DE EJEMPLO</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="3" schemeName="31">901210113</cbc:CompanyID>
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
    <cac:TaxRepresentativeParty>
        <cac:PartyIdentification>
            <cbc:ID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeName="13">123456789</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name>Persona autorizada para descargar</cbc:Name>
        </cac:PartyName>
    </cac:TaxRepresentativeParty>
    <cac:Delivery>
        <cbc:ActualDeliveryDate>2019-02-15</cbc:ActualDeliveryDate>
        <cbc:ActualDeliveryTime>19:30:00</cbc:ActualDeliveryTime>
        <cac:DeliveryAddress>
            <cbc:ID>11001</cbc:ID>
            <cbc:CityName>BOGOTA</cbc:CityName>
            <cbc:CountrySubentity>Districto Capital</cbc:CountrySubentity>
            <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
            <cac:AddressLine>
                <cbc:Line>CR 9 A N0 99 - 07 OF 802</cbc:Line>
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
                    <cbc:CityName>PEREIRA</cbc:CityName>
                    <cbc:CountrySubentity>Districto Capital</cbc:CountrySubentity>
                    <cbc:CountrySubentityCode>91</cbc:CountrySubentityCode>
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
                <cbc:RegistrationName>Empresa de transporte</cbc:RegistrationName>
                <cbc:CompanyID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" schemeID="1" schemeName="31">981223983</cbc:CompanyID>
                <cbc:TaxLevelCode listName="05">O-99</cbc:TaxLevelCode>
                <cac:TaxScheme>
                    <cbc:ID>01</cbc:ID>
                    <cbc:Name>IVA</cbc:Name>
                </cac:TaxScheme>
            </cac:PartyTaxScheme>
            <cac:PartyLegalEntity>
                <cac:CorporateRegistrationScheme>
                    <cbc:Name>54321</cbc:Name>
                </cac:CorporateRegistrationScheme>
            </cac:PartyLegalEntity>
        </cac:DeliveryParty>
    </cac:Delivery>
    <cac:PaymentMeans>
        <cbc:ID>1</cbc:ID>
        <cbc:PaymentMeansCode>41</cbc:PaymentMeansCode>
        <cbc:PaymentDueDate>2019-06-12</cbc:PaymentDueDate>
        <cbc:PaymentID>1</cbc:PaymentID>
    </cac:PaymentMeans>
    <cac:PaymentTerms>
        <cbc:ReferenceEventCode>2</cbc:ReferenceEventCode>
        <cac:SettlementPeriod>
            <cbc:DurationMeasure unitCode="DAY">60</cbc:DurationMeasure>
        </cac:SettlementPeriod>
    </cac:PaymentTerms>
    <cac:AllowanceCharge>
        <cbc:ID>1</cbc:ID>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>11</cbc:AllowanceChargeReasonCode>
        <cbc:AllowanceChargeReason>Descuento cliente frecuente</cbc:AllowanceChargeReason>
        <cbc:MultiplierFactorNumeric>2.5</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="COP">10000.00</cbc:Amount>
        <cbc:BaseAmount currencyID="COP">1720000.00</cbc:BaseAmount>
    </cac:AllowanceCharge>
    <cac:AllowanceCharge>
        <cbc:ID>2</cbc:ID>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>07</cbc:AllowanceChargeReasonCode>
        <cbc:AllowanceChargeReason>Descuento por IVA asumido</cbc:AllowanceChargeReason>
        <cbc:MultiplierFactorNumeric>2.5</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="COP">19000.00</cbc:Amount>
        <cbc:BaseAmount currencyID="COP">1720000.00</cbc:BaseAmount>
    </cac:AllowanceCharge>
    <cac:AllowanceCharge>
        <cbc:ID>3</cbc:ID>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>11</cbc:AllowanceChargeReasonCode>
        <cbc:AllowanceChargeReason>Descuento por temporada</cbc:AllowanceChargeReason>
        <cbc:MultiplierFactorNumeric>2.5</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="COP">30000.00</cbc:Amount>
        <cbc:BaseAmount currencyID="COP">1720000.00</cbc:BaseAmount>
    </cac:AllowanceCharge>
    <cac:AllowanceCharge>
        <cbc:ID>4</cbc:ID>
        <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReason>Cargo financiero pago 30d</cbc:AllowanceChargeReason>
        <cbc:MultiplierFactorNumeric>2.5</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="COP">15000.00</cbc:Amount>
        <cbc:BaseAmount currencyID="COP">1720000.00</cbc:BaseAmount>
    </cac:AllowanceCharge>
    <cac:AllowanceCharge>
        <cbc:ID>5</cbc:ID>
        <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReason>Cargo financiero estudio de crédito</cbc:AllowanceChargeReason>
        <cbc:MultiplierFactorNumeric>2.5</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="COP">5000.00</cbc:Amount>
        <cbc:BaseAmount currencyID="COP">1720000.00</cbc:BaseAmount>
    </cac:AllowanceCharge>
    <cac:PaymentExchangeRate>
        <cbc:SourceCurrencyCode>COP</cbc:SourceCurrencyCode>
        <cbc:SourceCurrencyBaseRate>1.00</cbc:SourceCurrencyBaseRate>
        <cbc:TargetCurrencyCode>COP</cbc:TargetCurrencyCode>
        <cbc:TargetCurrencyBaseRate>1.00</cbc:TargetCurrencyBaseRate>
        <cbc:CalculationRate>1.00</cbc:CalculationRate>
        <cbc:Date>2019-02-18</cbc:Date>
    </cac:PaymentExchangeRate>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="COP">342000.00</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="COP">1800000.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="COP">342000.00</cbc:TaxAmount>
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
        <cbc:TaxAmount currencyID="COP">0.00</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="COP">0.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="COP">0.00</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:Percent>0.00</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>02</cbc:ID>
                    <cbc:Name>Impuesto al Consumo</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="COP">0.00</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="COP">0.00</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="COP">0.00</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:Percent>0.00</cbc:Percent>
                <cac:TaxScheme>
                    <cbc:ID>03</cbc:ID>
                    <cbc:Name>Industria Comercio Avisos</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="COP">60.00</cbc:TaxAmount>
        <cbc:TaxEvidenceIndicator>false</cbc:TaxEvidenceIndicator>
        <cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="COP">60.00</cbc:TaxAmount>
            <cbc:BaseUnitMeasure unitCode="NIU">1.000000</cbc:BaseUnitMeasure>
            <cbc:PerUnitAmount currencyID="COP">30.00</cbc:PerUnitAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>22</cbc:ID>
                    <cbc:Name>Impuesto sobre las bolsas</cbc:Name>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="COP">1720000.00</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="COP">1800000.00</cbc:TaxExclusiveAmount>
        <cbc:TaxInclusiveAmount currencyID="COP">2062060.00</cbc:TaxInclusiveAmount>
        <cbc:AllowanceTotalAmount currencyID="COP">59000.00</cbc:AllowanceTotalAmount>
        <cbc:ChargeTotalAmount currencyID="COP">20000.00</cbc:ChargeTotalAmount>
        <cbc:PayableAmount currencyID="COP">2023060.00</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    <cac:InvoiceLine>
        <cbc:ID>1</cbc:ID>
        <cbc:InvoicedQuantity unitCode="EA">1.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">250000.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>false</cbc:FreeOfChargeIndicator>
        <cac:AllowanceCharge>
            <cbc:ID>1</cbc:ID>
            <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReason>Discount</cbc:AllowanceChargeReason>
            <cbc:MultiplierFactorNumeric>25.00</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="COP">50000.00</cbc:Amount>
            <cbc:BaseAmount currencyID="COP">300000.00</cbc:BaseAmount>
        </cac:AllowanceCharge>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="COP">47500.00</cbc:TaxAmount>
            <cbc:TaxEvidenceIndicator>false</cbc:TaxEvidenceIndicator>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="COP">250000.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="COP">47500.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>19.00</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>01</cbc:ID>
                        <cbc:Name>IVA</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
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
            <cbc:PriceAmount currencyID="COP">300000.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
    <cac:InvoiceLine>
        <cbc:ID>2</cbc:ID>
        <cbc:InvoicedQuantity unitCode="EA">1.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">0.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>true</cbc:FreeOfChargeIndicator>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="COP">100000.00</cbc:PriceAmount>
                <cbc:PriceTypeCode>03</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="COP">19000.00</cbc:TaxAmount>
            <cbc:TaxEvidenceIndicator>false</cbc:TaxEvidenceIndicator>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="COP">100000.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="COP">19000.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>19.00</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>01</cbc:ID>
                        <cbc:Name>IVA</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>Antena (regalo)</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeID="999" schemeName="EAN13">3543542234414</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">0.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
    <cac:InvoiceLine>
        <cbc:ID>3</cbc:ID>
        <cbc:InvoicedQuantity unitCode="EA">1.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">1410000.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>false</cbc:FreeOfChargeIndicator>
        <cac:AllowanceCharge>
            <cbc:ID>1</cbc:ID>
            <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReason>Cargo</cbc:AllowanceChargeReason>
            <cbc:MultiplierFactorNumeric>10.00</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="COP">10000.00</cbc:Amount>
        </cac:AllowanceCharge>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="COP">267900.00</cbc:TaxAmount>
            <cbc:TaxEvidenceIndicator>false</cbc:TaxEvidenceIndicator>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="COP">1410000.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="COP">267900.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>19.00</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>01</cbc:ID>
                        <cbc:Name>IVA</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>TV</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeID="999" schemeName="EAN13">12435423151234</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">1400000.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
    <cac:InvoiceLine>
        <cbc:ID>4</cbc:ID>
        <cbc:InvoicedQuantity unitCode="EA">1.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">20000.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>false</cbc:FreeOfChargeIndicator>
        <cac:Item>
            <cbc:Description>Servicio (excluido)</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeAgencyName="195" schemeName="EAN13">6543542313534</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">20000.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="NIU">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
    <cac:InvoiceLine>
        <cbc:ID>5</cbc:ID>
        <cbc:InvoicedQuantity unitCode="EA">1.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">40000.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>false</cbc:FreeOfChargeIndicator>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="COP">7600.00</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="COP">40000.00</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="COP">7600.00</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>19.00</cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>01</cbc:ID>
                        <cbc:Name>IVA</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>Acarreo</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeAgencyName="195" schemeName="EAN13">6543542313534</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">40000.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="NIU">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
    <cac:InvoiceLine>
        <cbc:ID>6</cbc:ID>
        <cbc:InvoicedQuantity unitCode="NIU">2.000000</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="COP">0.00</cbc:LineExtensionAmount>
        <cbc:FreeOfChargeIndicator>true</cbc:FreeOfChargeIndicator>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="COP">200.00</cbc:PriceAmount>
                <cbc:PriceTypeCode>03</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="COP">60.00</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxAmount currencyID="COP">60.00</cbc:TaxAmount>
                <cbc:BaseUnitMeasure unitCode="NIU">1.000000</cbc:BaseUnitMeasure>
                <cbc:PerUnitAmount currencyID="COP">30.00</cbc:PerUnitAmount>
                <cac:TaxCategory>
                    <cac:TaxScheme>
                        <cbc:ID>22</cbc:ID>
                        <cbc:Name>Impuesto sobre las bolsas</cbc:Name>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description>Bolsas</cbc:Description>
            <cac:SellersItemIdentification>
                <cbc:ID>AOHV84-225</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
                <cbc:ID schemeAgencyName="195" schemeID="001" schemeName="UNSPSC">18937100-7</cbc:ID>
            </cac:StandardItemIdentification>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="COP">0.00</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="EA">1.000000</cbc:BaseQuantity>
        </cac:Price>
    </cac:InvoiceLine>
</Invoice>
XML;

    /** @test */
    public function it_generates_signature_XAdES_sha1()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signInvoice = new SignInvoice($pathCertificate, $passwors, $this->xmlString, SignInvoice::ALGO_SHA1);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha1"', $signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha256()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signInvoice = new SignInvoice($pathCertificate, $passwors, $this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"', $signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_sha512()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signInvoice = new SignInvoice($pathCertificate, $passwors, $this->xmlString, SignInvoice::ALGO_SHA512);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha512"', $signInvoice->xml);

        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_software_security_code()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signInvoice = new SignInvoice($pathCertificate, $passwors);

        // Software security code
        $signInvoice->softwareID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
        $signInvoice->pin = '12345';

        // Sign
        $signInvoice->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('54d15940890b5ed395e7476d294a972cee650200942adb7a899de35cb0cf22f98831e7c9a95d90b961c6845340b09efd', $signInvoice->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }

    /** @test */
    public function it_generates_signature_XAdES_and_calculate_cufe()
    {
        $pathCertificate = dirname(dirname(__FILE__)).'/certicamara.p12';
        $passwors = '3T3rN4661343';

        $signInvoice = new SignInvoice($pathCertificate, $passwors);

        // CUFE
        $signInvoice->technicalKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        // Sign
        $signInvoice->sign($this->xmlString);

        $domDocumentValidate = new DOMDocument();
        $domDocumentValidate->validateOnParse = true;

        $this->assertContains('d48db2461fc229b54a1b92df388fc66844d23cddd120bf0cc952dda6243c9db9046b23ca3cea0185aac2a985d1fb6c7c', $signInvoice->xml);
        $this->assertSame(true, $domDocumentValidate->loadXML($signInvoice->xml));
    }
}
