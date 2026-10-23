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
use GlpiPlugin\Datainjection\Tests\AbstractDataInjectionTestCase;
use PluginDatainjectionModel;
use Session;
use User;

final class ModelRightsTest extends AbstractDataInjectionTestCase
{
    /**
     * The default test user's active session is scoped to a fixed set of
     * entities that does not include the root entity, so models under test
     * need a real entity from that set, not entities_id => 0.
     */
    private function createAccessibleEntityId(): int
    {
        return (int) $this->createItem(Entity::class, [
            'name'        => 'Test_Model_Rights_Entity_' . uniqid(),
            'entities_id' => 0,
        ])->getID();
    }

    private function createModel(array $overrides = []): PluginDatainjectionModel
    {
        return $this->createItem(PluginDatainjectionModel::class, array_merge([
            'name'            => 'Test_Model_Rights_' . uniqid(),
            'itemtype'        => Computer::class,
            'entities_id'     => $this->createAccessibleEntityId(),
            'is_private'      => PluginDatainjectionModel::MODEL_PUBLIC,
            'users_id'        => Session::getLoginUserID(),
            'behavior_add'    => 1,
            'behavior_update' => 1,
        ], $overrides));
    }

    public function testCanCreateItemIsDeniedWhenNativeItemtypeRightIsRemoved(): void
    {
        $model = $this->createModel();

        self::assertTrue($model->canCreateItem());

        $_SESSION['glpiactiveprofile']['computer'] = 0;
        self::assertFalse($model->canCreateItem());
    }

    public function testCanCreateItemIsAllowedWhenNativeItemtypeRightIsPresent(): void
    {
        $model = $this->createModel();

        $_SESSION['glpiactiveprofile']['computer'] = ALLSTANDARDRIGHT;
        self::assertTrue($model->canCreateItem());
    }

    public function testCanViewItemHidesPrivateModelOwnedByAnotherUser(): void
    {
        $model = $this->createModel([
            'is_private' => PluginDatainjectionModel::MODEL_PRIVATE,
            'users_id'   => getItemByTypeName(User::class, 'tech', true),
        ]);

        self::assertFalse($model->canViewItem());
    }

    public function testCanViewItemAllowsPrivateModelOwnedBySelf(): void
    {
        $model = $this->createModel([
            'is_private' => PluginDatainjectionModel::MODEL_PRIVATE,
        ]);

        self::assertTrue($model->canViewItem());
    }

    public function testCanViewItemRespectsEntityRestriction(): void
    {
        $entity = $this->createItem(Entity::class, [
            'name'        => 'Test_Model_Rights_Entity',
            'entities_id' => 0,
        ]);

        $model = $this->createModel([
            'entities_id'  => $entity->getID(),
            'is_recursive' => 0,
        ]);

        self::assertTrue($model->canViewItem());

        $_SESSION['glpiactiveentities'] = [0];
        self::assertFalse($model->canViewItem());
    }
}
