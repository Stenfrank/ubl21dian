# UBL 2.1 DIAN

Core for electronic invoicing pre-validation - DIAN UBL 2.1.

# Tags
* 1.0: Contains valid tests with binary security token (SOAP) and XAdES signature (XML) with algorithms sha1, sha256 and sha512.
* 1.1: Contains CUFE algorithms and Software Security Code.

## How to Install

Install with [`composer.phar`](http://getcomposer.org).

```sh
composer require stenfrank/ubl21dian
```

## Basic usage soap

```php
$pathCertificate = 'PATH_CERTIFICATE.p12';
$passwors = 'PASSWORS_CERTIFICATE';

$xmlString = <<<XML
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:wcf="http://wcf.dian.colombia">
    <soap:Header/>
    <soap:Body>
        <wcf:GetStatus>
            <!--Optional:-->
            <wcf:trackId>123456666</wcf:trackId>
        </wcf:GetStatus>
    </soap:Body>
</soap:Envelope>
XML;

$domDocument = new DOMDocument();
$domDocument->loadXML($xmlString);

$soapdian21 = new SOAPDIAN21($pathCertificate, $passwors);
$soapdian21->Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatus';

$soapdian21->sign($domDocument->saveXML());

file_put_contents('./SOAPDIAN21.xml', $soapdian21->xml);
```
## Basic usage sing XAdES sha1

```php
$pathCertificate = 'PATH_CERTIFICATE.p12';
$passwors = 'PASSWORS_CERTIFICATE';

$xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice>
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
</Invoice>
XML;

$domDocument = new DOMDocument();
$domDocument->loadXML($xmlString);

$xadesDIAN = new XAdESDIAN($pathCertificate, $passwors, $this->xmlString, XAdESDIAN::ALGO_SHA1);

file_put_contents('./SING1.xml', $xadesDIAN->xml);
```

## Basic usage sing XAdES sha256

```php
$pathCertificate = 'PATH_CERTIFICATE.p12';
$passwors = 'PASSWORS_CERTIFICATE';

$xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice>
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
</Invoice>
XML;

$domDocument = new DOMDocument();
$domDocument->loadXML($xmlString);

$xadesDIAN = new XAdESDIAN($pathCertificate, $passwors, $this->xmlString);

file_put_contents('./SING256.xml', $xadesDIAN->xml);
```

## Basic usage sing XAdES sha512

```php
$pathCertificate = 'PATH_CERTIFICATE.p12';
$passwors = 'PASSWORS_CERTIFICATE';

$xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Invoice>
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
</Invoice>
XML;

$domDocument = new DOMDocument();
$domDocument->loadXML($xmlString);

$xadesDIAN = new XAdESDIAN($pathCertificate, $passwors, $this->xmlString, XAdESDIAN::ALGO_SHA512);

file_put_contents('./SING512.xml', $xadesDIAN->xml);
```

## Calculate software security code
```php

// If you assign the values "softwareID" and "pin" the library will calculate and assign "Software Security Code" at the moment of signing the document.
$xadesDIAN->softwareID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
$xadesDIAN->pin = '12345';
```

## Calculate "UUID" (CUFE)
```php

// If you assign the value "technicalKey" the library will calculate and assign "UUID" (CUFE) at the moment of signing the document
$xadesDIAN->technicalKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
```

## Authors

* **Frank Aguirre** - *Maintainer* - [Stenfrank](https://github.com/Stenfrank/)

## Donation
If this library help you reduce time to develop, you can give me a cup of coffee :smiley:.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/stenfrank/1?locale.x=es_XC)
 
