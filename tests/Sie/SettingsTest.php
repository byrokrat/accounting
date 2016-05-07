<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultAccountingYear()
    {
        $this->assertSame(
            date('Y') . '-01-01',
            (new Settings)->getAccountingYearFirstDay()->format('Y-m-d')
        );

        $this->assertSame(
            date('Y') . '-12-31',
            (new Settings)->getAccountingYearLastDay()->format('Y-m-d')
        );
    }

    public function settersAndGettersProvider()
    {
        return [
            ['setProgram', 'getProgram'],
            ['setProgramVersion', 'getProgramVersion'],
            ['setCreator', 'getCreator'],
            ['setTargetCompany', 'getTargetCompany'],
            ['setDescription', 'getDescription'],
        ];
    }

    /**
     * @dataProvider settersAndGettersProvider
     */
    public function testSettersAndGetters($setter, $getter)
    {
        $settings = new Settings;

        $this->assertInternalType(
            'string',
            $settings->$getter()
        );

        $settings->$setter('foobar');

        $this->assertSame(
            'foobar',
            $settings->$getter()
        );
    }
}
