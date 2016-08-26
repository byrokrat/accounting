<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie;

use byrokrat\accounting\Account;
use byrokrat\accounting\Exception;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Verification;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

/**
 * @covers \byrokrat\accounting\Sie\SIE
 */
class SIETest extends \PHPUnit_Framework_TestCase
{
    public function testUnbalancedVerification()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $sie = new SIE();
        $ver = new Verification();
        $ver->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100', 2)));
        $ver->addTransaction(new Transaction(new Account\Earning('3000', 'Income'), new Amount('-50', 2)));
        $sie->addVerification($ver);
    }

    public function testAccountingYearError()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $sie = new SIE();
        $sie->setYear(new \DateTime('2012-01-01'), new \DateTime('2012-12-31'));
        $ver = (new Verification)->setDate(new \DateTimeImmutable('2013-01-01'));
        $ver->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100', 2)));
        $ver->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('-100', 2)));
        $sie->addVerification($ver);
    }

    public function testSetProgram()
    {
        $sie = new SIE();
        $sie->setProgram('foo"bar', "1.\n0");
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"foo\\\"bar\" \"1.0\"\r\n#FORMAT PC8"
            ."\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\"\r\n"
            ."#KPTYP \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($expected, $sie->generate());
    }

    public function testSetCreator()
    {
        $sie = new SIE();
        $sie->setCreator('foo');
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"foo\"\r\n#SIETYP 4\r\n#FNAMN \"\"\r\n#KPTYP"
            ." \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($expected, $sie->generate());
    }

    public function testSetCompany()
    {
        $sie = new SIE();
        $sie->setCompany('foo');
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
           ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"foo\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($expected, $sie->generate());
    }

    public function testSetYear()
    {
        $sie = new SIE();
        $sie->setYear(new \DateTime('2013-01-01'), new \DateTime('2013-12-31'));
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n#RAR 0 20130101 20131231\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($expected, $sie->generate());
    }

    public function testsetTypeOfChart()
    {
        $sie = new SIE();
        $sie->setCompany('foo');
        $sie->setTypeOfChart('BAS96');
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"foo"
            ."\"\r\n#KPTYP \"BAS96\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($expected, $sie->generate());
    }

    public function testGenerate()
    {
        $sie = new SIE();
        $year = date('Y');
        $sie->setYear(new \DateTime("$year-01-01"), new \DateTime("$year-12-31"));

        $ver = new Verification();
        $ver->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100', 2)));
        $ver->addTransaction(new Transaction(new Account\Earning('3000', 'Income'), new Amount('-100', 2)));
        $sie->addVerification($ver);

        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n#RAR 0 {$year}0101 {$year}1231\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \"3000"
            ."\" \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n"
            ."\r\n#VER \"\" \"\" $date \"\"\r\n{\r\n"
            ."\t#TRANS 1920 {} 100.00\r\n"
            ."\t#TRANS 3000 {} -100.00\r\n"
            ."}\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);

        $this->assertEquals($expected, $sie->generate());
    }

    public function testExportChart()
    {
        $accounts = new Query([
            new Account\Asset('1920', 'Bank'),
            new Account\Earning('3000', 'Income')
        ]);

        $date = date('Ymd');

        $expected = "#FILTYP KONTO\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#TEXT"
            ." \"FOOBAR\"\r\n#FORMAT PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#KPTYP"
            ." TODO\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \"3000\""
            ." \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n";

        $expected = iconv("UTF-8", "CP437", $expected);

        $this->assertEquals(
            $expected,
            (new SIE)->exportChart('FOOBAR', $accounts)
        );
    }

    public function testImportChart()
    {
        $siestr = "#FILTYP KONTO\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#TEXT"
            ." \"FOOBAR\"\r\n#FORMAT PC8\r\n#GEN 20120430 \"byrokrat_SIE\"\r\n"
            ."#KPTYP \"BAS2010\"\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \""
            ."3000\" \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n";

        $accounts = (new SIE)->importChart(
            iconv("UTF-8", "CP437", $siestr)
        );

        // TODO not supported at the moment..
        // $this->assertEquals('BAS2010', $accounts->getChartType());

        $this->assertEquals(
            [
                new Account\Asset('1920', 'Bank'),
                new Account\Earning('3000', 'Income')
            ],
            $accounts
        );
    }

    public function testImportChartInvalidChartType()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $siestr = "#FILTYP KONTO\r\n#KPTYP";
        $siestr = iconv("UTF-8", "CP437", $siestr);
        (new SIE)->importChart($siestr);
    }

    public function invalidSieAccountStringProvider()
    {
        return [
            ["#KONTO \"1920\"\r\n#KTYP \"1920\" \"T\""],
            ["#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\""],
            ["#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1510\" \"T\""],
            ["#KONTO \"1920\" \"Bank\""]
        ];
    }

    /**
     * @dataProvider invalidSieAccountStringProvider
     */
    public function testImportChartInvalidAccount($account)
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $siestr = "#FILTYP KONTO\r\n#KPTYP \"BAS2010\"\r\n";
        $siestr .= $account;
        $siestr = iconv("UTF-8", "CP437", $siestr);
        (new SIE)->importChart($siestr);
    }

    public function testClear()
    {
        $sie = new SIE();
        $ver = new Verification();
        $ver->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100', 2)));
        $ver->addTransaction(new Transaction(new Account\Earning('3000', 'Income'), new Amount('-100', 2)));
        $sie->addVerification($ver);
        $sie->clear();
        $this->assertEquals(0, preg_match('/#VER/', $sie->generate()));
    }
}
