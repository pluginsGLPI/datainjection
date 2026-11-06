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
use DeviceMemory;
use DeviceProcessor;
use Glpi\Tests\DbTestCase;
use Item_DeviceMemory;
use Item_DeviceProcessor;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionEngine;
use PluginDatainjectionInfo;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use Session;

final class AdditionalInformationTest extends DbTestCase
{
    /**
     * Model on Computer, CSV maps only the name; both device designations come from additional information
     */
    private function createModel(): PluginDatainjectionModel
    {
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'            => 'Test_model_infos_' . random_int(1, PHP_INT_MAX),
            'itemtype'        => 'Computer',
            'behavior_add'    => 1,
            'behavior_update' => 1,
            'users_id'        => Session::getLoginUserID(),
        ]);
        $this->createItem(PluginDatainjectionMapping::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => 'Computer',
            'rank'         => 0,
            'name'         => 'Name',
            'value'        => 'name',
            'is_mandatory' => 1,
        ]);
        foreach (['DeviceMemory', 'DeviceProcessor'] as $itemtype) {
            $this->createItem(PluginDatainjectionInfo::class, [
                'models_id'    => $model->getID(),
                'itemtype'     => $itemtype,
                'value'        => 'designation',
                'is_mandatory' => 1,
            ]);
        }

        return $model;
    }

    public function testSameFieldOnSeveralItemtypesKeepsEachValue(): void
    {
        $this->login();

        $entities_id = $this->getTestRootEntity(true);
        $computer    = $this->createItem(Computer::class, [
            'name'        => 'Test_Computer_infos_' . random_int(1, PHP_INT_MAX),
            'entities_id' => $entities_id,
        ]);
        $model       = $this->createModel();
        $suffix      = random_int(1, PHP_INT_MAX);
        $infos       = [
            'DeviceMemory'    => ['designation' => 'Test_RAM_' . $suffix],
            'DeviceProcessor' => ['designation' => 'Test_CPU_' . $suffix],
        ];

        self::assertTrue($model->checkMandatoryFields($infos));
        self::assertFalse($model->checkMandatoryFields(['DeviceMemory' => $infos['DeviceMemory']]));

        $engine  = new PluginDatainjectionEngine($model, $infos, $entities_id);
        $results = $engine->injectLine([$computer->fields['name']], 2);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);

        $memory = new DeviceMemory();
        self::assertTrue($memory->getFromDBByCrit(['designation' => 'Test_RAM_' . $suffix]));
        self::assertSame(1, countElementsInTable(Item_DeviceMemory::getTable(), [
            'devicememories_id' => $memory->getID(),
            'itemtype'          => 'Computer',
            'items_id'          => $computer->getID(),
        ]));

        $processor = new DeviceProcessor();
        self::assertTrue($processor->getFromDBByCrit(['designation' => 'Test_CPU_' . $suffix]));
        self::assertSame(1, countElementsInTable(Item_DeviceProcessor::getTable(), [
            'deviceprocessors_id' => $processor->getID(),
            'itemtype'            => 'Computer',
            'items_id'            => $computer->getID(),
        ]));
    }

    public function testFormInputIsNamedAfterItemtypeAndField(): void
    {
        $this->login();

        $info = new PluginDatainjectionInfo();
        $info->fields = ['itemtype' => 'DeviceProcessor', 'value' => 'designation', 'is_mandatory' => 0];

        ob_start();
        PluginDatainjectionInfo::displayAdditionalInformation($info);
        $html = ob_get_clean();

        self::assertStringContainsString("name='info[DeviceProcessor][designation]'", $html);
        self::assertStringContainsString(DeviceProcessor::getTypeName(1) . ' - ', $html);
    }
}
