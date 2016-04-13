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
 */
class ChartOfTemplates
{
    /**
     * @var array List of loaded templates
     */
    private $templates = array();

    /**
     * Add template
     *
     * If multiple templates with the same id are added the former is
     * overwritten
     *
     * @param  Template $template
     * @return void
     */
    public function addTemplate(Template $template)
    {
        $id = $template->getId();
        $this->templates[$id] = $template;
    }

    /**
     * Drop template using id
     *
     * @param  string $id
     * @return void
     */
    public function dropTemplate($id)
    {
        assert('is_string($id)');
        unset($this->templates[$id]);
    }

    /**
     * Check if template exists
     *
     * @param  string $id
     * @return bool
     */
    public function exists($id)
    {
        assert('is_string($id)');
        return isset($this->templates[$id]);
    }

    /**
     * Get a template clone using id
     *
     * @param  string $id
     * @return Template
     * @throws Exception\OutOfBoundsException If template does not exist
     */
    public function getTemplate($id)
    {
        assert('is_string($id)');
        if (!$this->exists($id)) {
            throw new Exception\OutOfBoundsException("Template <$id> does not exist");
        }
        return clone $this->templates[$id];
    }

    /**
     * Get loaded tempaltes
     *
     * @return array Template ids as keys
     */
    public function getTemplates()
    {
        return $this->templates;
    }
}
