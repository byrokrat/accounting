<?php

namespace byrokrat\accounting\Sie4\Parser;

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
                    $this->parsedAttributes['flag'] = $flag;
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
                    $this->parsedAttributes['checksum'] = $checksum;
                    $this->getLogger()->log('notice', 'Checksum detected but currently not handled');
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
                $this->getLogger()->log('warning', 'Misplaced identification post');
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
                $this->getLogger()->log('warning', 'Misplaced account plan post');
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
                $this->parsedAttributes['company_address'] = [(string)$contact, (string)$address, (string)$location, (string)$phone];
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
                    $this->parsedAttributes['company_sni_code'] = $sni;
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
                    $this->parsedAttributes['company_name'] = $name;
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
                    $this->parsedAttributes['company_id'] = $id;
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
                        $this->getLogger()->log('warning', "Unknown charset $charset defined using #FORMAT");
                    }

                    $this->parsedAttributes['charset'] = $charset;
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
                    $this->parsedAttributes['company_type'] = $type;
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
                    $this->parsedAttributes['generation_date'] = [$date, strval($sign)];
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
                    $this->parsedAttributes['account_plan_type'] = $type;
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
                    $this->parsedAttributes['period_end_date'] = $date;
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
                    $this->parsedAttributes['company_org_nr'] = [$number, intval($acquisition), intval($operation)];
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
                    $this->parsedAttributes['program'] = [$name, $version];
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

        $_value81 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value81[] = $this->value;

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
            $_value81[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_value79 = array();
            $_cut80 = $this->cut;

            while (true) {
                $_position78 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value79[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position78;
                $this->value = $_value79;
            }

            $this->cut = $_cut80;

            if ($_success) {
                $text = $this->value;
            }
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value81[] = $this->value;

            $this->value = $_value81;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$text) {
                $this->parsedAttributes['free_text'] = implode(' ', $text);
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

        $_value88 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value88[] = $this->value;

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
            $_value88[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value88[] = $this->value;

            $_position82 = $this->position;
            $_cut83 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position82;
                $this->value = null;
            }

            $this->cut = $_cut83;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value88[] = $this->value;

            $_position84 = $this->position;
            $_cut85 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position84;
                $this->value = null;
            }

            $this->cut = $_cut85;

            if ($_success) {
                $startDate = $this->value;
            }
        }

        if ($_success) {
            $_value88[] = $this->value;

            $_position86 = $this->position;
            $_cut87 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position86;
                $this->value = null;
            }

            $this->cut = $_cut87;

            if ($_success) {
                $endDate = $this->value;
            }
        }

        if ($_success) {
            $_value88[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value88[] = $this->value;

            $this->value = $_value88;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$startDate, &$endDate) {
                if ($this->assertInt($year) && $this->assertDate($startDate) && $this->assertDate($endDate)) {
                    $this->parsedAttributes["financial_year[$year]"] = [$startDate, $endDate];
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

        $_value91 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value91[] = $this->value;

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
            $_value91[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value91[] = $this->value;

            $_position89 = $this->position;
            $_cut90 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position89;
                $this->value = null;
            }

            $this->cut = $_cut90;

            if ($_success) {
                $ver = $this->value;
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
            $this->value = call_user_func(function () use (&$ver) {
                if ($this->assertInt($ver, 'Expected SIE version')) {
                    $this->parsedAttributes['sie_version'] = $ver;
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

        $_value92 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value92[] = $this->value;

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
            $_value92[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value92[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value92[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value92[] = $this->value;

            $this->value = $_value92;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year) {
                if ($this->assertInt($year)) {
                    $this->parsedAttributes["taxation_year"] = $year;
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

        $_value95 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value95[] = $this->value;

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
            $_value95[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value95[] = $this->value;

            $_position93 = $this->position;
            $_cut94 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position93;
                $this->value = null;
            }

            $this->cut = $_cut94;

            if ($_success) {
                $currency = $this->value;
            }
        }

        if ($_success) {
            $_value95[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value95[] = $this->value;

            $this->value = $_value95;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$currency) {
                if ($this->assertString($currency, 'Expected currency name')) {
                    $this->parsedAttributes['currency'] = $currency;
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

        $_position96 = $this->position;
        $_cut97 = $this->cut;

        $this->cut = false;
        $_success = $this->parseKONTO_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseKTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseENHET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseSRU_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseUNDERDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseOBJEKT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position96;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut97;

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

        $_value102 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value102[] = $this->value;

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
            $_value102[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_position98 = $this->position;
            $_cut99 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position98;
                $this->value = null;
            }

            $this->cut = $_cut99;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value102[] = $this->value;

            $_position100 = $this->position;
            $_cut101 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position100;
                $this->value = null;
            }

            $this->cut = $_cut101;

            if ($_success) {
                $desc = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$desc) {
                if ($this->assertString($number, 'Expected account number') && $this->assertString($desc, 'Expected account description')) {
                    $this->getAccountBuilder()->addAccount($number, $desc);
                    $this->getAccountBuilder()->getAccount($number)->setAttribute(
                        'incoming_balance',
                        $this->getCurrencyBuilder()->createMoney('0')
                    );
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

        $_value107 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value107[] = $this->value;

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
            $_value107[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value107[] = $this->value;

            $_position103 = $this->position;
            $_cut104 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position103;
                $this->value = null;
            }

            $this->cut = $_cut104;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value107[] = $this->value;

            $_position105 = $this->position;
            $_cut106 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position105;
                $this->value = null;
            }

            $this->cut = $_cut106;

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value107[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value107[] = $this->value;

            $this->value = $_value107;
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

        $_value112 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value112[] = $this->value;

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
            $_value112[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value112[] = $this->value;

            $_position108 = $this->position;
            $_cut109 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position108;
                $this->value = null;
            }

            $this->cut = $_cut109;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value112[] = $this->value;

            $_position110 = $this->position;
            $_cut111 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position110;
                $this->value = null;
            }

            $this->cut = $_cut111;

            if ($_success) {
                $unit = $this->value;
            }
        }

        if ($_success) {
            $_value112[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value112[] = $this->value;

            $this->value = $_value112;
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

        $_value117 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value117[] = $this->value;

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
            $_value117[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_position113 = $this->position;
            $_cut114 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position113;
                $this->value = null;
            }

            $this->cut = $_cut114;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_position115 = $this->position;
            $_cut116 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position115;
                $this->value = null;
            }

            $this->cut = $_cut116;

            if ($_success) {
                $sru = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value117[] = $this->value;

            $this->value = $_value117;
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

        $_value122 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value122[] = $this->value;

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
            $_value122[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value122[] = $this->value;

            $_position118 = $this->position;
            $_cut119 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position118;
                $this->value = null;
            }

            $this->cut = $_cut119;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value122[] = $this->value;

            $_position120 = $this->position;
            $_cut121 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position120;
                $this->value = null;
            }

            $this->cut = $_cut121;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value122[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value122[] = $this->value;

            $this->value = $_value122;
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

        $_value129 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value129[] = $this->value;

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
            $_value129[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value129[] = $this->value;

            $_position123 = $this->position;
            $_cut124 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position123;
                $this->value = null;
            }

            $this->cut = $_cut124;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value129[] = $this->value;

            $_position125 = $this->position;
            $_cut126 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position125;
                $this->value = null;
            }

            $this->cut = $_cut126;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value129[] = $this->value;

            $_position127 = $this->position;
            $_cut128 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position127;
                $this->value = null;
            }

            $this->cut = $_cut128;

            if ($_success) {
                $super = $this->value;
            }
        }

        if ($_success) {
            $_value129[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value129[] = $this->value;

            $this->value = $_value129;
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

        $_value136 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value136[] = $this->value;

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
            $_value136[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value136[] = $this->value;

            $_position130 = $this->position;
            $_cut131 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position130;
                $this->value = null;
            }

            $this->cut = $_cut131;

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value136[] = $this->value;

            $_position132 = $this->position;
            $_cut133 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position132;
                $this->value = null;
            }

            $this->cut = $_cut133;

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value136[] = $this->value;

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
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value136[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value136[] = $this->value;

            $this->value = $_value136;
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

        $_position137 = $this->position;
        $_cut138 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIB_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseOIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseOUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parsePBUDGET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parsePSALDO_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseRES_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseVER_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position137;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut138;

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

        $_value147 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value147[] = $this->value;

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
            $_value147[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value147[] = $this->value;

            $_position139 = $this->position;
            $_cut140 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position139;
                $this->value = null;
            }

            $this->cut = $_cut140;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value147[] = $this->value;

            $_position141 = $this->position;
            $_cut142 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position141;
                $this->value = null;
            }

            $this->cut = $_cut142;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value147[] = $this->value;

            $_position143 = $this->position;
            $_cut144 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position143;
                $this->value = null;
            }

            $this->cut = $_cut144;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value147[] = $this->value;

            $_position145 = $this->position;
            $_cut146 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position145;
                $this->value = null;
            }

            $this->cut = $_cut146;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value147[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value147[] = $this->value;

            $this->value = $_value147;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    $quantity = $quantity ?: new Amount('0');
                    $this->writeAttribute($account, "incoming_balance", $balance, $year);
                    $this->writeAttribute($account, "incoming_quantity", $quantity, $year);
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

        $_value156 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value156[] = $this->value;

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
            $_value156[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value156[] = $this->value;

            $_position148 = $this->position;
            $_cut149 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position148;
                $this->value = null;
            }

            $this->cut = $_cut149;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value156[] = $this->value;

            $_position150 = $this->position;
            $_cut151 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position150;
                $this->value = null;
            }

            $this->cut = $_cut151;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value156[] = $this->value;

            $_position152 = $this->position;
            $_cut153 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position152;
                $this->value = null;
            }

            $this->cut = $_cut153;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value156[] = $this->value;

            $_position154 = $this->position;
            $_cut155 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position154;
                $this->value = null;
            }

            $this->cut = $_cut155;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value156[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value156[] = $this->value;

            $this->value = $_value156;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    $quantity = $quantity ?: new Amount('0');
                    $this->writeAttribute($account, "outgoing_balance", $balance, $year);
                    $this->writeAttribute($account, "outgoing_quantity", $quantity, $year);
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

        $_value167 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value167[] = $this->value;

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
            $_value167[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position157 = $this->position;
            $_cut158 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position157;
                $this->value = null;
            }

            $this->cut = $_cut158;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position159 = $this->position;
            $_cut160 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position159;
                $this->value = null;
            }

            $this->cut = $_cut160;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position161 = $this->position;
            $_cut162 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position161;
                $this->value = null;
            }

            $this->cut = $_cut162;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position163 = $this->position;
            $_cut164 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position163;
                $this->value = null;
            }

            $this->cut = $_cut164;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_position165 = $this->position;
            $_cut166 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position165;
                $this->value = null;
            }

            $this->cut = $_cut166;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value167[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value167[] = $this->value;

            $this->value = $_value167;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$dims, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($balance)) {
                    $quantity = $quantity ?: new Amount('0');
                    foreach ($dims as $dim) {
                        $this->writeAttribute($dim, "incoming_balance", $balance, $year);
                        $this->writeAttribute($dim, "incoming_quantity", $quantity, $year);
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

        $_value178 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value178[] = $this->value;

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
            $_value178[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_position168 = $this->position;
            $_cut169 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position168;
                $this->value = null;
            }

            $this->cut = $_cut169;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_position170 = $this->position;
            $_cut171 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position170;
                $this->value = null;
            }

            $this->cut = $_cut171;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_position172 = $this->position;
            $_cut173 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position172;
                $this->value = null;
            }

            $this->cut = $_cut173;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_position174 = $this->position;
            $_cut175 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position174;
                $this->value = null;
            }

            $this->cut = $_cut175;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_position176 = $this->position;
            $_cut177 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position176;
                $this->value = null;
            }

            $this->cut = $_cut177;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value178[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value178[] = $this->value;

            $this->value = $_value178;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$dims, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($balance)) {
                    $quantity = $quantity ?: new Amount('0');
                    foreach ($dims as $dim) {
                        $this->writeAttribute($dim, "outgoing_balance", $balance, $year);
                        $this->writeAttribute($dim, "outgoing_quantity", $quantity, $year);
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

        $_value191 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value191[] = $this->value;

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
            $_value191[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position179 = $this->position;
            $_cut180 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position179;
                $this->value = null;
            }

            $this->cut = $_cut180;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position181 = $this->position;
            $_cut182 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position181;
                $this->value = null;
            }

            $this->cut = $_cut182;

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position183 = $this->position;
            $_cut184 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position183;
                $this->value = null;
            }

            $this->cut = $_cut184;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position185 = $this->position;
            $_cut186 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position185;
                $this->value = null;
            }

            $this->cut = $_cut186;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position187 = $this->position;
            $_cut188 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position187;
                $this->value = null;
            }

            $this->cut = $_cut188;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_position189 = $this->position;
            $_cut190 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position189;
                $this->value = null;
            }

            $this->cut = $_cut190;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value191[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value191[] = $this->value;

            $this->value = $_value191;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$dims, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertDate($period) && $this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($balance)) {
                    $key = "$year.{$period->format('Ym')}";
                    $quantity = $quantity ?: new Amount('0');

                    $this->writeAttribute($account, "period_budget_balance", $balance, $key);
                    $this->writeAttribute($account, "period_budget_quantity", $quantity, $key);

                    foreach ($dims as $dim) {
                        $this->writeAttribute($dim, "period_budget_balance", $balance, $key);
                        $this->writeAttribute($dim, "period_budget_quantity", $quantity, $key);
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

        $_value204 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value204[] = $this->value;

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
            $_value204[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position192 = $this->position;
            $_cut193 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position192;
                $this->value = null;
            }

            $this->cut = $_cut193;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position194 = $this->position;
            $_cut195 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position194;
                $this->value = null;
            }

            $this->cut = $_cut195;

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position196 = $this->position;
            $_cut197 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position196;
                $this->value = null;
            }

            $this->cut = $_cut197;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position198 = $this->position;
            $_cut199 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position198;
                $this->value = null;
            }

            $this->cut = $_cut199;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position200 = $this->position;
            $_cut201 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position200;
                $this->value = null;
            }

            $this->cut = $_cut201;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_position202 = $this->position;
            $_cut203 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position202;
                $this->value = null;
            }

            $this->cut = $_cut203;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value204[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value204[] = $this->value;

            $this->value = $_value204;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$dims, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertDate($period) && $this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($balance)) {
                    $key = "$year.{$period->format('Ym')}";
                    $quantity = $quantity ?: new Amount('0');

                    $this->writeAttribute($account, "period_balance", $balance, $key);
                    $this->writeAttribute($account, "period_quantity", $quantity, $key);

                    foreach ($dims as $dim) {
                        $this->writeAttribute($dim, "period_balance", $balance, $key);
                        $this->writeAttribute($dim, "period_quantity", $quantity, $key);
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

        $_value213 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value213[] = $this->value;

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
            $_value213[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_position205 = $this->position;
            $_cut206 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position205;
                $this->value = null;
            }

            $this->cut = $_cut206;

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_position207 = $this->position;
            $_cut208 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position207;
                $this->value = null;
            }

            $this->cut = $_cut208;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_position209 = $this->position;
            $_cut210 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position209;
                $this->value = null;
            }

            $this->cut = $_cut210;

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_position211 = $this->position;
            $_cut212 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position211;
                $this->value = null;
            }

            $this->cut = $_cut212;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value213[] = $this->value;

            $this->value = $_value213;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                if ($this->assertInt($year) && $this->assertAccount($account) && $this->assertAmount($balance)) {
                    $quantity = $quantity ?: new Amount('0');
                    $this->writeAttribute($account, "result_balance", $balance, $year);
                    $this->writeAttribute($account, "result_quantity", $quantity, $year);
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

        $_value226 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value226[] = $this->value;

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
            $_value226[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position214 = $this->position;
            $_cut215 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position214;
                $this->value = null;
            }

            $this->cut = $_cut215;

            if ($_success) {
                $series = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position216 = $this->position;
            $_cut217 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position216;
                $this->value = null;
            }

            $this->cut = $_cut217;

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position218 = $this->position;
            $_cut219 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position218;
                $this->value = null;
            }

            $this->cut = $_cut219;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position220 = $this->position;
            $_cut221 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position220;
                $this->value = null;
            }

            $this->cut = $_cut221;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position222 = $this->position;
            $_cut223 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position222;
                $this->value = null;
            }

            $this->cut = $_cut223;

            if ($_success) {
                $regdate = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_position224 = $this->position;
            $_cut225 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position224;
                $this->value = null;
            }

            $this->cut = $_cut225;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_success = $this->parseSUBROW_START();
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_success = $this->parseTRANS_LIST();

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value226[] = $this->value;

            $_success = $this->parseSUBROW_END();
        }

        if ($_success) {
            $_value226[] = $this->value;

            $this->value = $_value226;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$series, &$number, &$date, &$desc, &$regdate, &$sign, &$trans) {
                if ($this->assertString($series) && $this->assertString($number) && $this->assertDate($date)) {
                    $this->parsedItems[] = $this->createVerification(
                        $series,
                        $number,
                        $date,
                        $desc ?: '',
                        $regdate ?: null,
                        $sign ?: '',
                        $trans
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

        $_value230 = array();
        $_cut231 = $this->cut;

        while (true) {
            $_position229 = $this->position;

            $this->cut = false;
            $_position227 = $this->position;
            $_cut228 = $this->cut;

            $this->cut = false;
            $_success = $this->parseTRANS_POST();

            if (!$_success && !$this->cut) {
                $this->position = $_position227;

                $_success = $this->parseBTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position227;

                $_success = $this->parseRTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position227;

                $_success = $this->parseUNKNOWN_POST();
            }

            $this->cut = $_cut228;

            if (!$_success) {
                break;
            }

            $_value230[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position229;
            $this->value = $_value230;
        }

        $this->cut = $_cut231;

        if ($_success) {
            $trans = $this->value;
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

        $_value246 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value246[] = $this->value;

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
            $_value246[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position232 = $this->position;
            $_cut233 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position232;
                $this->value = null;
            }

            $this->cut = $_cut233;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position234 = $this->position;
            $_cut235 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position234;
                $this->value = null;
            }

            $this->cut = $_cut235;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position236 = $this->position;
            $_cut237 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position236;
                $this->value = null;
            }

            $this->cut = $_cut237;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position238 = $this->position;
            $_cut239 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position238;
                $this->value = null;
            }

            $this->cut = $_cut239;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position240 = $this->position;
            $_cut241 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position240;
                $this->value = null;
            }

            $this->cut = $_cut241;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position242 = $this->position;
            $_cut243 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position242;
                $this->value = null;
            }

            $this->cut = $_cut243;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_position244 = $this->position;
            $_cut245 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position244;
                $this->value = null;
            }

            $this->cut = $_cut245;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value246[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value246[] = $this->value;

            $this->value = $_value246;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$dims, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($amount)) {
                    return [
                        'type' => Transaction\Transaction::CLASS,
                        'account' => $account,
                        'dimensions' => $dims,
                        'amount' => $amount,
                        'date' => $date,
                        'description' => $desc,
                        'quantity' => $quantity ?: new Amount('0'),
                        'signature' => $sign,
                    ];
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

        $_value261 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value261[] = $this->value;

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
            $_value261[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position247 = $this->position;
            $_cut248 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position247;
                $this->value = null;
            }

            $this->cut = $_cut248;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position249 = $this->position;
            $_cut250 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position249;
                $this->value = null;
            }

            $this->cut = $_cut250;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position251 = $this->position;
            $_cut252 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position251;
                $this->value = null;
            }

            $this->cut = $_cut252;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position253 = $this->position;
            $_cut254 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position253;
                $this->value = null;
            }

            $this->cut = $_cut254;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position255 = $this->position;
            $_cut256 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position255;
                $this->value = null;
            }

            $this->cut = $_cut256;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position257 = $this->position;
            $_cut258 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position257;
                $this->value = null;
            }

            $this->cut = $_cut258;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_position259 = $this->position;
            $_cut260 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position259;
                $this->value = null;
            }

            $this->cut = $_cut260;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value261[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value261[] = $this->value;

            $this->value = $_value261;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$dims, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($amount)) {
                    return [
                        'type' => Transaction\DeletedTransaction::CLASS,
                        'account' => $account,
                        'dimensions' => $dims,
                        'amount' => $amount,
                        'date' => $date,
                        'description' => $desc,
                        'quantity' => $quantity ?: new Amount('0'),
                        'signature' => $sign,
                    ];
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

        $_value276 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value276[] = $this->value;

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
            $_value276[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position262 = $this->position;
            $_cut263 = $this->cut;

            $this->cut = false;
            $_success = $this->parseACCOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position262;
                $this->value = null;
            }

            $this->cut = $_cut263;

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position264 = $this->position;
            $_cut265 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_LIST();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position264;
                $this->value = null;
            }

            $this->cut = $_cut265;

            if ($_success) {
                $dims = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position266 = $this->position;
            $_cut267 = $this->cut;

            $this->cut = false;
            $_success = $this->parseAMOUNT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position266;
                $this->value = null;
            }

            $this->cut = $_cut267;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position268 = $this->position;
            $_cut269 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTIONAL_DATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position268;
                $this->value = null;
            }

            $this->cut = $_cut269;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position270 = $this->position;
            $_cut271 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position270;
                $this->value = null;
            }

            $this->cut = $_cut271;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position272 = $this->position;
            $_cut273 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFLOAT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position272;
                $this->value = null;
            }

            $this->cut = $_cut273;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_position274 = $this->position;
            $_cut275 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position274;
                $this->value = null;
            }

            $this->cut = $_cut275;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value276[] = $this->value;

            $_success = $this->parseTRANS_POST();
        }

        if ($_success) {
            $_value276[] = $this->value;

            $this->value = $_value276;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$dims, &$amount, &$date, &$desc, &$quantity, &$sign) {
                if ($this->assertAccount($account) && $this->assertArray($dims) && $this->assertAmount($amount)) {
                    return [
                        'type' => Transaction\AddedTransaction::CLASS,
                        'account' => $account,
                        'dimensions' => $dims,
                        'amount' => $amount,
                        'date' => $date,
                        'description' => $desc,
                        'quantity' => $quantity ?: new Amount('0'),
                        'signature' => $sign,
                    ];
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

        $_position277 = $this->position;
        $_cut278 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUNKNOWN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position277;

            $_success = $this->parseINVALID_LINE();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position277;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut278;

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

        $_value284 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value284[] = $this->value;

            $_position279 = $this->position;
            $_cut280 = $this->cut;

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

            $this->position = $_position279;
            $this->cut = $_cut280;
        }

        if ($_success) {
            $_value284[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $_value282 = array($this->value);
                $_cut283 = $this->cut;

                while (true) {
                    $_position281 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseSTRING();

                    if (!$_success) {
                        break;
                    }

                    $_value282[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position281;
                    $this->value = $_value282;
                }

                $this->cut = $_cut283;
            }

            if ($_success) {
                $fields = $this->value;
            }
        }

        if ($_success) {
            $_value284[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value284[] = $this->value;

            $this->value = $_value284;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$fields) {
                $this->getLogger()->log('warning', 'Ignored invalid line "' . implode(' ', $fields) . '"');
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

        $_value290 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value290[] = $this->value;

            $_position285 = $this->position;
            $_cut286 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_LABEL();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position285;
            $this->cut = $_cut286;
        }

        if ($_success) {
            $_value290[] = $this->value;

            $_success = $this->parseVALID_CHARS();

            if ($_success) {
                $label = $this->value;
            }
        }

        if ($_success) {
            $_value290[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value290[] = $this->value;

            $_value288 = array();
            $_cut289 = $this->cut;

            while (true) {
                $_position287 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value288[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position287;
                $this->value = $_value288;
            }

            $this->cut = $_cut289;

            if ($_success) {
                $vars = $this->value;
            }
        }

        if ($_success) {
            $_value290[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value290[] = $this->value;

            $this->value = $_value290;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$label, &$vars) {
                $this->getLogger()->log(
                    'notice',
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

        $_position291 = $this->position;
        $_cut292 = $this->cut;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

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
            $this->position = $_position291;

            if (substr($this->string, $this->position, strlen('VER')) === 'VER') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VER'));
                $this->position += strlen('VER');
            } else {
                $_success = false;

                $this->report($this->position, '\'VER\'');
            }
        }

        $this->cut = $_cut292;

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

        $_value293 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value293[] = $this->value;

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
            $_value293[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value293[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value293[] = $this->value;

            $this->value = $_value293;
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

        $_value296 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value296[] = $this->value;

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
            $_value296[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value296[] = $this->value;

            $_position294 = $this->position;
            $_cut295 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position294;
                $this->value = null;
            }

            $this->cut = $_cut295;
        }

        if ($_success) {
            $_value296[] = $this->value;

            $this->value = $_value296;
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

        $_value300 = array();

        $_value298 = array();
        $_cut299 = $this->cut;

        while (true) {
            $_position297 = $this->position;

            $this->cut = false;
            $_success = $this->parseEMPTY_LINE();

            if (!$_success) {
                break;
            }

            $_value298[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position297;
            $this->value = $_value298;
        }

        $this->cut = $_cut299;

        if ($_success) {
            $_value300[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value300[] = $this->value;

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
            $_value300[] = $this->value;

            $this->value = $_value300;
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

        $_value306 = array();

        $_value302 = array();
        $_cut303 = $this->cut;

        while (true) {
            $_position301 = $this->position;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success) {
                break;
            }

            $_value302[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position301;
            $this->value = $_value302;
        }

        $this->cut = $_cut303;

        if ($_success) {
            $fields = $this->value;
        }

        if ($_success) {
            $_value306[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value306[] = $this->value;

            $_position304 = $this->position;
            $_cut305 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position304;
                $this->value = null;
            }

            $this->cut = $_cut305;
        }

        if ($_success) {
            $_value306[] = $this->value;

            $this->value = $_value306;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$fields) {
                foreach ($fields as $field) {
                    $this->getLogger()->log('notice', "Ignored unknown field $field at end of line");
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

        $_position307 = $this->position;
        $_cut308 = $this->cut;

        $this->cut = false;
        $_success = $this->parseDATE();

        if (!$_success && !$this->cut) {
            $this->position = $_position307;

            $_success = $this->parseEMPTY_STRING();
        }

        $this->cut = $_cut308;

        if ($_success) {
            $date = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                return $date ?: null;
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

        $_value311 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value311[] = $this->value;

            $_position309 = $this->position;
            $_cut310 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_DATE();

            if (!$_success && !$this->cut) {
                $this->position = $_position309;

                $_success = $this->parseQUOTED_DATE();
            }

            $this->cut = $_cut310;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value311[] = $this->value;

            $_success = $this->parse_();
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

        $_value312 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value312[] = $this->value;

            $_success = $this->parseRAW_DATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value312[] = $this->value;

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
            $_value312[] = $this->value;

            $this->value = $_value312;
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

        $_value320 = array();

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

        if ($_success) {
            $year = $this->value;
        }

        if ($_success) {
            $_value320[] = $this->value;

            $_position315 = $this->position;
            $_cut316 = $this->cut;

            $this->cut = false;
            $_value314 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value314[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value314[] = $this->value;

                $this->value = $_value314;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position315;
                $this->value = null;
            }

            $this->cut = $_cut316;

            if ($_success) {
                $month = $this->value;
            }
        }

        if ($_success) {
            $_value320[] = $this->value;

            $_position318 = $this->position;
            $_cut319 = $this->cut;

            $this->cut = false;
            $_value317 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value317[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value317[] = $this->value;

                $this->value = $_value317;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position318;
                $this->value = null;
            }

            $this->cut = $_cut319;

            if ($_success) {
                $day = $this->value;
            }
        }

        if ($_success) {
            $_value320[] = $this->value;

            $this->value = $_value320;
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

        $_value323 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value323[] = $this->value;

            $_position321 = $this->position;
            $_cut322 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_AMOUNT();

            if (!$_success && !$this->cut) {
                $this->position = $_position321;

                $_success = $this->parseQUOTED_AMOUNT();
            }

            $this->cut = $_cut322;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value323[] = $this->value;

            $_success = $this->parse_();
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

        $_value324 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value324[] = $this->value;

            $_success = $this->parseRAW_AMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value324[] = $this->value;

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
            $_value324[] = $this->value;

            $this->value = $_value324;
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

        $_value337 = array();

        $_position325 = $this->position;
        $_cut326 = $this->cut;

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
            $this->position = $_position325;
            $this->value = null;
        }

        $this->cut = $_cut326;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value337[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value328 = array($this->value);
                $_cut329 = $this->cut;

                while (true) {
                    $_position327 = $this->position;

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

                    $_value328[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position327;
                    $this->value = $_value328;
                }

                $this->cut = $_cut329;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value337[] = $this->value;

            $_position330 = $this->position;
            $_cut331 = $this->cut;

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
                $this->position = $_position330;
                $this->value = null;
            }

            $this->cut = $_cut331;
        }

        if ($_success) {
            $_value337[] = $this->value;

            $_value336 = array();

            $_position332 = $this->position;
            $_cut333 = $this->cut;

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
                $this->position = $_position332;
                $this->value = null;
            }

            $this->cut = $_cut333;

            if ($_success) {
                $_value336[] = $this->value;

                $_position334 = $this->position;
                $_cut335 = $this->cut;

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
                    $this->position = $_position334;
                    $this->value = null;
                }

                $this->cut = $_cut335;
            }

            if ($_success) {
                $_value336[] = $this->value;

                $this->value = $_value336;
            }

            if ($_success) {
                $subunits = $this->value;
            }
        }

        if ($_success) {
            $_value337[] = $this->value;

            $this->value = $_value337;
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

        $_value338 = array();

        $_success = $this->parseINT();

        if ($_success) {
            $super = $this->value;
        }

        if ($_success) {
            $_value338[] = $this->value;

            $_success = $this->parseOBJECT_LIST_SAFE_STRING();

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value338[] = $this->value;

            $this->value = $_value338;
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

        $_value341 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value341[] = $this->value;

            $_position339 = $this->position;
            $_cut340 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOBJECT_ID_SAFE_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position339;

                $_success = $this->parseQUOTED_STRING();
            }

            $this->cut = $_cut340;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value341[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value341[] = $this->value;

            $this->value = $_value341;
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
            $_value343 = array($this->value);
            $_cut344 = $this->cut;

            while (true) {
                $_position342 = $this->position;

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

                $_value343[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position342;
                $this->value = $_value343;
            }

            $this->cut = $_cut344;
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

        $_value348 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value348[] = $this->value;

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
            $_value348[] = $this->value;

            $_value346 = array();
            $_cut347 = $this->cut;

            while (true) {
                $_position345 = $this->position;

                $this->cut = false;
                $_success = $this->parseOBJECT();

                if (!$_success) {
                    break;
                }

                $_value346[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position345;
                $this->value = $_value346;
            }

            $this->cut = $_cut347;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value348[] = $this->value;

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
            $_value348[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value348[] = $this->value;

            $this->value = $_value348;
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

        $_value351 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value351[] = $this->value;

            $_position349 = $this->position;
            $_cut350 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_FLOAT();

            if (!$_success && !$this->cut) {
                $this->position = $_position349;

                $_success = $this->parseQUOTED_FLOAT();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position349;

                $_success = $this->parseEMPTY_STRING();
            }

            $this->cut = $_cut350;

            if ($_success) {
                $float = $this->value;
            }
        }

        if ($_success) {
            $_value351[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value351[] = $this->value;

            $this->value = $_value351;
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

        $_value352 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value352[] = $this->value;

            $_success = $this->parseRAW_FLOAT();

            if ($_success) {
                $float = $this->value;
            }
        }

        if ($_success) {
            $_value352[] = $this->value;

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
            $_value352[] = $this->value;

            $this->value = $_value352;
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

        $_value358 = array();

        $_success = $this->parseRAW_INT();

        if ($_success) {
            $int = $this->value;
        }

        if ($_success) {
            $_value358[] = $this->value;

            $_position353 = $this->position;
            $_cut354 = $this->cut;

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
                $this->position = $_position353;
                $this->value = null;
            }

            $this->cut = $_cut354;
        }

        if ($_success) {
            $_value358[] = $this->value;

            $_value356 = array();
            $_cut357 = $this->cut;

            while (true) {
                $_position355 = $this->position;

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

                $_value356[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position355;
                $this->value = $_value356;
            }

            $this->cut = $_cut357;

            if ($_success) {
                $trailing = $this->value;
            }
        }

        if ($_success) {
            $_value358[] = $this->value;

            $this->value = $_value358;
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

        $_value361 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value361[] = $this->value;

            $_position359 = $this->position;
            $_cut360 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_INT();

            if (!$_success && !$this->cut) {
                $this->position = $_position359;

                $_success = $this->parseQUOTED_INT();
            }

            $this->cut = $_cut360;

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value361[] = $this->value;

            $_success = $this->parse_();
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

        $_value362 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value362[] = $this->value;

            $_success = $this->parseRAW_INT();

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value362[] = $this->value;

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
            $_value362[] = $this->value;

            $this->value = $_value362;
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

        $_value368 = array();

        $_position363 = $this->position;
        $_cut364 = $this->cut;

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
            $this->position = $_position363;
            $this->value = null;
        }

        $this->cut = $_cut364;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value368[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value366 = array($this->value);
                $_cut367 = $this->cut;

                while (true) {
                    $_position365 = $this->position;

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

                    $_value366[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position365;
                    $this->value = $_value366;
                }

                $this->cut = $_cut367;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value368[] = $this->value;

            $this->value = $_value368;
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

        $_value371 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value371[] = $this->value;

            $_position369 = $this->position;
            $_cut370 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_BOOLEAN();

            if (!$_success && !$this->cut) {
                $this->position = $_position369;

                $_success = $this->parseQUOTED_BOOLEAN();
            }

            $this->cut = $_cut370;

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value371[] = $this->value;

            $_success = $this->parse_();
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

        $_value372 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value372[] = $this->value;

            $_success = $this->parseRAW_BOOLEAN();

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value372[] = $this->value;

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
            $_value372[] = $this->value;

            $this->value = $_value372;
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

        $_value375 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value375[] = $this->value;

            $_position373 = $this->position;
            $_cut374 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position373;

                $_success = $this->parseQUOTED_STRING();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position373;

                $_success = $this->parseEMPTY_STRING();
            }

            $this->cut = $_cut374;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value375[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value375[] = $this->value;

            $this->value = $_value375;
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

        $_value381 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value381[] = $this->value;

            $_value379 = array();
            $_cut380 = $this->cut;

            while (true) {
                $_position378 = $this->position;

                $this->cut = false;
                $_position376 = $this->position;
                $_cut377 = $this->cut;

                $this->cut = false;
                $_success = $this->parseESCAPED_QUOTE();

                if (!$_success && !$this->cut) {
                    $this->position = $_position376;

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
                    $this->position = $_position376;

                    $_success = $this->parseVALID_CHARS();
                }

                $this->cut = $_cut377;

                if (!$_success) {
                    break;
                }

                $_value379[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position378;
                $this->value = $_value379;
            }

            $this->cut = $_cut380;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value381[] = $this->value;

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
            $_value381[] = $this->value;

            $this->value = $_value381;
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
            $_value383 = array($this->value);
            $_cut384 = $this->cut;

            while (true) {
                $_position382 = $this->position;

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

                $_value383[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position382;
                $this->value = $_value383;
            }

            $this->cut = $_cut384;
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

        $_value385 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value385[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value385[] = $this->value;

            $this->value = $_value385;
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

        $_value388 = array();

        $_position386 = $this->position;
        $_cut387 = $this->cut;

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
            $this->position = $_position386;
            $this->value = null;
        }

        $this->cut = $_cut387;

        if ($_success) {
            $_value388[] = $this->value;

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
            $_value388[] = $this->value;

            $this->value = $_value388;
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

        $_value392 = array();
        $_cut393 = $this->cut;

        while (true) {
            $_position391 = $this->position;

            $this->cut = false;
            $_position389 = $this->position;
            $_cut390 = $this->cut;

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
                $this->position = $_position389;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut390;

            if (!$_success) {
                break;
            }

            $_value392[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position391;
            $this->value = $_value392;
        }

        $this->cut = $_cut393;

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