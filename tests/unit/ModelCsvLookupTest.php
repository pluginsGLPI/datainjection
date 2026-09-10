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

final class ModelCsvLookupTest extends DbTestCase
{
    private function createModel(): int
    {
        $model = new PluginDatainjectionModel();
        $models_id = $model->add([
            'name'            => 'Test_ModelCsv_Lookup',
            'itemtype'        => 'Computer',
            'filetype'        => 'csv',
            'entities_id'     => 0,
            'is_private'      => 0,
            'behavior_add'    => 1,
            'behavior_update' => 0,
            'users_id'        => Session::getLoginUserID(),
        ]);
        $this->assertGreaterThan(0, $models_id);

        return (int) $models_id;
    }

    public function testRowIsCreatedThenReusedForSameModel(): void
    {
        $models_id = $this->createModel();

        $csv = new PluginDatainjectionModelCsv();
        $first = $csv->getFromDBByModelID($models_id);

        $this->assertGreaterThan(0, $first);
        $this->assertSame($models_id, (int) $csv->fields['models_id']);

        $second = (new PluginDatainjectionModelCsv())->getFromDBByModelID($models_id);
        $this->assertSame($first, $second);
    }

    public function testNonNumericModelIdIsRejected(): void
    {
        $csv = new PluginDatainjectionModelCsv();

        $this->expectException(TypeError::class);
        $csv->getFromDBByModelID("id' AND SLEEP(5)-- ");
    }
}
