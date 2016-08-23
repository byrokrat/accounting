<?php

namespace byrokrat\accounting\Sie4;

class Grammar
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseFILE()
    {
        $_position = $this->position;

        if (isset($this->cache['FILE'][$_position])) {
            $_success = $this->cache['FILE'][$_position]['success'];
            $this->position = $this->cache['FILE'][$_position]['position'];
            $this->value = $this->cache['FILE'][$_position]['value'];

            return $_success;
        }

        $_value13 = array();

        $_value2 = array();
        $_cut3 = $this->cut;

        while (true) {
            $_position1 = $this->position;

            $this->cut = false;
            $_success = $this->parseEMPTY_LINE();

            if (!$_success) {
                break;
            }

            $_value2[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position1;
            $this->value = $_value2;
        }

        $this->cut = $_cut3;

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseFLAGGA_POST();
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_value5 = array();
            $_cut6 = $this->cut;

            while (true) {
                $_position4 = $this->position;

                $this->cut = false;
                $_success = $this->parseIDENTIFICATION_POST();

                if (!$_success) {
                    break;
                }

                $_value5[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position4;
                $this->value = $_value5;
            }

            $this->cut = $_cut6;
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_value8 = array();
            $_cut9 = $this->cut;

            while (true) {
                $_position7 = $this->position;

                $this->cut = false;
                $_success = $this->parseACCOUNT_PLAN_POST();

                if (!$_success) {
                    break;
                }

                $_value8[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position7;
                $this->value = $_value8;
            }

            $this->cut = $_cut9;
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_value11 = array();
            $_cut12 = $this->cut;

            while (true) {
                $_position10 = $this->position;

                $this->cut = false;
                $_success = $this->parseBALANCE_POST();

                if (!$_success) {
                    break;
                }

                $_value11[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position10;
                $this->value = $_value11;
            }

            $this->cut = $_cut12;
        }

        if ($_success) {
            $_value13[] = $this->value;

            $this->value = $_value13;
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

    protected function parseFLAGGA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['FLAGGA_POST'][$_position])) {
            $_success = $this->cache['FLAGGA_POST'][$_position]['success'];
            $this->position = $this->cache['FLAGGA_POST'][$_position]['position'];
            $this->value = $this->cache['FLAGGA_POST'][$_position]['value'];

            return $_success;
        }

        $_value14 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value14[] = $this->value;

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
            $_value14[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value14[] = $this->value;

            $_success = $this->parseBOOLEAN();

            if ($_success) {
                $flag = $this->value;
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
            $this->value = call_user_func(function () use (&$flag) {
                $this->onFlagga($flag);
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

    protected function parseIDENTIFICATION_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['IDENTIFICATION_POST'][$_position])) {
            $_success = $this->cache['IDENTIFICATION_POST'][$_position]['success'];
            $this->position = $this->cache['IDENTIFICATION_POST'][$_position]['position'];
            $this->value = $this->cache['IDENTIFICATION_POST'][$_position]['value'];

            return $_success;
        }

        $_position15 = $this->position;
        $_cut16 = $this->cut;

        $this->cut = false;
        $_success = $this->parseADRESS_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position15;

            $_success = $this->parseOMFATTN_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position15;

            $_success = $this->parseSIETYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position15;

            $_success = $this->parseVALUTA_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position15;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut16;

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

        $_value17 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value17[] = $this->value;

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
            $_value17[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $contact = $this->value;
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $address = $this->value;
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $location = $this->value;
            }
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $phone = $this->value;
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
            $this->value = call_user_func(function () use (&$contact, &$address, &$location, &$phone) {
                $this->onAdress((string)$contact, (string)$address, (string)$location, (string)$phone);
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

    protected function parseOMFATTN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OMFATTN_POST'][$_position])) {
            $_success = $this->cache['OMFATTN_POST'][$_position]['success'];
            $this->position = $this->cache['OMFATTN_POST'][$_position]['position'];
            $this->value = $this->cache['OMFATTN_POST'][$_position]['value'];

            return $_success;
        }

        $_value18 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value18[] = $this->value;

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
            $_value18[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value18[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value18[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value18[] = $this->value;

            $this->value = $_value18;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$date) {
                // TODO här funderar jag på om det kan vara en enklare lösning...
                    // finns det något bra sätt att testa detta??
                    // inject Container till Parser i så fall
                    // ska jag använda svenska namn...

                /*
                    Grammar kan extends BuilderAware (eller vad den kan tänkas heta...)
                        som tar de olika builders som argument till constructor...
                        på detta sätt får jag en ingång att testa som jag tror kan bli ganska så bra...

                    Då kan Parser vara en decorator till SieGrammar
                        SieParser

                        $parser = new SieParser(
                            new SieGrammar(new CurrencyBuilder, new AccountBuilder, new DimensionBuilder ...)
                        );

                        // ekvivalent till

                        $parser = new SieParserFactory->createParser();



                    AccountBuilder
                    CurrencyBuilder
                    DimensionBuilder
                    Logger

                    SieGrammar
                    SieDependencyManager
                    SieParser
                    SieParserFactory

                    VerificationBuilder
                 */

                $this->getContainer()->setAttribute('OMFATTN', $date);

                // Det gamla...
                $this->onOmfattn($date);
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

    protected function parseSIETYP_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['SIETYP_POST'][$_position])) {
            $_success = $this->cache['SIETYP_POST'][$_position]['success'];
            $this->position = $this->cache['SIETYP_POST'][$_position]['position'];
            $this->value = $this->cache['SIETYP_POST'][$_position]['value'];

            return $_success;
        }

        $_value19 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value19[] = $this->value;

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
            $_value19[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value19[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $ver = $this->value;
            }
        }

        if ($_success) {
            $_value19[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value19[] = $this->value;

            $this->value = $_value19;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$ver) {
                $this->onSietyp($ver);
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

    protected function parseVALUTA_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VALUTA_POST'][$_position])) {
            $_success = $this->cache['VALUTA_POST'][$_position]['success'];
            $this->position = $this->cache['VALUTA_POST'][$_position]['position'];
            $this->value = $this->cache['VALUTA_POST'][$_position]['value'];

            return $_success;
        }

        $_value20 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value20[] = $this->value;

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
            $_value20[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value20[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $currency = $this->value;
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
            $this->value = call_user_func(function () use (&$currency) {
                $this->onValuta($currency);
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

        $_position21 = $this->position;
        $_cut22 = $this->cut;

        $this->cut = false;
        $_success = $this->parseKONTO_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseKTYP_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseENHET_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseSRU_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseUNDERDIM_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseOBJEKT_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position21;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut22;

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

        $_value23 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value23[] = $this->value;

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
            $_value23[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value23[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value23[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $description = $this->value;
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

        if ($_success) {
            $this->value = call_user_func(function () use (&$number, &$description) {
                $this->onKonto($number, $description);
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

        $_value24 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value24[] = $this->value;

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
            $_value24[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $type = $this->value;
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
            $this->value = call_user_func(function () use (&$number, &$type) {
                $this->onKtyp($number, $type);
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

        $_value25 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value25[] = $this->value;

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
            $_value25[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $unit = $this->value;
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
            $this->value = call_user_func(function () use (&$account, &$unit) {
                $this->onEnhet($account, $unit);
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

        $_value26 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value26[] = $this->value;

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
            $_value26[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $sru = $this->value;
            }
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value26[] = $this->value;

            $this->value = $_value26;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$sru) {
                $this->onSru($account, $sru);
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

        $_value27 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value27[] = $this->value;

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
            $_value27[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value27[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value27[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value27[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value27[] = $this->value;

            $this->value = $_value27;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$desc) {
                $this->onDim($dim, $desc);
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

        $_value28 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value28[] = $this->value;

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
            $_value28[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $superdim = $this->value;
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
            $this->value = call_user_func(function () use (&$dim, &$desc, &$superdim) {
                $this->onUnderdim($dim, $desc, $superdim);
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

        $_value29 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value29[] = $this->value;

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
            $_value29[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $dim = $this->value;
            }
        }

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $obj = $this->value;
            }
        }

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value29[] = $this->value;

            $this->value = $_value29;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$dim, &$obj, &$desc) {
                $this->onObjekt($dim, $obj, $desc);
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

        $_position30 = $this->position;
        $_cut31 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIB_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position30;

            $_success = $this->parseOIB_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position30;

            $_success = $this->parseVER_POST();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position30;

            $_success = $this->parseVOID_ROW();
        }

        $this->cut = $_cut31;

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

        $_value34 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value34[] = $this->value;

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
            $_value34[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_position32 = $this->position;
            $_cut33 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position32;
                $this->value = null;
            }

            $this->cut = $_cut33;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value34[] = $this->value;

            $this->value = $_value34;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$year, &$account, &$balance, &$quantity) {
                $this->onIb($year, $account, $balance, $quantity ?: 0);
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

    protected function parseOIB_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['OIB_POST'][$_position])) {
            $_success = $this->cache['OIB_POST'][$_position]['success'];
            $this->position = $this->cache['OIB_POST'][$_position]['position'];
            $this->value = $this->cache['OIB_POST'][$_position]['value'];

            return $_success;
        }

        $_value37 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value37[] = $this->value;

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
            $_value37[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $year = $this->value;
            }
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $balance = $this->value;
            }
        }

        if ($_success) {
            $_value37[] = $this->value;

            $_position35 = $this->position;
            $_cut36 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position35;
                $this->value = null;
            }

            $this->cut = $_cut36;

            if ($_success) {
                $quantity = $this->value;
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
            $this->value = call_user_func(function () use (&$year, &$account, &$objects, &$balance, &$quantity) {
                $this->onOib($year, $account, $objects, $balance, $quantity ?: 0);
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

    protected function parseTRANS_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['TRANS_POST'][$_position])) {
            $_success = $this->cache['TRANS_POST'][$_position]['success'];
            $this->position = $this->cache['TRANS_POST'][$_position]['position'];
            $this->value = $this->cache['TRANS_POST'][$_position]['value'];

            return $_success;
        }

        $_value46 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value46[] = $this->value;

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
            $_value46[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_success = $this->parseACCOUNT();

            if ($_success) {
                $account = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_success = $this->parseOBJECT_LIST();

            if ($_success) {
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_success = $this->parseAMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_position38 = $this->position;
            $_cut39 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position38;
                $this->value = null;
            }

            $this->cut = $_cut39;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_position40 = $this->position;
            $_cut41 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position40;
                $this->value = null;
            }

            $this->cut = $_cut41;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_position42 = $this->position;
            $_cut43 = $this->cut;

            $this->cut = false;
            $_success = $this->parseINT();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position42;
                $this->value = null;
            }

            $this->cut = $_cut43;

            if ($_success) {
                $quantity = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_position44 = $this->position;
            $_cut45 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position44;
                $this->value = null;
            }

            $this->cut = $_cut45;

            if ($_success) {
                $signature = $this->value;
            }
        }

        if ($_success) {
            $_value46[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value46[] = $this->value;

            $this->value = $_value46;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$account, &$objects, &$amount, &$date, &$desc, &$quantity, &$signature) {
                // TODO hur fungerar det egentligen med optional arguments här??
                return $this->onTrans($account, $objects, $amount, $date, $desc, $quantity, $signature);
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

    protected function parseVER_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['VER_POST'][$_position])) {
            $_success = $this->cache['VER_POST'][$_position]['success'];
            $this->position = $this->cache['VER_POST'][$_position]['position'];
            $this->value = $this->cache['VER_POST'][$_position]['value'];

            return $_success;
        }

        $_value58 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value58[] = $this->value;

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
            $_value58[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseSTRING();

            if ($_success) {
                $series = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseDATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_position47 = $this->position;
            $_cut48 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position47;
                $this->value = null;
            }

            $this->cut = $_cut48;

            if ($_success) {
                $desc = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_position49 = $this->position;
            $_cut50 = $this->cut;

            $this->cut = false;
            $_success = $this->parseDATE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position49;
                $this->value = null;
            }

            $this->cut = $_cut50;

            if ($_success) {
                $regdate = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

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
                $sign = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseROW_END();
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseSUBROW_START();
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_value56 = array();
            $_cut57 = $this->cut;

            while (true) {
                $_position55 = $this->position;

                $this->cut = false;
                $_position53 = $this->position;
                $_cut54 = $this->cut;

                $this->cut = false;
                $_success = $this->parseTRANS_POST();

                if (!$_success && !$this->cut) {
                    $this->position = $_position53;

                    $_success = $this->parseVOID_ROW();
                }

                $this->cut = $_cut54;

                if (!$_success) {
                    break;
                }

                $_value56[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position55;
                $this->value = $_value56;
            }

            $this->cut = $_cut57;

            if ($_success) {
                $trans = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseSUBROW_END();
        }

        if ($_success) {
            $_value58[] = $this->value;

            $this->value = $_value58;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$series, &$number, &$date, &$desc, &$regdate, &$sign, &$trans) {
                // TODO hur fungerar det egentligen med optional arguments här??
                return $this->onVer($series, $number, $date, $desc, $regdate, $sign, $trans);
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

    protected function parseVOID_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['VOID_ROW'][$_position])) {
            $_success = $this->cache['VOID_ROW'][$_position]['success'];
            $this->position = $this->cache['VOID_ROW'][$_position]['position'];
            $this->value = $this->cache['VOID_ROW'][$_position]['value'];

            return $_success;
        }

        $_position59 = $this->position;
        $_cut60 = $this->cut;

        $this->cut = false;
        $_success = $this->parseUNKNOWN_POST();

        if (!$_success && !$this->cut) {
            $this->position = $_position59;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut60;

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

    protected function parseUNKNOWN_POST()
    {
        $_position = $this->position;

        if (isset($this->cache['UNKNOWN_POST'][$_position])) {
            $_success = $this->cache['UNKNOWN_POST'][$_position]['success'];
            $this->position = $this->cache['UNKNOWN_POST'][$_position]['position'];
            $this->value = $this->cache['UNKNOWN_POST'][$_position]['value'];

            return $_success;
        }

        $_value66 = array();

        $_success = $this->parseROW_START();

        if ($_success) {
            $_value66[] = $this->value;

            $_position61 = $this->position;
            $_cut62 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_LABEL();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position61;
            $this->cut = $_cut62;
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = $this->parseVALID_CHARS();

            if ($_success) {
                $label = $this->value;
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

            $_value64 = array();
            $_cut65 = $this->cut;

            while (true) {
                $_position63 = $this->position;

                $this->cut = false;
                $_success = $this->parseSTRING();

                if (!$_success) {
                    break;
                }

                $_value64[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position63;
                $this->value = $_value64;
            }

            $this->cut = $_cut65;

            if ($_success) {
                $vars = $this->value;
            }
        }

        if ($_success) {
            $_value66[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value66[] = $this->value;

            $this->value = $_value66;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$label, &$vars) {
                $this->onUnknown($label, $vars);
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

        $_position67 = $this->position;
        $_cut68 = $this->cut;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

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
            $this->position = $_position67;

            if (substr($this->string, $this->position, strlen('VER')) === 'VER') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('VER'));
                $this->position += strlen('VER');
            } else {
                $_success = false;

                $this->report($this->position, '\'VER\'');
            }
        }

        $this->cut = $_cut68;

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

        $_value69 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value69[] = $this->value;

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
            $_value69[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value69[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value69[] = $this->value;

            $this->value = $_value69;
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

        $_value70 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value70[] = $this->value;

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
            $_value70[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value70[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value70[] = $this->value;

            $this->value = $_value70;
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

        $_value71 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value71[] = $this->value;

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
            $_value71[] = $this->value;

            $this->value = $_value71;
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

        $_value75 = array();

        $_value73 = array();
        $_cut74 = $this->cut;

        while (true) {
            $_position72 = $this->position;

            $this->cut = false;
            $_success = $this->parseSTRING();

            if (!$_success) {
                break;
            }

            $_value73[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position72;
            $this->value = $_value73;
        }

        $this->cut = $_cut74;

        if ($_success) {
            $_value75[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value75[] = $this->value;

            $this->value = $_value75;
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

        $_value78 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value78[] = $this->value;

            $_position76 = $this->position;
            $_cut77 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_DATE();

            if (!$_success && !$this->cut) {
                $this->position = $_position76;

                $_success = $this->parseQUOTED_DATE();
            }

            $this->cut = $_cut77;

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value78[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value78[] = $this->value;

            $this->value = $_value78;
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

        $_value79 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value79[] = $this->value;

            $_success = $this->parseRAW_DATE();

            if ($_success) {
                $date = $this->value;
            }
        }

        if ($_success) {
            $_value79[] = $this->value;

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
            $_value79[] = $this->value;

            $this->value = $_value79;
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

        $_value87 = array();

        $_value80 = array();

        if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value80[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value80[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value80[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value80[] = $this->value;

            $this->value = $_value80;
        }

        if ($_success) {
            $year = $this->value;
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position82 = $this->position;
            $_cut83 = $this->cut;

            $this->cut = false;
            $_value81 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value81[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value81[] = $this->value;

                $this->value = $_value81;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position82;
                $this->value = null;
            }

            $this->cut = $_cut83;

            if ($_success) {
                $month = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $_position85 = $this->position;
            $_cut86 = $this->cut;

            $this->cut = false;
            $_value84 = array();

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value84[] = $this->value;

                if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value84[] = $this->value;

                $this->value = $_value84;
            }

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position85;
                $this->value = null;
            }

            $this->cut = $_cut86;

            if ($_success) {
                $day = $this->value;
            }
        }

        if ($_success) {
            $_value87[] = $this->value;

            $this->value = $_value87;
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

        $_value90 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value90[] = $this->value;

            $_position88 = $this->position;
            $_cut89 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_AMOUNT();

            if (!$_success && !$this->cut) {
                $this->position = $_position88;

                $_success = $this->parseQUOTED_AMOUNT();
            }

            $this->cut = $_cut89;

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value90[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value90[] = $this->value;

            $this->value = $_value90;
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

        $_value91 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value91[] = $this->value;

            $_success = $this->parseRAW_AMOUNT();

            if ($_success) {
                $amount = $this->value;
            }
        }

        if ($_success) {
            $_value91[] = $this->value;

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
            $_value91[] = $this->value;

            $this->value = $_value91;
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

        $_value104 = array();

        $_position92 = $this->position;
        $_cut93 = $this->cut;

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
            $this->position = $_position92;
            $this->value = null;
        }

        $this->cut = $_cut93;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value104[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value95 = array($this->value);
                $_cut96 = $this->cut;

                while (true) {
                    $_position94 = $this->position;

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

                    $_value95[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position94;
                    $this->value = $_value95;
                }

                $this->cut = $_cut96;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value104[] = $this->value;

            $_position97 = $this->position;
            $_cut98 = $this->cut;

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
                $this->position = $_position97;
                $this->value = null;
            }

            $this->cut = $_cut98;
        }

        if ($_success) {
            $_value104[] = $this->value;

            $_value103 = array();

            $_position99 = $this->position;
            $_cut100 = $this->cut;

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
                $this->position = $_position99;
                $this->value = null;
            }

            $this->cut = $_cut100;

            if ($_success) {
                $_value103[] = $this->value;

                $_position101 = $this->position;
                $_cut102 = $this->cut;

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
                    $this->position = $_position101;
                    $this->value = null;
                }

                $this->cut = $_cut102;
            }

            if ($_success) {
                $_value103[] = $this->value;

                $this->value = $_value103;
            }

            if ($_success) {
                $subunits = $this->value;
            }
        }

        if ($_success) {
            $_value104[] = $this->value;

            $this->value = $_value104;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$negation, &$units, &$subunits) {
                return $this->createMoney($negation.implode($units).'.'.implode($subunits));
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

        $_success = $this->parseINT();

        if ($_success) {
            $number = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$number) {
                return $this->getAccount($number);
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

        $_value105 = array();

        $_success = $this->parseINT();

        if ($_success) {
            $super = $this->value;
        }

        if ($_success) {
            $_value105[] = $this->value;

            $_success = $this->parseINT();

            if ($_success) {
                $number = $this->value;
            }
        }

        if ($_success) {
            $_value105[] = $this->value;

            $this->value = $_value105;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$super, &$number) {
                return $this->getObject($super, $number);
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

        $_value109 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value109[] = $this->value;

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
            $_value109[] = $this->value;

            $_value107 = array();
            $_cut108 = $this->cut;

            while (true) {
                $_position106 = $this->position;

                $this->cut = false;
                $_success = $this->parseOBJECT();

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
                $objects = $this->value;
            }
        }

        if ($_success) {
            $_value109[] = $this->value;

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
            $_value109[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value109[] = $this->value;

            $this->value = $_value109;
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

        $_value112 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value112[] = $this->value;

            $_position110 = $this->position;
            $_cut111 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_INT();

            if (!$_success && !$this->cut) {
                $this->position = $_position110;

                $_success = $this->parseQUOTED_INT();
            }

            $this->cut = $_cut111;

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value112[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value112[] = $this->value;

            $this->value = $_value112;
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

        $_value113 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value113[] = $this->value;

            $_success = $this->parseRAW_INT();

            if ($_success) {
                $int = $this->value;
            }
        }

        if ($_success) {
            $_value113[] = $this->value;

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
            $_value113[] = $this->value;

            $this->value = $_value113;
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

        $_value119 = array();

        $_position114 = $this->position;
        $_cut115 = $this->cut;

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
            $this->position = $_position114;
            $this->value = null;
        }

        $this->cut = $_cut115;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value119[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value117 = array($this->value);
                $_cut118 = $this->cut;

                while (true) {
                    $_position116 = $this->position;

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

                    $_value117[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position116;
                    $this->value = $_value117;
                }

                $this->cut = $_cut118;
            }

            if ($_success) {
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value119[] = $this->value;

            $this->value = $_value119;
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

        $_value122 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value122[] = $this->value;

            $_position120 = $this->position;
            $_cut121 = $this->cut;

            $this->cut = false;
            $_success = $this->parseRAW_BOOLEAN();

            if (!$_success && !$this->cut) {
                $this->position = $_position120;

                $_success = $this->parseQUOTED_BOOLEAN();
            }

            $this->cut = $_cut121;

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value122[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value122[] = $this->value;

            $this->value = $_value122;
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

        $_value123 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value123[] = $this->value;

            $_success = $this->parseRAW_BOOLEAN();

            if ($_success) {
                $bool = $this->value;
            }
        }

        if ($_success) {
            $_value123[] = $this->value;

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
            $_value123[] = $this->value;

            $this->value = $_value123;
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

        $_value126 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value126[] = $this->value;

            $_position124 = $this->position;
            $_cut125 = $this->cut;

            $this->cut = false;
            $_success = $this->parseVALID_CHARS();

            if (!$_success && !$this->cut) {
                $this->position = $_position124;

                $_success = $this->parseQUOTED_STRING();
            }

            $this->cut = $_cut125;

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value126[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value126[] = $this->value;

            $this->value = $_value126;
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

        $_value132 = array();

        if (substr($this->string, $this->position, strlen('"')) === '"') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('"'));
            $this->position += strlen('"');
        } else {
            $_success = false;

            $this->report($this->position, '\'"\'');
        }

        if ($_success) {
            $_value132[] = $this->value;

            $_position127 = $this->position;
            $_cut128 = $this->cut;

            $this->cut = false;
            $_success = $this->parseESCAPED_QUOTE();

            if (!$_success && !$this->cut) {
                $this->position = $_position127;

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
                $this->position = $_position127;

                $_success = $this->parseVALID_CHARS();
            }

            $this->cut = $_cut128;

            if ($_success) {
                $_value130 = array($this->value);
                $_cut131 = $this->cut;

                while (true) {
                    $_position129 = $this->position;

                    $this->cut = false;
                    $_position127 = $this->position;
                    $_cut128 = $this->cut;

                    $this->cut = false;
                    $_success = $this->parseESCAPED_QUOTE();

                    if (!$_success && !$this->cut) {
                        $this->position = $_position127;

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
                        $this->position = $_position127;

                        $_success = $this->parseVALID_CHARS();
                    }

                    $this->cut = $_cut128;

                    if (!$_success) {
                        break;
                    }

                    $_value130[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position129;
                    $this->value = $_value130;
                }

                $this->cut = $_cut131;
            }

            if ($_success) {
                $string = $this->value;
            }
        }

        if ($_success) {
            $_value132[] = $this->value;

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
            $_value132[] = $this->value;

            $this->value = $_value132;
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

        if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`{|}~]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value134 = array($this->value);
            $_cut135 = $this->cut;

            while (true) {
                $_position133 = $this->position;

                $this->cut = false;
                if (preg_match('/^[a-zA-Z0-9!#$%&\'()*+,-.\\/:;<=>?@\\[\\\\\\]^_`{|}~]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value134[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position133;
                $this->value = $_value134;
            }

            $this->cut = $_cut135;
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

        $_value136 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value136[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value136[] = $this->value;

            $this->value = $_value136;
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

        $_value139 = array();

        $_position137 = $this->position;
        $_cut138 = $this->cut;

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
            $this->position = $_position137;
            $this->value = null;
        }

        $this->cut = $_cut138;

        if ($_success) {
            $_value139[] = $this->value;

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
            $_value139[] = $this->value;

            $this->value = $_value139;
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

        $_value143 = array();
        $_cut144 = $this->cut;

        while (true) {
            $_position142 = $this->position;

            $this->cut = false;
            $_position140 = $this->position;
            $_cut141 = $this->cut;

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
                $this->position = $_position140;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut141;

            if (!$_success) {
                break;
            }

            $_value143[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position142;
            $this->value = $_value143;
        }

        $this->cut = $_cut144;

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