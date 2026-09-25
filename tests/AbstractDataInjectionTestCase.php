<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

namespace GlpiPlugin\Datainjection\Tests;

use Glpi\Tests\DbTestCase;
use Plugin;

abstract class AbstractDataInjectionTestCase extends DbTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->login();

        // Functions defined in hook.php (migration steps, needUpdateOrInstall(), ...)
        // are only required when the plugin is actually (re)loaded, which
        // Plugin::activate() skips once it is already active. Load explicitly so
        // tests can call those functions directly.
        Plugin::load('datainjection', true);
    }

    /**
     * Run DDL (CREATE/DROP/ALTER TABLE) outside of the transaction that wraps each test.
     */
    protected function withoutTransaction(callable $callback): mixed
    {
        global $DB;
        $DB->commit();
        try {
            return $callback();
        } finally {
            $DB->clearSchemaCache();
            $DB->beginTransaction();
        }
    }
}
