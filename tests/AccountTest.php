<?php
/**
 * This file is part of ledgr/accounting.
 *
 * Copyright (c) 2014 Hannes Forsgård
 *
 * ledgr/accounting is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ledgr/accounting is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ledgr/accounting.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ledgr\accounting;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * List of invalid account values
     */
    public function invalidAccountProvider()
    {
        return array(
            array('a', 'I', 'Name'),
            array('', 'I', 'Name'),
            array('123', 'I', 'Name'),
            array('12345', 'I', 'Name'),
            array('1234', 'A', 'Name'),
            array('1234', 'I', ''),
            array('1234', 'I', 123),
        );
    }

    /**
     * @expectedException ledgr\accounting\Exception\InvalidAccountException
     * @dataProvider invalidAccountProvider
     */
    public function testAddAccountFaliure($account, $type, $name)
    {
        new Account($account, $type, $name);
    }

    public function testConstruct()
    {
        new Account('1920', 'T', 'PlusGiro');
        $this->assertTrue(true);
    }


    public function testEquals()
    {
        $a = new Account('1920', 'T', 'PlusGiro');
        $a1 = new Account('1920', 'T', 'PlusGiro');
        $b = new Account('3000', 'T', 'PlusGiro');
        $c = new Account('1920', 'I', 'PlusGiro');
        $d = new Account('1920', 'T', 'Bank');
        $this->assertTrue($a->equals($a1));
        $this->assertFalse($a->equals($b));
        $this->assertFalse($a->equals($c));
        $this->assertFalse($a->equals($d));
    }
}
