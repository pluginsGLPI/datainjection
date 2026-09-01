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

namespace GlpiPlugin\Datainjection\Tests\Unit;

use GlpiPlugin\Datainjection\Tests\AbstractDataInjectionTestCase;
use Plugin;

final class PluginLifecycleTest extends AbstractDataInjectionTestCase
{
    private function getPlugin(): Plugin
    {
        $plugin = new Plugin();
        $plugin->getFromDBbyDir('datainjection');

        return $plugin;
    }

    public function testPluginIsInstalledAndActivatedByDefault(): void
    {
        $plugin = $this->getPlugin();

        self::assertSame(Plugin::ACTIVATED, (int) $plugin->fields['state']);
    }

    /**
     * Only the DB-backed `state` field is checked here, not
     * Plugin::isPluginActive(): it reads a static, process-wide cache that
     * activate()/unactivate() do not keep reliably in sync (only a full
     * Plugin::bootPlugins() rescan does), so it is not a trustworthy signal
     * within a single test run and asserting on it would make this test
     * order-dependent on unrelated tests.
     */
    public function testDeactivateThenActivateTogglesState(): void
    {
        $plugin = $this->getPlugin();
        $id     = $plugin->getID();

        $plugin->unactivate($id);
        self::assertTrue($plugin->getFromDB($id));
        self::assertSame(Plugin::NOTACTIVATED, (int) $plugin->fields['state']);

        $plugin->activate($id);
        self::assertTrue($plugin->getFromDB($id));
        self::assertSame(Plugin::ACTIVATED, (int) $plugin->fields['state']);
    }

    public function testNeedUpdateOrInstallReportsUpToDateWhenFullyInstalled(): void
    {
        self::assertSame(-1, plugin_datainjection_needUpdateOrInstall());
    }

    public function testUninstallThenReinstallRecreatesSchemaAndDefaultRights(): void
    {
        $plugin = $this->getPlugin();
        $id     = $plugin->getID();

        /** @var \DBmysql $DB */
        global $DB;

        $tables = [
            'glpi_plugin_datainjection_models',
            'glpi_plugin_datainjection_modelcsvs',
            'glpi_plugin_datainjection_mappings',
            'glpi_plugin_datainjection_infos',
        ];

        try {
            $this->withoutTransaction(static fn() => $plugin->uninstall($id));

            foreach ($tables as $table) {
                self::assertFalse($DB->tableExists($table), "$table should have been dropped");
            }
            self::assertSame(
                0,
                countElementsInTable('glpi_profilerights', ['name' => 'plugin_datainjection_model']),
                'Uninstall should remove the plugin rights from every profile',
            );

            self::assertTrue($plugin->getFromDB($id));
            self::assertSame(Plugin::NOTINSTALLED, (int) $plugin->fields['state']);

            self::assertSame(0, plugin_datainjection_needUpdateOrInstall());

            $this->withoutTransaction(static fn() => $plugin->install($id));

            foreach ($tables as $table) {
                self::assertTrue($DB->tableExists($table), "$table should have been recreated");
            }
            self::assertTrue($plugin->getFromDB($id));
            self::assertSame(Plugin::NOTACTIVATED, (int) $plugin->fields['state']);
            self::assertGreaterThan(
                0,
                countElementsInTable('glpi_profilerights', ['name' => 'plugin_datainjection_model']),
                'Install should seed the plugin right for the current profile',
            );
        } finally {
            // Whatever happened above, leave the environment exactly as every
            // other test in this suite expects it: installed and activated.
            if (!$DB->tableExists('glpi_plugin_datainjection_models')) {
                $this->withoutTransaction(static fn() => $plugin->install($id));
            }
            $plugin->getFromDB($id);
            if ((int) $plugin->fields['state'] !== Plugin::ACTIVATED) {
                $plugin->activate($id);
            }
            unset($_SESSION['MESSAGE_AFTER_REDIRECT']);
        }
    }
}
