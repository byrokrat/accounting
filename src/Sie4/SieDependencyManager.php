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

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Container;

/**
 * Manage parser dependencies
 */
class SieDependencyManager
{
    /**
     * @var AccountBuilder Builder for account objects
     */
    private $accountBuilder;

    /**
     * @var Container Container for parsed data
     */
    private $container;

    /**
     * @var Logger Internal error log
     */
    private $logger;

    /**
     * @var CurrencyBuilder Builder for monetary objects
     */
    private $currencyBuilder;

    /**
     * @var DimensionBuilder Builder for dimension objects
     */
    private $dimensionBuilder;

    /**
     * Inject dependencies at construct
     */
    public function __construct(
        Logger $logger,
        AccountBuilder $accountBuilder,
        CurrencyBuilder $currencyBuilder,
        DimensionBuilder $dimensionBuilder
    ) {
        $this->logger = $logger;
        $this->accountBuilder = $accountBuilder;
        $this->currencyBuilder = $currencyBuilder;
        $this->dimensionBuilder = $dimensionBuilder;
        $this->resetContainer();
    }

    /**
     * Reset container to empty state
     */
    public function resetContainer()
    {
        $this->container = new Container;
    }

    /**
     * Get container with parsed data
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Get builder of account objects
     */
    protected function getAccountBuilder(): AccountBuilder
    {
        return $this->accountBuilder;
    }

    /**
     * Get builder of monetary objects
     */
    protected function getCurrencyBuilder(): CurrencyBuilder
    {
        return $this->currencyBuilder;
    }

    /**
     * Get builder of dimension objects
     */
    protected function getDimensionBuilder(): DimensionBuilder
    {
        return $this->dimensionBuilder;
    }

    /**
     * Get internal error log
     */
    protected function getLogger(): Logger
    {
        return $this->logger;
    }
}
