<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie;

use byrokrat\accounting\Verification;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Account;
use byrokrat\accounting\AccountSet;
use byrokrat\accounting\Exception;
use byrokrat\amount\Amount;

class SIETest extends \PHPUnit_Framework_TestCase
{
    public function testUnbalancedVerification()
    {
        $this->setExpectedException(Exception\UnexpectedValueException::CLASS);
        $sie = new SIE();
        $v = new Verification('testver');
        $v->addTransaction(new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100', 2)));
        $v->addTransaction(new Transaction(new Account\Earning(3000, 'Income'), new Amount('-50', 2)));
        $sie->addVerification($v);
    }

    public function testAccountingYearError()
    {
        $this->setExpectedException(Exception\OutOfBoundsException::CLASS);
        $sie = new SIE();
        $sie->setYear(new \DateTime('2012-01-01'), new \DateTime('2012-12-31'));
        $v = new Verification('testver', new \DateTimeImmutable('2013-01-01'));
        $sie->addVerification($v);
    }

    public function testSetProgram()
    {
        $sie = new SIE();
        $sie->setProgram('foo"bar', "1.\n0");
        $txt = $sie->generate();
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"foo\\\"bar\" \"1.0\"\r\n#FORMAT PC8"
            ."\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\"\r\n"
            ."#KPTYP \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($txt, $expected);
    }

    public function testSetCreator()
    {
        $sie = new SIE();
        $sie->setCreator('foo');
        $txt = $sie->generate();
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"foo\"\r\n#SIETYP 4\r\n#FNAMN \"\"\r\n#KPTYP"
            ." \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($txt, $expected);
    }

    public function testSetCompany()
    {
        $sie = new SIE();
        $sie->setCompany('foo');
        $txt = $sie->generate();
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
           ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"foo\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($txt, $expected);
    }

    public function testSetYear()
    {
        $sie = new SIE();
        $sie->setYear(new \DateTime('2013-01-01'), new \DateTime('2013-12-31'));
        $txt = $sie->generate();
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n#RAR 0 20130101 20131231\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($txt, $expected);
    }

    public function testsetTypeOfChart()
    {
        $sie = new SIE();
        $sie->setCompany('foo');
        $sie->setTypeOfChart('BAS96');
        $txt = $sie->generate();
        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"foo"
            ."\"\r\n#KPTYP \"BAS96\"\r\n\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);
        $this->assertEquals($txt, $expected);
    }

    public function testGenerate()
    {
        $sie = new SIE();
        $year = date('Y');
        $sie->setYear(new \DateTime("$year-01-01"), new \DateTime("$year-12-31"));

        $v = new Verification('testver');
        $v->addTransaction(new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100', 2)));
        $v->addTransaction(new Transaction(new Account\Earning(3000, 'Income'), new Amount('-100', 2)));
        $sie->addVerification($v);

        $txt = $sie->generate();

        $date = date('Ymd');
        $expected = "#FLAGGA 0\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#FORMAT"
            ." PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#SIETYP 4\r\n#FNAMN \"\""
            ."\r\n#KPTYP \"EUBAS97\"\r\n#RAR 0 {$year}0101 {$year}1231\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \"3000"
            ."\" \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n"
            ."\r\n#VER \"\" \"\" $date \"testver\"\r\n{\r\n"
            ."\t#TRANS 1920 {} 100.00\r\n"
            ."\t#TRANS 3000 {} -100.00\r\n"
            ."}\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);

        $this->assertEquals($expected, $txt);
    }

    public function testExportChart()
    {
        $accounts = new AccountSet();
        $accounts->addAccount(new Account\Asset(1920, 'Bank'));
        $accounts->addAccount(new Account\Earning(3000, 'Income'));

        $sie = new SIE();
        $txt = $sie->exportChart('FOOBAR', $accounts);

        $date = date('Ymd');
        $expected = "#FILTYP KONTO\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#TEXT"
            ." \"FOOBAR\"\r\n#FORMAT PC8\r\n#GEN $date \"byrokrat_SIE\"\r\n#KPTYP"
            ." TODO\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \"3000\""
            ." \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n";
        $expected = iconv("UTF-8", "CP437", $expected);

        $this->assertEquals($expected, $txt);
    }

    public function testImportChart()
    {
        $siestr = "#FILTYP KONTO\r\n#PROGRAM \"byrokrat_SIE\" \"1.0\"\r\n#TEXT"
            ." \"FOOBAR\"\r\n#FORMAT PC8\r\n#GEN 20120430 \"byrokrat_SIE\"\r\n"
            ."#KPTYP \"BAS2010\"\r\n\r\n"
            ."#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\" \"T\"\r\n#KONTO \""
            ."3000\" \"Income\"\r\n#KTYP \"3000\" \"I\"\r\n";
        $siestr = iconv("UTF-8", "CP437", $siestr);

        $sie = new SIE();
        $accounts = $sie->importChart($siestr);

        // TODO not supported at the moment..
        // $this->assertEquals('BAS2010', $accounts->getChartType());

        $expected = array(
            '1920' => new Account\Asset(1920, 'Bank'),
            '3000' => new Account\Earning(3000, 'Income')
        );
        $this->assertEquals($expected, iterator_to_array($accounts));
    }

    public function testImportChartInvalidChartType()
    {
        $this->setExpectedException(Exception\RangeException::CLASS);
        $siestr = "#FILTYP KONTO\r\n#KPTYP";
        $siestr = iconv("UTF-8", "CP437", $siestr);
        $sie = new SIE();
        $sie->importChart($siestr);
    }

    public function invalidSieAccountStringProvider()
    {
        return array(
            array("#KONTO \"1920\"\r\n#KTYP \"1920\" \"T\""),
            array("#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1920\""),
            array("#KONTO \"1920\" \"Bank\"\r\n#KTYP \"1510\" \"T\""),
            array("#KONTO \"1920\" \"Bank\""),
        );
    }

    /**
     * @dataProvider invalidSieAccountStringProvider
     */
    public function testImportChartInvalidAccount($account)
    {
        $this->setExpectedException(Exception\RangeException::CLASS);
        $siestr = "#FILTYP KONTO\r\n#KPTYP \"BAS2010\"\r\n";
        $siestr .= $account;
        $siestr = iconv("UTF-8", "CP437", $siestr);
        $sie = new SIE();
        $sie->importChart($siestr);
    }

    public function testClear()
    {
        $sie = new SIE();
        $v = new Verification('testver');
        $v->addTransaction(new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100', 2)));
        $v->addTransaction(new Transaction(new Account\Earning(3000, 'Income'), new Amount('-100', 2)));
        $sie->addVerification($v);
        $sie->clear();
        $this->assertEquals(0, preg_match('/#VER/', $sie->generate()));
    }
}
