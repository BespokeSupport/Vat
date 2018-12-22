<?php

namespace BespokeSupport\Vat;

use SoapClient;

/**
 * @method static VatResponseObject|null checkVat(array $args)
 */
class VatSoapClient
{
    private static function client()
    {
        $client = new SoapClient(__DIR__ . '/../checkVatService.wsdl.xml');

        return $client;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $client = self::client();

        return $client->$method($args[0]);
    }
}
