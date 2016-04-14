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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

namespace byrokrat\accounting;

/**
 * Manage a collection of templates
 *
 * TODO Om denna ska sparas så ska metodnamn mm konvergeras med AccountSet
 */
class ChartOfTemplates
{
    /**
     * @var Template[] List of loaded templates
     */
    private $templates = [];

    /**
     * Add template
     *
     * If multiple templates with the same name are added the former is overwritten
     */
    public function addTemplate(Template $template)
    {
        $this->templates[$template->getName()] = $template;
    }

    /**
     * Drop template
     */
    public function dropTemplate(string $name)
    {
        unset($this->templates[$name]);
    }

    /**
     * Check if template exists
     */
    public function exists(string $name): bool
    {
        return isset($this->templates[$name]);
    }

    /**
     * Get a template clone
     *
     * @throws Exception\OutOfBoundsException If template does not exist
     */
    public function getTemplate(string $name): Template
    {
        if (!$this->exists($name)) {
            throw new Exception\OutOfBoundsException("Template $name does not exist");
        }
        return clone $this->templates[$name];
    }

    /**
     * Get loaded tempaltes
     *
     * @return Template[]
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }
}
