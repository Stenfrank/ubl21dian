# soap-dian

Web Service consumption of the DIAN SOAP UBL 2.1.

The author of soap-dian is Frank Aguirre.

# Branches
Master is currently the only actively maintained branch.
* 0.1: Contains valid tests with Binary Security Token, for the DIAN.
* 0.2: Fixed timestamp error, CARBON is no longer a dependency.

# Requirements

OpenSSL and PHP version >= 7.0


## How to Install

Install with [`composer.phar`](http://getcomposer.org).

```sh
composer require stenfrank/soap-dian
```

## Basic usage

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

$soapdian21->startNodes($domDocument->saveXML());

file_put_contents('./SOAPDIAN21.xml', $soapdian21->soap);
```

## Authors ✒️

* **Frank Aguirre** - *Maintainer* - [Stenfrank](https://github.com/Stenfrank/)

## Donation
If this library help you reduce time to develop, you can give me a cup of coffee :smiley:.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4CRKGHBJAY2SJ&source=url)
 
