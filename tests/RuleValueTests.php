<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SengentoBV\ChronopostSdk\ChronoApiClient;
use SengentoBV\ChronopostSdk\ChronoFaultHandler;
use SengentoBV\ChronopostSdk\ServiceClients\ChronoQuickCostServiceClient;
use SengentoBV\ChronopostSdk\ServiceClients\ChronoRelayPointServiceClient;
use SengentoBV\ChronopostSdk\ServiceClients\ChronoShippingServiceClient;
use SengentoBV\ChronopostSdk\ServiceClients\ChronoSlotGeoServiceClient;
use SengentoBV\ChronopostSdk\ServiceClients\ChronoTrackingServiceClient;
use SengentoBV\ChronopostSdk\Services\ChronoSoapServiceMap;
use SengentoBV\PhpRulesEngine\RuleValue;

class RuleValueTests extends TestCase
{
    public function setUp(): void
    {

    }

    public function testValidStaticConstructorWorks()
    {
        $var = new RuleValue('someValue', RuleValue::TYPE_STATIC);

        $this->assertEquals('someValue', $var->value);
    }

    public function testValidFieldConstructorWorks()
    {
        $var = new RuleValue('someField', RuleValue::TYPE_FIELD);

        $this->assertEquals('someField', $var->value);
    }

    public function testDefaultConstructorWorks()
    {
        $var = new RuleValue('someValue');

        $this->assertEquals('someValue', $var->value);
        $this->assertEquals(RuleValue::TYPE_STATIC, $var->type);
    }

    public function testValidStaticToArrayWorks()
    {
        $var = new RuleValue('someValue', RuleValue::TYPE_STATIC);
        $array = $var->toArray();

        $this->assertEquals(['$type' => RuleValue::TYPE_STATIC, 'value' => 'someValue'], $array);
    }

    public function testValidFieldToArrayWorks()
    {
        $var = new RuleValue('someField', RuleValue::TYPE_FIELD);
        $array = $var->toArray();

        $this->assertEquals(['$type' => RuleValue::TYPE_FIELD, 'value' => 'someField'], $array);
    }
}