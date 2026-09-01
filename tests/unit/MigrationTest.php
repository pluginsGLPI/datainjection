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

use DBmysql;
use GlpiPlugin\Datainjection\Tests\AbstractDataInjectionTestCase;
use Migration;
use Psr\Log\LogLevel;
use Session;

final class MigrationTest extends AbstractDataInjectionTestCase
{
    public function testMigration2158To2159FixesLegacyNegativeEntityId(): void
    {
        /** @var DBmysql $DB */
        global $DB;

        $table = 'glpi_plugin_datainjection_models';

        // Recreate the pre-2158 column shape: signed, defaulting to -1,
        // exactly as described in the migration's own comments.
        $this->withoutTransaction(static function () use ($DB, $table): void {
            $DB->doQuery(sprintf("ALTER TABLE `%s` CHANGE `entities_id` `entities_id` int NOT NULL default '-1'", $table));
        });
        // GLPI itself warns on signed keys; expected here since we are
        // deliberately recreating the legacy (pre-migration) column shape.
        $this->hasPhpLogRecordThatContains(
            'Usage of signed integers in primary or foreign keys is discouraged',
            LogLevel::WARNING,
        );

        $id = null;

        try {
            $DB->insert($table, [
                'name'         => 'Test_Migration_Legacy_Private_Model',
                'itemtype'     => 'Computer',
                'entities_id'  => -1,
                'is_private'   => 1,
                'is_recursive' => 0,
                'users_id'     => Session::getLoginUserID(),
                'behavior_add' => 1,
            ]);
            $id = $DB->insertId();

            $this->withoutTransaction(static function (): void {
                plugin_datainjection_migration_2158_2159(new Migration(PLUGIN_DATAINJECTION_VERSION));
            });

            $row = $DB->request(['FROM' => $table, 'WHERE' => ['id' => $id]])->current();

            self::assertSame(0, (int) $row['entities_id']);
            self::assertSame(1, (int) $row['is_private']);
            self::assertSame(1, (int) $row['is_recursive']);

            $column = null;
            foreach ($DB->listFields($table) as $field) {
                if ($field['Field'] === 'entities_id') {
                    $column = $field;
                }
            }

            self::assertNotNull($column);
            self::assertStringContainsString('unsigned', $column['Type']);
        } finally {
            if ($id !== null) {
                $DB->delete($table, ['id' => $id]);
            }

            // Guarantee the column ends up unsigned regardless of whether the
            // assertions above passed, matching the schema every other test
            // in this suite relies on.
            $this->withoutTransaction(static function () use ($DB, $table): void {
                $DB->doQuery(sprintf("ALTER TABLE `%s` CHANGE `entities_id` `entities_id` int unsigned NOT NULL default '0'", $table));
            });
        }
    }
}
