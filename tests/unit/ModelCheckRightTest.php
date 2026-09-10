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

final class ModelCheckRightTest extends DbTestCase
{
    private function createModel(string $itemtype = Computer::class): int
    {
        $model = new PluginDatainjectionModel();
        $models_id = $model->add([
            'name'            => 'Test_Model_CheckRight_' . $itemtype . '_' . random_int(1, PHP_INT_MAX),
            'itemtype'        => $itemtype,
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

    public function testUnknownModelIsAllowed(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $this->assertTrue(PluginDatainjectionModel::checkRightOnModel(999999));
    }

    public function testCreationPathReturnsBooleanOnEmptyModel(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $model = new PluginDatainjectionModel();
        $model->getEmpty();

        $this->assertIsBool($model->canCreateItem());
    }

    public function testMappedRelationItemtypeWithoutRightsIsDenied(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $models_id  = $this->createModel();
        $control_id = $this->createModel();

        $mapping = new PluginDatainjectionMapping();
        $this->assertGreaterThan(0, $mapping->add([
            'models_id'    => $models_id,
            'itemtype'     => Group_User::class,
            'rank'         => 0,
            'name'         => 'groups_id',
            'value'        => 'groups_id',
            'is_mandatory' => 0,
        ]));

        $this->assertTrue(PluginDatainjectionModel::checkRightOnModel($models_id));

        $_SESSION['glpiactiveprofile'][User::$rightname]  = 0;
        $_SESSION['glpiactiveprofile'][Group::$rightname] = 0;

        //Control model keeps its own granted itemtype: only the mapped relation may deny
        $this->assertTrue(PluginDatainjectionModel::checkRightOnModel($control_id));
        $this->assertFalse(PluginDatainjectionModel::checkRightOnModel($models_id));
    }
}
