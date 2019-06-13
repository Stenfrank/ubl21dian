# soap-dian

Web Service consumption of the DIAN SOAP UBL 2.1.

# Tags
* 0.1: Contains valid tests with Binary Security Token, for the DIAN.
* 0.2: Fixed timestamp error, CARBON is no longer a dependency.
* 1.0: XAdES signature is added with the sha256 and sha512 algorithm.

## How to Install

Install with [`composer.phar`](http://getcomposer.org).

```sh
composer require stenfrank/soap-dian
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

## Basic usage sing sha256

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

## Basic usage sing sha512

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

## Authors

* **Frank Aguirre** - *Maintainer* - [Stenfrank](https://github.com/Stenfrank/)

## Donation
If this library help you reduce time to develop, you can give me a cup of coffee :smiley:.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4CRKGHBJAY2SJ&source=url)
 
