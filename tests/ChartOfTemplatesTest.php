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

class ChartOfTemplatesTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $c = new ChartOfTemplates();
        $this->assertFalse($c->exists('T'));

        $T = new Template();
        $T->setId('T');
        $c->addTemplate($T);
        $this->assertTrue($c->exists('T'));

        $this->assertEquals($T, $c->getTemplate('T'));
        $templates = $c->getTemplates();
        $this->assertEquals($T, $templates['T']);

        $c->dropTemplate('T');
        $this->assertFalse($c->exists('T'));
    }

    /**
     * @expectedException ledgr\accounting\Exception\InvalidTemplateException
     */
    public function testTemplateDoesNotExistError()
    {
        $c = new ChartOfTemplates();
        $c->getTemplate('T');
    }

    public function testExportImport()
    {
        $c = new ChartOfTemplates();
        $T = new Template();
        $T->setId('T');
        $c->addTemplate($T);

        $str = serialize($c);
        $c2 = unserialize($str);

        $this->assertTrue($c2->exists('T'));
        $this->assertEquals($T, $c2->getTemplate('T'));
    }
}
