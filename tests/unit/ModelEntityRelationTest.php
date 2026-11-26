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
use Entity;
use Glpi\Tests\DbTestCase;
use PluginDatainjectionModel;
use Session;

final class ModelEntityRelationTest extends DbTestCase
{
    public function testModelIsMovedWhenItsEntityIsReplaced(): void
    {
        $this->login();
        $root_id = getItemByTypeName(Entity::class, '_test_root_entity', true);

        $entity = $this->createItem(Entity::class, [
            'name'        => __FUNCTION__,
            'entities_id' => $root_id,
        ]);
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'         => __FUNCTION__,
            'itemtype'     => Computer::class,
            'filetype'     => 'csv',
            'behavior_add' => 1,
            'entities_id'  => $entity->getID(),
            'is_recursive' => 0,
            'is_private'   => 0,
            'users_id'     => Session::getLoginUserID(),
        ]);

        $this->assertTrue($entity->isUsed());

        $this->assertTrue($entity->delete(['id' => $entity->getID(), '_replace_by' => $root_id], true));
        $this->assertTrue($model->getFromDB($model->getID()));
        $this->assertSame($root_id, $model->fields['entities_id']);
    }
}
