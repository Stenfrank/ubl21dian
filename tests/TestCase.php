<?php

namespace Stenfrank\Tests;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Test case.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{

    protected $pathCert = __DIR__.'/resources/certs/certicamara.p12';
    protected $passwordCert;



    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        //Se inicializa el archivo de entorno
        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__."/.env");

        $this->passwordCert = getenv('PASSWORD_CERT');;
    }
}
