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
use Glpi\Tests\DbTestCase;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionData;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use PluginDatainjectionModelcsv;
use Session;

final class ModelIsFileCorrectTest extends DbTestCase
{
    public static function headerProvider(): array
    {
        return [
            'raw header'            => ['-serial', PluginDatainjectionCommonInjectionLib::SUCCESS],
            'formula-escaped header' => ["'-serial", PluginDatainjectionCommonInjectionLib::SUCCESS],
            'other header'          => ['serial', PluginDatainjectionCommonInjectionLib::FAILED],
        ];
    }

    /**
     * @dataProvider headerProvider
     */
    public function testHeaderMatchesMappingName(string $file_header, int $expected_status): void
    {
        $this->login();

        $model     = new PluginDatainjectionModel();
        $models_id = $model->add([
            'name'            => 'Test_Model_IsFileCorrect_' . random_int(1, PHP_INT_MAX),
            'itemtype'        => Computer::class,
            'filetype'        => 'csv',
            'entities_id'     => 0,
            'is_private'      => 0,
            'behavior_add'    => 1,
            'behavior_update' => 0,
            'users_id'        => Session::getLoginUserID(),
        ]);
        $this->assertGreaterThan(0, $models_id);

        $mapping = new PluginDatainjectionMapping();
        $this->assertGreaterThan(0, $mapping->add([
            'models_id'    => $models_id,
            'itemtype'     => Computer::class,
            'rank'         => 0,
            'name'         => '-serial',
            'value'        => 'serial',
            'is_mandatory' => 0,
        ]));

        $this->assertTrue($model->getFromDB($models_id));
        $model->loadMappings();

        $specific = new PluginDatainjectionModelcsv();
        $specific->fields['is_header_present'] = 1;
        $model->specific_model = $specific;

        $data = new PluginDatainjectionData();
        $data->addToData([[$file_header]]);

        $model->injectionData = $data;

        $this->assertSame($expected_status, $model->isFileCorrect()['status']);
    }
}
