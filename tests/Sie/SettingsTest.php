<?php
declare(strict_types=1);

namespace byrokrat\accounting\Sie;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultAccountingYear()
    {
        list($firstDay, $lastDay) = (new Settings)->getAccountingYear();

        $this->assertSame(
            date('Y') . '-01-01',
            $firstDay->format('Y-m-d')
        );

        $this->assertSame(
            date('Y') . '-12-31',
            $lastDay->format('Y-m-d')
        );
    }

    public function settersAndGettersProvider()
    {
        return [
            ['setProgram', 'getProgram'],
            ['setProgramVersion', 'getProgramVersion'],
            ['setCreator', 'getCreator'],
            ['setCompany', 'getCompany'],
            ['setChartType', 'getChartType'],
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
