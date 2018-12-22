<?php

namespace App;

use PHPUnit\Framework\TestCase;

use BespokeSupport\Vat\VatService;

class VatServiceTest extends TestCase
{
    public function testSplitNull()
    {
        $var = null;
        $res = VatService::split($var);
        $this->assertNull($res);
    }

    public function testSplitText()
    {
        $var = 'GB';
        $res = VatService::split($var);
        $this->assertNull($res);
    }

    public function testSplitNumShort()
    {
        $var = 'GB1234567';
        $res = VatService::split($var);
        $this->assertNull($res);
    }

    public function testSplitNum()
    {
        $var = '222452988';
        $res = VatService::split($var);

        $vat = $res['vatNumber'] ?? null;

        $this->assertEquals($var, $vat);
    }

    public function testSplit()
    {
        $num = '222452988';
        $var = 'GB222452988';
        $res = VatService::split($var);

        $number = $res['vatNumber'] ?? null;
        $vat = $res['vat'] ?? null;
        $valid = $res['valid'] ?? false;

        $this->assertEquals($num, $number);
        $this->assertEquals($var, $vat);
        $this->assertTrue($valid);
    }
}
