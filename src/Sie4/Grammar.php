<?php

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Transaction;

class Grammar extends DependencyManager
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

        $_value22 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value22[] = $this->value;

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
            $_value22[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value22[] = $this->value;

            $_success = $this->parseBOOLEAN();

            if ($_success) {
                $flag = $this->value;
            }
        }

        if ($_success) {
            $_value22[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value22[] = $this->value;

            $this->value = $_value22;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$flag) {
                $this->getContainer()->setAttribute('FLAGGA', $flag);
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

        $_value23 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value23[] = $this->value;

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
            $_value23[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value23[] = $this->value;

            $this->value = $_value23;
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

        $_value24 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value24[] = $this->value;

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
            $_value24[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $checksum = $this->value;
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
            $this->value = call_user_func(function () use (&$checksum) {
                $this->getContainer()->setAttribute('KSUMMA', $checksum);
                $this->getLogger()->notice('Checksum detected but currently not handled');
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

        $_position25 = $this->position;
        $_cut26 = $this->cut;

        $this->cut = false;
        $_success = $this->parseADRESS_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseBKOD_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseFNAMN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseFNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseFORMAT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseFTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseGEN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseKPTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseOMFATTN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseORGNR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parsePROGRAM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parsePROSA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseRAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseSIETYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseTAXAR_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseVALUTA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position25;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut26;

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

        $_value35 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value35[] = $this->value;

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
            $_value35[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_position27 = $this->position;
            $_cut28 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position27;
                $this->value = null;
            }

            $this->cut = $_cut28;

            if ($_success) {
                $contact = $this->value;
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

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
                $address = $this->value;
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

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
                $location = $this->value;
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

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
                $phone = $this->value;
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value35[] = $this->value;

            $this->value = $_value35;
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

        $_value36 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value36[] = $this->value;

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
            $_value36[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $sni = $this->value;
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
            $this->value = call_user_func(function () use (&$sni) {
                $this->getContainer()->setAttribute('BKOD', $sni);
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

        $_value37 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value37[] = $this->value;

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
            $_value37[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value37[] = $this->value;

            $this->value = $_value37;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                $this->getContainer()->setAttribute('FNAMN', $name);
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

        $_value38 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value38[] = $this->value;

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
            $_value38[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value38[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $id = $this->value;
            }
        }

        if ($_success) {
            $_value38[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value38[] = $this->value;

            $this->value = $_value38;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$id) {
                $this->getContainer()->setAttribute('FNR', $id);
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

        $_value39 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value39[] = $this->value;

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
            $_value39[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $charset = $this->value;
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
            $this->value = call_user_func(function () use (&$charset) {
                if ($charset != 'PC8') {
                    $this->getLogger()->warning("Unknown charset $charset defined using #FORMAT");
                }
                $this->getContainer()->setAttribute('FORMAT', $charset);
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

        $_value40 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value40[] = $this->value;

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
            $_value40[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value40[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $type = $this->value;
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
            $this->value = call_user_func(function () use (&$type) {
                $this->getContainer()->setAttribute('FTYP', $type);
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

        $_value43 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value43[] = $this->value;

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
            $_value43[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value43[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $date = $this->value;
            }
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
                $sign = $this->value;
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
            $this->value = call_user_func(function () use (&$date, &$sign) {
                $this->getContainer()->setAttribute('GEN', [$date, strval($sign)]);
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

        $_value44 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value44[] = $this->value;

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
            $_value44[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value44[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $type = $this->value;
            }
        }

        if ($_success) {
            $_value44[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value44[] = $this->value;

            $this->value = $_value44;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$type) {
                $this->getContainer()->setAttribute('KPTYP', $type);
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

        $_value45 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value45[] = $this->value;

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
            $_value45[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value45[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $date = $this->value;
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
            $this->value = call_user_func(function () use (&$date) {
                $this->getContainer()->setAttribute('OMFATTN', $date);
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

        $_value52 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value52[] = $this->value;

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
            $_value52[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value52[] = $this->value;

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
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value52[] = $this->value;

            $_position48 = $this->position;
            $_cut49 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position48;
                $this->value = null;
            }

            $this->cut = $_cut49;

            if ($_success) {
                $acquisition = $this->value;
            }
        }

        if ($_success) {
            $_value52[] = $this->value;

            $_position50 = $this->position;
            $_cut51 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position50;
                $this->value = null;
            }

            $this->cut = $_cut51;

            if ($_success) {
                $operation = $this->value;
            }
        }

        if ($_success) {
            $_value52[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value52[] = $this->value;

            $this->value = $_value52;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$acquisition, &$operation) {
                $this->getContainer()->setAttribute('ORGNR', [(string)$number, intval($acquisition), intval($operation)]);
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

        $_value53 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value53[] = $this->value;

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
            $_value53[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value53[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $name = $this->value;
            }
        }

        if ($_success) {
            $_value53[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $version = $this->value;
            }
        }

        if ($_success) {
            $_value53[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value53[] = $this->value;

            $this->value = $_value53;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name, &$version) {
                $this->getContainer()->setAttribute('PROGRAM', [$name, $version]);
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

        $_value54 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value54[] = $this->value;

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
            $_value54[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value54[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $text = $this->value;
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
            $this->value = call_user_func(function () use (&$text) {
                $this->getContainer()->setAttribute('PROSA', $text);
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

        $_value59 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value59[] = $this->value;

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
            $_value59[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value59[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
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
                $startDate = $this->value;
            }
        }

        if ($_success) {
            $_value59[] = $this->value;

            $_position57 = $this->position;
            $_cut58 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position57;
                $this->value = null;
            }

            $this->cut = $_cut58;

            if ($_success) {
                $endDate = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$startDate, &$endDate) {
                if (is_null($startDate)) {
                    $this->getLogger()->warning("Accounting year start date not defined");
                }

                if (is_null($endDate)) {
                    $this->getLogger()->warning("Accounting year end date not defined");
                }

                $this->getContainer()->setAttribute("RAR $year", [$startDate, $endDate]);
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

        $_value60 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value60[] = $this->value;

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
            $_value60[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value60[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $ver = $this->value;
            }
        }

        if ($_success) {
            $_value60[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value60[] = $this->value;

            $this->value = $_value60;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$ver) {
                $this->getContainer()->setAttribute('SIETYP', $ver);
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

        $_value61 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value61[] = $this->value;

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
            $_value61[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value61[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
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
            $this->value = call_user_func(function () use (&$year) {
                $this->getContainer()->setAttribute("TAXAR", $year);
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

        $_value62 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value62[] = $this->value;

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
            $_value62[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value62[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $currency = $this->value;
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
            $this->value = call_user_func(function () use (&$currency) {
                $this->getContainer()->setAttribute('VALUTA', $currency);
                $this->getCurrencyBuilder()->setCurrencyClass($currency);
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

        $_position63 = $this->position;
        $_cut64 = $this->cut;

        $this->cut = false;
        $_success = $this->parseKONTO_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseKTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseENHET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseSRU_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseUNDERDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseOBJEKT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position63;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut64;

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

        $_value65 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value65[] = $this->value;

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
            $_value65[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $description = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$description) {
                $this->getAccountBuilder()->addAccount($number, $description);
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

        $_value66 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value66[] = $this->value;

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
            $_value66[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $type = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$type) {
                $this->getAccountBuilder()->setAccountType($number, $type);
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

        $_value67 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value67[] = $this->value;

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
            $_value67[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value67[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value67[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $unit = $this->value;
            }
        }

        if ($_success) {
            $_value67[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value67[] = $this->value;

            $this->value = $_value67;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$unit) {
                $this->getAccountBuilder()->getAccount($account)->setAttribute('unit', $unit);
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

        $_value70 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value70[] = $this->value;

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
            $_value70[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value70[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value70[] = $this->value;

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
                $sru = $this->value;
            }
        }

        if ($_success) {
            $_value70[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value70[] = $this->value;

            $this->value = $_value70;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$sru) {
                if (is_null($sru)) {
                    $this->getLogger()->warning('Sru number not defined');
                }

                $this->getAccountBuilder()->getAccount($account)->setAttribute('sru', $sru ?: 0);
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

        $_value71 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value71[] = $this->value;

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
            $_value71[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value71[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
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
            $this->value = call_user_func(function () use (&$dim, &$desc) {
                $this->getDimensionBuilder()->addDimension((string)$dim, $desc);
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

        $_value72 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value72[] = $this->value;

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
            $_value72[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value72[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $superdim = $this->value;
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
            $this->value = call_user_func(function () use (&$dim, &$desc, &$superdim) {
                $this->getDimensionBuilder()->addDimension((string)$dim, $desc, (string)$superdim);
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

        $_value73 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value73[] = $this->value;

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
            $_value73[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value73[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value73[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value73[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value73[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value73[] = $this->value;

            $this->value = $_value73;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$obj, &$desc) {
                $this->getDimensionBuilder()->addObject((string)$dim, $obj, $desc);
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

        $_position74 = $this->position;
        $_cut75 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIB_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseOIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseOUB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parsePBUDGET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parsePSALDO_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseRES_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseVER_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position74;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut75;

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

        $_value78 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value78[] = $this->value;

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
            $_value78[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_position76 = $this->position;
            $_cut77 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position76;
                $this->value = null;
            }

            $this->cut = $_cut77;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value78[] = $this->value;

            $this->value = $_value78;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                $account->setAttribute("IB $year", [$balance, $quantity ?: 0]);
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

        $_value81 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value81[] = $this->value;

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
            $_value81[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value81[] = $this->value;

            $_position79 = $this->position;
            $_cut80 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position79;
                $this->value = null;
            }

            $this->cut = $_cut80;

            if ($_success) {
                $quantity = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                $account->setAttribute("UB $year", [$balance, $quantity ?: 0]);
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

        $_value84 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value84[] = $this->value;

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
            $_value84[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value84[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value84[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value84[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value84[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value84[] = $this->value;

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
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value84[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value84[] = $this->value;

            $this->value = $_value84;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$objects, &$balance, &$quantity) {
                foreach ($objects as $object) {
                    $object->setAttribute("IB $year", [$balance, $quantity ?: 0]);
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

        $_value87 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value87[] = $this->value;

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
            $_value87[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position85 = $this->position;
            $_cut86 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position85;
                $this->value = null;
            }

            $this->cut = $_cut86;

            if ($_success) {
                $quantity = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$account, &$objects, &$balance, &$quantity) {
                foreach ($objects as $object) {
                    $object->setAttribute("UB $year", [$balance, $quantity ?: 0]);
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

        $_value90 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value90[] = $this->value;

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
            $_value90[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
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
                $quantity = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$objects, &$balance, &$quantity) {
                $key = "PBUDGET $year " . $period->format('Ym');

                $account->setAttribute($key, [$balance, $quantity ?: 0]);

                foreach ($objects as $object) {
                    $object->setAttribute($key, [$balance, $quantity ?: 0]);
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

        $_value93 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value93[] = $this->value;

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
            $_value93[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $period = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value93[] = $this->value;

            $_position91 = $this->position;
            $_cut92 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position91;
                $this->value = null;
            }

            $this->cut = $_cut92;

            if ($_success) {
                $quantity = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$period, &$account, &$objects, &$balance, &$quantity) {
                $key = "PSALDO $year " . $period->format('Ym');

                $account->setAttribute($key, [$balance, $quantity ?: 0]);

                foreach ($objects as $object) {
                    $object->setAttribute($key, [$balance, $quantity ?: 0]);
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

        $_value96 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value96[] = $this->value;

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
            $_value96[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value96[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value96[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value96[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value96[] = $this->value;

            $_position94 = $this->position;
            $_cut95 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position94;
                $this->value = null;
            }

            $this->cut = $_cut95;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value96[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value96[] = $this->value;

            $this->value = $_value96;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                $account->setAttribute("RES $year", [$balance, $quantity ?: 0]);
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

        $_value103 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value103[] = $this->value;

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
            $_value103[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $series = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

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
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_position99 = $this->position;
            $_cut100 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position99;
                $this->value = null;
            }

            $this->cut = $_cut100;

            if ($_success) {
                $regdate = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_position101 = $this->position;
            $_cut102 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position101;
                $this->value = null;
            }

            $this->cut = $_cut102;

            if ($_success) {
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseSUBROW_START();
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseTRANS_LIST();

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value103[] = $this->value;

            $_success = $this->parseSUBROW_END();
        }

        if ($_success) {
            $_value103[] = $this->value;

            $this->value = $_value103;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$series, &$number, &$date, &$desc, &$regdate, &$sign, &$trans) {
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

        $_value107 = array();
        $_cut108 = $this->cut;

        while (true) {
            $_position106 = $this->position;

            $this->cut = false;
            $_position104 = $this->position;
            $_cut105 = $this->cut;

            $this->cut = false;
            $_success = $this->parseTRANS_POST();

            if (!$_success && !$this->cut) {
                $this->position = $_position104;

                $_success = $this->parseBTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position104;

                $_success = $this->parseRTRANS_POST();
            }

            if (!$_success && !$this->cut) {
                $this->position = $_position104;

                $_success = $this->parseUNKNOWN_POST();
            }

            $this->cut = $_cut105;

            if (!$_success) {
                break;
            }

            $_value107[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position106;
            $this->value = $_value107;
        }

        $this->cut = $_cut108;

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

        $_value117 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value117[] = $this->value;

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
            $_value117[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_position109 = $this->position;
            $_cut110 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position109;
                $this->value = null;
            }

            $this->cut = $_cut110;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

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
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

            $_position113 = $this->position;
            $_cut114 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position113;
                $this->value = null;
            }

            $this->cut = $_cut114;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value117[] = $this->value;

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
                $signature = $this->value;
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
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$signature) {
                return $this->getVerificationBuilder()->createTransaction(
                    $account,
                    $objects,
                    $amount,
                    $date ?: null,
                    $desc ?: '',
                    $quantity ?: 0,
                    $signature ?: ''
                );
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

        $_value126 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value126[] = $this->value;

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
            $_value126[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_position118 = $this->position;
            $_cut119 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position118;
                $this->value = null;
            }

            $this->cut = $_cut119;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

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
            $_value126[] = $this->value;

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
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

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
                $signature = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value126[] = $this->value;

            $this->value = $_value126;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$signature) {
                $this->getLogger()->notice('Detected a BTRANS post, removed transactions are not supported yet..');
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

        $_value135 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value135[] = $this->value;

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
            $_value135[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position127 = $this->position;
            $_cut128 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position127;
                $this->value = null;
            }

            $this->cut = $_cut128;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position129 = $this->position;
            $_cut130 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position129;
                $this->value = null;
            }

            $this->cut = $_cut130;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value135[] = $this->value;

            $_position131 = $this->position;
            $_cut132 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position131;
                $this->value = null;
            }

            $this->cut = $_cut132;

            if ($_success) {
                $quantity = $this->value;
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
                $signature = $this->value;
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
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$signature) {
                $this->getLogger()->notice('Detected a RTRANS post, added transactions are not supported yet..');
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

        $_position136 = $this->position;
        $_cut137 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUNKNOWN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseINVALID_LINE();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position136;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut137;

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

        $_value143 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value143[] = $this->value;

            $_position138 = $this->position;
            $_cut139 = $this->cut;

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

            $this->position = $_position138;
            $this->cut = $_cut139;
        }

        if ($_success) {
            $_value143[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $_value141 = array($this->value);
                $_cut142 = $this->cut;

                while (true) {
                    $_position140 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseSTRING();

                    if (!$_success) {
                        break;
                    }

                    $_value141[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position140;
                    $this->value = $_value141;
                }

                $this->cut = $_cut142;
            }

            if ($_success) {
                $fields = $this->value;
            }
        }

        if ($_success) {
            $_value143[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value143[] = $this->value;

            $this->value = $_value143;
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

        $_value149 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value149[] = $this->value;

            $_position144 = $this->position;
            $_cut145 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_LABEL();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position144;
            $this->cut = $_cut145;
        }

        if ($_success) {
            $_value149[] = $this->value;

            $_success = $this->parseVALID_CHARS();

            if ($_success) {
                $label = $this->value;
            }
        }

        if ($_success) {
            $_value149[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value149[] = $this->value;

            $_value147 = array();
            $_cut148 = $this->cut;

            while (true) {
                $_position146 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value147[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position146;
                $this->value = $_value147;
            }

            $this->cut = $_cut148;

            if ($_success) {
                $vars = $this->value;
            }
        }

        if ($_success) {
            $_value149[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value149[] = $this->value;

            $this->value = $_value149;
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

        $_position150 = $this->position;
        $_cut151 = $this->cut;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

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
            $this->position = $_position150;

            if (substr($this->string, $this->position, strlen('VER')) === 'VER') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VER'));
                $this->position += strlen('VER');
            } else {
                $_success = false;

                $this->report($this->position, '\'VER\'');
            }
        }

        $this->cut = $_cut151;

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

        $_value152 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value152[] = $this->value;

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
            $_value152[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value152[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value152[] = $this->value;

            $this->value = $_value152;
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

        $_value155 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value155[] = $this->value;

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
            $_value155[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value155[] = $this->value;

            $_position153 = $this->position;
            $_cut154 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position153;
                $this->value = null;
            }

            $this->cut = $_cut154;
        }

        if ($_success) {
            $_value155[] = $this->value;

            $this->value = $_value155;
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

        $_value159 = array();

        $_value157 = array();
        $_cut158 = $this->cut;

        while (true) {
            $_position156 = $this->position;

            $this->cut = false;
            $_success = $this->parseEMPTY_LINE();

            if (!$_success) {
                break;
            }

            $_value157[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position156;
            $this->value = $_value157;
        }

        $this->cut = $_cut158;

        if ($_success) {
            $_value159[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value159[] = $this->value;

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
            $_value159[] = $this->value;

            $this->value = $_value159;
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

        $_value165 = array();

        $_value161 = array();
        $_cut162 = $this->cut;

        while (true) {
            $_position160 = $this->position;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success) {
                break;
            }

            $_value161[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position160;
            $this->value = $_value161;
        }

        $this->cut = $_cut162;

        if ($_success) {
            $fields = $this->value;
        }

        if ($_success) {
            $_value165[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value165[] = $this->value;

            $_position163 = $this->position;
            $_cut164 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEOL();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position163;
                $this->value = null;
            }

            $this->cut = $_cut164;
        }

        if ($_success) {
            $_value165[] = $this->value;

            $this->value = $_value165;
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

    protected function parseDATE()
    {
        $_position = $this->position;

        if (isset($this->cache['DATE'][$_position])) {
            $_success = $this->cache['DATE'][$_position]['success'];
            $this->position = $this->cache['DATE'][$_position]['position'];
            $this->value = $this->cache['DATE'][$_position]['value'];

            return $_success;
        }

        $_value168 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value168[] = $this->value;

            $_position166 = $this->position;
            $_cut167 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_DATE();

            if (!$_success && !$this->cut) {
                $this->position = $_position166;

                $_success = $this->parseQUOTED_DATE();
            }

            $this->cut = $_cut167;

            if ($_success) {
                $date = $this->value;
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

        $_value169 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value169[] = $this->value;

            $_success = $this->parseRAW_DATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value169[] = $this->value;

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
            $_value169[] = $this->value;

            $this->value = $_value169;
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

        $_value177 = array();

        $_value170 = array();

        if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value170[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value170[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value170[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value170[] = $this->value;

            $this->value = $_value170;
        }

        if ($_success) {
            $year = $this->value;
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position172 = $this->position;
            $_cut173 = $this->cut;

            $this->cut = false;
            $_value171 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value171[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value171[] = $this->value;

                $this->value = $_value171;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position172;
                $this->value = null;
            }

            $this->cut = $_cut173;

            if ($_success) {
                $month = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $_position175 = $this->position;
            $_cut176 = $this->cut;

            $this->cut = false;
            $_value174 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value174[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value174[] = $this->value;

                $this->value = $_value174;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position175;
                $this->value = null;
            }

            $this->cut = $_cut176;

            if ($_success) {
                $day = $this->value;
            }
        }

        if ($_success) {
            $_value177[] = $this->value;

            $this->value = $_value177;
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

        $_value180 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value180[] = $this->value;

            $_position178 = $this->position;
            $_cut179 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_AMOUNT();

            if (!$_success && !$this->cut) {
                $this->position = $_position178;

                $_success = $this->parseQUOTED_AMOUNT();
            }

            $this->cut = $_cut179;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value180[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value180[] = $this->value;

            $this->value = $_value180;
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

        $_value181 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value181[] = $this->value;

            $_success = $this->parseRAW_AMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value181[] = $this->value;

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
            $_value181[] = $this->value;

            $this->value = $_value181;
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

        $_value194 = array();

        $_position182 = $this->position;
        $_cut183 = $this->cut;

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
            $this->position = $_position182;
            $this->value = null;
        }

        $this->cut = $_cut183;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value194[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value185 = array($this->value);
                $_cut186 = $this->cut;

                while (true) {
                    $_position184 = $this->position;

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

                    $_value185[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position184;
                    $this->value = $_value185;
                }

                $this->cut = $_cut186;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value194[] = $this->value;

            $_position187 = $this->position;
            $_cut188 = $this->cut;

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
                $this->position = $_position187;
                $this->value = null;
            }

            $this->cut = $_cut188;
        }

        if ($_success) {
            $_value194[] = $this->value;

            $_value193 = array();

            $_position189 = $this->position;
            $_cut190 = $this->cut;

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
                $this->position = $_position189;
                $this->value = null;
            }

            $this->cut = $_cut190;

            if ($_success) {
                $_value193[] = $this->value;

                $_position191 = $this->position;
                $_cut192 = $this->cut;

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
                    $this->position = $_position191;
                    $this->value = null;
                }

                $this->cut = $_cut192;
            }

            if ($_success) {
                $_value193[] = $this->value;

                $this->value = $_value193;
            }

            if ($_success) {
                $subunits = $this->value;
            }
        }

        if ($_success) {
            $_value194[] = $this->value;

            $this->value = $_value194;
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

        $_value195 = array();

        $_success = $this->parseINT();

        if ($_success) {
            $super = $this->value;
        }

        if ($_success) {
            $_value195[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value195[] = $this->value;

            $this->value = $_value195;
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

    protected function parseOBJECT_LIST()
    {
        $_position = $this->position;

        if (isset($this->cache['OBJECT_LIST'][$_position])) {
            $_success = $this->cache['OBJECT_LIST'][$_position]['success'];
            $this->position = $this->cache['OBJECT_LIST'][$_position]['position'];
            $this->value = $this->cache['OBJECT_LIST'][$_position]['value'];

            return $_success;
        }

        $_value199 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value199[] = $this->value;

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
            $_value199[] = $this->value;

            $_value197 = array();
            $_cut198 = $this->cut;

            while (true) {
                $_position196 = $this->position;

                $this->cut = false;
                $_success = $this->parseOBJECT();

                if (!$_success) {
                    break;
                }

                $_value197[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position196;
                $this->value = $_value197;
            }

            $this->cut = $_cut198;

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value199[] = $this->value;

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
            $_value199[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value199[] = $this->value;

            $this->value = $_value199;
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

    protected function parseINT()
    {
        $_position = $this->position;

        if (isset($this->cache['INT'][$_position])) {
            $_success = $this->cache['INT'][$_position]['success'];
            $this->position = $this->cache['INT'][$_position]['position'];
            $this->value = $this->cache['INT'][$_position]['value'];

            return $_success;
        }

        $_value202 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value202[] = $this->value;

            $_position200 = $this->position;
            $_cut201 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_INT();

            if (!$_success && !$this->cut) {
                $this->position = $_position200;

                $_success = $this->parseQUOTED_INT();
            }

            $this->cut = $_cut201;

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value202[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value202[] = $this->value;

            $this->value = $_value202;
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

        $_value203 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value203[] = $this->value;

            $_success = $this->parseRAW_INT();

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value203[] = $this->value;

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
            $_value203[] = $this->value;

            $this->value = $_value203;
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

        $_value209 = array();

        $_position204 = $this->position;
        $_cut205 = $this->cut;

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
            $this->position = $_position204;
            $this->value = null;
        }

        $this->cut = $_cut205;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value209[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value207 = array($this->value);
                $_cut208 = $this->cut;

                while (true) {
                    $_position206 = $this->position;

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

                    $_value207[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position206;
                    $this->value = $_value207;
                }

                $this->cut = $_cut208;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value209[] = $this->value;

            $this->value = $_value209;
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

        $_value212 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value212[] = $this->value;

            $_position210 = $this->position;
            $_cut211 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_BOOLEAN();

            if (!$_success && !$this->cut) {
                $this->position = $_position210;

                $_success = $this->parseQUOTED_BOOLEAN();
            }

            $this->cut = $_cut211;

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value212[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value212[] = $this->value;

            $this->value = $_value212;
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

        $_value213 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value213[] = $this->value;

            $_success = $this->parseRAW_BOOLEAN();

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value213[] = $this->value;

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
            $_value213[] = $this->value;

            $this->value = $_value213;
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

        $_value216 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value216[] = $this->value;

            $_position214 = $this->position;
            $_cut215 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position214;

                $_success = $this->parseQUOTED_STRING();
            }

            $this->cut = $_cut215;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value216[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value216[] = $this->value;

            $this->value = $_value216;
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

    protected function parseQUOTED_STRING()
    {
        $_position = $this->position;

        if (isset($this->cache['QUOTED_STRING'][$_position])) {
            $_success = $this->cache['QUOTED_STRING'][$_position]['success'];
            $this->position = $this->cache['QUOTED_STRING'][$_position]['position'];
            $this->value = $this->cache['QUOTED_STRING'][$_position]['value'];

            return $_success;
        }

        $_value222 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value222[] = $this->value;

            $_value220 = array();
            $_cut221 = $this->cut;

            while (true) {
                $_position219 = $this->position;

                $this->cut = false;
                $_position217 = $this->position;
                $_cut218 = $this->cut;

                $this->cut = false;
                $_success = $this->parseESCAPED_QUOTE();

                if (!$_success && !$this->cut) {
                    $this->position = $_position217;

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
                    $this->position = $_position217;

                    $_success = $this->parseVALID_CHARS();
                }

                $this->cut = $_cut218;

                if (!$_success) {
                    break;
                }

                $_value220[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position219;
                $this->value = $_value220;
            }

            $this->cut = $_cut221;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value222[] = $this->value;

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
            $_value222[] = $this->value;

            $this->value = $_value222;
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
            $_value224 = array($this->value);
            $_cut225 = $this->cut;

            while (true) {
                $_position223 = $this->position;

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

                $_value224[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position223;
                $this->value = $_value224;
            }

            $this->cut = $_cut225;
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

    protected function parseEMPTY_LINE()
    {
        $_position = $this->position;

        if (isset($this->cache['EMPTY_LINE'][$_position])) {
            $_success = $this->cache['EMPTY_LINE'][$_position]['success'];
            $this->position = $this->cache['EMPTY_LINE'][$_position]['position'];
            $this->value = $this->cache['EMPTY_LINE'][$_position]['value'];

            return $_success;
        }

        $_value226 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value226[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value226[] = $this->value;

            $this->value = $_value226;
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

        $_value229 = array();

        $_position227 = $this->position;
        $_cut228 = $this->cut;

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
            $this->position = $_position227;
            $this->value = null;
        }

        $this->cut = $_cut228;

        if ($_success) {
            $_value229[] = $this->value;

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
            $_value229[] = $this->value;

            $this->value = $_value229;
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

        $_value233 = array();
        $_cut234 = $this->cut;

        while (true) {
            $_position232 = $this->position;

            $this->cut = false;
            $_position230 = $this->position;
            $_cut231 = $this->cut;

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
                $this->position = $_position230;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut231;

            if (!$_success) {
                break;
            }

            $_value233[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position232;
            $this->value = $_value233;
        }

        $this->cut = $_cut234;

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