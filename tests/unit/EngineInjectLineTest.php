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
use GlpiPlugin\Datainjection\Tests\AbstractDataInjectionTestCase;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionEngine;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use Session;

final class EngineInjectLineTest extends AbstractDataInjectionTestCase
{
    private function createModelWithMappings(array $model_overrides = []): PluginDatainjectionModel
    {
        $model = $this->createItem(PluginDatainjectionModel::class, array_merge([
            'name'            => 'Test_Engine_Model_' . uniqid(),
            'itemtype'        => Computer::class,
            'entities_id'     => 0,
            'is_private'      => 0,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 1,
            'behavior_update' => 1,
        ], $model_overrides));

        $this->createItem(PluginDatainjectionMapping::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => Computer::class,
            'rank'         => 0,
            'name'         => 'Name',
            'value'        => 'name',
            'is_mandatory' => 1,
        ]);

        $this->createItem(PluginDatainjectionMapping::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => Computer::class,
            'rank'         => 1,
            'name'         => 'Serial',
            'value'        => 'serial',
            'is_mandatory' => 0,
        ]);

        return $model;
    }

    public function testInjectLineCreatesItemFromMappedCsvRow(): void
    {
        $model = $this->createModelWithMappings();

        $engine  = new PluginDatainjectionEngine($model, [], $model->fields['entities_id']);
        $results = $engine->injectLine(['Test_Engine_Computer', 'ABC123'], 1);

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $results['type']);
        self::assertEmpty($engine->getLinesInError());

        $computer = new Computer();
        self::assertTrue($computer->getFromDB($results['Computer']));
        self::assertSame('Test_Engine_Computer', $computer->fields['name']);
        self::assertSame('ABC123', $computer->fields['serial']);
    }

    public function testInjectLineUpdatesExistingItemFromMappedCsvRow(): void
    {
        $model = $this->createModelWithMappings();

        $computer = $this->createItem(Computer::class, [
            'name'        => 'Test_Engine_Computer_Update',
            'entities_id' => 0,
            'serial'      => 'OLD_SERIAL',
        ]);

        $engine  = new PluginDatainjectionEngine($model, [], $model->fields['entities_id']);
        $results = $engine->injectLine(['Test_Engine_Computer_Update', 'NEW_SERIAL'], 1);

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $results['type']);

        $computer->getFromDB($computer->getID());
        self::assertSame('NEW_SERIAL', $computer->fields['serial']);
    }

    public function testInjectLineRejectsNewItemWhenModelDisallowsAdd(): void
    {
        $model = $this->createModelWithMappings(['behavior_add' => 0]);

        $engine  = new PluginDatainjectionEngine($model, [], $model->fields['entities_id']);
        $results = $engine->injectLine(['Test_Engine_Computer_NoAdd', 'XYZ'], 1);

        self::assertSame(PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_IMPORT, $results['status']);
        self::assertNotEmpty($engine->getLinesInError());

        $computer = new Computer();
        self::assertFalse($computer->getFromDBbyCrit(['name' => 'Test_Engine_Computer_NoAdd']));
    }

    public function testInjectLineRejectsUpdateWhenModelDisallowsUpdate(): void
    {
        $model = $this->createModelWithMappings(['behavior_update' => 0]);

        $computer = $this->createItem(Computer::class, [
            'name'        => 'Test_Engine_Computer_NoUpdate',
            'entities_id' => 0,
            'serial'      => 'ORIGINAL',
        ]);

        $engine  = new PluginDatainjectionEngine($model, [], $model->fields['entities_id']);
        $results = $engine->injectLine(['Test_Engine_Computer_NoUpdate', 'CHANGED'], 1);

        self::assertSame(PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_UPDATE, $results['status']);

        $computer->getFromDB($computer->getID());
        self::assertSame('ORIGINAL', $computer->fields['serial']);
    }
}
