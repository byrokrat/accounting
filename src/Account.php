<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\accounting;

use ledgr\accounting\Exception\InvalidAccountException;

/**
 * Simple Account class
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Account
{
    /**
     * @var string Account number
     */
    private $number;

    /**
     * @var string Account type
     */
    private $type;

    /**
     * @var string Account name
     */
    private $name;

    /**
     * Constructor
     *
     * @param  string                  $number
     * @param  string                  $type   Account type (T, S, I or K)
     * @param  string                  $name
     * @throws InvalidAccountException If any data is invalid
     */
    public function __construct($number, $type, $name)
    {
        $number = intval($number);
        if ($number < 1000 || $number > 9999) {
            $msg = "Account must be numeric, 999 < number < 10000";
            throw new InvalidAccountException($msg);
        }

        $this->number = (string)$number;

        if (!in_array($type, array('T', 'S', 'I', 'K'))) {
            $msg = "Account type must be T, S, I or K";
            throw new InvalidAccountException($msg);
        }

        $this->type = $type;

        if (!is_string($name) || empty($name)) {
            $msg = "Account name can not be empty";
            throw new InvalidAccountException($msg);
        }

        $this->name = $name;
    }

    /**
     * Get account number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get account type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get account name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Validate that $account equals this instance
     *
     * @param  Account $account
     * @return bool
     */
    public function equals(Account $account)
    {
        if ($this->getNumber() != $account->getNumber()
            || $this->getType() != $account->getType()
            || $this->getName() != $account->getName()
        ) {
            return false;
        }
        return true;
    }
}
