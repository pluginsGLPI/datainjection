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

use Computer;
use DBConnection;
use DBmysql;
use Entity;
use Glpi\Tests\DbTestCase;
use Plugin;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionComputerInjection;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use PluginFieldsComputerstub;
use PluginFieldsProfile;
use Session;

final class FieldsContainerRightTest extends DbTestCase
{
    private int $entities_id = 0;

    //Table DDL must run outside the per-test transaction opened by DbTestCase
    public static function setUpBeforeClass(): void
    {
        if (Plugin::isPluginLoaded('fields')) {
            self::markTestSkipped('Fields stubs cannot be declared while the fields plugin is loaded');
        }

        parent::setUpBeforeClass();

        require_once __DIR__ . '/../fixtures/fields_stubs.php';

        /** @var DBmysql $DB */
        global $DB;
        $DB->doQuery(sprintf(
            'CREATE TABLE IF NOT EXISTS %s (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `items_id` INT UNSIGNED NOT NULL,
                `itemtype` VARCHAR(255) DEFAULT %s,
                `plugin_fields_containers_id` INT UNSIGNED NOT NULL DEFAULT %d,
                `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
                `%s` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=%s COLLATE=%s ROW_FORMAT=DYNAMIC',
            $DB->quoteName(PluginFieldsComputerstub::getTable()),
            $DB->quoteValue(Computer::class),
            PluginFieldsComputerstub::CONTAINER_ID,
            PluginFieldsComputerstub::VALUE_FIELD,
            DBConnection::getDefaultCharset(),
            DBConnection::getDefaultCollation(),
        ));
        $DB->clearSchemaCache();
    }

    public static function tearDownAfterClass(): void
    {
        /** @var DBmysql $DB */
        global $DB;
        $DB->dropTable(PluginFieldsComputerstub::getTable(), true);
        $DB->clearSchemaCache();

        parent::tearDownAfterClass();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->login();
        $this->entities_id    = getItemByTypeName(Entity::class, '_test_root_entity', true);
        PluginFieldsProfile::$right = CREATE;
    }

    private function createModelMappedToContainer(): int
    {
        $model     = new PluginDatainjectionModel();
        $models_id = (int) $model->add([
            'name'            => 'Test_Model_Fields_' . random_int(1, PHP_INT_MAX),
            'itemtype'        => Computer::class,
            'filetype'        => 'csv',
            'behavior_add'    => 1,
            'behavior_update' => 1,
            'entities_id'     => $this->entities_id,
            'is_private'      => 0,
            'users_id'        => Session::getLoginUserID(),
        ]);
        $this->assertGreaterThan(0, $models_id);

        $mapping = new PluginDatainjectionMapping();
        $this->assertGreaterThan(0, $mapping->add([
            'models_id'    => $models_id,
            'itemtype'     => PluginFieldsComputerstub::class,
            'rank'         => 0,
            'name'         => PluginFieldsComputerstub::VALUE_FIELD,
            'value'        => PluginFieldsComputerstub::VALUE_FIELD,
            'is_mandatory' => 0,
        ]));

        return $models_id;
    }

    private function injectComputerWithFieldValue(string $name, string $value): array
    {
        $lib = new PluginDatainjectionCommonInjectionLib(
            new PluginDatainjectionComputerInjection(),
            [
                Computer::class                 => ['name' => $name, 'entities_id' => $this->entities_id],
                PluginFieldsComputerstub::class => [PluginFieldsComputerstub::VALUE_FIELD => $value],
            ],
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                    'overwrite_notempty_fields' => true,
                ],
                'mandatory_fields' => [Computer::class => ['name' => true]],
                'entities_id'      => $this->entities_id,
            ],
        );
        $lib->processAddOrUpdate();

        return $lib->getInjectionResults();
    }

    public function testModelMappedToFieldsContainerIsAllowedWithWriteRightOnContainer(): void
    {
        $models_id = $this->createModelMappedToContainer();

        $this->assertTrue(PluginDatainjectionModel::checkRightOnModel($models_id));
    }

    public function testModelMappedToFieldsContainerIsDeniedWithReadRightOnContainer(): void
    {
        $models_id = $this->createModelMappedToContainer();

        PluginFieldsProfile::$right = READ;

        $this->assertFalse(PluginDatainjectionModel::checkRightOnModel($models_id));
    }

    public function testFieldsValueIsInjectedWithWriteRightOnContainer(): void
    {
        $name = 'Test_Computer_fields_' . random_int(1, PHP_INT_MAX);

        $results = $this->injectComputerWithFieldValue($name, 'Injected value');

        $this->assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        $container_item = new PluginFieldsComputerstub();
        $this->assertTrue($container_item->getFromDBByCrit([
            'itemtype' => Computer::class,
            'items_id' => $results[Computer::class],
        ]));
        $this->assertSame('Injected value', $container_item->fields[PluginFieldsComputerstub::VALUE_FIELD]);
    }

    public function testFieldsValueIsRejectedWithReadRightOnContainer(): void
    {
        $name = 'Test_Computer_fields_' . random_int(1, PHP_INT_MAX);

        PluginFieldsProfile::$right = READ;
        $results = $this->injectComputerWithFieldValue($name, 'Injected value');

        $this->assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);
        $this->assertSame(0, countElementsInTable(
            PluginFieldsComputerstub::getTable(),
            ['items_id' => $results[Computer::class]],
        ));
    }

    public function testFieldsValueIsUpdatedWithWriteRightOnContainer(): void
    {
        $name = 'Test_Computer_fields_' . random_int(1, PHP_INT_MAX);
        $this->injectComputerWithFieldValue($name, 'Injected value');

        $results = $this->injectComputerWithFieldValue($name, 'Updated value');

        $this->assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $results['type']);
        $this->assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        $container_item = new PluginFieldsComputerstub();
        $this->assertTrue($container_item->getFromDBByCrit([
            'itemtype' => Computer::class,
            'items_id' => $results[Computer::class],
        ]));
        $this->assertSame('Updated value', $container_item->fields[PluginFieldsComputerstub::VALUE_FIELD]);
    }

    public function testFieldsValueUpdateIsRejectedWithReadRightOnContainer(): void
    {
        $name = 'Test_Computer_fields_' . random_int(1, PHP_INT_MAX);
        $this->injectComputerWithFieldValue($name, 'Injected value');

        PluginFieldsProfile::$right = READ;
        $results = $this->injectComputerWithFieldValue($name, 'Updated value');

        $this->assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $results['type']);
        $this->assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);
        $this->assertContains(
            [PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_UPDATE, PluginFieldsComputerstub::class],
            $results[PluginDatainjectionCommonInjectionLib::ACTION_CHECK],
        );
        $container_item = new PluginFieldsComputerstub();
        $this->assertTrue($container_item->getFromDBByCrit([
            'itemtype' => Computer::class,
            'items_id' => $results[Computer::class],
        ]));
        $this->assertSame('Injected value', $container_item->fields[PluginFieldsComputerstub::VALUE_FIELD]);
    }
}
