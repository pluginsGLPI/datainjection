<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2026 Teclib' and contributors.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

namespace GlpiPlugin\Datainjection\Glpi\Asset;

use Glpi\Asset\Asset;
use Glpi\Asset\AssetDefinition;
use Glpi\Asset\AssetDefinitionManager;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionInjectionInterface;
use Search;

use function Safe\preg_replace;

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
        return true;
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
            } else {
                if ($value === self::getTable()) {
                    $data[$key] = self::getVirtualTable();
                }
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
        //        $tab = Search::getOptions(static::class);
        $tab = Search::getOptions(self::getVirtualType());

        $tab = self::replaceTableName($tab);
        unset($tab[4]);
        unset($tab[40]);

        $tab2 = [
//            [
//                'id' => '4',
//                'table' => 'glpi_assets_assettypes',
//                'field' => 'name',
//                'linkfield' => 'assets_assettypes_id',
//                'name' => __('Type'),
//                'datatype' => 'dropdown',
//                'injectable' => true,
//            ],
//            [
//                'id' => '40',
//                'table' => 'glpi_assets_assetmodels',
//                'field' => 'name',
//                'linkfield' => 'assets_assetmodels_id',
//                'name' => __('Model'),
//                'datatype' => 'dropdown',
//                'injectable' => true,
//            ],
            [
                'id' => '200',
                'table' => $this->getTable(),
                'field' => 'assets_assetdefinitions_id',
                'linkfield' => 'assets_assetdefinitions_id',
                'name' => AssetDefinition::getTypeName(),
                'datatype' => 'number',
                'massiveaction' => false,
                'injectable' => true,
            ],
        ];

        $tab = array_merge($tab, $tab2);
        //Remove some options because some fields cannot be imported
        $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(static::class);
        $blacklist = [];
        $notimportable            = ['300', '301'];
        $options['ignore_fields'] = array_merge($blacklist, $notimportable);

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options ?? [], $this);
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
