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
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use Session;

final class ModelCrudTest extends AbstractDataInjectionTestCase
{
    public function testAddRefusesModelWithoutAName(): void
    {
        $model = new PluginDatainjectionModel();
        $id = $model->add([
            'name'            => '',
            'itemtype'        => Computer::class,
            'entities_id'     => 0,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 1,
            'behavior_update' => 0,
        ]);

        self::assertFalse($id);
        $this->hasSessionMessages(ERROR, ['Please enter a name for the model']);
    }

    public function testAddRefusesModelWithNeitherAddNorUpdateBehavior(): void
    {
        $model = new PluginDatainjectionModel();
        $id = $model->add([
            'name'            => 'Test_Model_Crud_NoBehavior',
            'itemtype'        => Computer::class,
            'entities_id'     => 0,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 0,
            'behavior_update' => 0,
        ]);

        self::assertFalse($id);
        $this->hasSessionMessages(ERROR, ['Your model should allow import and/or update of data']);
    }

    public function testUpdateRefusesChangingOwnerOfAPrivateModel(): void
    {
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'            => 'Test_Model_Crud_Private',
            'itemtype'        => Computer::class,
            'entities_id'     => 0,
            'is_private'      => PluginDatainjectionModel::MODEL_PRIVATE,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 1,
            'behavior_update' => 0,
        ]);

        $success = $model->update([
            'id'         => $model->getID(),
            'is_private' => 1,
            'users_id'   => Session::getLoginUserID() + 1,
        ]);

        self::assertFalse($success);
        $this->hasSessionMessages(ERROR, ['You are not the initial creator of this model']);
    }

    public function testPurgingAModelDeletesItsMappings(): void
    {
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'            => 'Test_Model_Crud_Purge',
            'itemtype'        => Computer::class,
            'entities_id'     => 0,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 1,
            'behavior_update' => 0,
        ]);

        $mapping = $this->createItem(PluginDatainjectionMapping::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => Computer::class,
            'rank'         => 0,
            'name'         => 'Name',
            'value'        => 'name',
            'is_mandatory' => 1,
        ]);

        $this->deleteItem(PluginDatainjectionModel::class, $model->getID(), true);

        $reloaded_mapping = new PluginDatainjectionMapping();
        self::assertFalse($reloaded_mapping->getFromDB($mapping->getID()));
    }
}
