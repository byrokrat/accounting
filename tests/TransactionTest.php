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

use ledgr\utils\Amount;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAccount()
    {
        $account = new Account('1920', 'T', 'PlusGiro');
        $amount = new Amount('100.101');
        $t = new Transaction($account, $amount);
        $this->assertEquals($account, $t->getAccount());
    }

    public function testGetAmount()
    {
        $account = new Account('1920', 'T', 'PlusGiro');
        $amount = new Amount('100');
        $t = new Transaction($account, $amount);
        $a = $t->getAmount();
        $this->assertEquals($amount, $a);
    }
}
