<?php
/**
 * This file is part of byrokrat/accounting.
 *
 * byrokrat/accounting is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * byrokrat/accounting is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\Account;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Verification;
use byrokrat\amount\Currency;

/**
 * Helper that creates and keeps track of verifications
 */
trait VerificationHelper
{
    /**
     * Called when a #TRANS post is encountered
     */
    public function onTrans(
        Account $account,
        array $objects,
        Amount $amount,
        \DateTimeInterface $date = null,
        string $desc = '',
        int $quantity = 0,
        string $signature = ''
    ): Transaction {
        // TODO ska jag kanske skriva det här som att transaction är en typ istället...
            // vilket skulle betyda createTransaction och passa bättre med arbetssättet...

        $transaction = new Transaction($account, $amount, $quantity, ...$objects);

        if ($date) {
            $transaction->setDate($date);
        }

        if ($desc) {
            $transaction->setDescription($desc);
        }

        if ($signature) {
            $transaction->setSignature($signature);
        }

        return $transaction;
    }

    /**
     * Called when a #VER post is encountered
     */
    public function onVer(
        string $series,
        int $number,
        \DateTimeInterface $date,
        string $desc = '',
        \DateTimeInterface $regdate = null,
        string $sign = '',
        array $transactions = []
    ): Verification {
        // TODO hantera serie på något sätt...

        // TODO kanske även här skriva Ver som en type, med createVerification
            // och så kan onVer ta emot seriens nummer samt en färdigbakat verifikation...
            // det kan faktiskt bli ganska kraftfullt tror jag..
            // och enklare att skriva unit-tests för helper...

        $verification = (new Verification)->setNumber($number)->setDate($date);

        if ($desc) {
            $verification->setDescription($desc);
        }

        if ($regdate) {
            $verification->setRegistrationDate($regdate);
        }

        if ($sign) {
            $verification->setSignature($sign);
        }

        foreach ($transactions as $transaction) {
            if (!$transaction instanceof Transaction) {
                continue;
            }

            if (!$transaction->hasDate()) {
                $transaction->setDate($verification->getDate());
            }

            if (!$transaction->getDescription()) {
                $transaction->setDescription($verification->getDescription());
            }

            $verification->addTransaction($transaction);
        }

        // TODO kontrolera att verifikation är balanserad...
            // om inte så kicka en warning!!

        // TODO Spara alla genererade verifikationer i container.
            // ska vara relativt series på något sätt...

        // TODO inga test finns skriva för den här klassen..
    }
}
