<?php

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\MoneyFactory;
use byrokrat\accounting\Sie4\SieMetaData;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\accounting\Verification\Verification;
use Money\Currency;

class Grammar
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseSIE_CONTENT()
    {
        $_position = $this->position;

        if (isset($this->cache['SIE_CONTENT'][$_position])) {
            $_success = $this->cache['SIE_CONTENT'][$_position]['success'];
            $this->position = $this->cache['SIE_CONTENT'][$_position]['position'];
            $this->value = $this->cache['SIE_CONTENT'][$_position]['value'];

            return $_success;
        }

        $_value6 = array();

        $_success = $this->parseRESET_STATE();

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parseFLAGGA_POST();
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_value4 = array();
            $_cut5 = $this->cut;

            while (true) {
                $_position3 = $this->position;

                $this->cut = false;
                $_position1 = $this->position;
                $_cut2 = $this->cut;

                $this->cut = false;
                $_success = $this->parseVALID_POST();

                if (!$_success && !$this->cut) {
                    $this->position = $_position1;

                    $_success = $this->parseIGNORED_CONTENT();
                }

                $this->cut = $_cut2;

                if (!$_success) {
                    break;
                }

                $_value4[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position3;
                $this->value = $_value4;
            }

            $this->cut = $_cut5;

            if ($_success) {
                $posts = $this->value;
            }
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

            $this->value = $_value6;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$posts) {
                return array_filter(
                    $posts,
                    fn($post) => $post instanceof Verification
                );
            });
        }

        $this->cache['SIE_CONTENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SIE_CONTENT');
        }

        return $_success;
    }

    protected function parseRESET_STATE()
    {
        $_position = $this->position;

        if (isset($this->cache['RESET_STATE'][$_position])) {
            $_success = $this->cache['RESET_STATE'][$_position]['success'];
            $this->position = $this->cache['RESET_STATE'][$_position]['position'];
            $this->value = $this->cache['RESET_STATE'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen('')) === '') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen(''));
            $this->position += strlen('');
        } else {
            $_success = false;

            $this->report($this->position, '\'\'');
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                $this->accounts = new AccountBuilder();
                $this->dimensions = new DimensionBuilder();
                $this->meta = new SieMetaData();
                $this->moneyFactory = new MoneyFactory();
            });
        }

        $this->cache['RESET_STATE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RESET_STATE');
        }

        return $_success;
    }

    protected function parseFLAGGA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FLAGGA_POST'][$_position])) {
            $_success = $this->cache['FLAGGA_POST'][$_position]['success'];
            $this->position = $this->cache['FLAGGA_POST'][$_position]['position'];
            $this->value = $this->cache['FLAGGA_POST'][$_position]['value'];

            return $_success;
        }

        $_value9 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value9[] = $this->value;

            if (substr($this->string, $this->position, strlen('FLAGGA')) === 'FLAGGA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FLAGGA'));
                $this->position += strlen('FLAGGA');
            } else {
                $_success = false;

                $this->report($this->position, '\'FLAGGA\'');
            }
        }

        if ($_success) {
            $_value9[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value9[] = $this->value;

            $_position7 = $this->position;
            $_cut8 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position7;
                $this->value = null;
            }

            $this->cut = $_cut8;

            if ($_success) {
                $flag = $this->value;
            }
        }

        if ($_success) {
            $_value9[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value9[] = $this->value;

            $this->value = $_value9;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$flag) {
                $this->meta->sieFlag = (string)$flag;
            });
        }

        $this->cache['FLAGGA_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FLAGGA_POST');
        }

        return $_success;
    }

    protected function parseVALID_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VALID_POST'][$_position])) {
            $_success = $this->cache['VALID_POST'][$_position]['success'];
            $this->position = $this->cache['VALID_POST'][$_position]['position'];
            $this->value = $this->cache['VALID_POST'][$_position]['value'];

            return $_success;
        }

        $_position10 = $this->position;
        $_cut11 = $this->cut;

        $this->cut = false;
        $_success = $this->parseFNAMN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseFNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseFORMAT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseGEN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseKPTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseORGNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parsePROGRAM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parsePROSA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseSIETYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseRAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseTAXAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseVALUTA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseKONTO_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseKTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseUNDERDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseOBJEKT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseOIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseOUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseRES_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseVER_POST();
        }

        $this->cut = $_cut11;

        $this->cache['VALID_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VALID_POST');
        }

        return $_success;
    }

    protected function parseFNAMN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FNAMN_POST'][$_position])) {
            $_success = $this->cache['FNAMN_POST'][$_position]['success'];
            $this->position = $this->cache['FNAMN_POST'][$_position]['position'];
            $this->value = $this->cache['FNAMN_POST'][$_position]['value'];

            return $_success;
        }

        $_value14 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value14[] = $this->value;

            if (substr($this->string, $this->position, strlen('FNAMN')) === 'FNAMN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FNAMN'));
                $this->position += strlen('FNAMN');
            } else {
                $_success = false;

                $this->report($this->position, '\'FNAMN\'');
            }
        }

        if ($_success) {
            $_value14[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value14[] = $this->value;

            $_position12 = $this->position;
            $_cut13 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position12;
                $this->value = null;
            }

            $this->cut = $_cut13;

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value14[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value14[] = $this->value;

            $this->value = $_value14;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                $this->meta->companyName = (string)$name;
            });
        }

        $this->cache['FNAMN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FNAMN_POST');
        }

        return $_success;
    }

    protected function parseFNR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FNR_POST'][$_position])) {
            $_success = $this->cache['FNR_POST'][$_position]['success'];
            $this->position = $this->cache['FNR_POST'][$_position]['position'];
            $this->value = $this->cache['FNR_POST'][$_position]['value'];

            return $_success;
        }

        $_value17 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value17[] = $this->value;

            if (substr($this->string, $this->position, strlen('FNR')) === 'FNR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FNR'));
                $this->position += strlen('FNR');
            } else {
                $_success = false;

                $this->report($this->position, '\'FNR\'');
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_position15 = $this->position;
            $_cut16 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position15;
                $this->value = null;
            }

            $this->cut = $_cut16;

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value17[] = $this->value;

            $this->value = $_value17;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$id) {
                $this->meta->companyIdCode = (string)$id;
            });
        }

        $this->cache['FNR_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FNR_POST');
        }

        return $_success;
    }

    protected function parseFORMAT_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FORMAT_POST'][$_position])) {
            $_success = $this->cache['FORMAT_POST'][$_position]['success'];
            $this->position = $this->cache['FORMAT_POST'][$_position]['position'];
            $this->value = $this->cache['FORMAT_POST'][$_position]['value'];

            return $_success;
        }

        $_value20 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value20[] = $this->value;

            if (substr($this->string, $this->position, strlen('FORMAT')) === 'FORMAT') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FORMAT'));
                $this->position += strlen('FORMAT');
            } else {
                $_success = false;

                $this->report($this->position, '\'FORMAT\'');
            }
        }

        if ($_success) {
            $_value20[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value20[] = $this->value;

            $_position18 = $this->position;
            $_cut19 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position18;
                $this->value = null;
            }

            $this->cut = $_cut19;

            if ($_success) {
                $charset = $this->value;
            }
        }

        if ($_success) {
            $_value20[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value20[] = $this->value;

            $this->value = $_value20;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$charset) {
                $this->meta->charset = (string)$charset;
            });
        }

        $this->cache['FORMAT_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FORMAT_POST');
        }

        return $_success;
    }

    protected function parseGEN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['GEN_POST'][$_position])) {
            $_success = $this->cache['GEN_POST'][$_position]['success'];
            $this->position = $this->cache['GEN_POST'][$_position]['position'];
            $this->value = $this->cache['GEN_POST'][$_position]['value'];

            return $_success;
        }

        $_value25 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value25[] = $this->value;

            if (substr($this->string, $this->position, strlen('GEN')) === 'GEN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('GEN'));
                $this->position += strlen('GEN');
            } else {
                $_success = false;

                $this->report($this->position, '\'GEN\'');
            }
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_position21 = $this->position;
            $_cut22 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position21;
                $this->value = null;
            }

            $this->cut = $_cut22;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_position23 = $this->position;
            $_cut24 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position23;
                $this->value = null;
            }

            $this->cut = $_cut24;

            if ($_success) {
                $user = $this->value;
            }
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value25[] = $this->value;

            $this->value = $_value25;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date, &$user) {
                $this->meta->generationDate = (string)$date;
                $this->meta->generatingUser = (string)$user;
            });
        }

        $this->cache['GEN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'GEN_POST');
        }

        return $_success;
    }

    protected function parseKPTYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['KPTYP_POST'][$_position])) {
            $_success = $this->cache['KPTYP_POST'][$_position]['success'];
            $this->position = $this->cache['KPTYP_POST'][$_position]['position'];
            $this->value = $this->cache['KPTYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value28 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value28[] = $this->value;

            if (substr($this->string, $this->position, strlen('KPTYP')) === 'KPTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KPTYP'));
                $this->position += strlen('KPTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'KPTYP\'');
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_position26 = $this->position;
            $_cut27 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position26;
                $this->value = null;
            }

            $this->cut = $_cut27;

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value28[] = $this->value;

            $this->value = $_value28;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$type) {
                $this->meta->accountPlanType = (string)$type;
            });
        }

        $this->cache['KPTYP_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'KPTYP_POST');
        }

        return $_success;
    }

    protected function parseORGNR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['ORGNR_POST'][$_position])) {
            $_success = $this->cache['ORGNR_POST'][$_position]['success'];
            $this->position = $this->cache['ORGNR_POST'][$_position]['position'];
            $this->value = $this->cache['ORGNR_POST'][$_position]['value'];

            return $_success;
        }

        $_value31 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value31[] = $this->value;

            if (substr($this->string, $this->position, strlen('ORGNR')) === 'ORGNR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('ORGNR'));
                $this->position += strlen('ORGNR');
            } else {
                $_success = false;

                $this->report($this->position, '\'ORGNR\'');
            }
        }

        if ($_success) {
            $_value31[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value31[] = $this->value;

            $_position29 = $this->position;
            $_cut30 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position29;
                $this->value = null;
            }

            $this->cut = $_cut30;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value31[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value31[] = $this->value;

            $this->value = $_value31;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number) {
                $this->meta->companyOrgNr = (string)$number;
            });
        }

        $this->cache['ORGNR_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ORGNR_POST');
        }

        return $_success;
    }

    protected function parsePROGRAM_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['PROGRAM_POST'][$_position])) {
            $_success = $this->cache['PROGRAM_POST'][$_position]['success'];
            $this->position = $this->cache['PROGRAM_POST'][$_position]['position'];
            $this->value = $this->cache['PROGRAM_POST'][$_position]['value'];

            return $_success;
        }

        $_value36 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value36[] = $this->value;

            if (substr($this->string, $this->position, strlen('PROGRAM')) === 'PROGRAM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PROGRAM'));
                $this->position += strlen('PROGRAM');
            } else {
                $_success = false;

                $this->report($this->position, '\'PROGRAM\'');
            }
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_position32 = $this->position;
            $_cut33 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position32;
                $this->value = null;
            }

            $this->cut = $_cut33;

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_position34 = $this->position;
            $_cut35 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position34;
                $this->value = null;
            }

            $this->cut = $_cut35;

            if ($_success) {
                $version = $this->value;
            }
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value36[] = $this->value;

            $this->value = $_value36;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name, &$version) {
                $this->meta->generatingProgram = (string)$name;
                $this->meta->generatingProgramVersion = (string)$version;
            });
        }

        $this->cache['PROGRAM_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PROGRAM_POST');
        }

        return $_success;
    }

    protected function parsePROSA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['PROSA_POST'][$_position])) {
            $_success = $this->cache['PROSA_POST'][$_position]['success'];
            $this->position = $this->cache['PROSA_POST'][$_position]['position'];
            $this->value = $this->cache['PROSA_POST'][$_position]['value'];

            return $_success;
        }

        $_value40 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value40[] = $this->value;

            if (substr($this->string, $this->position, strlen('PROSA')) === 'PROSA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PROSA'));
                $this->position += strlen('PROSA');
            } else {
                $_success = false;

                $this->report($this->position, '\'PROSA\'');
            }
        }

        if ($_success) {
            $_value40[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value40[] = $this->value;

            $_value38 = array();
            $_cut39 = $this->cut;

            while (true) {
                $_position37 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value38[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position37;
                $this->value = $_value38;
            }

            $this->cut = $_cut39;

            if ($_success) {
                $text = $this->value;
            }
        }

        if ($_success) {
            $_value40[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value40[] = $this->value;

            $this->value = $_value40;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$text) {
                $this->meta->description = implode(' ', $text);
            });
        }

        $this->cache['PROSA_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PROSA_POST');
        }

        return $_success;
    }

    protected function parseSIETYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['SIETYP_POST'][$_position])) {
            $_success = $this->cache['SIETYP_POST'][$_position]['success'];
            $this->position = $this->cache['SIETYP_POST'][$_position]['position'];
            $this->value = $this->cache['SIETYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value43 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value43[] = $this->value;

            if (substr($this->string, $this->position, strlen('SIETYP')) === 'SIETYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('SIETYP'));
                $this->position += strlen('SIETYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'SIETYP\'');
            }
        }

        if ($_success) {
            $_value43[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value43[] = $this->value;

            $_position41 = $this->position;
            $_cut42 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position41;
                $this->value = null;
            }

            $this->cut = $_cut42;

            if ($_success) {
                $ver = $this->value;
            }
        }

        if ($_success) {
            $_value43[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value43[] = $this->value;

            $this->value = $_value43;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$ver) {
                $this->meta->sieVersion = (string)$ver;
            });
        }

        $this->cache['SIETYP_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SIETYP_POST');
        }

        return $_success;
    }

    protected function parseRAR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['RAR_POST'][$_position])) {
            $_success = $this->cache['RAR_POST'][$_position]['success'];
            $this->position = $this->cache['RAR_POST'][$_position]['position'];
            $this->value = $this->cache['RAR_POST'][$_position]['value'];

            return $_success;
        }

        $_position44 = $this->position;
        $_cut45 = $this->cut;

        $this->cut = false;
        $_success = $this->parseRAR_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position44;

            $_success = $this->parseRAR_PREVIOUS_YEAR();
        }

        $this->cut = $_cut45;

        $this->cache['RAR_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAR_POST');
        }

        return $_success;
    }

    protected function parseRAR_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['RAR_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['RAR_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['RAR_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['RAR_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value50 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value50[] = $this->value;

            if (substr($this->string, $this->position, strlen('RAR')) === 'RAR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RAR'));
                $this->position += strlen('RAR');
            } else {
                $_success = false;

                $this->report($this->position, '\'RAR\'');
            }
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_position46 = $this->position;
            $_cut47 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position46;
                $this->value = null;
            }

            $this->cut = $_cut47;

            if ($_success) {
                $yearStart = $this->value;
            }
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_position48 = $this->position;
            $_cut49 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position48;
                $this->value = null;
            }

            $this->cut = $_cut49;

            if ($_success) {
                $yearEnd = $this->value;
            }
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value50[] = $this->value;

            $this->value = $_value50;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$yearStart, &$yearEnd) {
                $this->meta->accountingYearStart = (string)$yearStart;
                $this->meta->accountingYearEnd = (string)$yearEnd;
            });
        }

        $this->cache['RAR_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAR_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseRAR_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['RAR_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['RAR_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['RAR_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['RAR_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value55 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value55[] = $this->value;

            if (substr($this->string, $this->position, strlen('RAR')) === 'RAR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RAR'));
                $this->position += strlen('RAR');
            } else {
                $_success = false;

                $this->report($this->position, '\'RAR\'');
            }
        }

        if ($_success) {
            $_value55[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value55[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value55[] = $this->value;

            $_position51 = $this->position;
            $_cut52 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position51;
                $this->value = null;
            }

            $this->cut = $_cut52;

            if ($_success) {
                $yearStart = $this->value;
            }
        }

        if ($_success) {
            $_value55[] = $this->value;

            $_position53 = $this->position;
            $_cut54 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position53;
                $this->value = null;
            }

            $this->cut = $_cut54;

            if ($_success) {
                $yearEnd = $this->value;
            }
        }

        if ($_success) {
            $_value55[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value55[] = $this->value;

            $this->value = $_value55;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$yearStart, &$yearEnd) {
                $this->meta->previousAccountingYearStart = (string)$yearStart;
                $this->meta->previousAccountingYearEnd = (string)$yearEnd;
            });
        }

        $this->cache['RAR_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAR_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseTAXAR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['TAXAR_POST'][$_position])) {
            $_success = $this->cache['TAXAR_POST'][$_position]['success'];
            $this->position = $this->cache['TAXAR_POST'][$_position]['position'];
            $this->value = $this->cache['TAXAR_POST'][$_position]['value'];

            return $_success;
        }

        $_value58 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value58[] = $this->value;

            if (substr($this->string, $this->position, strlen('TAXAR')) === 'TAXAR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('TAXAR'));
                $this->position += strlen('TAXAR');
            } else {
                $_success = false;

                $this->report($this->position, '\'TAXAR\'');
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_position56 = $this->position;
            $_cut57 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position56;
                $this->value = null;
            }

            $this->cut = $_cut57;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value58[] = $this->value;

            $this->value = $_value58;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year) {
                $this->meta->taxationYear = (string)$year;
            });
        }

        $this->cache['TAXAR_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'TAXAR_POST');
        }

        return $_success;
    }

    protected function parseVALUTA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VALUTA_POST'][$_position])) {
            $_success = $this->cache['VALUTA_POST'][$_position]['success'];
            $this->position = $this->cache['VALUTA_POST'][$_position]['position'];
            $this->value = $this->cache['VALUTA_POST'][$_position]['value'];

            return $_success;
        }

        $_value61 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value61[] = $this->value;

            if (substr($this->string, $this->position, strlen('VALUTA')) === 'VALUTA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VALUTA'));
                $this->position += strlen('VALUTA');
            } else {
                $_success = false;

                $this->report($this->position, '\'VALUTA\'');
            }
        }

        if ($_success) {
            $_value61[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value61[] = $this->value;

            $_position59 = $this->position;
            $_cut60 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position59;
                $this->value = null;
            }

            $this->cut = $_cut60;

            if ($_success) {
                $currency = $this->value;
            }
        }

        if ($_success) {
            $_value61[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value61[] = $this->value;

            $this->value = $_value61;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$currency) {
                $this->meta->currency = (string)$currency;
                $this->moneyFactory->setCurrency(new Currency($currency));
            });
        }

        $this->cache['VALUTA_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VALUTA_POST');
        }

        return $_success;
    }

    protected function parseKONTO_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['KONTO_POST'][$_position])) {
            $_success = $this->cache['KONTO_POST'][$_position]['success'];
            $this->position = $this->cache['KONTO_POST'][$_position]['position'];
            $this->value = $this->cache['KONTO_POST'][$_position]['value'];

            return $_success;
        }

        $_value66 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value66[] = $this->value;

            if (substr($this->string, $this->position, strlen('KONTO')) === 'KONTO') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KONTO'));
                $this->position += strlen('KONTO');
            } else {
                $_success = false;

                $this->report($this->position, '\'KONTO\'');
            }
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_position62 = $this->position;
            $_cut63 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position62;
                $this->value = null;
            }

            $this->cut = $_cut63;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_position64 = $this->position;
            $_cut65 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position64;
                $this->value = null;
            }

            $this->cut = $_cut65;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value66[] = $this->value;

            $this->value = $_value66;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$desc) {
                $this->accounts->defineAccount(id: (string)$number, description: (string)$desc);
            });
        }

        $this->cache['KONTO_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'KONTO_POST');
        }

        return $_success;
    }

    protected function parseKTYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['KTYP_POST'][$_position])) {
            $_success = $this->cache['KTYP_POST'][$_position]['success'];
            $this->position = $this->cache['KTYP_POST'][$_position]['position'];
            $this->value = $this->cache['KTYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value71 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value71[] = $this->value;

            if (substr($this->string, $this->position, strlen('KTYP')) === 'KTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KTYP'));
                $this->position += strlen('KTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'KTYP\'');
            }
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_position67 = $this->position;
            $_cut68 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position67;
                $this->value = null;
            }

            $this->cut = $_cut68;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_position69 = $this->position;
            $_cut70 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position69;
                $this->value = null;
            }

            $this->cut = $_cut70;

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value71[] = $this->value;

            $this->value = $_value71;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$type) {
                $this->accounts->defineAccount(id: (string)$number, type: (string)$type);
            });
        }

        $this->cache['KTYP_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'KTYP_POST');
        }

        return $_success;
    }

    protected function parseDIM_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['DIM_POST'][$_position])) {
            $_success = $this->cache['DIM_POST'][$_position]['success'];
            $this->position = $this->cache['DIM_POST'][$_position]['position'];
            $this->value = $this->cache['DIM_POST'][$_position]['value'];

            return $_success;
        }

        $_value76 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value76[] = $this->value;

            if (substr($this->string, $this->position, strlen('DIM')) === 'DIM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('DIM'));
                $this->position += strlen('DIM');
            } else {
                $_success = false;

                $this->report($this->position, '\'DIM\'');
            }
        }

        if ($_success) {
            $_value76[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value76[] = $this->value;

            $_position72 = $this->position;
            $_cut73 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position72;
                $this->value = null;
            }

            $this->cut = $_cut73;

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value76[] = $this->value;

            $_position74 = $this->position;
            $_cut75 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position74;
                $this->value = null;
            }

            $this->cut = $_cut75;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value76[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value76[] = $this->value;

            $this->value = $_value76;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$id, &$desc) {
                $this->dimensions->defineDimension(id: (string)$id, description: (string)$desc);
            });
        }

        $this->cache['DIM_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'DIM_POST');
        }

        return $_success;
    }

    protected function parseUNDERDIM_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['UNDERDIM_POST'][$_position])) {
            $_success = $this->cache['UNDERDIM_POST'][$_position]['success'];
            $this->position = $this->cache['UNDERDIM_POST'][$_position]['position'];
            $this->value = $this->cache['UNDERDIM_POST'][$_position]['value'];

            return $_success;
        }

        $_value83 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value83[] = $this->value;

            if (substr($this->string, $this->position, strlen('UNDERDIM')) === 'UNDERDIM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('UNDERDIM'));
                $this->position += strlen('UNDERDIM');
            } else {
                $_success = false;

                $this->report($this->position, '\'UNDERDIM\'');
            }
        }

        if ($_success) {
            $_value83[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value83[] = $this->value;

            $_position77 = $this->position;
            $_cut78 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position77;
                $this->value = null;
            }

            $this->cut = $_cut78;

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value83[] = $this->value;

            $_position79 = $this->position;
            $_cut80 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position79;
                $this->value = null;
            }

            $this->cut = $_cut80;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value83[] = $this->value;

            $_position81 = $this->position;
            $_cut82 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position81;
                $this->value = null;
            }

            $this->cut = $_cut82;

            if ($_success) {
                $parent = $this->value;
            }
        }

        if ($_success) {
            $_value83[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value83[] = $this->value;

            $this->value = $_value83;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$id, &$desc, &$parent) {
                $this->dimensions->defineDimension(id: (string)$id, parent: (string)$parent, description: (string)$desc);
            });
        }

        $this->cache['UNDERDIM_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UNDERDIM_POST');
        }

        return $_success;
    }

    protected function parseOBJEKT_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJEKT_POST'][$_position])) {
            $_success = $this->cache['OBJEKT_POST'][$_position]['success'];
            $this->position = $this->cache['OBJEKT_POST'][$_position]['position'];
            $this->value = $this->cache['OBJEKT_POST'][$_position]['value'];

            return $_success;
        }

        $_value90 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value90[] = $this->value;

            if (substr($this->string, $this->position, strlen('OBJEKT')) === 'OBJEKT') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OBJEKT'));
                $this->position += strlen('OBJEKT');
            } else {
                $_success = false;

                $this->report($this->position, '\'OBJEKT\'');
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_position84 = $this->position;
            $_cut85 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position84;
                $this->value = null;
            }

            $this->cut = $_cut85;

            if ($_success) {
                $parent = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_position86 = $this->position;
            $_cut87 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position86;
                $this->value = null;
            }

            $this->cut = $_cut87;

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_position88 = $this->position;
            $_cut89 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position88;
                $this->value = null;
            }

            $this->cut = $_cut89;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value90[] = $this->value;

            $this->value = $_value90;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parent, &$id, &$desc) {
                $this->dimensions->defineObject(id: (string)$id, parent: (string)$parent, description: (string)$desc);
            });
        }

        $this->cache['OBJEKT_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJEKT_POST');
        }

        return $_success;
    }

    protected function parseIB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['IB_POST'][$_position])) {
            $_success = $this->cache['IB_POST'][$_position]['success'];
            $this->position = $this->cache['IB_POST'][$_position]['position'];
            $this->value = $this->cache['IB_POST'][$_position]['value'];

            return $_success;
        }

        $_position91 = $this->position;
        $_cut92 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIB_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position91;

            $_success = $this->parseIB_PREVIOUS_YEAR();
        }

        $this->cut = $_cut92;

        $this->cache['IB_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IB_POST');
        }

        return $_success;
    }

    protected function parseIB_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['IB_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['IB_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['IB_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['IB_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value93 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value93[] = $this->value;

            if (substr($this->string, $this->position, strlen('IB')) === 'IB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('IB'));
                $this->position += strlen('IB');
            } else {
                $_success = false;

                $this->report($this->position, '\'IB\'');
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value93[] = $this->value;

            $this->value = $_value93;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, incomingBalance: $this->moneyFactory->createMoney($balance));
            });
        }

        $this->cache['IB_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IB_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseIB_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['IB_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['IB_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['IB_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['IB_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value94 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value94[] = $this->value;

            if (substr($this->string, $this->position, strlen('IB')) === 'IB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('IB'));
                $this->position += strlen('IB');
            } else {
                $_success = false;

                $this->report($this->position, '\'IB\'');
            }
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value94[] = $this->value;

            $this->value = $_value94;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, attributes: [ParserAttributes::PREVIOUS_INCOMING_BALANCE_ATTRIBUTE => $balance]);
            });
        }

        $this->cache['IB_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IB_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseUB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['UB_POST'][$_position])) {
            $_success = $this->cache['UB_POST'][$_position]['success'];
            $this->position = $this->cache['UB_POST'][$_position]['position'];
            $this->value = $this->cache['UB_POST'][$_position]['value'];

            return $_success;
        }

        $_position95 = $this->position;
        $_cut96 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUB_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseUB_PREVIOUS_YEAR();
        }

        $this->cut = $_cut96;

        $this->cache['UB_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UB_POST');
        }

        return $_success;
    }

    protected function parseUB_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['UB_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['UB_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['UB_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['UB_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value97 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value97[] = $this->value;

            if (substr($this->string, $this->position, strlen('UB')) === 'UB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('UB'));
                $this->position += strlen('UB');
            } else {
                $_success = false;

                $this->report($this->position, '\'UB\'');
            }
        }

        if ($_success) {
            $_value97[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value97[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value97[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value97[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value97[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value97[] = $this->value;

            $this->value = $_value97;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, attributes: [ParserAttributes::OUTGOING_BALANCE_ATTRIBUTE => $balance]);
            });
        }

        $this->cache['UB_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UB_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseUB_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['UB_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['UB_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['UB_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['UB_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value98 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value98[] = $this->value;

            if (substr($this->string, $this->position, strlen('UB')) === 'UB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('UB'));
                $this->position += strlen('UB');
            } else {
                $_success = false;

                $this->report($this->position, '\'UB\'');
            }
        }

        if ($_success) {
            $_value98[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value98[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value98[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value98[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value98[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value98[] = $this->value;

            $this->value = $_value98;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, attributes: [ParserAttributes::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE => $balance]);
            });
        }

        $this->cache['UB_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UB_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseOIB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OIB_POST'][$_position])) {
            $_success = $this->cache['OIB_POST'][$_position]['success'];
            $this->position = $this->cache['OIB_POST'][$_position]['position'];
            $this->value = $this->cache['OIB_POST'][$_position]['value'];

            return $_success;
        }

        $_position99 = $this->position;
        $_cut100 = $this->cut;

        $this->cut = false;
        $_success = $this->parseOIB_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position99;

            $_success = $this->parseOIB_PREVIOUS_YEAR();
        }

        $this->cut = $_cut100;

        $this->cache['OIB_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OIB_POST');
        }

        return $_success;
    }

    protected function parseOIB_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['OIB_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['OIB_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['OIB_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['OIB_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value101 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value101[] = $this->value;

            if (substr($this->string, $this->position, strlen('OIB')) === 'OIB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OIB'));
                $this->position += strlen('OIB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OIB\'');
            }
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = $this->parseSTRING();
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value101[] = $this->value;

            $this->value = $_value101;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dims, &$balance) {
                foreach ($dims as list($parent, $id)) {
                    $this->dimensions->defineObject(id: $id, parent: $parent, incomingBalance: $this->moneyFactory->createMoney($balance));
                }
            });
        }

        $this->cache['OIB_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OIB_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseOIB_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['OIB_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['OIB_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['OIB_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['OIB_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value102 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value102[] = $this->value;

            if (substr($this->string, $this->position, strlen('OIB')) === 'OIB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OIB'));
                $this->position += strlen('OIB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OIB\'');
            }
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = $this->parseSTRING();
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value102[] = $this->value;

            $this->value = $_value102;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dims, &$balance) {
                foreach ($dims as list($parent, $id)) {
                    $this->dimensions->defineObject(id: $id, parent: $parent, attributes: [ParserAttributes::PREVIOUS_INCOMING_BALANCE_ATTRIBUTE => $balance]);
                }
            });
        }

        $this->cache['OIB_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OIB_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseOUB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OUB_POST'][$_position])) {
            $_success = $this->cache['OUB_POST'][$_position]['success'];
            $this->position = $this->cache['OUB_POST'][$_position]['position'];
            $this->value = $this->cache['OUB_POST'][$_position]['value'];

            return $_success;
        }

        $_position103 = $this->position;
        $_cut104 = $this->cut;

        $this->cut = false;
        $_success = $this->parseOUB_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position103;

            $_success = $this->parseOUB_PREVIOUS_YEAR();
        }

        $this->cut = $_cut104;

        $this->cache['OUB_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OUB_POST');
        }

        return $_success;
    }

    protected function parseOUB_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['OUB_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['OUB_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['OUB_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['OUB_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value105 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value105[] = $this->value;

            if (substr($this->string, $this->position, strlen('OUB')) === 'OUB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OUB'));
                $this->position += strlen('OUB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OUB\'');
            }
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseSTRING();
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value105[] = $this->value;

            $this->value = $_value105;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dims, &$balance) {
                foreach ($dims as list($parent, $id)) {
                    $this->dimensions->defineObject(id: $id, parent: $parent, attributes: [ParserAttributes::OUTGOING_BALANCE_ATTRIBUTE => $balance]);
                }
            });
        }

        $this->cache['OUB_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OUB_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseOUB_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['OUB_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['OUB_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['OUB_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['OUB_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value106 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value106[] = $this->value;

            if (substr($this->string, $this->position, strlen('OUB')) === 'OUB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OUB'));
                $this->position += strlen('OUB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OUB\'');
            }
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = $this->parseSTRING();
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value106[] = $this->value;

            $this->value = $_value106;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dims, &$balance) {
                foreach ($dims as list($parent, $id)) {
                    $this->dimensions->defineObject(id: $id, parent: $parent, attributes: [ParserAttributes::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE => $balance]);
                }
            });
        }

        $this->cache['OUB_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OUB_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseRES_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['RES_POST'][$_position])) {
            $_success = $this->cache['RES_POST'][$_position]['success'];
            $this->position = $this->cache['RES_POST'][$_position]['position'];
            $this->value = $this->cache['RES_POST'][$_position]['value'];

            return $_success;
        }

        $_position107 = $this->position;
        $_cut108 = $this->cut;

        $this->cut = false;
        $_success = $this->parseRES_CURRENT_YEAR();

        if (!$_success && !$this->cut) {
            $this->position = $_position107;

            $_success = $this->parseRES_PREVIOUS_YEAR();
        }

        $this->cut = $_cut108;

        $this->cache['RES_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RES_POST');
        }

        return $_success;
    }

    protected function parseRES_CURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['RES_CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['RES_CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['RES_CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['RES_CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value109 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value109[] = $this->value;

            if (substr($this->string, $this->position, strlen('RES')) === 'RES') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RES'));
                $this->position += strlen('RES');
            } else {
                $_success = false;

                $this->report($this->position, '\'RES\'');
            }
        }

        if ($_success) {
            $_value109[] = $this->value;

            $_success = $this->parseCURRENT_YEAR();
        }

        if ($_success) {
            $_value109[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value109[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value109[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value109[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value109[] = $this->value;

            $this->value = $_value109;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, attributes: [ParserAttributes::OUTGOING_BALANCE_ATTRIBUTE => $balance]);
            });
        }

        $this->cache['RES_CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RES_CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parseRES_PREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['RES_PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['RES_PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['RES_PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['RES_PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value110 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value110[] = $this->value;

            if (substr($this->string, $this->position, strlen('RES')) === 'RES') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RES'));
                $this->position += strlen('RES');
            } else {
                $_success = false;

                $this->report($this->position, '\'RES\'');
            }
        }

        if ($_success) {
            $_value110[] = $this->value;

            $_success = $this->parsePREVIOUS_YEAR();
        }

        if ($_success) {
            $_value110[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value110[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value110[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value110[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value110[] = $this->value;

            $this->value = $_value110;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$balance) {
                $this->accounts->defineAccount(id: $number, attributes: [ParserAttributes::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE => $balance]);
            });
        }

        $this->cache['RES_PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RES_PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseVER_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VER_POST'][$_position])) {
            $_success = $this->cache['VER_POST'][$_position]['success'];
            $this->position = $this->cache['VER_POST'][$_position]['position'];
            $this->value = $this->cache['VER_POST'][$_position]['value'];

            return $_success;
        }

        $_value123 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value123[] = $this->value;

            if (substr($this->string, $this->position, strlen('VER')) === 'VER') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VER'));
                $this->position += strlen('VER');
            } else {
                $_success = false;

                $this->report($this->position, '\'VER\'');
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position111 = $this->position;
            $_cut112 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position111;
                $this->value = null;
            }

            $this->cut = $_cut112;

            if ($_success) {
                $series = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position113 = $this->position;
            $_cut114 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position113;
                $this->value = null;
            }

            $this->cut = $_cut114;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position115 = $this->position;
            $_cut116 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position115;
                $this->value = null;
            }

            $this->cut = $_cut116;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position117 = $this->position;
            $_cut118 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position117;
                $this->value = null;
            }

            $this->cut = $_cut118;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position119 = $this->position;
            $_cut120 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position119;
                $this->value = null;
            }

            $this->cut = $_cut120;

            if ($_success) {
                $regdate = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_position121 = $this->position;
            $_cut122 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position121;
                $this->value = null;
            }

            $this->cut = $_cut122;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_success = $this->parseTRANS_LIST();

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

            $this->value = $_value123;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$series, &$number, &$date, &$desc, &$regdate, &$sign, &$trans) {
                return new Verification(
                    id: (string)$number,
                    transactionDate: AccountingDate::fromString((string)$date),
                    registrationDate: $regdate ? AccountingDate::fromString($regdate) : null,
                    description: (string)$desc,
                    signature: (string)$sign,
                    transactions: array_map(
                        fn($tran) => new Transaction(
                            added: $tran['added'] ?? false,
                            deleted: $tran['deleted'] ?? false,
                            verificationId: (string)$number,
                            transactionDate: AccountingDate::fromString($tran['date'] ?: (string)$date),
                            description: $tran['desc'] ?: (string)$desc,
                            signature: $tran['sign'] ?: (string)$sign,
                            amount: $this->moneyFactory->createMoney($tran['amount']),
                            account: $this->accounts->getAccount($tran['account']),
                            dimensions: array_map(
                                fn($def) => $this->dimensions->getObject(id: $def[1], parent: $def[0]),
                                $tran['dims']
                            ),
                        ),
                        $trans
                    ),
                    attributes: [ParserAttributes::VERIFICATION_SERIES_ATTRIBUTE => (string)$series],
                );
            });
        }

        $this->cache['VER_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VER_POST');
        }

        return $_success;
    }

    protected function parseTRANS_LIST()
    {
        $_position = $this->position;

        if (isset($this->cache['TRANS_LIST'][$_position])) {
            $_success = $this->cache['TRANS_LIST'][$_position]['success'];
            $this->position = $this->cache['TRANS_LIST'][$_position]['position'];
            $this->value = $this->cache['TRANS_LIST'][$_position]['value'];

            return $_success;
        }

        $_value131 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value131[] = $this->value;

            if (substr($this->string, $this->position, strlen('{')) === '{') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('{'));
                $this->position += strlen('{');
            } else {
                $_success = false;

                $this->report($this->position, '\'{\'');
            }
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_value127 = array();
            $_cut128 = $this->cut;

            while (true) {
                $_position126 = $this->position;

                $this->cut = false;
                $_position124 = $this->position;
                $_cut125 = $this->cut;

                $this->cut = false;
                $_success = $this->parseTRANS_POST();

                if (!$_success && !$this->cut) {
                    $this->position = $_position124;

                    $_success = $this->parseADDED_TRANS_POST();
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position124;

                    $_success = $this->parseIGNORED_CONTENT();
                }

                $this->cut = $_cut125;

                if (!$_success) {
                    break;
                }

                $_value127[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position126;
                $this->value = $_value127;
            }

            $this->cut = $_cut128;

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value131[] = $this->value;

            if (substr($this->string, $this->position, strlen('}')) === '}') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('}'));
                $this->position += strlen('}');
            } else {
                $_success = false;

                $this->report($this->position, '\'}\'');
            }
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value131[] = $this->value;

            $_position129 = $this->position;
            $_cut130 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position129;
                $this->value = null;
            }

            $this->cut = $_cut130;
        }

        if ($_success) {
            $_value131[] = $this->value;

            $this->value = $_value131;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$trans) {
                return array_filter($trans);
            });
        }

        $this->cache['TRANS_LIST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'TRANS_LIST');
        }

        return $_success;
    }

    protected function parseTRANS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['TRANS_POST'][$_position])) {
            $_success = $this->cache['TRANS_POST'][$_position]['success'];
            $this->position = $this->cache['TRANS_POST'][$_position]['position'];
            $this->value = $this->cache['TRANS_POST'][$_position]['value'];

            return $_success;
        }

        $_value142 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value142[] = $this->value;

            $_position132 = $this->position;
            $_cut133 = $this->cut;

            $this->cut = false;
            if (substr($this->string, $this->position, strlen('TRANS')) === 'TRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('TRANS'));
                $this->position += strlen('TRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'TRANS\'');
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position132;

                if (substr($this->string, $this->position, strlen('BTRANS')) === 'BTRANS') {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen('BTRANS'));
                    $this->position += strlen('BTRANS');
                } else {
                    $_success = false;

                    $this->report($this->position, '\'BTRANS\'');
                }
            }

            $this->cut = $_cut133;

            if ($_success) {
                $label = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_position134 = $this->position;
            $_cut135 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position134;
                $this->value = null;
            }

            $this->cut = $_cut135;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_position136 = $this->position;
            $_cut137 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position136;
                $this->value = null;
            }

            $this->cut = $_cut137;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_position138 = $this->position;
            $_cut139 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position138;
                $this->value = null;
            }

            $this->cut = $_cut139;
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_position140 = $this->position;
            $_cut141 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position140;
                $this->value = null;
            }

            $this->cut = $_cut141;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value142[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value142[] = $this->value;

            $this->value = $_value142;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$label, &$account, &$dims, &$amount, &$date, &$desc, &$sign) {
                return [
                    'deleted' => 'BTRANS' == $label,
                    'account' => $account,
                    'amount' => $amount,
                    'date' => $date,
                    'desc' => (string)$desc,
                    'sign' => (string)$sign,
                    'dims' => $dims,
                ];
            });
        }

        $this->cache['TRANS_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'TRANS_POST');
        }

        return $_success;
    }

    protected function parseADDED_TRANS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['ADDED_TRANS_POST'][$_position])) {
            $_success = $this->cache['ADDED_TRANS_POST'][$_position]['success'];
            $this->position = $this->cache['ADDED_TRANS_POST'][$_position]['position'];
            $this->value = $this->cache['ADDED_TRANS_POST'][$_position]['value'];

            return $_success;
        }

        $_value151 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value151[] = $this->value;

            if (substr($this->string, $this->position, strlen('RTRANS')) === 'RTRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RTRANS'));
                $this->position += strlen('RTRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'RTRANS\'');
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_position143 = $this->position;
            $_cut144 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position143;
                $this->value = null;
            }

            $this->cut = $_cut144;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_position145 = $this->position;
            $_cut146 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position145;
                $this->value = null;
            }

            $this->cut = $_cut146;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_position147 = $this->position;
            $_cut148 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position147;
                $this->value = null;
            }

            $this->cut = $_cut148;
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_position149 = $this->position;
            $_cut150 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position149;
                $this->value = null;
            }

            $this->cut = $_cut150;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value151[] = $this->value;

            $_success = $this->parseTRANS_POST();
        }

        if ($_success) {
            $_value151[] = $this->value;

            $this->value = $_value151;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$dims, &$amount, &$date, &$desc, &$sign) {
                return [
                    'added' => true,
                    'account' => $account,
                    'amount' => $amount,
                    'date' => $date,
                    'desc' => (string)$desc,
                    'sign' => (string)$sign,
                    'dims' => $dims,
                ];
            });
        }

        $this->cache['ADDED_TRANS_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ADDED_TRANS_POST');
        }

        return $_success;
    }

    protected function parseIGNORED_CONTENT()
    {
        $_position = $this->position;

        if (isset($this->cache['IGNORED_CONTENT'][$_position])) {
            $_success = $this->cache['IGNORED_CONTENT'][$_position]['success'];
            $this->position = $this->cache['IGNORED_CONTENT'][$_position]['position'];
            $this->value = $this->cache['IGNORED_CONTENT'][$_position]['value'];

            return $_success;
        }

        $_position152 = $this->position;
        $_cut153 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUNKNOWN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position152;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut153;

        $this->cache['IGNORED_CONTENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IGNORED_CONTENT');
        }

        return $_success;
    }

    protected function parseUNKNOWN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['UNKNOWN_POST'][$_position])) {
            $_success = $this->cache['UNKNOWN_POST'][$_position]['success'];
            $this->position = $this->cache['UNKNOWN_POST'][$_position]['position'];
            $this->value = $this->cache['UNKNOWN_POST'][$_position]['value'];

            return $_success;
        }

        $_value157 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value157[] = $this->value;

            $_value155 = array();
            $_cut156 = $this->cut;

            while (true) {
                $_position154 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value155[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position154;
                $this->value = $_value155;
            }

            $this->cut = $_cut156;
        }

        if ($_success) {
            $_value157[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value157[] = $this->value;

            $this->value = $_value157;
        }

        $this->cache['UNKNOWN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UNKNOWN_POST');
        }

        return $_success;
    }

    protected function parseROW_START()
    {
        $_position = $this->position;

        if (isset($this->cache['ROW_START'][$_position])) {
            $_success = $this->cache['ROW_START'][$_position]['success'];
            $this->position = $this->cache['ROW_START'][$_position]['position'];
            $this->value = $this->cache['ROW_START'][$_position]['value'];

            return $_success;
        }

        $_value161 = array();

        $_value159 = array();
        $_cut160 = $this->cut;

        while (true) {
            $_position158 = $this->position;

            $this->cut = false;
            $_success = $this->parseEMPTY_LINE();

            if (!$_success) {
                break;
            }

            $_value159[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position158;
            $this->value = $_value159;
        }

        $this->cut = $_cut160;

        if ($_success) {
            $_value161[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value161[] = $this->value;

            if (substr($this->string, $this->position, strlen('#')) === '#') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('#'));
                $this->position += strlen('#');
            } else {
                $_success = false;

                $this->report($this->position, '\'#\'');
            }
        }

        if ($_success) {
            $_value161[] = $this->value;

            $this->value = $_value161;
        }

        $this->cache['ROW_START'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ROW_START');
        }

        return $_success;
    }

    protected function parseROW_END()
    {
        $_position = $this->position;

        if (isset($this->cache['ROW_END'][$_position])) {
            $_success = $this->cache['ROW_END'][$_position]['success'];
            $this->position = $this->cache['ROW_END'][$_position]['position'];
            $this->value = $this->cache['ROW_END'][$_position]['value'];

            return $_success;
        }

        $_value167 = array();

        $_value163 = array();
        $_cut164 = $this->cut;

        while (true) {
            $_position162 = $this->position;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success) {
                break;
            }

            $_value163[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position162;
            $this->value = $_value163;
        }

        $this->cut = $_cut164;

        if ($_success) {
            $fields = $this->value;
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position165 = $this->position;
            $_cut166 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position165;
                $this->value = null;
            }

            $this->cut = $_cut166;
        }

        if ($_success) {
            $_value167[] = $this->value;

            $this->value = $_value167;
        }

        $this->cache['ROW_END'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ROW_END');
        }

        return $_success;
    }

    protected function parseCURRENT_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['CURRENT_YEAR'][$_position])) {
            $_success = $this->cache['CURRENT_YEAR'][$_position]['success'];
            $this->position = $this->cache['CURRENT_YEAR'][$_position]['position'];
            $this->value = $this->cache['CURRENT_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value168 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value168[] = $this->value;

            if (substr($this->string, $this->position, strlen('0')) === '0') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('0'));
                $this->position += strlen('0');
            } else {
                $_success = false;

                $this->report($this->position, '\'0\'');
            }
        }

        if ($_success) {
            $_value168[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value168[] = $this->value;

            $this->value = $_value168;
        }

        $this->cache['CURRENT_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CURRENT_YEAR');
        }

        return $_success;
    }

    protected function parsePREVIOUS_YEAR()
    {
        $_position = $this->position;

        if (isset($this->cache['PREVIOUS_YEAR'][$_position])) {
            $_success = $this->cache['PREVIOUS_YEAR'][$_position]['success'];
            $this->position = $this->cache['PREVIOUS_YEAR'][$_position]['position'];
            $this->value = $this->cache['PREVIOUS_YEAR'][$_position]['value'];

            return $_success;
        }

        $_value169 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value169[] = $this->value;

            if (substr($this->string, $this->position, strlen('-1')) === '-1') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('-1'));
                $this->position += strlen('-1');
            } else {
                $_success = false;

                $this->report($this->position, '\'-1\'');
            }
        }

        if ($_success) {
            $_value169[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value169[] = $this->value;

            $this->value = $_value169;
        }

        $this->cache['PREVIOUS_YEAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PREVIOUS_YEAR');
        }

        return $_success;
    }

    protected function parseOBJECT_LIST()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_LIST'][$_position])) {
            $_success = $this->cache['OBJECT_LIST'][$_position]['success'];
            $this->position = $this->cache['OBJECT_LIST'][$_position]['position'];
            $this->value = $this->cache['OBJECT_LIST'][$_position]['value'];

            return $_success;
        }

        $_value173 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value173[] = $this->value;

            if (substr($this->string, $this->position, strlen('{')) === '{') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('{'));
                $this->position += strlen('{');
            } else {
                $_success = false;

                $this->report($this->position, '\'{\'');
            }
        }

        if ($_success) {
            $_value173[] = $this->value;

            $_value171 = array();
            $_cut172 = $this->cut;

            while (true) {
                $_position170 = $this->position;

                $this->cut = false;
                $_success = $this->parseOBJECT_DEFINITION();

                if (!$_success) {
                    break;
                }

                $_value171[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position170;
                $this->value = $_value171;
            }

            $this->cut = $_cut172;

            if ($_success) {
                $definitions = $this->value;
            }
        }

        if ($_success) {
            $_value173[] = $this->value;

            if (substr($this->string, $this->position, strlen('}')) === '}') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('}'));
                $this->position += strlen('}');
            } else {
                $_success = false;

                $this->report($this->position, '\'}\'');
            }
        }

        if ($_success) {
            $_value173[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value173[] = $this->value;

            $this->value = $_value173;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$definitions) {
                return $definitions;
            });
        }

        $this->cache['OBJECT_LIST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT_LIST');
        }

        return $_success;
    }

    protected function parseOBJECT_DEFINITION()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_DEFINITION'][$_position])) {
            $_success = $this->cache['OBJECT_DEFINITION'][$_position]['success'];
            $this->position = $this->cache['OBJECT_DEFINITION'][$_position]['position'];
            $this->value = $this->cache['OBJECT_DEFINITION'][$_position]['value'];

            return $_success;
        }

        $_value174 = array();

        $_success = $this->parseOBJECT_LIST_SAFE_STRING();

        if ($_success) {
            $parent = $this->value;
        }

        if ($_success) {
            $_value174[] = $this->value;

            $_success = $this->parseOBJECT_LIST_SAFE_STRING();

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value174[] = $this->value;

            $this->value = $_value174;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parent, &$id) {
                return [$parent, $id];
            });
        }

        $this->cache['OBJECT_DEFINITION'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT_DEFINITION');
        }

        return $_success;
    }

    protected function parseOBJECT_LIST_SAFE_STRING()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_LIST_SAFE_STRING'][$_position])) {
            $_success = $this->cache['OBJECT_LIST_SAFE_STRING'][$_position]['success'];
            $this->position = $this->cache['OBJECT_LIST_SAFE_STRING'][$_position]['position'];
            $this->value = $this->cache['OBJECT_LIST_SAFE_STRING'][$_position]['value'];

            return $_success;
        }

        $_value181 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value181[] = $this->value;

            $_position179 = $this->position;
            $_cut180 = $this->cut;

            $this->cut = false;
            $_position178 = $this->position;

            $_success = $this->parseOBJECT_ID_SAFE_CHAR();

            if ($_success) {
                $_value176 = array($this->value);
                $_cut177 = $this->cut;

                while (true) {
                    $_position175 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseOBJECT_ID_SAFE_CHAR();

                    if (!$_success) {
                        break;
                    }

                    $_value176[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position175;
                    $this->value = $_value176;
                }

                $this->cut = $_cut177;
            }

            if ($_success) {
                $this->value = strval(substr($this->string, $_position178, $this->position - $_position178));
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position179;

                $_success = $this->parseQUOTED_STRING();
            }

            $this->cut = $_cut180;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value181[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value181[] = $this->value;

            $this->value = $_value181;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$string) {
                return $string;
            });
        }

        $this->cache['OBJECT_LIST_SAFE_STRING'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT_LIST_SAFE_STRING');
        }

        return $_success;
    }

    protected function parseOBJECT_ID_SAFE_CHAR()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_ID_SAFE_CHAR'][$_position])) {
            $_success = $this->cache['OBJECT_ID_SAFE_CHAR'][$_position]['success'];
            $this->position = $this->cache['OBJECT_ID_SAFE_CHAR'][$_position]['position'];
            $this->value = $this->cache['OBJECT_ID_SAFE_CHAR'][$_position]['value'];

            return $_success;
        }

        $_value184 = array();

        $_position182 = $this->position;
        $_cut183 = $this->cut;

        $this->cut = false;
        if (preg_match('/^[{}]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if (!$_success) {
            $_success = true;
            $this->value = null;
        } else {
            $_success = false;
        }

        $this->position = $_position182;
        $this->cut = $_cut183;

        if ($_success) {
            $_value184[] = $this->value;

            $_success = $this->parseCHAR();
        }

        if ($_success) {
            $_value184[] = $this->value;

            $this->value = $_value184;
        }

        $this->cache['OBJECT_ID_SAFE_CHAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT_ID_SAFE_CHAR');
        }

        return $_success;
    }

    protected function parseSTRING()
    {
        $_position = $this->position;

        if (isset($this->cache['STRING'][$_position])) {
            $_success = $this->cache['STRING'][$_position]['success'];
            $this->position = $this->cache['STRING'][$_position]['position'];
            $this->value = $this->cache['STRING'][$_position]['value'];

            return $_success;
        }

        $_value191 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value191[] = $this->value;

            $_position189 = $this->position;
            $_cut190 = $this->cut;

            $this->cut = false;
            $_position188 = $this->position;

            $_success = $this->parseCHAR();

            if ($_success) {
                $_value186 = array($this->value);
                $_cut187 = $this->cut;

                while (true) {
                    $_position185 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseCHAR();

                    if (!$_success) {
                        break;
                    }

                    $_value186[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position185;
                    $this->value = $_value186;
                }

                $this->cut = $_cut187;
            }

            if ($_success) {
                $this->value = strval(substr($this->string, $_position188, $this->position - $_position188));
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position189;

                $_success = $this->parseQUOTED_STRING();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position189;

                $_success = $this->parseEMPTY_STRING();
            }

            $this->cut = $_cut190;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value191[] = $this->value;

            $this->value = $_value191;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$string) {
                return $string;
            });
        }

        $this->cache['STRING'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'STRING');
        }

        return $_success;
    }

    protected function parseEMPTY_STRING()
    {
        $_position = $this->position;

        if (isset($this->cache['EMPTY_STRING'][$_position])) {
            $_success = $this->cache['EMPTY_STRING'][$_position]['success'];
            $this->position = $this->cache['EMPTY_STRING'][$_position]['position'];
            $this->value = $this->cache['EMPTY_STRING'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen('""')) === '""') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('""'));
            $this->position += strlen('""');
        } else {
            $_success = false;

            $this->report($this->position, '\'""\'');
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                return '';
            });
        }

        $this->cache['EMPTY_STRING'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EMPTY_STRING');
        }

        return $_success;
    }

    protected function parseQUOTED_STRING()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_STRING'][$_position])) {
            $_success = $this->cache['QUOTED_STRING'][$_position]['success'];
            $this->position = $this->cache['QUOTED_STRING'][$_position]['position'];
            $this->value = $this->cache['QUOTED_STRING'][$_position]['value'];

            return $_success;
        }

        $_value197 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value197[] = $this->value;

            $_value195 = array();
            $_cut196 = $this->cut;

            while (true) {
                $_position194 = $this->position;

                $this->cut = false;
                $_position192 = $this->position;
                $_cut193 = $this->cut;

                $this->cut = false;
                $_success = $this->parseESCAPED_QUOTE();

                if (!$_success && !$this->cut) {
                    $this->position = $_position192;

                    if (substr($this->string, $this->position, strlen(' ')) === ' ') {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen(' '));
                        $this->position += strlen(' ');
                    } else {
                        $_success = false;

                        $this->report($this->position, '\' \'');
                    }
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position192;

                    $_success = $this->parseCHAR();
                }

                $this->cut = $_cut193;

                if (!$_success) {
                    break;
                }

                $_value195[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position194;
                $this->value = $_value195;
            }

            $this->cut = $_cut196;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value197[] = $this->value;

            if (substr($this->string, $this->position, strlen('"')) === '"') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('"'));
                $this->position += strlen('"');
            } else {
                $_success = false;

                $this->report($this->position, '\'"\'');
            }
        }

        if ($_success) {
            $_value197[] = $this->value;

            $this->value = $_value197;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$string) {
                return implode($string);
            });
        }

        $this->cache['QUOTED_STRING'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_STRING');
        }

        return $_success;
    }

    protected function parseESCAPED_QUOTE()
    {
        $_position = $this->position;

        if (isset($this->cache['ESCAPED_QUOTE'][$_position])) {
            $_success = $this->cache['ESCAPED_QUOTE'][$_position]['success'];
            $this->position = $this->cache['ESCAPED_QUOTE'][$_position]['position'];
            $this->value = $this->cache['ESCAPED_QUOTE'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen('\"')) === '\"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('\"'));
            $this->position += strlen('\"');
        } else {
            $_success = false;

            $this->report($this->position, '\'\\"\'');
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                return '"';
            });
        }

        $this->cache['ESCAPED_QUOTE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ESCAPED_QUOTE');
        }

        return $_success;
    }

    protected function parseCHAR()
    {
        $_position = $this->position;

        if (isset($this->cache['CHAR'][$_position])) {
            $_success = $this->cache['CHAR'][$_position]['success'];
            $this->position = $this->cache['CHAR'][$_position]['position'];
            $this->value = $this->cache['CHAR'][$_position]['value'];

            return $_success;
        }

        if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`{|}~⌂ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        $this->cache['CHAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CHAR');
        }

        return $_success;
    }

    protected function parseEMPTY_LINE()
    {
        $_position = $this->position;

        if (isset($this->cache['EMPTY_LINE'][$_position])) {
            $_success = $this->cache['EMPTY_LINE'][$_position]['success'];
            $this->position = $this->cache['EMPTY_LINE'][$_position]['position'];
            $this->value = $this->cache['EMPTY_LINE'][$_position]['value'];

            return $_success;
        }

        $_value198 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value198[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value198[] = $this->value;

            $this->value = $_value198;
        }

        $this->cache['EMPTY_LINE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EMPTY_LINE');
        }

        return $_success;
    }

    protected function parseEOL()
    {
        $_position = $this->position;

        if (isset($this->cache['EOL'][$_position])) {
            $_success = $this->cache['EOL'][$_position]['success'];
            $this->position = $this->cache['EOL'][$_position]['position'];
            $this->value = $this->cache['EOL'][$_position]['value'];

            return $_success;
        }

        $_value201 = array();

        $_position199 = $this->position;
        $_cut200 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("\r")) === "\r") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\r"));
            $this->position += strlen("\r");
        } else {
            $_success = false;

            $this->report($this->position, '"\\r"');
        }

        if (!$_success && !$this->cut) {
            $_success = true;
            $this->position = $_position199;
            $this->value = null;
        }

        $this->cut = $_cut200;

        if ($_success) {
            $_value201[] = $this->value;

            if (substr($this->string, $this->position, strlen("\n")) === "\n") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\n"));
                $this->position += strlen("\n");
            } else {
                $_success = false;

                $this->report($this->position, '"\\n"');
            }
        }

        if ($_success) {
            $_value201[] = $this->value;

            $this->value = $_value201;
        }

        $this->cache['EOL'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EOL');
        }

        return $_success;
    }

    protected function parse_()
    {
        $_position = $this->position;

        if (isset($this->cache['_'][$_position])) {
            $_success = $this->cache['_'][$_position]['success'];
            $this->position = $this->cache['_'][$_position]['position'];
            $this->value = $this->cache['_'][$_position]['value'];

            return $_success;
        }

        $_value205 = array();
        $_cut206 = $this->cut;

        while (true) {
            $_position204 = $this->position;

            $this->cut = false;
            $_position202 = $this->position;
            $_cut203 = $this->cut;

            $this->cut = false;
            if (substr($this->string, $this->position, strlen(" ")) === " ") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(" "));
                $this->position += strlen(" ");
            } else {
                $_success = false;

                $this->report($this->position, '" "');
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position202;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut203;

            if (!$_success) {
                break;
            }

            $_value205[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position204;
            $this->value = $_value205;
        }

        $this->cut = $_cut206;

        $this->cache['_'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, '_');
        }

        return $_success;
    }

    private function line()
    {
        if (!empty($this->errors)) {
            $positions = array_keys($this->errors);
        } else {
            $positions = array_keys($this->warnings);
        }

        return count(explode("\n", substr($this->string, 0, max($positions))));
    }

    private function rest()
    {
        return '"' . substr($this->string, $this->position) . '"';
    }

    protected function report($position, $expecting)
    {
        if ($this->cut) {
            $this->errors[$position][] = $expecting;
        } else {
            $this->warnings[$position][] = $expecting;
        }
    }

    private function expecting()
    {
        if (!empty($this->errors)) {
            ksort($this->errors);

            return end($this->errors)[0];
        }

        ksort($this->warnings);

        return implode(', ', end($this->warnings));
    }

    public function parse($_string)
    {
        $this->string = $_string;
        $this->position = 0;
        $this->value = null;
        $this->cache = array();
        $this->cut = false;
        $this->errors = array();
        $this->warnings = array();

        $_success = $this->parseSIE_CONTENT();

        if ($_success && $this->position < strlen($this->string)) {
            $_success = false;

            $this->report($this->position, "end of file");
        }

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        return $this->value;
    }
}