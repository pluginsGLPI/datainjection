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
use Group;
use Group_User;
use PluginDatainjectionInfo;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use Session;
use User;
use Glpi\Tests\DbTestCase;

final class ModelListRightTest extends DbTestCase
{
    private function createModel(array $behaviors): int
    {
        $model = $this->createItem(PluginDatainjectionModel::class, $behaviors + [
            'name'         => 'Test_Model_ListRight_' . random_int(1, PHP_INT_MAX),
            'itemtype'     => Computer::class,
            'filetype'     => 'csv',
            'entities_id'  => 0,
            'is_recursive' => 1,
            'is_private'   => 0,
            'users_id'     => Session::getLoginUserID(),
        ]);

        return $model->getID();
    }

    /**
     * Models listed by getModels(), among the given ones
     *
     * @param array<int> $models_ids
     *
     * @return array<int>
     */
    private function getListedModels(array $models_ids): array
    {
        $listed = [];
        foreach (PluginDatainjectionModel::getModels(Session::getLoginUserID(), 'name', $_SESSION['glpiactive_entity'], true) as $model) {
            if (in_array((int) $model['id'], $models_ids, true)) {
                $listed[] = (int) $model['id'];
            }
        }

        sort($listed);

        return $listed;
    }

    public function testListKeepsTheModelsAllowedOneByOne(): void
    {
        $this->login();

        $add_id     = $this->createModel(['behavior_add' => 1, 'behavior_update' => 0]);
        $update_id  = $this->createModel(['behavior_add' => 0, 'behavior_update' => 1]);
        $mapping_id = $this->createModel(['behavior_add' => 1, 'behavior_update' => 0]);
        $info_id    = $this->createModel(['behavior_add' => 1, 'behavior_update' => 0]);
        $models_ids = [$add_id, $update_id, $mapping_id, $info_id];

        $this->createItem(PluginDatainjectionMapping::class, [
            'models_id'    => $mapping_id,
            'itemtype'     => Group_User::class,
            'rank'         => 0,
            'name'         => 'groups_id',
            'value'        => 'groups_id',
            'is_mandatory' => 0,
        ]);
        $this->createItem(PluginDatainjectionInfo::class, [
            'models_id'    => $info_id,
            'itemtype'     => Group_User::class,
            'value'        => 'groups_id',
            'is_mandatory' => 0,
        ]);

        $this->assertSame($models_ids, $this->getListedModels($models_ids));

        //Each denied right removes the models that need it, as checkRightOnModel() does
        $_SESSION['glpiactiveprofile'][User::$rightname]     = 0;
        $_SESSION['glpiactiveprofile'][Group::$rightname]    = 0;
        $_SESSION['glpiactiveprofile'][Computer::$rightname] = CREATE;

        $expected = array_values(array_filter(
            $models_ids,
            PluginDatainjectionModel::checkRightOnModel(...),
        ));
        $this->assertSame([$add_id], $expected);
        $this->assertSame($expected, $this->getListedModels($models_ids));
    }
}
