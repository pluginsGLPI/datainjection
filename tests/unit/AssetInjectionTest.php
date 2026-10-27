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

use Glpi\Asset\Asset;
use Glpi\Asset\AssetDefinition;
use Glpi\Asset\Capacity;
use Glpi\Asset\Capacity\HasAntivirusCapacity;
use Glpi\Asset\Capacity\HasInfocomCapacity;
use Glpi\Asset\Capacity\HasOperatingSystemCapacity;
use Glpi\Asset\Capacity\HasSoftwaresCapacity;
use Glpi\Asset\Capacity\HasVolumesCapacity;
use Glpi\Asset\CustomFieldDefinition;
use Glpi\Asset\CustomFieldType\BooleanType;
use Glpi\Asset\CustomFieldType\DateTimeType;
use Glpi\Asset\CustomFieldType\DateType;
use Glpi\Asset\CustomFieldType\DropdownType;
use Glpi\Asset\CustomFieldType\NumberType;
use Glpi\Asset\CustomFieldType\StringType;
use Glpi\Asset\CustomFieldType\TextType;
use Glpi\Exception\Http\HttpException;
use Glpi\Tests\DbTestCase;
use GlpiPlugin\Datainjection\AssetInjection;
use Group;
use Infocom;
use Item_OperatingSystem;
use Location;
use Manufacturer;
use OperatingSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionInjectionType;

final class AssetInjectionTest extends DbTestCase
{
    public function testAssetDefinitionIsInjectable(): void
    {
        $definition = $this->initAssetDefinition();
        $this->login(); // load the rights of the new definition

        $itemtype = $definition->getAssetClassName();
        self::assertSame($definition->getTranslatedName(), PluginDatainjectionInjectionType::getItemtypes(true)[$itemtype] ?? null);

        $injection = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($itemtype);
        self::assertInstanceOf(AssetInjection::class, $injection);
        self::assertSame($itemtype, PluginDatainjectionCommonInjectionLib::getItemtypeByInjectionClass($injection));
    }

    public function testAssetDefinitionWithoutRightIsNotInjectable(): void
    {
        $definition = $this->initAssetDefinition(profiles: []);
        $this->login();

        self::assertArrayNotHasKey($definition->getAssetClassName(), PluginDatainjectionInjectionType::getItemtypes(true));
    }

    public function testOptions(): void
    {
        $definition = $this->initAssetDefinition(capacities: [
            new Capacity(name: HasOperatingSystemCapacity::class),
            new Capacity(name: HasVolumesCapacity::class),
            new Capacity(name: HasSoftwaresCapacity::class),
            new Capacity(name: HasAntivirusCapacity::class),
        ]);
        $this->createCustomField($definition, 'reference', StringType::class);
        $this->createCustomField($definition, 'tags', DropdownType::class, Location::class, ['multiple' => true]);
        $this->createCustomField($definition, 'secret', StringType::class, field_options: ['hidden' => [-1]]);
        $this->login();

        $options = $this->getInjection($definition)->getOptions();
        $by_linkfield = array_column($options, null, 'linkfield');

        foreach (['name', 'serial', 'otherserial', 'locations_id', 'manufacturers_id', 'states_id', 'custom_reference'] as $linkfield) {
            self::assertArrayHasKey($linkfield, $by_linkfield);
        }

        self::assertSame('dropdown', $by_linkfield['assets_assettypes_id']['displaytype']);
        self::assertSame('dropdown', $by_linkfield['assets_assetmodels_id']['displaytype']);
        self::assertSame('multiline_text', $by_linkfield['comment']['displaytype']);
        self::assertSame('user', $by_linkfield['users_id']['displaytype']);
        self::assertArrayHasKey('groups_id_normal', $by_linkfield);
        self::assertArrayHasKey('groups_id', $by_linkfield);

        // Each option is stored in the asset itself, linked items have their own injection type
        foreach (array_keys($by_linkfield) as $linkfield) {
            self::assertTrue(
                $this->getInjection($definition)->isField($linkfield)
                || in_array($linkfield, ['groups_id', 'groups_id_normal', 'templates_id', 'custom_reference'], true),
                "{$linkfield} is not stored in the asset",
            );
        }

        self::assertArrayNotHasKey('assets_assetdefinitions_id', $by_linkfield);
        self::assertArrayNotHasKey('custom_fields', $by_linkfield);
        self::assertArrayNotHasKey('custom_tags', $by_linkfield);
        self::assertArrayNotHasKey('custom_secret', $by_linkfield);
    }

    public function testInjectAsset(): void
    {
        $definition = $this->initAssetDefinition();
        $dropdown_definition = $this->initDropdownDefinition();
        foreach (
            [
                'reference' => StringType::class,
                'notes'     => TextType::class,
                'quantity'  => NumberType::class,
                'delivery'  => DateType::class,
                'audited'   => DateTimeType::class,
                'is_spare'  => BooleanType::class,
            ] as $system_name => $type
        ) {
            $this->createCustomField($definition, $system_name, $type);
        }

        $this->createCustomField($definition, 'room', DropdownType::class, Location::class);
        $this->createCustomField($definition, 'color', DropdownType::class, $dropdown_definition->getDropdownClassName());
        $group      = $this->createItem(Group::class, ['name' => 'Users group', 'entities_id' => 0, 'is_recursive' => 1]);
        $tech_group = $this->createItem(Group::class, ['name' => 'Tech group', 'entities_id' => 0, 'is_recursive' => 1]);
        $this->login();

        $results = $this->inject($definition, [
            'name'                  => 'Injected asset',
            'serial'                => 'SN-001',
            'comment'               => "first line\nsecond line",
            'assets_assettypes_id'  => 'Server',
            'assets_assetmodels_id' => 'X42',
            'manufacturers_id'      => 'ACME',
            'groups_id_normal'      => $group->fields['name'],
            'groups_id'             => $tech_group->fields['name'],
            'custom_reference'      => 'REF-42',
            'custom_notes'          => 'Some notes',
            'custom_quantity'       => '7',
            'custom_delivery'       => '2026-10-23',
            'custom_audited'        => '2026-10-23 10:30:00',
            'custom_is_spare'       => '1',
            'custom_room'           => 'Room 101',
            'custom_color'          => 'Blue',
        ]);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $results['type']);

        $asset = $this->getItem($definition, $results);
        self::assertSame('Injected asset', $asset->fields['name']);
        self::assertSame('SN-001', $asset->fields['serial']);
        self::assertSame("first line\nsecond line", $asset->fields['comment']);
        self::assertSame($definition->getID(), $asset->fields['assets_assetdefinitions_id']);

        // Missing dropdowns are created for the injected asset definition
        $type = $definition->getAssetTypeClassName();
        self::assertSame('Server', $type::getById($asset->fields['assets_assettypes_id'])->fields['name']);
        $model = $definition->getAssetModelClassName();
        self::assertSame('X42', $model::getById($asset->fields['assets_assetmodels_id'])->fields['name']);
        self::assertSame('ACME', Manufacturer::getById($asset->fields['manufacturers_id'])->fields['name']);
        self::assertSame([$group->getID()], $asset->fields['groups_id']);
        self::assertSame([$tech_group->getID()], $asset->fields['groups_id_tech']);

        self::assertSame('REF-42', $asset->fields['custom_reference']);
        self::assertSame('Some notes', $asset->fields['custom_notes']);
        self::assertEquals(7, $asset->fields['custom_quantity']);
        self::assertSame('2026-10-23', $asset->fields['custom_delivery']);
        self::assertSame('2026-10-23 10:30:00', $asset->fields['custom_audited']);
        self::assertTrue((bool) $asset->fields['custom_is_spare']);
        self::assertSame('Room 101', Location::getById($asset->fields['custom_room'])->fields['name']);
        $color = $dropdown_definition->getDropdownClassName();
        self::assertSame('Blue', $color::getById($asset->fields['custom_color'])->fields['name']);
    }

    public function testUpdateOnlyMatchesAssetsOfTheSameDefinition(): void
    {
        $definition       = $this->initAssetDefinition();
        $other_definition = $this->initAssetDefinition();
        $this->login();

        $other = $this->getItem($other_definition, $this->inject($other_definition, ['name' => 'Shared name', 'serial' => 'OTHER']));

        $added = $this->inject($definition, ['name' => 'Shared name', 'serial' => 'FIRST']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $added['type']);

        $updated = $this->inject($definition, ['name' => 'Shared name', 'serial' => 'SECOND']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $updated['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $updated['type']);
        self::assertSame($this->getItem($definition, $added)->getID(), $this->getItem($definition, $updated)->getID());
        self::assertSame('SECOND', $this->getItem($definition, $updated)->fields['serial']);

        self::assertTrue($other->getFromDB($other->getID()));
        self::assertSame('OTHER', $other->fields['serial']);
    }

    public function testDropdownsOfAnotherDefinitionAreNotReused(): void
    {
        $definition_a = $this->initAssetDefinition();
        $definition_b = $this->initAssetDefinition();
        $colors_a     = $this->initDropdownDefinition()->getDropdownClassName();
        $colors_b     = $this->initDropdownDefinition()->getDropdownClassName();
        $this->createCustomField($definition_a, 'color', DropdownType::class, $colors_a);
        $this->createCustomField($definition_b, 'color', DropdownType::class, $colors_b);

        // Definition A already has the same names
        $type_a  = $this->createItem($definition_a->getAssetTypeClassName(), ['name' => 'Server']);
        $model_a = $this->createItem($definition_a->getAssetModelClassName(), ['name' => 'X42']);
        $color_a = $this->createItem($colors_a, ['name' => 'Blue']);
        $this->login();

        $results = $this->inject($definition_b, [
            'name'                  => 'Asset of B',
            'assets_assettypes_id'  => 'Server',
            'assets_assetmodels_id' => 'X42',
            'custom_color'          => 'Blue',
        ]);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);

        $asset = $this->getItem($definition_b, $results);
        self::assertNotEquals($type_a->getID(), $asset->fields['assets_assettypes_id']);
        self::assertNotEquals($model_a->getID(), $asset->fields['assets_assetmodels_id']);
        self::assertNotEquals($color_a->getID(), $asset->fields['custom_color']);

        // The items are created for definition B
        $type_b = $definition_b->getAssetTypeClassName();
        self::assertSame('Server', $type_b::getById($asset->fields['assets_assettypes_id'])->fields['name']);
        $model_b = $definition_b->getAssetModelClassName();
        self::assertSame('X42', $model_b::getById($asset->fields['assets_assetmodels_id'])->fields['name']);
        self::assertSame('Blue', $colors_b::getById($asset->fields['custom_color'])->fields['name']);
    }

    public static function customFieldMatchProvider(): iterable
    {
        yield 'string' => [StringType::class, [], 'Réf/1', 'Réf/1', 'Réf/2'];
        yield 'integer written differently' => [NumberType::class, [], '7', '07', '8'];
        yield 'decimal written differently' => [NumberType::class, ['step' => 0.01], '7', '7.00', '7.5'];
        yield 'yes/no stored as a boolean' => [BooleanType::class, [], '1', '1', '0'];
    }

    #[DataProvider('customFieldMatchProvider')]
    public function testUpdateMatchesACustomField(
        string $type,
        array $field_options,
        string $stored_value,
        string $matching_value,
        string $other_value,
    ): void {
        $definition = $this->initAssetDefinition();
        $this->createCustomField($definition, 'key', $type, field_options: $field_options);
        $this->login();

        $mandatory = ['custom_key' => true];
        $added     = $this->inject($definition, ['custom_key' => $stored_value, 'name' => 'Before'], mandatory_fields: $mandatory);
        $this->inject($definition, ['custom_key' => $other_value, 'name' => 'Other'], mandatory_fields: $mandatory);

        $updated = $this->inject($definition, ['custom_key' => $matching_value, 'name' => 'After'], mandatory_fields: $mandatory);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $updated['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $updated['type']);
        self::assertSame($this->getItem($definition, $added)->getID(), $this->getItem($definition, $updated)->getID());
        self::assertSame('After', $this->getItem($definition, $updated)->fields['name']);
    }

    public function testEmptyCustomFieldIsNotSetToZero(): void
    {
        $definition = $this->initAssetDefinition();
        // Contains "is_", like the boolean fields which are set to 0 when empty
        $this->createCustomField($definition, 'basis_note', StringType::class);
        $this->login();

        $results = $this->inject($definition, ['name' => 'Empty custom field', 'custom_basis_note' => '']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame('', $this->getItem($definition, $results)->fields['custom_basis_note']);
    }

    public function testInjectionClassCannotBeResolvedFromItsName(): void
    {
        $this->expectException(HttpException::class);
        PluginDatainjectionCommonInjectionLib::getItemtypeByInjection(AssetInjection::class);
    }

    public function testInjectLinkedItems(): void
    {
        $definition = $this->initAssetDefinition(capacities: [
            new Capacity(name: HasOperatingSystemCapacity::class),
            new Capacity(name: HasInfocomCapacity::class),
        ]);
        $this->login();

        $results = $this->inject($definition, ['name' => 'Asset with linked items'], [
            Item_OperatingSystem::class => ['operatingsystems_id' => 'Debian'],
            Infocom::class              => ['order_number' => 'PO-42'],
        ]);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);

        $asset = $this->getItem($definition, $results);
        $criteria = ['itemtype' => $asset::class, 'items_id' => $asset->getID()];

        $item_os = new Item_OperatingSystem();
        self::assertTrue($item_os->getFromDBByCrit($criteria));
        self::assertSame('Debian', OperatingSystem::getById($item_os->fields['operatingsystems_id'])->fields['name']);

        $infocom = new Infocom();
        self::assertTrue($infocom->getFromDBByCrit($criteria));
        self::assertSame('PO-42', $infocom->fields['order_number']);
    }

    private function createCustomField(
        AssetDefinition $definition,
        string $system_name,
        string $type,
        ?string $itemtype = null,
        array $field_options = [],
    ): void {
        $this->createItem(CustomFieldDefinition::class, [
            'assets_assetdefinitions_id' => $definition->getID(),
            'system_name'                => $system_name,
            'label'                      => ucfirst($system_name),
            'type'                       => $type,
            'itemtype'                   => $itemtype,
            'field_options'              => $field_options,
        ], ['itemtype', 'field_options']);
    }

    private function getInjection(AssetDefinition $definition): AssetInjection
    {
        $injection = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($definition->getAssetClassName());
        self::assertInstanceOf(AssetInjection::class, $injection);

        return $injection;
    }

    private function inject(
        AssetDefinition $definition,
        array $values,
        array $linked_values = [],
        array $mandatory_fields = ['name' => true],
    ): array {
        $itemtype = $definition->getAssetClassName();

        return $this->getInjection($definition)->addOrUpdateObject(
            // Added by the engine
            [$itemtype => $values + ['entities_id' => $this->getTestRootEntity(true)]] + $linked_values,
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                    'overwrite_notempty_fields' => true,
                ],
                'mandatory_fields' => [$itemtype => $mandatory_fields],
                'entities_id'      => $this->getTestRootEntity(true),
            ],
        );
    }

    private function getItem(AssetDefinition $definition, array $results): Asset
    {
        $itemtype = $definition->getAssetClassName();
        $asset    = new $itemtype();
        self::assertTrue($asset->getFromDB((int) $results[$itemtype]));

        return $asset;
    }
}
