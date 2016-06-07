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
        $_success = $this->parseADRESS();

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

        if (substr($this->string, $this->position, strlen('#')) === '#') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('#'));
            $this->position += strlen('#');
        } else {
            $_success = false;

            $this->report($this->position, '\'#\'');
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

    protected function parseADRESS()
    {
        $_position = $this->position;

        if (isset($this->cache['ADRESS'][$_position])) {
            $_success = $this->cache['ADRESS'][$_position]['success'];
            $this->position = $this->cache['ADRESS'][$_position]['position'];
            $this->value = $this->cache['ADRESS'][$_position]['value'];

            return $_success;
        }

        $_value21 = array();

        if (substr($this->string, $this->position, strlen('#ADRESS')) === '#ADRESS') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('#ADRESS'));
            $this->position += strlen('#ADRESS');
        } else {
            $_success = false;

            $this->report($this->position, '\'#ADRESS\'');
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_position13 = $this->position;
            $_cut14 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFIELD();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position13;
                $this->value = null;
            }

            $this->cut = $_cut14;

            if ($_success) {
                $kontakt = $this->value;
            }
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_position15 = $this->position;
            $_cut16 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFIELD();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position15;
                $this->value = null;
            }

            $this->cut = $_cut16;

            if ($_success) {
                $utdelningsadr = $this->value;
            }
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_position17 = $this->position;
            $_cut18 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFIELD();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position17;
                $this->value = null;
            }

            $this->cut = $_cut18;

            if ($_success) {
                $postadr = $this->value;
            }
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_position19 = $this->position;
            $_cut20 = $this->cut;

            $this->cut = false;
            $_success = $this->parseFIELD();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position19;
                $this->value = null;
            }

            $this->cut = $_cut20;

            if ($_success) {
                $tel = $this->value;
            }
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_success = $this->parseEND_OF_FIELD();
        }

        if ($_success) {
            $_value21[] = $this->value;

            $this->value = $_value21;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$kontakt, &$utdelningsadr, &$postadr, &$tel) {
                return $this->onAdress((string)$kontakt, (string)$utdelningsadr, (string)$postadr, (string)$tel);
            });
        }

        $this->cache['ADRESS'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ADRESS');
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

        $_value22 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value22[] = $this->value;

            $_success = $this->parseCONTENT();

            if ($_success) {
                $content = $this->value;
            }
        }

        if ($_success) {
            $_value22[] = $this->value;

            $_success = $this->parseSPACE();
        }

        if ($_success) {
            $_value22[] = $this->value;

            $this->value = $_value22;
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
            $_value24 = array($this->value);
            $_cut25 = $this->cut;

            while (true) {
                $_position23 = $this->position;

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

                $_value24[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position23;
                $this->value = $_value24;
            }

            $this->cut = $_cut25;
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

    protected function parseSPACE()
    {
        $_position = $this->position;

        if (isset($this->cache['SPACE'][$_position])) {
            $_success = $this->cache['SPACE'][$_position]['success'];
            $this->position = $this->cache['SPACE'][$_position]['position'];
            $this->value = $this->cache['SPACE'][$_position]['value'];

            return $_success;
        }

        $_value29 = array();
        $_cut30 = $this->cut;

        while (true) {
            $_position28 = $this->position;

            $this->cut = false;
            $_position26 = $this->position;
            $_cut27 = $this->cut;

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
                $this->position = $_position26;

                if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen("\t"));
                    $this->position += strlen("\t");
                } else {
                    $_success = false;

                    $this->report($this->position, '"\\t"');
                }
            }

            $this->cut = $_cut27;

            if (!$_success) {
                break;
            }

            $_value29[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position28;
            $this->value = $_value29;
        }

        $this->cut = $_cut30;

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

        $_value33 = array();

        $_position31 = $this->position;
        $_cut32 = $this->cut;

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
            $this->position = $_position31;
            $this->value = null;
        }

        $this->cut = $_cut32;

        if ($_success) {
            $_value33[] = $this->value;

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
            $_value33[] = $this->value;

            $this->value = $_value33;
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

    protected function parseEMPTY_LINE()
    {
        $_position = $this->position;

        if (isset($this->cache['EMPTY_LINE'][$_position])) {
            $_success = $this->cache['EMPTY_LINE'][$_position]['success'];
            $this->position = $this->cache['EMPTY_LINE'][$_position]['position'];
            $this->value = $this->cache['EMPTY_LINE'][$_position]['value'];

            return $_success;
        }

        $_value34 = array();

        $_success = $this->parseSPACE();

        if ($_success) {
            $_value34[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value34[] = $this->value;

            $this->value = $_value34;
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

    protected function parseEND_OF_FIELD()
    {
        $_position = $this->position;

        if (isset($this->cache['END_OF_FIELD'][$_position])) {
            $_success = $this->cache['END_OF_FIELD'][$_position]['success'];
            $this->position = $this->cache['END_OF_FIELD'][$_position]['position'];
            $this->value = $this->cache['END_OF_FIELD'][$_position]['value'];

            return $_success;
        }

        $_value38 = array();

        $_value36 = array();
        $_cut37 = $this->cut;

        while (true) {
            $_position35 = $this->position;

            $this->cut = false;
            $_success = $this->parsefield();

            if (!$_success) {
                break;
            }

            $_value36[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position35;
            $this->value = $_value36;
        }

        $this->cut = $_cut37;

        if ($_success) {
            $_value38[] = $this->value;

            $_success = $this->parseEOL();
        }

        if ($_success) {
            $_value38[] = $this->value;

            $this->value = $_value38;
        }

        $this->cache['END_OF_FIELD'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'END_OF_FIELD');
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