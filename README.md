# UBL 2.1 DIAN

## Español

Nucleo para facturacion electronica con pre-validacion DIAN UBL 2.1.

## Etiquetas de versiones

* 1.0: Contiene pruebas válidas con token de seguridad binario (SOAP) y firma XAdES (XML) con algoritmos sha1, sha256 y sha512.
* 1.1: Contiene plantillas principales para el consumo del servicio web, requiere curl como dependencia.
* 1.1.1: Se solucionó el error de canonización.
* 1.2: Contiene pruebas válidas para el envío de notas de crédito y el cálculo del CUDE.
* 1.3: Licencia LGPL.
* 2.0: Contiene pruebas válidas para el envío de notas de débito y el nombre del documento estándar.
* 3.0: Contiene gestion de peticiones y solicitudes al web service

### Instalacion

`composer require stenfrank/ubl21dian`



## English

Core for electronic invoicing pre-validation - DIAN UBL 2.1.

## Tags
* 1.0: Contains valid tests with binary security token (SOAP) and XAdES signature (XML) with algorithms sha1, sha256 and sha512.
* 1.1: Contains main templates for Web Service consumption, require curl as a dependency.
* 1.1.1: Canonization error is solved.
* 1.2: Contains valid proofs for the sending of credit notes and calculation of the CUDE.
* 1.3: License LGPL.
* 2.0: Contains valid proofs for the sending of debit notes and document name standard.

## Resources
* [Documentation](https://soenac.com/ubl21-dian)

### Authors

* **Frank Aguirre** - *Maintainer* - [Stenfrank](https://github.com/Stenfrank/)
* **Juan Diaz** - *Accomplice* - [FuriosoJack](https://github.com/FuriosoJack/)

## Donation
If this library help you reduce time to develop, you can give me a cup of coffee :smiley:.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/stenfrank/1?locale.x=es_XC)



