<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\accounting;

use ledgr\utils\Amount;

/**
 * Simple accounting transaction class
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Transaction
{
    /**
     * @var Account Account object
     */
    private $account;

    /**
     * @var Amount Amount object
     */
    private $amount;

    /**
     * Constructor
     *
     * @param Account $account
     * @param Amount  $amount
     */
    public function __construct(Account $account, Amount $amount)
    {
        $this->account = $account;
        $this->amount = $amount;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Get amount
     *
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
