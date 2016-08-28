<?php

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Transaction;
use byrokrat\amount\Amount;

class Grammar extends AbstractParser
{
    protected function parseFILE()
    {
        $_position = $this->position;

        if (isset($this->cache['FILE'][$_position])) {
            $_success = $this->cache['FILE'][$_position]['success'];
            $this->position = $this->cache['FILE'][$_position]['position'];
            $this->value = $this->cache['FILE'][$_position]['value'];

            return $_success;
        }

        $_value6 = array();

        $_success = $this->parseFLAGGA_POST();

        if ($_success) {
            $_value6[] = $this->value;

            $_position1 = $this->position;
            $_cut2 = $this->cut;

            $this->cut = false;
            $_success = $this->parseCHECKSUMED_SIE_CONTENT();

            if (!$_success && !$this->cut) {
                $this->position = $_position1;

                $_success = $this->parseSIE_CONTENT();
            }

            $this->cut = $_cut2;
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_value4 = array();
            $_cut5 = $this->cut;

            while (true) {
                $_position3 = $this->position;

                $this->cut = false;
                $_success = $this->parseEMPTY_LINE();

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
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

            $this->value = $_value6;
        }

        $this->cache['FILE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FILE');
        }

        return $_success;
    }

    protected function parseCHECKSUMED_SIE_CONTENT()
    {
        $_position = $this->position;

        if (isset($this->cache['CHECKSUMED_SIE_CONTENT'][$_position])) {
            $_success = $this->cache['CHECKSUMED_SIE_CONTENT'][$_position]['success'];
            $this->position = $this->cache['CHECKSUMED_SIE_CONTENT'][$_position]['position'];
            $this->value = $this->cache['CHECKSUMED_SIE_CONTENT'][$_position]['value'];

            return $_success;
        }

        $_value7 = array();

        $_success = $this->parseKSUMMA_START_POST();

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parseSIE_CONTENT();
        }

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parseKSUMMA_END_POST();
        }

        if ($_success) {
            $_value7[] = $this->value;

            $this->value = $_value7;
        }

        $this->cache['CHECKSUMED_SIE_CONTENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CHECKSUMED_SIE_CONTENT');
        }

        return $_success;
    }

    protected function parseSIE_CONTENT()
    {
        $_position = $this->position;

        if (isset($this->cache['SIE_CONTENT'][$_position])) {
            $_success = $this->cache['SIE_CONTENT'][$_position]['success'];
            $this->position = $this->cache['SIE_CONTENT'][$_position]['position'];
            $this->value = $this->cache['SIE_CONTENT'][$_position]['value'];

            return $_success;
        }

        $_value21 = array();

        $_value9 = array();
        $_cut10 = $this->cut;

        while (true) {
            $_position8 = $this->position;

            $this->cut = false;
            $_success = $this->parseIDENTIFICATION_POST();

            if (!$_success) {
                break;
            }

            $_value9[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position8;
            $this->value = $_value9;
        }

        $this->cut = $_cut10;

        if ($_success) {
            $_value21[] = $this->value;

            $_value14 = array();
            $_cut15 = $this->cut;

            while (true) {
                $_position13 = $this->position;

                $this->cut = false;
                $_position11 = $this->position;
                $_cut12 = $this->cut;

                $this->cut = false;
                $_success = $this->parseACCOUNT_PLAN_POST();

                if (!$_success && !$this->cut) {
                    $this->position = $_position11;

                    $_success = $this->parseMISPLACED_IDENTIFICATION_POST();
                }

                $this->cut = $_cut12;

                if (!$_success) {
                    break;
                }

                $_value14[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position13;
                $this->value = $_value14;
            }

            $this->cut = $_cut15;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_value19 = array();
            $_cut20 = $this->cut;

            while (true) {
                $_position18 = $this->position;

                $this->cut = false;
                $_position16 = $this->position;
                $_cut17 = $this->cut;

                $this->cut = false;
                $_success = $this->parseBALANCE_POST();

                if (!$_success && !$this->cut) {
                    $this->position = $_position16;

                    $_success = $this->parseMISPLACED_IDENTIFICATION_POST();
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position16;

                    $_success = $this->parseMISPLACED_ACCOUNT_PLAN_POST();
                }

                $this->cut = $_cut17;

                if (!$_success) {
                    break;
                }

                $_value19[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position18;
                $this->value = $_value19;
            }

            $this->cut = $_cut20;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $this->value = $_value21;
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

    protected function parseFLAGGA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FLAGGA_POST'][$_position])) {
            $_success = $this->cache['FLAGGA_POST'][$_position]['success'];
            $this->position = $this->cache['FLAGGA_POST'][$_position]['position'];
            $this->value = $this->cache['FLAGGA_POST'][$_position]['value'];

            return $_success;
        }

        $_value24 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value24[] = $this->value;

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
            $_value24[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_position22 = $this->position;
            $_cut23 = $this->cut;

            $this->cut = false;
            $_success = $this->parseBOOLEAN();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position22;
                $this->value = null;
            }

            $this->cut = $_cut23;

            if ($_success) {
                $flag = $this->value;
            }
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value24[] = $this->value;

            $this->value = $_value24;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$flag) {
                if ($this->assertBool($flag)) {
                    $this->getContainer()->setAttribute('FLAGGA', $flag);
                }
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

    protected function parseKSUMMA_START_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['KSUMMA_START_POST'][$_position])) {
            $_success = $this->cache['KSUMMA_START_POST'][$_position]['success'];
            $this->position = $this->cache['KSUMMA_START_POST'][$_position]['position'];
            $this->value = $this->cache['KSUMMA_START_POST'][$_position]['value'];

            return $_success;
        }

        $_value25 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value25[] = $this->value;

            if (substr($this->string, $this->position, strlen('KSUMMA')) === 'KSUMMA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KSUMMA'));
                $this->position += strlen('KSUMMA');
            } else {
                $_success = false;

                $this->report($this->position, '\'KSUMMA\'');
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

        $this->cache['KSUMMA_START_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'KSUMMA_START_POST');
        }

        return $_success;
    }

    protected function parseKSUMMA_END_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['KSUMMA_END_POST'][$_position])) {
            $_success = $this->cache['KSUMMA_END_POST'][$_position]['success'];
            $this->position = $this->cache['KSUMMA_END_POST'][$_position]['position'];
            $this->value = $this->cache['KSUMMA_END_POST'][$_position]['value'];

            return $_success;
        }

        $_value28 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value28[] = $this->value;

            if (substr($this->string, $this->position, strlen('KSUMMA')) === 'KSUMMA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KSUMMA'));
                $this->position += strlen('KSUMMA');
            } else {
                $_success = false;

                $this->report($this->position, '\'KSUMMA\'');
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_position26 = $this->position;
            $_cut27 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position26;
                $this->value = null;
            }

            $this->cut = $_cut27;

            if ($_success) {
                $checksum = $this->value;
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
            $this->value = call_user_func(function () use (&$checksum) {
                if ($this->assertInt($checksum, 'Expected checksum')) {
                    $this->getContainer()->setAttribute('KSUMMA', $checksum);
                    $this->getLogger()->notice('Checksum detected but currently not handled');
                }
            });
        }

        $this->cache['KSUMMA_END_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'KSUMMA_END_POST');
        }

        return $_success;
    }

    protected function parseMISPLACED_IDENTIFICATION_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['MISPLACED_IDENTIFICATION_POST'][$_position])) {
            $_success = $this->cache['MISPLACED_IDENTIFICATION_POST'][$_position]['success'];
            $this->position = $this->cache['MISPLACED_IDENTIFICATION_POST'][$_position]['position'];
            $this->value = $this->cache['MISPLACED_IDENTIFICATION_POST'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseIDENTIFICATION_POST();

        if ($_success) {
            $this->value = call_user_func(function () {
                $this->getLogger()->warning('Misplaced identification post');
            });
        }

        $this->cache['MISPLACED_IDENTIFICATION_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'MISPLACED_IDENTIFICATION_POST');
        }

        return $_success;
    }

    protected function parseMISPLACED_ACCOUNT_PLAN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['MISPLACED_ACCOUNT_PLAN_POST'][$_position])) {
            $_success = $this->cache['MISPLACED_ACCOUNT_PLAN_POST'][$_position]['success'];
            $this->position = $this->cache['MISPLACED_ACCOUNT_PLAN_POST'][$_position]['position'];
            $this->value = $this->cache['MISPLACED_ACCOUNT_PLAN_POST'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseACCOUNT_PLAN_POST();

        if ($_success) {
            $this->value = call_user_func(function () {
                $this->getLogger()->warning('Misplaced account plan post');
            });
        }

        $this->cache['MISPLACED_ACCOUNT_PLAN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'MISPLACED_ACCOUNT_PLAN_POST');
        }

        return $_success;
    }

    protected function parseIDENTIFICATION_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['IDENTIFICATION_POST'][$_position])) {
            $_success = $this->cache['IDENTIFICATION_POST'][$_position]['success'];
            $this->position = $this->cache['IDENTIFICATION_POST'][$_position]['position'];
            $this->value = $this->cache['IDENTIFICATION_POST'][$_position]['value'];

            return $_success;
        }

        $_position29 = $this->position;
        $_cut30 = $this->cut;

        $this->cut = false;
        $_success = $this->parseADRESS_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseBKOD_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseFNAMN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseFNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseFORMAT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseFTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseGEN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseKPTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseOMFATTN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseORGNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parsePROGRAM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parsePROSA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseRAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseSIETYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseTAXAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseVALUTA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position29;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut30;

        $this->cache['IDENTIFICATION_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IDENTIFICATION_POST');
        }

        return $_success;
    }

    protected function parseADRESS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['ADRESS_POST'][$_position])) {
            $_success = $this->cache['ADRESS_POST'][$_position]['success'];
            $this->position = $this->cache['ADRESS_POST'][$_position]['position'];
            $this->value = $this->cache['ADRESS_POST'][$_position]['value'];

            return $_success;
        }

        $_value39 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value39[] = $this->value;

            if (substr($this->string, $this->position, strlen('ADRESS')) === 'ADRESS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('ADRESS'));
                $this->position += strlen('ADRESS');
            } else {
                $_success = false;

                $this->report($this->position, '\'ADRESS\'');
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_position31 = $this->position;
            $_cut32 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position31;
                $this->value = null;
            }

            $this->cut = $_cut32;

            if ($_success) {
                $contact = $this->value;
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_position33 = $this->position;
            $_cut34 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position33;
                $this->value = null;
            }

            $this->cut = $_cut34;

            if ($_success) {
                $address = $this->value;
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_position35 = $this->position;
            $_cut36 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position35;
                $this->value = null;
            }

            $this->cut = $_cut36;

            if ($_success) {
                $location = $this->value;
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_position37 = $this->position;
            $_cut38 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position37;
                $this->value = null;
            }

            $this->cut = $_cut38;

            if ($_success) {
                $phone = $this->value;
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value39[] = $this->value;

            $this->value = $_value39;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$contact, &$address, &$location, &$phone) {
                $this->getContainer()->setAttribute('ADRESS', [(string)$contact, (string)$address, (string)$location, (string)$phone]);
            });
        }

        $this->cache['ADRESS_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ADRESS_POST');
        }

        return $_success;
    }

    protected function parseBKOD_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['BKOD_POST'][$_position])) {
            $_success = $this->cache['BKOD_POST'][$_position]['success'];
            $this->position = $this->cache['BKOD_POST'][$_position]['position'];
            $this->value = $this->cache['BKOD_POST'][$_position]['value'];

            return $_success;
        }

        $_value42 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value42[] = $this->value;

            if (substr($this->string, $this->position, strlen('BKOD')) === 'BKOD') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('BKOD'));
                $this->position += strlen('BKOD');
            } else {
                $_success = false;

                $this->report($this->position, '\'BKOD\'');
            }
        }

        if ($_success) {
            $_value42[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value42[] = $this->value;

            $_position40 = $this->position;
            $_cut41 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position40;
                $this->value = null;
            }

            $this->cut = $_cut41;

            if ($_success) {
                $sni = $this->value;
            }
        }

        if ($_success) {
            $_value42[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value42[] = $this->value;

            $this->value = $_value42;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$sni) {
                if ($this->assertInt($sni, 'Expected SNI code')) {
                    $this->getContainer()->setAttribute('BKOD', $sni);
                }
            });
        }

        $this->cache['BKOD_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'BKOD_POST');
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

        $_value45 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value45[] = $this->value;

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
            $_value45[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value45[] = $this->value;

            $_position43 = $this->position;
            $_cut44 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position43;
                $this->value = null;
            }

            $this->cut = $_cut44;

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value45[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value45[] = $this->value;

            $this->value = $_value45;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                if ($this->assertString($name, 'Expected company name')) {
                    $this->getContainer()->setAttribute('FNAMN', $name);
                }
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

        $_value48 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value48[] = $this->value;

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
            $_value48[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value48[] = $this->value;

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
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value48[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value48[] = $this->value;

            $this->value = $_value48;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$id) {
                if ($this->assertString($id, 'Expected company identifier')) {
                    $this->getContainer()->setAttribute('FNR', $id);
                }
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

        $_value51 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value51[] = $this->value;

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
            $_value51[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value51[] = $this->value;

            $_position49 = $this->position;
            $_cut50 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position49;
                $this->value = null;
            }

            $this->cut = $_cut50;

            if ($_success) {
                $charset = $this->value;
            }
        }

        if ($_success) {
            $_value51[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value51[] = $this->value;

            $this->value = $_value51;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$charset) {
                if ($this->assertString($charset, 'Expected charset identifier')) {
                    if ($charset != 'PC8') {
                        $this->getLogger()->warning("Unknown charset $charset defined using #FORMAT");
                    }

                    $this->getContainer()->setAttribute('FORMAT', $charset);
                }
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

    protected function parseFTYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FTYP_POST'][$_position])) {
            $_success = $this->cache['FTYP_POST'][$_position]['success'];
            $this->position = $this->cache['FTYP_POST'][$_position]['position'];
            $this->value = $this->cache['FTYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value54 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value54[] = $this->value;

            if (substr($this->string, $this->position, strlen('FTYP')) === 'FTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FTYP'));
                $this->position += strlen('FTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'FTYP\'');
            }
        }

        if ($_success) {
            $_value54[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value54[] = $this->value;

            $_position52 = $this->position;
            $_cut53 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position52;
                $this->value = null;
            }

            $this->cut = $_cut53;

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value54[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value54[] = $this->value;

            $this->value = $_value54;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$type) {
                if ($this->assertString($type, 'Expected company type identifier')) {
                    $this->getContainer()->setAttribute('FTYP', $type);
                }
            });
        }

        $this->cache['FTYP_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FTYP_POST');
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

        $_value59 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value59[] = $this->value;

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
            $_value59[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value59[] = $this->value;

            $_position55 = $this->position;
            $_cut56 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position55;
                $this->value = null;
            }

            $this->cut = $_cut56;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value59[] = $this->value;

            $_position57 = $this->position;
            $_cut58 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position57;
                $this->value = null;
            }

            $this->cut = $_cut58;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value59[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value59[] = $this->value;

            $this->value = $_value59;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date, &$sign) {
                if ($this->assertDate($date)) {
                    $this->getContainer()->setAttribute('GEN', [$date, strval($sign)]);
                }
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

        $_value62 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value62[] = $this->value;

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
            $_value62[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value62[] = $this->value;

            $_position60 = $this->position;
            $_cut61 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position60;
                $this->value = null;
            }

            $this->cut = $_cut61;

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value62[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value62[] = $this->value;

            $this->value = $_value62;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$type) {
                if ($this->assertString($type, 'Expected account plan type identifier')) {
                    $this->getContainer()->setAttribute('KPTYP', $type);
                }
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

    protected function parseOMFATTN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OMFATTN_POST'][$_position])) {
            $_success = $this->cache['OMFATTN_POST'][$_position]['success'];
            $this->position = $this->cache['OMFATTN_POST'][$_position]['position'];
            $this->value = $this->cache['OMFATTN_POST'][$_position]['value'];

            return $_success;
        }

        $_value65 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value65[] = $this->value;

            if (substr($this->string, $this->position, strlen('OMFATTN')) === 'OMFATTN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OMFATTN'));
                $this->position += strlen('OMFATTN');
            } else {
                $_success = false;

                $this->report($this->position, '\'OMFATTN\'');
            }
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_position63 = $this->position;
            $_cut64 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position63;
                $this->value = null;
            }

            $this->cut = $_cut64;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value65[] = $this->value;

            $this->value = $_value65;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                if ($this->assertDate($date)) {
                    $this->getContainer()->setAttribute('OMFATTN', $date);
                }
            });
        }

        $this->cache['OMFATTN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OMFATTN_POST');
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

        $_value72 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value72[] = $this->value;

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
            $_value72[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_position66 = $this->position;
            $_cut67 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position66;
                $this->value = null;
            }

            $this->cut = $_cut67;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_position68 = $this->position;
            $_cut69 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position68;
                $this->value = null;
            }

            $this->cut = $_cut69;

            if ($_success) {
                $acquisition = $this->value;
            }
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_position70 = $this->position;
            $_cut71 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position70;
                $this->value = null;
            }

            $this->cut = $_cut71;

            if ($_success) {
                $operation = $this->value;
            }
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value72[] = $this->value;

            $this->value = $_value72;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$acquisition, &$operation) {
                if ($this->assertString($number, 'Expected organisation number')) {
                    $this->getContainer()->setAttribute('ORGNR', [$number, intval($acquisition), intval($operation)]);
                }
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

        $_value77 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value77[] = $this->value;

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
            $_value77[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value77[] = $this->value;

            $_position73 = $this->position;
            $_cut74 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position73;
                $this->value = null;
            }

            $this->cut = $_cut74;

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value77[] = $this->value;

            $_position75 = $this->position;
            $_cut76 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position75;
                $this->value = null;
            }

            $this->cut = $_cut76;

            if ($_success) {
                $version = $this->value;
            }
        }

        if ($_success) {
            $_value77[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value77[] = $this->value;

            $this->value = $_value77;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name, &$version) {
                if ($this->assertString($name, 'Expected name') && $this->assertString($version, 'Expected version')) {
                    $this->getContainer()->setAttribute('PROGRAM', [$name, $version]);
                }
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

        $_value80 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value80[] = $this->value;

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
            $_value80[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value80[] = $this->value;

            $_position78 = $this->position;
            $_cut79 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position78;
                $this->value = null;
            }

            $this->cut = $_cut79;

            if ($_success) {
                $text = $this->value;
            }
        }

        if ($_success) {
            $_value80[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value80[] = $this->value;

            $this->value = $_value80;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$text) {
                if ($this->assertString($text, 'Expected free text content')) {
                    $this->getContainer()->setAttribute('PROSA', $text);
                }
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

    protected function parseRAR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['RAR_POST'][$_position])) {
            $_success = $this->cache['RAR_POST'][$_position]['success'];
            $this->position = $this->cache['RAR_POST'][$_position]['position'];
            $this->value = $this->cache['RAR_POST'][$_position]['value'];

            return $_success;
        }

        $_value87 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value87[] = $this->value;

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
            $_value87[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position81 = $this->position;
            $_cut82 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position81;
                $this->value = null;
            }

            $this->cut = $_cut82;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position83 = $this->position;
            $_cut84 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position83;
                $this->value = null;
            }

            $this->cut = $_cut84;

            if ($_success) {
                $startDate = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position85 = $this->position;
            $_cut86 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position85;
                $this->value = null;
            }

            $this->cut = $_cut86;

            if ($_success) {
                $endDate = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value87[] = $this->value;

            $this->value = $_value87;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$startDate, &$endDate) {
                // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                /*
                    Skulle kunna använda \DatePeriod för detta..
                    Men vad betyder det? Ska vi använda period istället för årsnummer?

                    $this->getContainer()->setAttribute('RAR', [$year, $datePeriod]);

                    $account->setAttribute("IB", [$year, $datePeriod, $balance, $quantity ?: 0]);

                    Hm, det här behöver jag fundera mer över...


                 */

                if ($this->assertInt($year) && $this->assertDate($startDate) && $this->assertDate($endDate)) {
                    $this->getContainer()->setAttribute("RAR $year", [$startDate, $endDate]);
                }
            });
        }

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

    protected function parseSIETYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['SIETYP_POST'][$_position])) {
            $_success = $this->cache['SIETYP_POST'][$_position]['success'];
            $this->position = $this->cache['SIETYP_POST'][$_position]['position'];
            $this->value = $this->cache['SIETYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value90 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value90[] = $this->value;

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
            $_value90[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_position88 = $this->position;
            $_cut89 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position88;
                $this->value = null;
            }

            $this->cut = $_cut89;

            if ($_success) {
                $ver = $this->value;
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
            $this->value = call_user_func(function () use (&$ver) {
                if ($this->assertInt($ver, 'Expected SIE version')) {
                    $this->getContainer()->setAttribute('SIETYP', $ver);
                }
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

    protected function parseTAXAR_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['TAXAR_POST'][$_position])) {
            $_success = $this->cache['TAXAR_POST'][$_position]['success'];
            $this->position = $this->cache['TAXAR_POST'][$_position]['position'];
            $this->value = $this->cache['TAXAR_POST'][$_position]['value'];

            return $_success;
        }

        $_value91 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value91[] = $this->value;

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
            $_value91[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value91[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value91[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value91[] = $this->value;

            $this->value = $_value91;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year) {
                if ($this->assertInt($year)) {
                    $this->getContainer()->setAttribute("TAXAR", $year);
                }
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

        $_value94 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value94[] = $this->value;

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
            $_value94[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value94[] = $this->value;

            $_position92 = $this->position;
            $_cut93 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position92;
                $this->value = null;
            }

            $this->cut = $_cut93;

            if ($_success) {
                $currency = $this->value;
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
            $this->value = call_user_func(function () use (&$currency) {
                if ($this->assertString($currency, 'Expected currency name')) {
                    $this->getContainer()->setAttribute('VALUTA', $currency);
                    $this->getCurrencyBuilder()->setCurrencyClass($currency);
                }
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

    protected function parseACCOUNT_PLAN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['ACCOUNT_PLAN_POST'][$_position])) {
            $_success = $this->cache['ACCOUNT_PLAN_POST'][$_position]['success'];
            $this->position = $this->cache['ACCOUNT_PLAN_POST'][$_position]['position'];
            $this->value = $this->cache['ACCOUNT_PLAN_POST'][$_position]['value'];

            return $_success;
        }

        $_position95 = $this->position;
        $_cut96 = $this->cut;

        $this->cut = false;
        $_success = $this->parseKONTO_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseKTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseENHET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseSRU_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseUNDERDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseOBJEKT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position95;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut96;

        $this->cache['ACCOUNT_PLAN_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ACCOUNT_PLAN_POST');
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

        $_value101 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value101[] = $this->value;

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
            $_value101[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_position97 = $this->position;
            $_cut98 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position97;
                $this->value = null;
            }

            $this->cut = $_cut98;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value101[] = $this->value;

            $_position99 = $this->position;
            $_cut100 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position99;
                $this->value = null;
            }

            $this->cut = $_cut100;

            if ($_success) {
                $desc = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$desc) {
                if ($this->assertString($number, 'Expected account number') && $this->assertString($desc, 'Expected account description')) {
                    $this->getAccountBuilder()->addAccount($number, $desc);
                }
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

        $_value106 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value106[] = $this->value;

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
            $_value106[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_position102 = $this->position;
            $_cut103 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position102;
                $this->value = null;
            }

            $this->cut = $_cut103;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value106[] = $this->value;

            $_position104 = $this->position;
            $_cut105 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position104;
                $this->value = null;
            }

            $this->cut = $_cut105;

            if ($_success) {
                $type = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$type) {
                if ($this->assertString($number, 'Expected account number') && $this->assertString($type, 'Expected account type identifier')) {
                    $this->getAccountBuilder()->setAccountType($number, $type);
                }
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

    protected function parseENHET_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['ENHET_POST'][$_position])) {
            $_success = $this->cache['ENHET_POST'][$_position]['success'];
            $this->position = $this->cache['ENHET_POST'][$_position]['position'];
            $this->value = $this->cache['ENHET_POST'][$_position]['value'];

            return $_success;
        }

        $_value111 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value111[] = $this->value;

            if (substr($this->string, $this->position, strlen('ENHET')) === 'ENHET') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('ENHET'));
                $this->position += strlen('ENHET');
            } else {
                $_success = false;

                $this->report($this->position, '\'ENHET\'');
            }
        }

        if ($_success) {
            $_value111[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value111[] = $this->value;

            $_position107 = $this->position;
            $_cut108 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position107;
                $this->value = null;
            }

            $this->cut = $_cut108;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value111[] = $this->value;

            $_position109 = $this->position;
            $_cut110 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position109;
                $this->value = null;
            }

            $this->cut = $_cut110;

            if ($_success) {
                $unit = $this->value;
            }
        }

        if ($_success) {
            $_value111[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value111[] = $this->value;

            $this->value = $_value111;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$unit) {
                if ($this->assertAccount($account) && $this->assertString($unit, 'Expected unit')) {
                    $account->setAttribute('unit', $unit);
                }
            });
        }

        $this->cache['ENHET_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ENHET_POST');
        }

        return $_success;
    }

    protected function parseSRU_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['SRU_POST'][$_position])) {
            $_success = $this->cache['SRU_POST'][$_position]['success'];
            $this->position = $this->cache['SRU_POST'][$_position]['position'];
            $this->value = $this->cache['SRU_POST'][$_position]['value'];

            return $_success;
        }

        $_value116 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value116[] = $this->value;

            if (substr($this->string, $this->position, strlen('SRU')) === 'SRU') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('SRU'));
                $this->position += strlen('SRU');
            } else {
                $_success = false;

                $this->report($this->position, '\'SRU\'');
            }
        }

        if ($_success) {
            $_value116[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value116[] = $this->value;

            $_position112 = $this->position;
            $_cut113 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position112;
                $this->value = null;
            }

            $this->cut = $_cut113;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value116[] = $this->value;

            $_position114 = $this->position;
            $_cut115 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position114;
                $this->value = null;
            }

            $this->cut = $_cut115;

            if ($_success) {
                $sru = $this->value;
            }
        }

        if ($_success) {
            $_value116[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value116[] = $this->value;

            $this->value = $_value116;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$sru) {
                if ($this->assertAccount($account) && $this->assertInt($sru, 'Expected SRU code')) {
                    $account->setAttribute('sru', $sru);
                }
            });
        }

        $this->cache['SRU_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SRU_POST');
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

        $_value121 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value121[] = $this->value;

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
            $_value121[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value121[] = $this->value;

            $_position117 = $this->position;
            $_cut118 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position117;
                $this->value = null;
            }

            $this->cut = $_cut118;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value121[] = $this->value;

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
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value121[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value121[] = $this->value;

            $this->value = $_value121;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$desc) {
                if ($this->assertInt($dim) && $this->assertString($desc)) {
                    $this->getDimensionBuilder()->addDimension((string)$dim, $desc);
                }
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

        $_value128 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value128[] = $this->value;

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
            $_value128[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value128[] = $this->value;

            $_position122 = $this->position;
            $_cut123 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position122;
                $this->value = null;
            }

            $this->cut = $_cut123;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value128[] = $this->value;

            $_position124 = $this->position;
            $_cut125 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position124;
                $this->value = null;
            }

            $this->cut = $_cut125;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value128[] = $this->value;

            $_position126 = $this->position;
            $_cut127 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position126;
                $this->value = null;
            }

            $this->cut = $_cut127;

            if ($_success) {
                $super = $this->value;
            }
        }

        if ($_success) {
            $_value128[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value128[] = $this->value;

            $this->value = $_value128;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$desc, &$super) {
                if ($this->assertInt($dim) && $this->assertString($desc) && $this->assertInt($super)) {
                    $this->getDimensionBuilder()->addDimension((string)$dim, $desc, (string)$super);
                }
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

        $_value135 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value135[] = $this->value;

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
            $_value135[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position129 = $this->position;
            $_cut130 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position129;
                $this->value = null;
            }

            $this->cut = $_cut130;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position131 = $this->position;
            $_cut132 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position131;
                $this->value = null;
            }

            $this->cut = $_cut132;

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position133 = $this->position;
            $_cut134 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position133;
                $this->value = null;
            }

            $this->cut = $_cut134;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value135[] = $this->value;

            $this->value = $_value135;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$obj, &$desc) {
                if ($this->assertInt($dim) && $this->assertString($obj) && $this->assertString($desc)) {
                    $this->getDimensionBuilder()->addObject((string)$dim, $obj, $desc);
                }
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

    protected function parseBALANCE_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['BALANCE_POST'][$_position])) {
            $_success = $this->cache['BALANCE_POST'][$_position]['success'];
            $this->position = $this->cache['BALANCE_POST'][$_position]['position'];
            $this->value = $this->cache['BALANCE_POST'][$_position]['value'];

            return $_success;
        }

        $_position136 = $this->position;
        $_cut137 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIB_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseOIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseOUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parsePBUDGET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parsePSALDO_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseRES_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseVER_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut137;

        $this->cache['BALANCE_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'BALANCE_POST');
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

        $_value146 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value146[] = $this->value;

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
            $_value146[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value146[] = $this->value;

            $_position138 = $this->position;
            $_cut139 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position138;
                $this->value = null;
            }

            $this->cut = $_cut139;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value146[] = $this->value;

            $_position140 = $this->position;
            $_cut141 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position140;
                $this->value = null;
            }

            $this->cut = $_cut141;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value146[] = $this->value;

            $_position142 = $this->position;
            $_cut143 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position142;
                $this->value = null;
            }

            $this->cut = $_cut143;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value146[] = $this->value;

            $_position144 = $this->position;
            $_cut145 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position144;
                $this->value = null;
            }

            $this->cut = $_cut145;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value146[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value146[] = $this->value;

            $this->value = $_value146;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    $account->setAttribute("IB $year", [$balance, $quantity ?: null]);
                }
            });
        }

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

    protected function parseUB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['UB_POST'][$_position])) {
            $_success = $this->cache['UB_POST'][$_position]['success'];
            $this->position = $this->cache['UB_POST'][$_position]['position'];
            $this->value = $this->cache['UB_POST'][$_position]['value'];

            return $_success;
        }

        $_value155 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value155[] = $this->value;

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
            $_value155[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_position147 = $this->position;
            $_cut148 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position147;
                $this->value = null;
            }

            $this->cut = $_cut148;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_position149 = $this->position;
            $_cut150 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position149;
                $this->value = null;
            }

            $this->cut = $_cut150;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_position151 = $this->position;
            $_cut152 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position151;
                $this->value = null;
            }

            $this->cut = $_cut152;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_position153 = $this->position;
            $_cut154 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position153;
                $this->value = null;
            }

            $this->cut = $_cut154;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value155[] = $this->value;

            $this->value = $_value155;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    $account->setAttribute("UB $year", [$balance, $quantity ?: null]);
                }
            });
        }

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

    protected function parseOIB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OIB_POST'][$_position])) {
            $_success = $this->cache['OIB_POST'][$_position]['success'];
            $this->position = $this->cache['OIB_POST'][$_position]['position'];
            $this->value = $this->cache['OIB_POST'][$_position]['value'];

            return $_success;
        }

        $_value166 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value166[] = $this->value;

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
            $_value166[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_position156 = $this->position;
            $_cut157 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position156;
                $this->value = null;
            }

            $this->cut = $_cut157;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_position158 = $this->position;
            $_cut159 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position158;
                $this->value = null;
            }

            $this->cut = $_cut159;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_position160 = $this->position;
            $_cut161 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position160;
                $this->value = null;
            }

            $this->cut = $_cut161;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_position162 = $this->position;
            $_cut163 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position162;
                $this->value = null;
            }

            $this->cut = $_cut163;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_position164 = $this->position;
            $_cut165 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position164;
                $this->value = null;
            }

            $this->cut = $_cut165;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value166[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value166[] = $this->value;

            $this->value = $_value166;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$objects, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    foreach ($objects as $object) {
                        $object->setAttribute("IB $year", [$balance, $quantity ?: null]);
                    }
                }
            });
        }

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

    protected function parseOUB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OUB_POST'][$_position])) {
            $_success = $this->cache['OUB_POST'][$_position]['success'];
            $this->position = $this->cache['OUB_POST'][$_position]['position'];
            $this->value = $this->cache['OUB_POST'][$_position]['value'];

            return $_success;
        }

        $_value177 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value177[] = $this->value;

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
            $_value177[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position167 = $this->position;
            $_cut168 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position167;
                $this->value = null;
            }

            $this->cut = $_cut168;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position169 = $this->position;
            $_cut170 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position169;
                $this->value = null;
            }

            $this->cut = $_cut170;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position171 = $this->position;
            $_cut172 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position171;
                $this->value = null;
            }

            $this->cut = $_cut172;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position173 = $this->position;
            $_cut174 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position173;
                $this->value = null;
            }

            $this->cut = $_cut174;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position175 = $this->position;
            $_cut176 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position175;
                $this->value = null;
            }

            $this->cut = $_cut176;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value177[] = $this->value;

            $this->value = $_value177;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$objects, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    foreach ($objects as $object) {
                        $object->setAttribute("UB $year", [$balance, $quantity ?: null]);
                    }
                }
            });
        }

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

    protected function parsePBUDGET_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['PBUDGET_POST'][$_position])) {
            $_success = $this->cache['PBUDGET_POST'][$_position]['success'];
            $this->position = $this->cache['PBUDGET_POST'][$_position]['position'];
            $this->value = $this->cache['PBUDGET_POST'][$_position]['value'];

            return $_success;
        }

        $_value190 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value190[] = $this->value;

            if (substr($this->string, $this->position, strlen('PBUDGET')) === 'PBUDGET') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PBUDGET'));
                $this->position += strlen('PBUDGET');
            } else {
                $_success = false;

                $this->report($this->position, '\'PBUDGET\'');
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position178 = $this->position;
            $_cut179 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position178;
                $this->value = null;
            }

            $this->cut = $_cut179;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position180 = $this->position;
            $_cut181 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position180;
                $this->value = null;
            }

            $this->cut = $_cut181;

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position182 = $this->position;
            $_cut183 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position182;
                $this->value = null;
            }

            $this->cut = $_cut183;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position184 = $this->position;
            $_cut185 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position184;
                $this->value = null;
            }

            $this->cut = $_cut185;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position186 = $this->position;
            $_cut187 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position186;
                $this->value = null;
            }

            $this->cut = $_cut187;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_position188 = $this->position;
            $_cut189 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position188;
                $this->value = null;
            }

            $this->cut = $_cut189;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value190[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value190[] = $this->value;

            $this->value = $_value190;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$objects, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertDate($period) && $this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    $key = "PBUDGET $year " . $period->format('Ym');

                    $account->setAttribute($key, [$balance, $quantity ?: null]);

                    foreach ($objects as $object) {
                        $object->setAttribute($key, [$balance, $quantity ?: null]);
                    }
                }
            });
        }

        $this->cache['PBUDGET_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PBUDGET_POST');
        }

        return $_success;
    }

    protected function parsePSALDO_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['PSALDO_POST'][$_position])) {
            $_success = $this->cache['PSALDO_POST'][$_position]['success'];
            $this->position = $this->cache['PSALDO_POST'][$_position]['position'];
            $this->value = $this->cache['PSALDO_POST'][$_position]['value'];

            return $_success;
        }

        $_value203 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value203[] = $this->value;

            if (substr($this->string, $this->position, strlen('PSALDO')) === 'PSALDO') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PSALDO'));
                $this->position += strlen('PSALDO');
            } else {
                $_success = false;

                $this->report($this->position, '\'PSALDO\'');
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position191 = $this->position;
            $_cut192 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position191;
                $this->value = null;
            }

            $this->cut = $_cut192;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position193 = $this->position;
            $_cut194 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position193;
                $this->value = null;
            }

            $this->cut = $_cut194;

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position195 = $this->position;
            $_cut196 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position195;
                $this->value = null;
            }

            $this->cut = $_cut196;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position197 = $this->position;
            $_cut198 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position197;
                $this->value = null;
            }

            $this->cut = $_cut198;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position199 = $this->position;
            $_cut200 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position199;
                $this->value = null;
            }

            $this->cut = $_cut200;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_position201 = $this->position;
            $_cut202 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position201;
                $this->value = null;
            }

            $this->cut = $_cut202;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value203[] = $this->value;

            $this->value = $_value203;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$objects, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertDate($period) && $this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($balance)) {
                    $key = "PSALDO $year " . $period->format('Ym');
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut

                    $account->setAttribute($key, [$balance, $quantity ?: null]);

                    foreach ($objects as $object) {
                        $object->setAttribute($key, [$balance, $quantity ?: null]);
                    }
                }
            });
        }

        $this->cache['PSALDO_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PSALDO_POST');
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

        $_value212 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value212[] = $this->value;

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
            $_value212[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_position204 = $this->position;
            $_cut205 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position204;
                $this->value = null;
            }

            $this->cut = $_cut205;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_position206 = $this->position;
            $_cut207 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position206;
                $this->value = null;
            }

            $this->cut = $_cut207;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_position208 = $this->position;
            $_cut209 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position208;
                $this->value = null;
            }

            $this->cut = $_cut209;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_position210 = $this->position;
            $_cut211 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position210;
                $this->value = null;
            }

            $this->cut = $_cut211;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value212[] = $this->value;

            $this->value = $_value212;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    // TODO här ska bokföringsår sparas på något intelligentare sätt till attribut
                    $account->setAttribute("RES $year", [$balance, $quantity ?: null]);
                }
            });
        }

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

    protected function parseVER_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VER_POST'][$_position])) {
            $_success = $this->cache['VER_POST'][$_position]['success'];
            $this->position = $this->cache['VER_POST'][$_position]['position'];
            $this->value = $this->cache['VER_POST'][$_position]['value'];

            return $_success;
        }

        $_value225 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value225[] = $this->value;

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
            $_value225[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position213 = $this->position;
            $_cut214 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position213;
                $this->value = null;
            }

            $this->cut = $_cut214;

            if ($_success) {
                $series = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position215 = $this->position;
            $_cut216 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position215;
                $this->value = null;
            }

            $this->cut = $_cut216;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position217 = $this->position;
            $_cut218 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position217;
                $this->value = null;
            }

            $this->cut = $_cut218;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position219 = $this->position;
            $_cut220 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position219;
                $this->value = null;
            }

            $this->cut = $_cut220;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position221 = $this->position;
            $_cut222 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position221;
                $this->value = null;
            }

            $this->cut = $_cut222;

            if ($_success) {
                $regdate = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_position223 = $this->position;
            $_cut224 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position223;
                $this->value = null;
            }

            $this->cut = $_cut224;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_success = $this->parseSUBROW_START();
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_success = $this->parseTRANS_LIST();

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value225[] = $this->value;

            $_success = $this->parseSUBROW_END();
        }

        if ($_success) {
            $_value225[] = $this->value;

            $this->value = $_value225;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$series, &$number, &$date, &$desc, &$regdate, &$sign, &$trans) {
                if ($this->assertString($series) && $this->assertString($number) && $this->assertDate($date)) {
                    $this->getContainer()->addItem(
                        $this->getVerificationBuilder()->createVerification(
                            $series,
                            $number,
                            $date,
                            $desc ?: '',
                            $regdate ?: null,
                            $sign ?: '',
                            $trans
                        )
                    );
                }
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

        $_value229 = array();
        $_cut230 = $this->cut;

        while (true) {
            $_position228 = $this->position;

            $this->cut = false;
            $_position226 = $this->position;
            $_cut227 = $this->cut;

            $this->cut = false;
            $_success = $this->parseTRANS_POST();

            if (!$_success && !$this->cut) {
                $this->position = $_position226;

                $_success = $this->parseBTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position226;

                $_success = $this->parseRTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position226;

                $_success = $this->parseUNKNOWN_POST();
            }

            $this->cut = $_cut227;

            if (!$_success) {
                break;
            }

            $_value229[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position228;
            $this->value = $_value229;
        }

        $this->cut = $_cut230;

        if ($_success) {
            $trans = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$trans) {
                return array_filter(
                    $trans,
                    function ($item) {
                        return $item instanceof Transaction;
                    }
                );
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

        $_value245 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value245[] = $this->value;

            if (substr($this->string, $this->position, strlen('TRANS')) === 'TRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('TRANS'));
                $this->position += strlen('TRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'TRANS\'');
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position231 = $this->position;
            $_cut232 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position231;
                $this->value = null;
            }

            $this->cut = $_cut232;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position233 = $this->position;
            $_cut234 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position233;
                $this->value = null;
            }

            $this->cut = $_cut234;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position235 = $this->position;
            $_cut236 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position235;
                $this->value = null;
            }

            $this->cut = $_cut236;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position237 = $this->position;
            $_cut238 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position237;
                $this->value = null;
            }

            $this->cut = $_cut238;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position239 = $this->position;
            $_cut240 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position239;
                $this->value = null;
            }

            $this->cut = $_cut240;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position241 = $this->position;
            $_cut242 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position241;
                $this->value = null;
            }

            $this->cut = $_cut242;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_position243 = $this->position;
            $_cut244 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position243;
                $this->value = null;
            }

            $this->cut = $_cut244;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value245[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value245[] = $this->value;

            $this->value = $_value245;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($amount)) {
                    return $this->getVerificationBuilder()->createTransaction(
                        $account,
                        $objects,
                        $amount,
                        $date ?: null,
                        $desc ?: '',
                        $quantity ?: null,
                        $sign ?: ''
                    );
                }
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

    protected function parseBTRANS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['BTRANS_POST'][$_position])) {
            $_success = $this->cache['BTRANS_POST'][$_position]['success'];
            $this->position = $this->cache['BTRANS_POST'][$_position]['position'];
            $this->value = $this->cache['BTRANS_POST'][$_position]['value'];

            return $_success;
        }

        $_value260 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value260[] = $this->value;

            if (substr($this->string, $this->position, strlen('BTRANS')) === 'BTRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('BTRANS'));
                $this->position += strlen('BTRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'BTRANS\'');
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position246 = $this->position;
            $_cut247 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position246;
                $this->value = null;
            }

            $this->cut = $_cut247;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position248 = $this->position;
            $_cut249 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position248;
                $this->value = null;
            }

            $this->cut = $_cut249;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position250 = $this->position;
            $_cut251 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position250;
                $this->value = null;
            }

            $this->cut = $_cut251;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position252 = $this->position;
            $_cut253 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position252;
                $this->value = null;
            }

            $this->cut = $_cut253;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position254 = $this->position;
            $_cut255 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position254;
                $this->value = null;
            }

            $this->cut = $_cut255;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position256 = $this->position;
            $_cut257 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position256;
                $this->value = null;
            }

            $this->cut = $_cut257;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_position258 = $this->position;
            $_cut259 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position258;
                $this->value = null;
            }

            $this->cut = $_cut259;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value260[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value260[] = $this->value;

            $this->value = $_value260;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($amount)) {
                    $this->getLogger()->notice('Detected a BTRANS post, removed transactions are not supported yet..');
                }
            });
        }

        $this->cache['BTRANS_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'BTRANS_POST');
        }

        return $_success;
    }

    protected function parseRTRANS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['RTRANS_POST'][$_position])) {
            $_success = $this->cache['RTRANS_POST'][$_position]['success'];
            $this->position = $this->cache['RTRANS_POST'][$_position]['position'];
            $this->value = $this->cache['RTRANS_POST'][$_position]['value'];

            return $_success;
        }

        $_value275 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value275[] = $this->value;

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
            $_value275[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position261 = $this->position;
            $_cut262 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position261;
                $this->value = null;
            }

            $this->cut = $_cut262;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position263 = $this->position;
            $_cut264 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position263;
                $this->value = null;
            }

            $this->cut = $_cut264;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position265 = $this->position;
            $_cut266 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position265;
                $this->value = null;
            }

            $this->cut = $_cut266;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position267 = $this->position;
            $_cut268 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position267;
                $this->value = null;
            }

            $this->cut = $_cut268;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position269 = $this->position;
            $_cut270 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position269;
                $this->value = null;
            }

            $this->cut = $_cut270;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position271 = $this->position;
            $_cut272 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position271;
                $this->value = null;
            }

            $this->cut = $_cut272;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_position273 = $this->position;
            $_cut274 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position273;
                $this->value = null;
            }

            $this->cut = $_cut274;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value275[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value275[] = $this->value;

            $this->value = $_value275;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($objects) && $this->assertAmount($amount)) {
                    $this->getLogger()->notice('Detected a RTRANS post, added transactions are not supported yet..');
                }
            });
        }

        $this->cache['RTRANS_POST'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RTRANS_POST');
        }

        return $_success;
    }

    protected function parseVOID_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['VOID_ROW'][$_position])) {
            $_success = $this->cache['VOID_ROW'][$_position]['success'];
            $this->position = $this->cache['VOID_ROW'][$_position]['position'];
            $this->value = $this->cache['VOID_ROW'][$_position]['value'];

            return $_success;
        }

        $_position276 = $this->position;
        $_cut277 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUNKNOWN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position276;

            $_success = $this->parseINVALID_LINE();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position276;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut277;

        $this->cache['VOID_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VOID_ROW');
        }

        return $_success;
    }

    protected function parseINVALID_LINE()
    {
        $_position = $this->position;

        if (isset($this->cache['INVALID_LINE'][$_position])) {
            $_success = $this->cache['INVALID_LINE'][$_position]['success'];
            $this->position = $this->cache['INVALID_LINE'][$_position]['position'];
            $this->value = $this->cache['INVALID_LINE'][$_position]['value'];

            return $_success;
        }

        $_value283 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value283[] = $this->value;

            $_position278 = $this->position;
            $_cut279 = $this->cut;

            $this->cut = false;
            if (substr($this->string, $this->position, strlen('#')) === '#') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('#'));
                $this->position += strlen('#');
            } else {
                $_success = false;

                $this->report($this->position, '\'#\'');
            }

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position278;
            $this->cut = $_cut279;
        }

        if ($_success) {
            $_value283[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $_value281 = array($this->value);
                $_cut282 = $this->cut;

                while (true) {
                    $_position280 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseSTRING();

                    if (!$_success) {
                        break;
                    }

                    $_value281[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position280;
                    $this->value = $_value281;
                }

                $this->cut = $_cut282;
            }

            if ($_success) {
                $fields = $this->value;
            }
        }

        if ($_success) {
            $_value283[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value283[] = $this->value;

            $this->value = $_value283;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$fields) {
                $this->getLogger()->warning('Ignored invalid line "' . implode(' ', $fields) . '"');
            });
        }

        $this->cache['INVALID_LINE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'INVALID_LINE');
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

        $_value289 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value289[] = $this->value;

            $_position284 = $this->position;
            $_cut285 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_LABEL();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position284;
            $this->cut = $_cut285;
        }

        if ($_success) {
            $_value289[] = $this->value;

            $_success = $this->parseVALID_CHARS();

            if ($_success) {
                $label = $this->value;
            }
        }

        if ($_success) {
            $_value289[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value289[] = $this->value;

            $_value287 = array();
            $_cut288 = $this->cut;

            while (true) {
                $_position286 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value287[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position286;
                $this->value = $_value287;
            }

            $this->cut = $_cut288;

            if ($_success) {
                $vars = $this->value;
            }
        }

        if ($_success) {
            $_value289[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value289[] = $this->value;

            $this->value = $_value289;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$label, &$vars) {
                $this->getLogger()->notice(
                    array_reduce(
                        $vars,
                        function ($carry, $var) {
                            return "$carry \"$var\"";
                        },
                        "Ignored unknown statement: #$label"
                    )
                );
            });
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

    protected function parseVALID_LABEL()
    {
        $_position = $this->position;

        if (isset($this->cache['VALID_LABEL'][$_position])) {
            $_success = $this->cache['VALID_LABEL'][$_position]['success'];
            $this->position = $this->cache['VALID_LABEL'][$_position]['position'];
            $this->value = $this->cache['VALID_LABEL'][$_position]['value'];

            return $_success;
        }

        $_position290 = $this->position;
        $_cut291 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen('ADRESS')) === 'ADRESS') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('ADRESS'));
            $this->position += strlen('ADRESS');
        } else {
            $_success = false;

            $this->report($this->position, '\'ADRESS\'');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('BKOD')) === 'BKOD') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('BKOD'));
                $this->position += strlen('BKOD');
            } else {
                $_success = false;

                $this->report($this->position, '\'BKOD\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('DIM')) === 'DIM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('DIM'));
                $this->position += strlen('DIM');
            } else {
                $_success = false;

                $this->report($this->position, '\'DIM\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('ENHET')) === 'ENHET') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('ENHET'));
                $this->position += strlen('ENHET');
            } else {
                $_success = false;

                $this->report($this->position, '\'ENHET\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('FLAGGA')) === 'FLAGGA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FLAGGA'));
                $this->position += strlen('FLAGGA');
            } else {
                $_success = false;

                $this->report($this->position, '\'FLAGGA\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('FNAMN')) === 'FNAMN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FNAMN'));
                $this->position += strlen('FNAMN');
            } else {
                $_success = false;

                $this->report($this->position, '\'FNAMN\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('FNR')) === 'FNR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FNR'));
                $this->position += strlen('FNR');
            } else {
                $_success = false;

                $this->report($this->position, '\'FNR\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('FORMAT')) === 'FORMAT') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FORMAT'));
                $this->position += strlen('FORMAT');
            } else {
                $_success = false;

                $this->report($this->position, '\'FORMAT\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('FTYP')) === 'FTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('FTYP'));
                $this->position += strlen('FTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'FTYP\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('GEN')) === 'GEN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('GEN'));
                $this->position += strlen('GEN');
            } else {
                $_success = false;

                $this->report($this->position, '\'GEN\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('IB')) === 'IB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('IB'));
                $this->position += strlen('IB');
            } else {
                $_success = false;

                $this->report($this->position, '\'IB\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('KONTO')) === 'KONTO') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KONTO'));
                $this->position += strlen('KONTO');
            } else {
                $_success = false;

                $this->report($this->position, '\'KONTO\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('KPTYP')) === 'KPTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KPTYP'));
                $this->position += strlen('KPTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'KPTYP\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('KSUMMA')) === 'KSUMMA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KSUMMA'));
                $this->position += strlen('KSUMMA');
            } else {
                $_success = false;

                $this->report($this->position, '\'KSUMMA\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('KTYP')) === 'KTYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('KTYP'));
                $this->position += strlen('KTYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'KTYP\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('OBJEKT')) === 'OBJEKT') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OBJEKT'));
                $this->position += strlen('OBJEKT');
            } else {
                $_success = false;

                $this->report($this->position, '\'OBJEKT\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('OIB')) === 'OIB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OIB'));
                $this->position += strlen('OIB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OIB\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('OMFATTN')) === 'OMFATTN') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OMFATTN'));
                $this->position += strlen('OMFATTN');
            } else {
                $_success = false;

                $this->report($this->position, '\'OMFATTN\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('ORGNR')) === 'ORGNR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('ORGNR'));
                $this->position += strlen('ORGNR');
            } else {
                $_success = false;

                $this->report($this->position, '\'ORGNR\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('OUB')) === 'OUB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('OUB'));
                $this->position += strlen('OUB');
            } else {
                $_success = false;

                $this->report($this->position, '\'OUB\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('PBUDGET')) === 'PBUDGET') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PBUDGET'));
                $this->position += strlen('PBUDGET');
            } else {
                $_success = false;

                $this->report($this->position, '\'PBUDGET\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('PROGRAM')) === 'PROGRAM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PROGRAM'));
                $this->position += strlen('PROGRAM');
            } else {
                $_success = false;

                $this->report($this->position, '\'PROGRAM\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('PROSA')) === 'PROSA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PROSA'));
                $this->position += strlen('PROSA');
            } else {
                $_success = false;

                $this->report($this->position, '\'PROSA\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('PSALDO')) === 'PSALDO') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('PSALDO'));
                $this->position += strlen('PSALDO');
            } else {
                $_success = false;

                $this->report($this->position, '\'PSALDO\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('RAR')) === 'RAR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RAR'));
                $this->position += strlen('RAR');
            } else {
                $_success = false;

                $this->report($this->position, '\'RAR\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('RES')) === 'RES') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RES'));
                $this->position += strlen('RES');
            } else {
                $_success = false;

                $this->report($this->position, '\'RES\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('SIETYP')) === 'SIETYP') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('SIETYP'));
                $this->position += strlen('SIETYP');
            } else {
                $_success = false;

                $this->report($this->position, '\'SIETYP\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('SRU')) === 'SRU') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('SRU'));
                $this->position += strlen('SRU');
            } else {
                $_success = false;

                $this->report($this->position, '\'SRU\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('TAXAR')) === 'TAXAR') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('TAXAR'));
                $this->position += strlen('TAXAR');
            } else {
                $_success = false;

                $this->report($this->position, '\'TAXAR\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('TRANS')) === 'TRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('TRANS'));
                $this->position += strlen('TRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'TRANS\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('RTRANS')) === 'RTRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('RTRANS'));
                $this->position += strlen('RTRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'RTRANS\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('BTRANS')) === 'BTRANS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('BTRANS'));
                $this->position += strlen('BTRANS');
            } else {
                $_success = false;

                $this->report($this->position, '\'BTRANS\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('UB')) === 'UB') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('UB'));
                $this->position += strlen('UB');
            } else {
                $_success = false;

                $this->report($this->position, '\'UB\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('UNDERDIM')) === 'UNDERDIM') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('UNDERDIM'));
                $this->position += strlen('UNDERDIM');
            } else {
                $_success = false;

                $this->report($this->position, '\'UNDERDIM\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('VALUTA')) === 'VALUTA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VALUTA'));
                $this->position += strlen('VALUTA');
            } else {
                $_success = false;

                $this->report($this->position, '\'VALUTA\'');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position290;

            if (substr($this->string, $this->position, strlen('VER')) === 'VER') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VER'));
                $this->position += strlen('VER');
            } else {
                $_success = false;

                $this->report($this->position, '\'VER\'');
            }
        }

        $this->cut = $_cut291;

        $this->cache['VALID_LABEL'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VALID_LABEL');
        }

        return $_success;
    }

    protected function parseSUBROW_START()
    {
        $_position = $this->position;

        if (isset($this->cache['SUBROW_START'][$_position])) {
            $_success = $this->cache['SUBROW_START'][$_position]['success'];
            $this->position = $this->cache['SUBROW_START'][$_position]['position'];
            $this->value = $this->cache['SUBROW_START'][$_position]['value'];

            return $_success;
        }

        $_value292 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value292[] = $this->value;

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
            $_value292[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value292[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value292[] = $this->value;

            $this->value = $_value292;
        }

        $this->cache['SUBROW_START'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SUBROW_START');
        }

        return $_success;
    }

    protected function parseSUBROW_END()
    {
        $_position = $this->position;

        if (isset($this->cache['SUBROW_END'][$_position])) {
            $_success = $this->cache['SUBROW_END'][$_position]['success'];
            $this->position = $this->cache['SUBROW_END'][$_position]['position'];
            $this->value = $this->cache['SUBROW_END'][$_position]['value'];

            return $_success;
        }

        $_value295 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value295[] = $this->value;

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
            $_value295[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value295[] = $this->value;

            $_position293 = $this->position;
            $_cut294 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position293;
                $this->value = null;
            }

            $this->cut = $_cut294;
        }

        if ($_success) {
            $_value295[] = $this->value;

            $this->value = $_value295;
        }

        $this->cache['SUBROW_END'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SUBROW_END');
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

        $_value299 = array();

        $_value297 = array();
        $_cut298 = $this->cut;

        while (true) {
            $_position296 = $this->position;

            $this->cut = false;
            $_success = $this->parseEMPTY_LINE();

            if (!$_success) {
                break;
            }

            $_value297[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position296;
            $this->value = $_value297;
        }

        $this->cut = $_cut298;

        if ($_success) {
            $_value299[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value299[] = $this->value;

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
            $_value299[] = $this->value;

            $this->value = $_value299;
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

        $_value305 = array();

        $_value301 = array();
        $_cut302 = $this->cut;

        while (true) {
            $_position300 = $this->position;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success) {
                break;
            }

            $_value301[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position300;
            $this->value = $_value301;
        }

        $this->cut = $_cut302;

        if ($_success) {
            $fields = $this->value;
        }

        if ($_success) {
            $_value305[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value305[] = $this->value;

            $_position303 = $this->position;
            $_cut304 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position303;
                $this->value = null;
            }

            $this->cut = $_cut304;
        }

        if ($_success) {
            $_value305[] = $this->value;

            $this->value = $_value305;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$fields) {
                foreach ($fields as $field) {
                    $this->getLogger()->notice("Ignored unknown field $field at end of line");
                }
            });
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

    protected function parseOPTIONAL_DATE()
    {
        $_position = $this->position;

        if (isset($this->cache['OPTIONAL_DATE'][$_position])) {
            $_success = $this->cache['OPTIONAL_DATE'][$_position]['success'];
            $this->position = $this->cache['OPTIONAL_DATE'][$_position]['position'];
            $this->value = $this->cache['OPTIONAL_DATE'][$_position]['value'];

            return $_success;
        }

        $_position306 = $this->position;
        $_cut307 = $this->cut;

        $this->cut = false;
        $_success = $this->parseDATE();

        if (!$_success && !$this->cut) {
            $this->position = $_position306;

            $_success = $this->parseEMPTY_STRING();
        }

        $this->cut = $_cut307;

        if ($_success) {
            $date = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                return $date;
            });
        }

        $this->cache['OPTIONAL_DATE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OPTIONAL_DATE');
        }

        return $_success;
    }

    protected function parseDATE()
    {
        $_position = $this->position;

        if (isset($this->cache['DATE'][$_position])) {
            $_success = $this->cache['DATE'][$_position]['success'];
            $this->position = $this->cache['DATE'][$_position]['position'];
            $this->value = $this->cache['DATE'][$_position]['value'];

            return $_success;
        }

        $_value310 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value310[] = $this->value;

            $_position308 = $this->position;
            $_cut309 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_DATE();

            if (!$_success && !$this->cut) {
                $this->position = $_position308;

                $_success = $this->parseQUOTED_DATE();
            }

            $this->cut = $_cut309;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value310[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value310[] = $this->value;

            $this->value = $_value310;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                return $date;
            });
        }

        $this->cache['DATE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'DATE');
        }

        return $_success;
    }

    protected function parseQUOTED_DATE()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_DATE'][$_position])) {
            $_success = $this->cache['QUOTED_DATE'][$_position]['success'];
            $this->position = $this->cache['QUOTED_DATE'][$_position]['position'];
            $this->value = $this->cache['QUOTED_DATE'][$_position]['value'];

            return $_success;
        }

        $_value311 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value311[] = $this->value;

            $_success = $this->parseRAW_DATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value311[] = $this->value;

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
            $_value311[] = $this->value;

            $this->value = $_value311;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                return $date;
            });
        }

        $this->cache['QUOTED_DATE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_DATE');
        }

        return $_success;
    }

    protected function parseRAW_DATE()
    {
        $_position = $this->position;

        if (isset($this->cache['RAW_DATE'][$_position])) {
            $_success = $this->cache['RAW_DATE'][$_position]['success'];
            $this->position = $this->cache['RAW_DATE'][$_position]['position'];
            $this->value = $this->cache['RAW_DATE'][$_position]['value'];

            return $_success;
        }

        $_value319 = array();

        $_value312 = array();

        if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value312[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value312[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value312[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value312[] = $this->value;

            $this->value = $_value312;
        }

        if ($_success) {
            $year = $this->value;
        }

        if ($_success) {
            $_value319[] = $this->value;

            $_position314 = $this->position;
            $_cut315 = $this->cut;

            $this->cut = false;
            $_value313 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value313[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value313[] = $this->value;

                $this->value = $_value313;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position314;
                $this->value = null;
            }

            $this->cut = $_cut315;

            if ($_success) {
                $month = $this->value;
            }
        }

        if ($_success) {
            $_value319[] = $this->value;

            $_position317 = $this->position;
            $_cut318 = $this->cut;

            $this->cut = false;
            $_value316 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value316[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value316[] = $this->value;

                $this->value = $_value316;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position317;
                $this->value = null;
            }

            $this->cut = $_cut318;

            if ($_success) {
                $day = $this->value;
            }
        }

        if ($_success) {
            $_value319[] = $this->value;

            $this->value = $_value319;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$month, &$day) {
                return new \DateTimeImmutable(
                    implode($year)
                    . (implode((array)$month) ?: '01')
                    . (implode((array)$day) ?: '01')
                );
            });
        }

        $this->cache['RAW_DATE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAW_DATE');
        }

        return $_success;
    }

    protected function parseAMOUNT()
    {
        $_position = $this->position;

        if (isset($this->cache['AMOUNT'][$_position])) {
            $_success = $this->cache['AMOUNT'][$_position]['success'];
            $this->position = $this->cache['AMOUNT'][$_position]['position'];
            $this->value = $this->cache['AMOUNT'][$_position]['value'];

            return $_success;
        }

        $_value322 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value322[] = $this->value;

            $_position320 = $this->position;
            $_cut321 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_AMOUNT();

            if (!$_success && !$this->cut) {
                $this->position = $_position320;

                $_success = $this->parseQUOTED_AMOUNT();
            }

            $this->cut = $_cut321;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value322[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value322[] = $this->value;

            $this->value = $_value322;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$amount) {
                return $amount;
            });
        }

        $this->cache['AMOUNT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'AMOUNT');
        }

        return $_success;
    }

    protected function parseQUOTED_AMOUNT()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_AMOUNT'][$_position])) {
            $_success = $this->cache['QUOTED_AMOUNT'][$_position]['success'];
            $this->position = $this->cache['QUOTED_AMOUNT'][$_position]['position'];
            $this->value = $this->cache['QUOTED_AMOUNT'][$_position]['value'];

            return $_success;
        }

        $_value323 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value323[] = $this->value;

            $_success = $this->parseRAW_AMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value323[] = $this->value;

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
            $_value323[] = $this->value;

            $this->value = $_value323;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$amount) {
                return $amount;
            });
        }

        $this->cache['QUOTED_AMOUNT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_AMOUNT');
        }

        return $_success;
    }

    protected function parseRAW_AMOUNT()
    {
        $_position = $this->position;

        if (isset($this->cache['RAW_AMOUNT'][$_position])) {
            $_success = $this->cache['RAW_AMOUNT'][$_position]['success'];
            $this->position = $this->cache['RAW_AMOUNT'][$_position]['position'];
            $this->value = $this->cache['RAW_AMOUNT'][$_position]['value'];

            return $_success;
        }

        $_value336 = array();

        $_position324 = $this->position;
        $_cut325 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("-")) === "-") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("-"));
            $this->position += strlen("-");
        } else {
            $_success = false;

            $this->report($this->position, '"-"');
        }

        if (!$_success && !$this->cut) {
            $_success = true;
            $this->position = $_position324;
            $this->value = null;
        }

        $this->cut = $_cut325;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value336[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value327 = array($this->value);
                $_cut328 = $this->cut;

                while (true) {
                    $_position326 = $this->position;

                    $this->cut = false;
                    if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, 1);
                        $this->position += 1;
                    } else {
                        $_success = false;
                    }

                    if (!$_success) {
                        break;
                    }

                    $_value327[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position326;
                    $this->value = $_value327;
                }

                $this->cut = $_cut328;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value336[] = $this->value;

            $_position329 = $this->position;
            $_cut330 = $this->cut;

            $this->cut = false;
            if (substr($this->string, $this->position, strlen(".")) === ".") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("."));
                $this->position += strlen(".");
            } else {
                $_success = false;

                $this->report($this->position, '"."');
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position329;
                $this->value = null;
            }

            $this->cut = $_cut330;
        }

        if ($_success) {
            $_value336[] = $this->value;

            $_value335 = array();

            $_position331 = $this->position;
            $_cut332 = $this->cut;

            $this->cut = false;
            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position331;
                $this->value = null;
            }

            $this->cut = $_cut332;

            if ($_success) {
                $_value335[] = $this->value;

                $_position333 = $this->position;
                $_cut334 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $_success = true;
                    $this->position = $_position333;
                    $this->value = null;
                }

                $this->cut = $_cut334;
            }

            if ($_success) {
                $_value335[] = $this->value;

                $this->value = $_value335;
            }

            if ($_success) {
                $subunits = $this->value;
            }
        }

        if ($_success) {
            $_value336[] = $this->value;

            $this->value = $_value336;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$negation, &$units, &$subunits) {
                return $this->getCurrencyBuilder()->createMoney($negation.implode($units).'.'.implode($subunits));
            });
        }

        $this->cache['RAW_AMOUNT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAW_AMOUNT');
        }

        return $_success;
    }

    protected function parseACCOUNT()
    {
        $_position = $this->position;

        if (isset($this->cache['ACCOUNT'][$_position])) {
            $_success = $this->cache['ACCOUNT'][$_position]['success'];
            $this->position = $this->cache['ACCOUNT'][$_position]['position'];
            $this->value = $this->cache['ACCOUNT'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseSTRING();

        if ($_success) {
            $number = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number) {
                return $this->getAccountBuilder()->getAccount($number);
            });
        }

        $this->cache['ACCOUNT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ACCOUNT');
        }

        return $_success;
    }

    protected function parseOBJECT()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT'][$_position])) {
            $_success = $this->cache['OBJECT'][$_position]['success'];
            $this->position = $this->cache['OBJECT'][$_position]['position'];
            $this->value = $this->cache['OBJECT'][$_position]['value'];

            return $_success;
        }

        $_value337 = array();

        $_success = $this->parseINT();

        if ($_success) {
            $super = $this->value;
        }

        if ($_success) {
            $_value337[] = $this->value;

            $_success = $this->parseOBJECT_LIST_SAFE_STRING();

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value337[] = $this->value;

            $this->value = $_value337;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$super, &$obj) {
                return $this->getDimensionBuilder()->getObject((string)$super, $obj);
            });
        }

        $this->cache['OBJECT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT');
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

        $_value340 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value340[] = $this->value;

            $_position338 = $this->position;
            $_cut339 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_ID_SAFE_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position338;

                $_success = $this->parseQUOTED_STRING();
            }

            $this->cut = $_cut339;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value340[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value340[] = $this->value;

            $this->value = $_value340;
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

    protected function parseOBJECT_ID_SAFE_CHARS()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_ID_SAFE_CHARS'][$_position])) {
            $_success = $this->cache['OBJECT_ID_SAFE_CHARS'][$_position]['success'];
            $this->position = $this->cache['OBJECT_ID_SAFE_CHARS'][$_position]['position'];
            $this->value = $this->cache['OBJECT_ID_SAFE_CHARS'][$_position]['value'];

            return $_success;
        }

        if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`|~⌂ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value342 = array($this->value);
            $_cut343 = $this->cut;

            while (true) {
                $_position341 = $this->position;

                $this->cut = false;
                if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`|~⌂ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value342[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position341;
                $this->value = $_value342;
            }

            $this->cut = $_cut343;
        }

        if ($_success) {
            $chars = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$chars) {
                return implode($chars);
            });
        }

        $this->cache['OBJECT_ID_SAFE_CHARS'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OBJECT_ID_SAFE_CHARS');
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

        $_value347 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value347[] = $this->value;

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
            $_value347[] = $this->value;

            $_value345 = array();
            $_cut346 = $this->cut;

            while (true) {
                $_position344 = $this->position;

                $this->cut = false;
                $_success = $this->parseOBJECT();

                if (!$_success) {
                    break;
                }

                $_value345[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position344;
                $this->value = $_value345;
            }

            $this->cut = $_cut346;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value347[] = $this->value;

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
            $_value347[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value347[] = $this->value;

            $this->value = $_value347;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$objects) {
                return $objects;
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

    protected function parseFLOAT()
    {
        $_position = $this->position;

        if (isset($this->cache['FLOAT'][$_position])) {
            $_success = $this->cache['FLOAT'][$_position]['success'];
            $this->position = $this->cache['FLOAT'][$_position]['position'];
            $this->value = $this->cache['FLOAT'][$_position]['value'];

            return $_success;
        }

        $_value350 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value350[] = $this->value;

            $_position348 = $this->position;
            $_cut349 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_FLOAT();

            if (!$_success && !$this->cut) {
                $this->position = $_position348;

                $_success = $this->parseQUOTED_FLOAT();
            }

            $this->cut = $_cut349;

            if ($_success) {
                $float = $this->value;
            }
        }

        if ($_success) {
            $_value350[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value350[] = $this->value;

            $this->value = $_value350;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$float) {
                return new Amount($float);
            });
        }

        $this->cache['FLOAT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FLOAT');
        }

        return $_success;
    }

    protected function parseQUOTED_FLOAT()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_FLOAT'][$_position])) {
            $_success = $this->cache['QUOTED_FLOAT'][$_position]['success'];
            $this->position = $this->cache['QUOTED_FLOAT'][$_position]['position'];
            $this->value = $this->cache['QUOTED_FLOAT'][$_position]['value'];

            return $_success;
        }

        $_value351 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value351[] = $this->value;

            $_success = $this->parseRAW_FLOAT();

            if ($_success) {
                $float = $this->value;
            }
        }

        if ($_success) {
            $_value351[] = $this->value;

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
            $_value351[] = $this->value;

            $this->value = $_value351;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$float) {
                return $float;
            });
        }

        $this->cache['QUOTED_FLOAT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_FLOAT');
        }

        return $_success;
    }

    protected function parseRAW_FLOAT()
    {
        $_position = $this->position;

        if (isset($this->cache['RAW_FLOAT'][$_position])) {
            $_success = $this->cache['RAW_FLOAT'][$_position]['success'];
            $this->position = $this->cache['RAW_FLOAT'][$_position]['position'];
            $this->value = $this->cache['RAW_FLOAT'][$_position]['value'];

            return $_success;
        }

        $_value357 = array();

        $_success = $this->parseRAW_INT();

        if ($_success) {
            $int = $this->value;
        }

        if ($_success) {
            $_value357[] = $this->value;

            $_position352 = $this->position;
            $_cut353 = $this->cut;

            $this->cut = false;
            if (substr($this->string, $this->position, strlen('.')) === '.') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('.'));
                $this->position += strlen('.');
            } else {
                $_success = false;

                $this->report($this->position, '\'.\'');
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position352;
                $this->value = null;
            }

            $this->cut = $_cut353;
        }

        if ($_success) {
            $_value357[] = $this->value;

            $_value355 = array();
            $_cut356 = $this->cut;

            while (true) {
                $_position354 = $this->position;

                $this->cut = false;
                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value355[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position354;
                $this->value = $_value355;
            }

            $this->cut = $_cut356;

            if ($_success) {
                $trailing = $this->value;
            }
        }

        if ($_success) {
            $_value357[] = $this->value;

            $this->value = $_value357;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$int, &$trailing) {
                return $int.'.'.implode($trailing);
            });
        }

        $this->cache['RAW_FLOAT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAW_FLOAT');
        }

        return $_success;
    }

    protected function parseINT()
    {
        $_position = $this->position;

        if (isset($this->cache['INT'][$_position])) {
            $_success = $this->cache['INT'][$_position]['success'];
            $this->position = $this->cache['INT'][$_position]['position'];
            $this->value = $this->cache['INT'][$_position]['value'];

            return $_success;
        }

        $_value360 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value360[] = $this->value;

            $_position358 = $this->position;
            $_cut359 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_INT();

            if (!$_success && !$this->cut) {
                $this->position = $_position358;

                $_success = $this->parseQUOTED_INT();
            }

            $this->cut = $_cut359;

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value360[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value360[] = $this->value;

            $this->value = $_value360;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$int) {
                return $int;
            });
        }

        $this->cache['INT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'INT');
        }

        return $_success;
    }

    protected function parseQUOTED_INT()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_INT'][$_position])) {
            $_success = $this->cache['QUOTED_INT'][$_position]['success'];
            $this->position = $this->cache['QUOTED_INT'][$_position]['position'];
            $this->value = $this->cache['QUOTED_INT'][$_position]['value'];

            return $_success;
        }

        $_value361 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value361[] = $this->value;

            $_success = $this->parseRAW_INT();

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value361[] = $this->value;

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
            $_value361[] = $this->value;

            $this->value = $_value361;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$int) {
                return $int;
            });
        }

        $this->cache['QUOTED_INT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_INT');
        }

        return $_success;
    }

    protected function parseRAW_INT()
    {
        $_position = $this->position;

        if (isset($this->cache['RAW_INT'][$_position])) {
            $_success = $this->cache['RAW_INT'][$_position]['success'];
            $this->position = $this->cache['RAW_INT'][$_position]['position'];
            $this->value = $this->cache['RAW_INT'][$_position]['value'];

            return $_success;
        }

        $_value367 = array();

        $_position362 = $this->position;
        $_cut363 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("-")) === "-") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("-"));
            $this->position += strlen("-");
        } else {
            $_success = false;

            $this->report($this->position, '"-"');
        }

        if (!$_success && !$this->cut) {
            $_success = true;
            $this->position = $_position362;
            $this->value = null;
        }

        $this->cut = $_cut363;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value367[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value365 = array($this->value);
                $_cut366 = $this->cut;

                while (true) {
                    $_position364 = $this->position;

                    $this->cut = false;
                    if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, 1);
                        $this->position += 1;
                    } else {
                        $_success = false;
                    }

                    if (!$_success) {
                        break;
                    }

                    $_value365[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position364;
                    $this->value = $_value365;
                }

                $this->cut = $_cut366;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value367[] = $this->value;

            $this->value = $_value367;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$negation, &$units) {
                return intval($negation.implode($units));
            });
        }

        $this->cache['RAW_INT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAW_INT');
        }

        return $_success;
    }

    protected function parseBOOLEAN()
    {
        $_position = $this->position;

        if (isset($this->cache['BOOLEAN'][$_position])) {
            $_success = $this->cache['BOOLEAN'][$_position]['success'];
            $this->position = $this->cache['BOOLEAN'][$_position]['position'];
            $this->value = $this->cache['BOOLEAN'][$_position]['value'];

            return $_success;
        }

        $_value370 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value370[] = $this->value;

            $_position368 = $this->position;
            $_cut369 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_BOOLEAN();

            if (!$_success && !$this->cut) {
                $this->position = $_position368;

                $_success = $this->parseQUOTED_BOOLEAN();
            }

            $this->cut = $_cut369;

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value370[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value370[] = $this->value;

            $this->value = $_value370;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$bool) {
                return $bool;
            });
        }

        $this->cache['BOOLEAN'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'BOOLEAN');
        }

        return $_success;
    }

    protected function parseQUOTED_BOOLEAN()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_BOOLEAN'][$_position])) {
            $_success = $this->cache['QUOTED_BOOLEAN'][$_position]['success'];
            $this->position = $this->cache['QUOTED_BOOLEAN'][$_position]['position'];
            $this->value = $this->cache['QUOTED_BOOLEAN'][$_position]['value'];

            return $_success;
        }

        $_value371 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value371[] = $this->value;

            $_success = $this->parseRAW_BOOLEAN();

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value371[] = $this->value;

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
            $_value371[] = $this->value;

            $this->value = $_value371;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$bool) {
                return $bool;
            });
        }

        $this->cache['QUOTED_BOOLEAN'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'QUOTED_BOOLEAN');
        }

        return $_success;
    }

    protected function parseRAW_BOOLEAN()
    {
        $_position = $this->position;

        if (isset($this->cache['RAW_BOOLEAN'][$_position])) {
            $_success = $this->cache['RAW_BOOLEAN'][$_position]['success'];
            $this->position = $this->cache['RAW_BOOLEAN'][$_position]['position'];
            $this->value = $this->cache['RAW_BOOLEAN'][$_position]['value'];

            return $_success;
        }

        if (preg_match('/^[01]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $bool = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$bool) {
                return !!$bool;
            });
        }

        $this->cache['RAW_BOOLEAN'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RAW_BOOLEAN');
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

        $_value374 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value374[] = $this->value;

            $_position372 = $this->position;
            $_cut373 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position372;

                $_success = $this->parseQUOTED_STRING();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position372;

                $_success = $this->parseEMPTY_STRING();
            }

            $this->cut = $_cut373;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value374[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value374[] = $this->value;

            $this->value = $_value374;
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

        $_value380 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value380[] = $this->value;

            $_value378 = array();
            $_cut379 = $this->cut;

            while (true) {
                $_position377 = $this->position;

                $this->cut = false;
                $_position375 = $this->position;
                $_cut376 = $this->cut;

                $this->cut = false;
                $_success = $this->parseESCAPED_QUOTE();

                if (!$_success && !$this->cut) {
                    $this->position = $_position375;

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
                    $this->position = $_position375;

                    $_success = $this->parseVALID_CHARS();
                }

                $this->cut = $_cut376;

                if (!$_success) {
                    break;
                }

                $_value378[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position377;
                $this->value = $_value378;
            }

            $this->cut = $_cut379;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value380[] = $this->value;

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
            $_value380[] = $this->value;

            $this->value = $_value380;
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

    protected function parseVALID_CHARS()
    {
        $_position = $this->position;

        if (isset($this->cache['VALID_CHARS'][$_position])) {
            $_success = $this->cache['VALID_CHARS'][$_position]['success'];
            $this->position = $this->cache['VALID_CHARS'][$_position]['position'];
            $this->value = $this->cache['VALID_CHARS'][$_position]['value'];

            return $_success;
        }

        if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`{|}~⌂ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value382 = array($this->value);
            $_cut383 = $this->cut;

            while (true) {
                $_position381 = $this->position;

                $this->cut = false;
                if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`{|}~⌂ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑªº¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘΩδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value382[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position381;
                $this->value = $_value382;
            }

            $this->cut = $_cut383;
        }

        if ($_success) {
            $chars = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$chars) {
                return implode($chars);
            });
        }

        $this->cache['VALID_CHARS'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'VALID_CHARS');
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

        $_value384 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value384[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value384[] = $this->value;

            $this->value = $_value384;
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

        $_value387 = array();

        $_position385 = $this->position;
        $_cut386 = $this->cut;

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
            $this->position = $_position385;
            $this->value = null;
        }

        $this->cut = $_cut386;

        if ($_success) {
            $_value387[] = $this->value;

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
            $_value387[] = $this->value;

            $this->value = $_value387;
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                $this->getLogger()->incrementLineCount();
            });
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

        $_value391 = array();
        $_cut392 = $this->cut;

        while (true) {
            $_position390 = $this->position;

            $this->cut = false;
            $_position388 = $this->position;
            $_cut389 = $this->cut;

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
                $this->position = $_position388;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut389;

            if (!$_success) {
                break;
            }

            $_value391[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position390;
            $this->value = $_value391;
        }

        $this->cut = $_cut392;

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

        $_success = $this->parseFILE();

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