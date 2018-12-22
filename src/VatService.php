<?php

namespace BespokeSupport\Vat;

class VatService
{
    /**
     * @var array
     *
     * @see https://github.com/dannyvankooten/vat.php/blob/master/src/Validator.php
     */
    protected static $patterns = array(
        'AT' => 'U[A-Z\d]{8}',
        'BE' => '(0\d{9}|\d{10})',
        'BG' => '\d{9,10}',
        'CY' => '\d{8}[A-Z]',
        'CZ' => '\d{8,10}',
        'DE' => '\d{9}',
        'DK' => '(\d{2} ?){3}\d{2}',
        'EE' => '\d{9}',
        'EL' => '\d{9}',
        'ES' => '[A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8}',
        'FI' => '\d{8}',
        'FR' => '([A-Z]{2}|\d{2})\d{9}',
        'GB' => '\d{9}|\d{12}|(GD|HA)\d{3}',
        'HR' => '\d{11}',
        'HU' => '\d{8}',
        'IE' => '[A-Z\d]{8}|[A-Z\d]{9}',
        'IT' => '\d{11}',
        'LT' => '(\d{9}|\d{12})',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'MT' => '\d{8}',
        'NL' => '\d{9}B\d{2}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}'
    );

    public static function split($string)
    {
        $countryCode = null;
        $vatNumber = null;
        $valid = null;

        $string = preg_replace('#[^\dA-Z]#', '', strtoupper($string));

        preg_match('#^([A-Z]{2,2})?([\w\d]{8,})$#', $string, $matches);

        if (!\count($matches)) {
            return null;
        }

        $initial = substr($string, 0, 2);

        $countryCode = $matches[1] ?? null;
        $vatNumber = $matches[2] ?? null;

        if (array_key_exists($initial, self::$patterns) && !$countryCode) {
            return null;
        }

        $pattern = self::$patterns[$countryCode] ?? null;

        if ($pattern) {
            $valid = (bool) preg_match("#$pattern#", $vatNumber);
        }

        return [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'vat' => $countryCode . $vatNumber,
            'valid' => $valid,
        ];
    }

    /**
     * @param string $countryCode
     * @param string $vatNumber
     * @return VatResponseObject
     */
    public static function lookup(string $countryCode, string $vatNumber)
    {
        $res = VatSoapClient::checkVat([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber
        ]);

        if (!$res) {
            return $res;
        }

        $res->name = trim($res->name);
        $res->address = trim($res->address);

        $res->vat = $res->countryCode . $res->vatNumber;

        return $res;
    }
}
