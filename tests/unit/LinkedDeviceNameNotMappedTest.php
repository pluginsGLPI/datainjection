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
use DeviceProcessor;
use Glpi\Tests\DbTestCase;
use Item_DeviceProcessor;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionComputerInjection;
use PluginDatainjectionModel;
use PluginDatainjectionSession;
use Session;

final class LinkedDeviceNameNotMappedTest extends DbTestCase
{
    private function injectComputerWithProcessor(array $processor): array
    {
        $computer_name = 'Test_Computer_cpu_' . random_int(1, PHP_INT_MAX);
        $entities_id   = $this->getTestRootEntity(true);
        $this->createItem(Computer::class, ['name' => $computer_name, 'entities_id' => $entities_id]);

        $lib = new PluginDatainjectionCommonInjectionLib(
            new PluginDatainjectionComputerInjection(),
            [
                'Computer'        => ['name' => $computer_name],
                'DeviceProcessor' => $processor,
            ],
            [
                'rights'           => ['can_add' => true, 'can_update' => true],
                'mandatory_fields' => ['Computer' => ['name' => true]],
                'entities_id'      => $entities_id,
            ],
        );
        $lib->processAddOrUpdate();

        return $lib->getInjectionResults();
    }

    public function testProcessorWithoutDesignationReportsTheFieldToMap(): void
    {
        $this->login();

        $before  = countElementsInTable(DeviceProcessor::getTable());
        $results = $this->injectComputerWithProcessor(['frequency_default' => '3900']);

        self::assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);
        self::assertContains(
            [PluginDatainjectionCommonInjectionLib::ERROR_NAME_NOT_MAPPED, DeviceProcessor::getTypeName(1) . ' - ' . __('Name')],
            $results[PluginDatainjectionCommonInjectionLib::ACTION_CHECK],
        );
        self::assertNotContains(
            [PluginDatainjectionCommonInjectionLib::WARNING, DeviceProcessor::class],
            $results[PluginDatainjectionCommonInjectionLib::ACTION_CHECK],
        );
        self::assertSame($before, countElementsInTable(DeviceProcessor::getTable()));

        // Same message as displayed in the log popup and the PDF export
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'         => 'Test_model_' . random_int(1, PHP_INT_MAX),
            'itemtype'     => 'Computer',
            'behavior_add' => 1,
            'users_id'     => Session::getLoginUserID(),
        ]);
        PluginDatainjectionSession::setParam('results', json_encode([$results + ['line' => 2]]));
        $logs = PluginDatainjectionModel::prepareLogResults($model->getID());
        self::assertStringContainsString(
            'Item not created: a mapping is needed on the following field (' . DeviceProcessor::getTypeName(1) . ' - ' . __('Name') . ')',
            $logs[PluginDatainjectionCommonInjectionLib::FAILED][0]['check_message'],
        );
    }

    public function testProcessorWithDesignationIsLinkedWithItsDefaultFrequency(): void
    {
        $this->login();

        $designation = 'Test_CPU_' . random_int(1, PHP_INT_MAX);
        $results     = $this->injectComputerWithProcessor(['designation' => $designation, 'frequency_default' => '3900']);

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);

        $processor = new DeviceProcessor();
        self::assertTrue($processor->getFromDBByCrit(['designation' => $designation]));
        self::assertSame(1, countElementsInTable(Item_DeviceProcessor::getTable(), [
            'deviceprocessors_id' => $processor->getID(),
            'itemtype'            => 'Computer',
            'items_id'            => $results['Computer'],
            'frequency'           => 3900,
        ]));
    }
}
