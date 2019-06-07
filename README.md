# soap-dian

Web Service consumption of the DIAN SOAP UBL 2.1.

The author of soap-dian is Frank Aguirre.

# Branches
Master is currently the only actively maintained branch.
* 0.1: Contains valid tests with Binary Security Token, for the DIAN

# Requirements

OpenSSL and PHP version >= 7.0


## How to Install

Install with [`composer.phar`](http://getcomposer.org).

```sh
composer require stenfrank/soap-dian
```

## Basic usage

```php
$pathCertificate = dirname(dirname(__FILE__)).'/PATH_CERTIFICATE.p12';
$passwors = 'PASSWORS_CERTIFICATE';

$domDocument = new DOMDocument();
$domDocument->loadXML($this->xmlString);

$soap21 = new SOAPDIAN21($pathCertificate, $passwors);
$soap21->Action = 'http://wcf.dian.colombia/IWcfDianCustomerServices/GetStatus';
$soap21->To = 'https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc';
$soap21->CurrentTime = time();
$soap21->TimeToLive = 60000;

$soap21->startNodes($domDocument->saveXML());

$domDocumentValidate = new DOMDocument;
$domDocumentValidate->validateOnParse = true;

$domDocumentValidate->save('./SOAPDIAN21.xml')
```

## Authors ✒️

* **Frank Agurre** - *Maintainer* - [Stenfrank](https://github.com/Stenfrank/)