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
     * @var Container Container for parsed data
     */
    private $container;

    /**
     * @var Logger Internal error log
     */
    private $logger;

    /**
     * Inject dependencies at construct
     */
    public function __construct(Container $container, Logger $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Get container for parsed data
     */
    protected function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Get internal error log
     */
    protected function getLogger(): Logger
    {
        return $this->logger;
    }
}
