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

        $_value2 = array();
        $_cut3 = $this->cut;

        while (true) {
            $_position1 = $this->position;

            $this->cut = false;
            $_success = $this->parseROW();

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

    protected function parseROW()
    {
        $_position = $this->position;

        if (isset($this->cache['ROW'][$_position])) {
            $_success = $this->cache['ROW'][$_position]['success'];
            $this->position = $this->cache['ROW'][$_position]['position'];
            $this->value = $this->cache['ROW'][$_position]['value'];

            return $_success;
        }

        $_position4 = $this->position;
        $_cut5 = $this->cut;

        $this->cut = false;
        $_success = $this->parseADRESS_ROW();

        if (!$_success && !$this->cut) {
            $this->position = $_position4;

            $_success = $this->parseUNKNOWN_ROW();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position4;

            $_success = $this->parseEMPTY_LINE();
        }

        $this->cut = $_cut5;

        $this->cache['ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ROW');
        }

        return $_success;
    }

    protected function parseUNKNOWN_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['UNKNOWN_ROW'][$_position])) {
            $_success = $this->cache['UNKNOWN_ROW'][$_position]['success'];
            $this->position = $this->cache['UNKNOWN_ROW'][$_position]['position'];
            $this->value = $this->cache['UNKNOWN_ROW'][$_position]['value'];

            return $_success;
        }

        $_value12 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value12[] = $this->value;

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
            $_value12[] = $this->value;

            if (preg_match('/^[a-zA-Z]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value7 = array($this->value);
                $_cut8 = $this->cut;

                while (true) {
                    $_position6 = $this->position;

                    $this->cut = false;
                    if (preg_match('/^[a-zA-Z]$/', substr($this->string, $this->position, 1))) {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, 1);
                        $this->position += 1;
                    } else {
                        $_success = false;
                    }

                    if (!$_success) {
                        break;
                    }

                    $_value7[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position6;
                    $this->value = $_value7;
                }

                $this->cut = $_cut8;
            }

            if ($_success) {
                $label = $this->value;
            }
        }

        if ($_success) {
            $_value12[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $_value10 = array($this->value);
                $_cut11 = $this->cut;

                while (true) {
                    $_position9 = $this->position;

                    $this->cut = false;
                    $_success = $this->parseFIELD();

                    if (!$_success) {
                        break;
                    }

                    $_value10[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position9;
                    $this->value = $_value10;
                }

                $this->cut = $_cut11;
            }

            if ($_success) {
                $fields = $this->value;
            }
        }

        if ($_success) {
            $_value12[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value12[] = $this->value;

            $this->value = $_value12;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$label, &$fields) {
                return $this->onUnknown(implode($label), $fields);
            });
        }

        $this->cache['UNKNOWN_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UNKNOWN_ROW');
        }

        return $_success;
    }

    protected function parseADRESS_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['ADRESS_ROW'][$_position])) {
            $_success = $this->cache['ADRESS_ROW'][$_position]['success'];
            $this->position = $this->cache['ADRESS_ROW'][$_position]['position'];
            $this->value = $this->cache['ADRESS_ROW'][$_position]['value'];

            return $_success;
        }

        $_value13 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value13[] = $this->value;

            if (substr($this->string, $this->position, strlen('#ADRESS')) === '#ADRESS') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('#ADRESS'));
                $this->position += strlen('#ADRESS');
            } else {
                $_success = false;

                $this->report($this->position, '\'#ADRESS\'');
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $kontakt = $this->value;
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $utdelningsadr = $this->value;
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $postadr = $this->value;
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $tel = $this->value;
            }
        }

        if ($_success) {
            $_value13[] = $this->value;

            $_success = $this->parseEND_OF_ROW();
        }

        if ($_success) {
            $_value13[] = $this->value;

            $this->value = $_value13;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$kontakt, &$utdelningsadr, &$postadr, &$tel) {
                return $this->onAdress((string)$kontakt, (string)$utdelningsadr, (string)$postadr, (string)$tel);
            });
        }

        $this->cache['ADRESS_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ADRESS_ROW');
        }

        return $_success;
    }

    protected function parseFLAG_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['FLAG_ROW'][$_position])) {
            $_success = $this->cache['FLAG_ROW'][$_position]['success'];
            $this->position = $this->cache['FLAG_ROW'][$_position]['position'];
            $this->value = $this->cache['FLAG_ROW'][$_position]['value'];

            return $_success;
        }

        $_value14 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value14[] = $this->value;

            if (substr($this->string, $this->position, strlen('#FLAGGA')) === '#FLAGGA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('#FLAGGA'));
                $this->position += strlen('#FLAGGA');
            } else {
                $_success = false;

                $this->report($this->position, '\'#FLAGGA\'');
            }
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

            $_success = $this->parseEND_OF_ROW();
        }

        if ($_success) {
            $_value14[] = $this->value;

            $this->value = $_value14;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$flag) {
                // TODO BOOLEAN should be a kind of field, see amount below..
                // TODO possibly implement as a trait, see Helper/CurrencyBuilder
                return $this->onFlag($flag);
            });
        }

        $this->cache['FLAG_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FLAG_ROW');
        }

        return $_success;
    }

    protected function parseCURRENCY_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['CURRENCY_ROW'][$_position])) {
            $_success = $this->cache['CURRENCY_ROW'][$_position]['success'];
            $this->position = $this->cache['CURRENCY_ROW'][$_position]['position'];
            $this->value = $this->cache['CURRENCY_ROW'][$_position]['value'];

            return $_success;
        }

        $_value15 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value15[] = $this->value;

            if (substr($this->string, $this->position, strlen('#VALUTA')) === '#VALUTA') {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen('#VALUTA'));
                $this->position += strlen('#VALUTA');
            } else {
                $_success = false;

                $this->report($this->position, '\'#VALUTA\'');
            }
        }

        if ($_success) {
            $_value15[] = $this->value;

            $_success = $this->parseFIELD();

            if ($_success) {
                $iso4217 = $this->value;
            }
        }

        if ($_success) {
            $_value15[] = $this->value;

            $_success = $this->parseEND_OF_ROW();
        }

        if ($_success) {
            $_value15[] = $this->value;

            $this->value = $_value15;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$iso4217) {
                return $this->onCurrency($iso4217);
            });
        }

        $this->cache['CURRENCY_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CURRENCY_ROW');
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

        $_value28 = array();

        $_position16 = $this->position;
        $_cut17 = $this->cut;

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
            $this->position = $_position16;
            $this->value = null;
        }

        $this->cut = $_cut17;

        if ($_success) {
            $negation = $this->value;
        }

        if ($_success) {
            $_value28[] = $this->value;

            if (preg_match('/^[0-9]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if ($_success) {
                $_value19 = array($this->value);
                $_cut20 = $this->cut;

                while (true) {
                    $_position18 = $this->position;

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
                $units = $this->value;
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_position21 = $this->position;
            $_cut22 = $this->cut;

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
                $this->position = $_position21;
                $this->value = null;
            }

            $this->cut = $_cut22;
        }

        if ($_success) {
            $_value28[] = $this->value;

            $_value27 = array();

            $_position23 = $this->position;
            $_cut24 = $this->cut;

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
                $this->position = $_position23;
                $this->value = null;
            }

            $this->cut = $_cut24;

            if ($_success) {
                $_value27[] = $this->value;

                $_position25 = $this->position;
                $_cut26 = $this->cut;

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
                    $this->position = $_position25;
                    $this->value = null;
                }

                $this->cut = $_cut26;
            }

            if ($_success) {
                $_value27[] = $this->value;

                $this->value = $_value27;
            }

            if ($_success) {
                $subunits = $this->value;
            }
        }

        if ($_success) {
            $_value28[] = $this->value;

            $this->value = $_value28;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$negation, &$units, &$subunits) {
                // TODO amount should be a kind of field (meaning support for SPACE delimiters and quotes...)
                // TODO a lot of testing needed: - optional, decimal delimiter optional, subunits 0-2 chars
                return $this->onAmount($negation.implode($units).'.'.implode($subunits));
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

    protected function parseFIELD()
    {
        $_position = $this->position;

        if (isset($this->cache['FIELD'][$_position])) {
            $_success = $this->cache['FIELD'][$_position]['success'];
            $this->position = $this->cache['FIELD'][$_position]['position'];
            $this->value = $this->cache['FIELD'][$_position]['value'];

            return $_success;
        }

        $_value29 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseCONTENT();

            if ($_success) {
                $content = $this->value;
            }
        }

        if ($_success) {
            $_value29[] = $this->value;

            $_success = $this->parseSPACE();
        }

        if ($_success) {
            $_value29[] = $this->value;

            $this->value = $_value29;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$content) {
                return implode($content);
            });
        }

        $this->cache['FIELD'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FIELD');
        }

        return $_success;
    }

    protected function parseCONTENT()
    {
        $_position = $this->position;

        if (isset($this->cache['CONTENT'][$_position])) {
            $_success = $this->cache['CONTENT'][$_position]['success'];
            $this->position = $this->cache['CONTENT'][$_position]['position'];
            $this->value = $this->cache['CONTENT'][$_position]['value'];

            return $_success;
        }

        if (preg_match('/^[a-zA-Z0-9]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value31 = array($this->value);
            $_cut32 = $this->cut;

            while (true) {
                $_position30 = $this->position;

                $this->cut = false;
                if (preg_match('/^[a-zA-Z0-9]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value31[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position30;
                $this->value = $_value31;
            }

            $this->cut = $_cut32;
        }

        $this->cache['CONTENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CONTENT');
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

        $_value33 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value33[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value33[] = $this->value;

            $this->value = $_value33;
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

    protected function parseEND_OF_ROW()
    {
        $_position = $this->position;

        if (isset($this->cache['END_OF_ROW'][$_position])) {
            $_success = $this->cache['END_OF_ROW'][$_position]['success'];
            $this->position = $this->cache['END_OF_ROW'][$_position]['position'];
            $this->value = $this->cache['END_OF_ROW'][$_position]['value'];

            return $_success;
        }

        $_value37 = array();

        $_value35 = array();
        $_cut36 = $this->cut;

        while (true) {
            $_position34 = $this->position;

            $this->cut = false;
            $_success = $this->parseFIELD();

            if (!$_success) {
                break;
            }

            $_value35[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position34;
            $this->value = $_value35;
        }

        $this->cut = $_cut36;

        if ($_success) {
            $_value37[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value37[] = $this->value;

            $this->value = $_value37;
        }

        $this->cache['END_OF_ROW'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'END_OF_ROW');
        }

        return $_success;
    }

    protected function parseSPACE()
    {
        $_position = $this->position;

        if (isset($this->cache['SPACE'][$_position])) {
            $_success = $this->cache['SPACE'][$_position]['success'];
            $this->position = $this->cache['SPACE'][$_position]['position'];
            $this->value = $this->cache['SPACE'][$_position]['value'];

            return $_success;
        }

        $_value41 = array();
        $_cut42 = $this->cut;

        while (true) {
            $_position40 = $this->position;

            $this->cut = false;
            $_position38 = $this->position;
            $_cut39 = $this->cut;

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
                $this->position = $_position38;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut39;

            if (!$_success) {
                break;
            }

            $_value41[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position40;
            $this->value = $_value41;
        }

        $this->cut = $_cut42;

        $this->cache['SPACE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SPACE');
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

        $_value45 = array();

        $_position43 = $this->position;
        $_cut44 = $this->cut;

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
            $this->position = $_position43;
            $this->value = null;
        }

        $this->cut = $_cut44;

        if ($_success) {
            $_value45[] = $this->value;

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
            $_value45[] = $this->value;

            $this->value = $_value45;
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