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

use PluginDatainjectionComputerInjection;
use PluginDatainjectionITILCategoryInjection;
use Glpi\Tests\DbTestCase;
use Group;
use ITILCategory;
use Computer;
use PluginDatainjectionCommonInjectionLib;

final class GroupInjectionTest extends DbTestCase
{
    public static function AssigneGroupToInjectedAssignableItemProvider(): array
    {
        return [
            'with_groups_id' => [
                'group_field'      => 'groups_id_tech',
                'injected_data'    => [
                    'Computer' => [
                        'name'      => 'Test_Computer_with_groups_id',
                        'groups_id' => 'GG_ADC_DRN_EDL',
                    ],
                ],
                'mandatory_fields' => ['Computer' => ['name' => true]],
                'expect_group'     => true,
            ],
            'with_groups_id_normal' => [
                'group_field'      => 'groups_id',
                'injected_data'    => [
                    'Computer' => [
                        'name'            => 'Test_Computer_with_groups_id_normal',
                        'groups_id_normal' => 'GG_ADC_DRN_EDL',
                    ],
                ],
                'mandatory_fields' => ['Computer' => ['name' => true]],
                'expect_group'     => true,
            ],
            'without_group' => [
                'group_field'      => 'groups_id',
                'injected_data'    => [
                    'Computer' => [
                        'name' => 'Test_Computer_without_group',
                    ],
                ],
                'mandatory_fields' => ['Computer' => ['name' => true]],
                'expect_group'     => false,
            ],
        ];
    }

    /**
     *
     * @dataProvider assigneGroupToInjectedAssignableItemProvider
     */
    public function testAssigneGroupToInjectedAssignableItem(
        string $group_field,
        array $injected_data,
        array $mandatory_fields,
        bool $expect_group
    ): void {
        $group = null;
        if ($expect_group) {
            $group = $this->createItem(Group::class, [
                'name'         => 'GG_ADC_DRN_EDL',
                'entities_id'  => 0,
                'is_recursive' => 1,
            ]);
        }

        $injection_class = new PluginDatainjectionComputerInjection();

        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            $injected_data,
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => $mandatory_fields,
                'entities_id'      => 0,
            ],
        );

        $lib->processAddOrUpdate();
        $results = $lib->getInjectionResults();

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $results['type']);

        $item_id = $results['Computer'];
        self::assertGreaterThan(0, $item_id);

        $item = new Computer();
        self::assertTrue($item->getFromDB($item_id));

        $field_value = $item->fields[$group_field] ?? [];
        if (!is_array($field_value)) {
            $field_value = [$field_value];
        }

        if ($expect_group) {
            self::assertContains($group->getID(), $field_value);
        } else {
            self::assertEmpty($field_value);
        }
    }

    public static function groupIsAssignedToInjectedNonAssignableItemProvider(): array
    {
        return [
            'with_groups_id' => [
                'injected_data'    => [
                    'ITILCategory' => [
                        'name'      => 'Test_ITILCategory_with_groups_id',
                        'groups_id' => 'GG_ADC_DRN_EDL',
                    ],
                ],
                'mandatory_fields' => ['ITILCategory' => ['name' => true]],
                'expect_group'     => true,
            ],
            'without_group' => [
                'injected_data'    => [
                    'ITILCategory' => [
                        'name' => 'Test_ITILCategory_without_group',
                    ],
                ],
                'mandatory_fields' => ['ITILCategory' => ['name' => true]],
                'expect_group'     => false,
            ],
        ];
    }

    /**
     *
     * @dataProvider groupIsAssignedToInjectedNonAssignableItemProvider
     */
    public function testGroupIsAssignedToInjectedNonAssignableItem(
        array $injected_data,
        array $mandatory_fields,
        bool $expect_group
    ): void {
        $group = null;
        if ($expect_group) {
            $group = $this->createItem(Group::class, [
                'name'         => 'GG_ADC_DRN_EDL',
                'entities_id'  => 0,
                'is_recursive' => 1,
            ]);
        }

        $injection_class = new PluginDatainjectionITILCategoryInjection();

        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            $injected_data,
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => $mandatory_fields,
                'entities_id'      => 0,
            ],
        );

        $lib->processAddOrUpdate();
        $results = $lib->getInjectionResults();

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $results['type']);

        $item_id = $results['ITILCategory'];
        self::assertGreaterThan(0, $item_id);

        $category = new ITILCategory();
        self::assertTrue($category->getFromDB($item_id));

        if ($expect_group) {
            self::assertSame(
                $group->getID(),
                (int) $category->fields['groups_id'],
            );
        } else {
            self::assertSame(0, (int) $category->fields['groups_id']);
        }
    }

    public static function groupIsUpdatedOnExistingAssignableItemProvider(): array
    {
        return [
            'with_groups_id' => [
                'group_field'      => 'groups_id_tech',
                'injected_data'    => [
                    'Computer' => [
                        'name'      => 'Test_Computer_update_groups_id',
                        'groups_id' => 'GG_ADC_DRN_EDL',
                    ],
                ],
                'mandatory_fields' => ['Computer' => ['name' => true]],
            ],
            'with_groups_id_normal' => [
                'group_field'      => 'groups_id',
                'injected_data'    => [
                    'Computer' => [
                        'name'            => 'Test_Computer_update_groups_id_normal',
                        'groups_id_normal' => 'GG_ADC_DRN_EDL',
                    ],
                ],
                'mandatory_fields' => ['Computer' => ['name' => true]],
            ],
        ];
    }

    /**
     * Updating an existing AssignableItem (Computer) via injection must route the group through
     * the AssignableItem normalisation path and persist it in Group_Item.
     *
     * @dataProvider groupIsUpdatedOnExistingAssignableItemProvider
     */
    public function testGroupIsUpdatedOnExistingAssignableItem(
        string $group_field,
        array $injected_data,
        array $mandatory_fields
    ): void {
        $group = $this->createItem(Group::class, [
            'name'         => 'GG_ADC_DRN_EDL',
            'entities_id'  => 0,
            'is_recursive' => 1,
        ]);

        $computer = $this->createItem(Computer::class, [
            'name'        => $injected_data['Computer']['name'],
            'entities_id' => 0,
        ]);

        $injection_class = new PluginDatainjectionComputerInjection();

        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            $injected_data,
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => $mandatory_fields,
                'entities_id'      => 0,
            ],
        );

        $lib->processAddOrUpdate();
        $results = $lib->getInjectionResults();

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $results['type']);

        $computer->getFromDB($computer->getID());

        $field_value = $computer->fields[$group_field] ?? [];
        if (!is_array($field_value)) {
            $field_value = [$field_value];
        }
        self::assertContains($group->getID(), $field_value);
    }

    /**
     * Updating an existing non assignable item via injection must also persist groups_id.
     */
    public function testGroupIsUpdatedOnExistingNonAssugnableItem(): void
    {
        $group = $this->createItem(Group::class, [
            'name'        => 'GG_ADC_DRN_EDL',
            'entities_id' => 0,
            'is_recursive' => 1,
        ]);

        $category = $this->createItem(ITILCategory::class, [
            'name'        => 'Demande',
            'entities_id' => 0,
            'groups_id'   => 0,
        ]);

        $injection_class = new PluginDatainjectionITILCategoryInjection();

        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            [
                'ITILCategory' => [
                    'name'      => 'Demande',
                    'groups_id' => 'GG_ADC_DRN_EDL',
                ],
            ],
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => [
                    'ITILCategory' => [
                        'name' => true,
                    ],
                ],
                'entities_id' => 0,
            ],
        );

        $lib->processAddOrUpdate();
        $results = $lib->getInjectionResults();

        self::assertSame(
            PluginDatainjectionCommonInjectionLib::SUCCESS,
            $results['status'],
        );
        self::assertSame(
            PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE,
            $results['type'],
        );

        $category->getFromDB($category->getID());
        self::assertSame(
            $group->getID(),
            (int) $category->fields['groups_id'],
        );
    }
}
