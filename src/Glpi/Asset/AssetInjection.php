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

namespace GlpiPlugin\Datainjection\Glpi\Asset;

use CommonDBTM;
use Glpi\Asset\Asset;
use Glpi\Asset\AssetDefinition;
use Glpi\Asset\AssetDefinitionManager;
use Glpi\Asset\CustomFieldDefinition;
use Glpi\Asset\CustomFieldType\BooleanType;
use Glpi\Asset\CustomFieldType\DateTimeType;
use Glpi\Asset\CustomFieldType\DateType;
use Glpi\Asset\CustomFieldType\DropdownType;
use Glpi\Asset\CustomFieldType\NumberType;
use Glpi\Asset\CustomFieldType\StringType;
use Glpi\Asset\CustomFieldType\TextType;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionInjectionInterface;
use Search;

abstract class AssetInjection extends Asset implements PluginDatainjectionInjectionInterface
{
    protected static string $definition_system_name = '';

    protected static int $fixed_asset_definition_id = 0;

    public static function getAssetDefinitionID(): int
    {

        // If a fixed ID is set (for dynamically created classes), use it
        if (static::$fixed_asset_definition_id > 0) {
            return static::$fixed_asset_definition_id;
        }

        return 0;
    }

    /**
     * Get the first active asset definition to use as system name
     */
    protected static function getAssetDefinitionSystemName(): string
    {
        // Get first active asset definition from the database
        // This ensures we have a valid definition to use
        $manager = AssetDefinitionManager::getInstance();
        foreach ($manager->getDefinitions() as $definition) {
            if ($definition->fields['id'] == static::getAssetDefinitionID()) {
                return $definition->fields['system_name'];
            }
        }

        // Fallback to a default if none found
        return '';
    }

    public static function getTypeName($nb = 0)
    {
        $manager = AssetDefinitionManager::getInstance();
        foreach ($manager->getDefinitions() as $definition) {
            if ($definition->fields['id'] == static::getAssetDefinitionID()) {
                return $definition->fields['label'];
            }
        }

        // Fallback to a default if none found
        return '';
    }

    public static function getDefinition(): AssetDefinition
    {
        if (empty(static::$definition_system_name)) {
            static::$definition_system_name = static::getAssetDefinitionSystemName();
        }

        return parent::getDefinition();
    }


    public static function getVirtualType($classname = null)
    {
        return 'Glpi\\CustomAsset\\' . static::getAssetDefinitionSystemName() . 'Asset';
    }

    public static function getVirtualTable($classname = null)
    {
        return getTableForItemType(self::getVirtualType());
    }

    public static function getTable($classname = null)
    {
        return getTableForItemType(Asset::class);
    }

    public static function canCreate(): bool
    {
        $virtual_type = static::getVirtualType();
        return $virtual_type::canCreate();
    }

    public function isPrimaryType()
    {
        return true;
    }

    public function connectedTo()
    {
        return [];
    }

    public function isNullable($field)
    {
        return true;
    }


    public static function replaceTableName(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::replaceTableName($value); // récursif
            } elseif ($value === self::getTable()) {
                $data[$key] = self::getVirtualTable();
            }
        }

        return $data;
    }

    /**
     * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
     */
    public function getOptions($primary_type = '')
    {

        // Build search options manually to avoid registration checks
        $tab = Search::getOptions(self::getVirtualType());

        $tab = self::replaceTableName($tab);
        unset($tab[4]);
        unset($tab[40]);

        $tab2 = [
            [
                'id' => '200',
                'table' => static::getTable(),
                'field' => 'assets_assetdefinitions_id',
                'linkfield' => 'assets_assetdefinitions_id',
                'name' => AssetDefinition::getTypeName(),
                'datatype' => 'number',
                'massiveaction' => false,
                'injectable' => true,
            ],
        ];

        foreach ($tab2 as $entry) {
            $tab[(int) $entry['id']] = $entry;
        }

        //Remove some options because some fields cannot be imported
        $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(static::class);
        $notimportable            = ['300', '301'];
        $options = [];
        $options['ignore_fields'] = array_merge($blacklist, $notimportable);
        $options['displaytype'] = ['multiline_text' => [16]];

        $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

        // Added after the generic filter, which only keeps the core options (id < 1000)
        foreach (static::getDefinition()->getCustomFieldDefinitions() as $custom_field) {
            $option = $this->getCustomFieldOption($custom_field);
            if ($option !== null) {
                $tab[$option['id']] = $option;
            }
        }

        return $tab;
    }

    /**
     * Injection option of a custom field
     *
     * The core search option of a custom field cannot be injected: its linkfield is the
     * JSON column "custom_fields", shared by every custom field. The value is injected
     * as "custom_<system_name>", which Asset::prepareInputForAdd/Update() stores in it.
     *
     * @return array|null null for a type that cannot be injected
     */
    private function getCustomFieldOption(CustomFieldDefinition $custom_field): ?array
    {
        $field_type = $custom_field->getFieldType();
        $option     = [
            'id'         => $custom_field->getSearchOptionID(),
            'name'       => $custom_field->computeFriendlyName(),
            'table'      => self::getVirtualTable(),
            'field'      => 'custom_' . $custom_field->fields['system_name'],
            'linkfield'  => 'custom_' . $custom_field->fields['system_name'],
            'injectable' => PluginDatainjectionCommonInjectionLib::FIELD_INJECTABLE,
        ];

        // DateTimeType extends DateType: test it first
        if ($field_type instanceof DateTimeType) {
            return $option + ['displaytype' => 'text', 'checktype' => 'datetime', 'datatype' => 'datetime'];
        }

        if ($field_type instanceof DateType) {
            return $option + ['displaytype' => 'text', 'checktype' => 'date', 'datatype' => 'date'];
        }

        if ($field_type instanceof BooleanType) {
            return $option + ['displaytype' => 'bool', 'checktype' => 'bool', 'datatype' => 'bool'];
        }

        if ($field_type instanceof NumberType) {
            return $option + ['displaytype' => 'text', 'checktype' => 'float', 'datatype' => 'decimal'];
        }

        if ($field_type instanceof TextType) {
            return $option + ['displaytype' => 'multiline_text', 'checktype' => 'text', 'datatype' => 'text'];
        }

        // Also URLType, which extends StringType
        if ($field_type instanceof StringType) {
            return $option + ['displaytype' => 'text', 'checktype' => 'text', 'datatype' => 'string'];
        }

        // A multiple dropdown would need a separator in the imported value: not handled
        if (
            $field_type instanceof DropdownType
            && !($custom_field->fields['field_options']['multiple'] ?? false)
            && is_a($custom_field->fields['itemtype'], CommonDBTM::class, true)
        ) {
            return $option + [
                'displaytype'       => 'dropdown',
                'checktype'         => 'text',
                'datatype'          => 'dropdown',
                // The option keeps the asset table, like the other custom fields: the lib
                // finds the dropdown through this itemtype (custom dropdowns share one table)
                'dropdown_itemtype' => $custom_field->fields['itemtype'],
            ];
        }

        return null;
    }


    /**
     * @param array $fields_toinject    array
     **/
    public function getValueForAdditionalMandatoryFields($fields_toinject = [])
    {
        $fields_toinject[self::getVirtualType()]['assets_assetdefinitions_id'] = static::getAssetDefinitionID();

        return $fields_toinject;
    }

    /**
     * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
     */
    public function addOrUpdateObject($values = [], $options = [])
    {

        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }
}
