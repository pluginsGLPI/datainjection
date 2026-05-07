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

use Glpi\Asset\AssetDefinition;
use Glpi\Asset\AssetDefinitionManager;
use GlpiPlugin\Datainjection\Glpi\Asset\Capacity\IsInjectableCapacity;

use function Safe\define;
use function Safe\mkdir;

define('PLUGIN_DATAINJECTION_VERSION', '2.15.6');

// Minimal GLPI version, inclusive
define("PLUGIN_DATAINJECTION_MIN_GLPI", "11.0.5");
// Maximum GLPI version, exclusive
define("PLUGIN_DATAINJECTION_MAX_GLPI", "11.0.99");

if (!defined("PLUGIN_DATAINJECTION_UPLOAD_DIR")) {
    define("PLUGIN_DATAINJECTION_UPLOAD_DIR", GLPI_PLUGIN_DOC_DIR . "/datainjection/");
}

function plugin_init_datainjection()
{
    /** @var array $PLUGIN_HOOKS */
    /** @var array $CFG_GLPI */
    /** @var array $INJECTABLE_TYPES */
    global $PLUGIN_HOOKS, $CFG_GLPI, $INJECTABLE_TYPES;

    if (!isset($CFG_GLPI['injectable_types'])) {
        $CFG_GLPI['injectable_types'] = [];
    }
    $asset_definition_manager = AssetDefinitionManager::getInstance();
    $asset_definition_manager->registerCapacity(new IsInjectableCapacity());

    $PLUGIN_HOOKS['csrf_compliant']['datainjection'] = true;
    $PLUGIN_HOOKS['migratetypes']['datainjection'] = 'plugin_datainjection_migratetypes_datainjection';

    $plugin = new Plugin();
    if ($plugin->isActivated("datainjection")) {
        Plugin::registerClass(
            'PluginDatainjectionProfile',
            ['addtabon' => ['Profile'],
            ],
        );

        //If directory doesn't exists, create it
        if (!plugin_datainjection_checkDirectories()) {
            @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR);
        }
        $PLUGIN_HOOKS["config_page"]['datainjection'] = "front/clientinjection.form.php";



        if (Session::haveRight('plugin_datainjection_use', READ)) {
            $PLUGIN_HOOKS["menu_toadd"]['datainjection'] = ['tools'  => 'PluginDatainjectionMenu'];
        }

        $PLUGIN_HOOKS['pre_item_purge']['datainjection']
          = ['Profile' => ['PluginDatainjectionProfile', 'purgeProfiles']];

        // Css file
        if (str_contains($_SERVER['REQUEST_URI'] ?? '', Plugin::getPhpDir('datainjection', false))) {
            $PLUGIN_HOOKS['add_css']['datainjection'] = 'css/datainjection.css';
        }

        // Javascript file
        $PLUGIN_HOOKS['add_javascript']['datainjection'] = 'js/datainjection.js';

        $INJECTABLE_TYPES = [];

//        plugin_datainjection_creationInjectableAssets();
    }
}


function plugin_version_datainjection()
{

    return [
        'name'         => __('Data injection', 'datainjection'),
        'author'       => 'Walid Nouh, Remi Collet, Nelly Mahu-Lasson, Xavier Caillaud',
        'homepage'     => 'https://github.com/pluginsGLPI/datainjection',
        'license'      => 'GPLv2+',
        'version'      => PLUGIN_DATAINJECTION_VERSION,
        'requirements' => [
            'glpi' => [
                'min' => PLUGIN_DATAINJECTION_MIN_GLPI,
                'max' => PLUGIN_DATAINJECTION_MAX_GLPI,
            ],
        ],
    ];
}


/**
 * Return all types that can be injected using datainjection
 *
 * @return void
 */
function getTypesToInject(): void
{
    /** @var array $INJECTABLE_TYPES */
    /** @var array $PLUGIN_HOOKS */
    global $INJECTABLE_TYPES,$CFG_GLPI;

    if (count($INJECTABLE_TYPES)) {
        // already populated
        return;
    }

    $INJECTABLE_TYPES = ['PluginDatainjectionCartridgeItemInjection'              => 'datainjection',
        'PluginDatainjectionBudgetInjection'                      => 'datainjection',
        'PluginDatainjectionComputerInjection'                    => 'datainjection',
        'PluginDatainjectionDatabaseInjection'                    => 'datainjection',
        'PluginDatainjectionDatabaseInstanceInjection'            => 'datainjection',
        'PluginDatainjectionNotepadInjection'                     => 'datainjection',
        //'PluginDatainjectionComputer_ItemInjection'               => 'datainjection',
        'PluginDatainjectionConsumableItemInjection'              => 'datainjection',
        'PluginDatainjectionContactInjection'                     => 'datainjection',
        'PluginDatainjectionContact_SupplierInjection'            => 'datainjection',
        'PluginDatainjectionContractInjection'                    => 'datainjection',
        'PluginDatainjectionContract_ItemInjection'               => 'datainjection',
        'PluginDatainjectionContract_SupplierInjection'           => 'datainjection',
        'PluginDatainjectionEntityInjection'                      => 'datainjection',
        'PluginDatainjectionGroupInjection'                       => 'datainjection',
        'PluginDatainjectionGroup_UserInjection'                  => 'datainjection',
        'PluginDatainjectionInfocomInjection'                     => 'datainjection',
        'PluginDatainjectionLocationInjection'                    => 'datainjection',
        'PluginDatainjectionCategoryInjection'                    => 'datainjection',
        'PluginDatainjectionStateInjection'                       => 'datainjection',
        'PluginDatainjectionManufacturerInjection'                => 'datainjection',
        'PluginDatainjectionMonitorInjection'                     => 'datainjection',
        'PluginDatainjectionNetworkequipmentInjection'            => 'datainjection',
        'PluginDatainjectionPeripheralInjection'                  => 'datainjection',
        'PluginDatainjectionPhoneInjection'                       => 'datainjection',
        'PluginDatainjectionPrinterInjection'                     => 'datainjection',
        'PluginDatainjectionProfileInjection'                     => 'datainjection',
        'PluginDatainjectionProfile_UserInjection'                => 'datainjection',
        'PluginDatainjectionSoftwareInjection'                    => 'datainjection',
        'PluginDatainjectionSoftwareLicenseInjection'             => 'datainjection',
        'PluginDatainjectionSoftwareVersionInjection'             => 'datainjection',
        'PluginDatainjectionSupplierInjection'                    => 'datainjection',
        'PluginDatainjectionUserInjection'                        => 'datainjection',
        'PluginDatainjectionNetworkportInjection'                 => 'datainjection',
        'PluginDatainjectionVlanInjection'                        => 'datainjection',
        'PluginDatainjectionNetworkport_VlanInjection'            => 'datainjection',
        'PluginDatainjectionNetworkNameInjection'                 => 'datainjection',
        'PluginDatainjectionNetpointInjection'                    => 'datainjection',
        'PluginDatainjectionKnowbaseItemCategoryInjection'        => 'datainjection',
        'PluginDatainjectionKnowbaseItemInjection'                => 'datainjection',
        'PluginDatainjectionITILFollowupTemplateInjection'        => 'datainjection',
        'PluginDatainjectionITILCategoryInjection'                => 'datainjection',
        'PluginDatainjectionTaskCategoryInjection'                => 'datainjection',
        'PluginDatainjectionTaskTemplateInjection'                => 'datainjection',
        'PluginDatainjectionSolutionTypeInjection'                => 'datainjection',
        'PluginDatainjectionRequestTypeInjection'                 => 'datainjection',
        'PluginDatainjectionSolutionTemplateInjection'            => 'datainjection',
        'PluginDatainjectionComputerTypeInjection'                => 'datainjection',
        'PluginDatainjectionMonitorTypeInjection'                 => 'datainjection',
        'PluginDatainjectionNetworkEquipmentTypeInjection'        => 'datainjection',
        'PluginDatainjectionPeripheralTypeInjection'              => 'datainjection',
        'PluginDatainjectionPrinterTypeInjection'                 => 'datainjection',
        'PluginDatainjectionPhoneTypeInjection'                   => 'datainjection',
        'PluginDatainjectionSoftwareLicenseTypeInjection'         => 'datainjection',
        'PluginDatainjectionContractTypeInjection'                => 'datainjection',
        'PluginDatainjectionContactTypeInjection'                 => 'datainjection',
        'PluginDatainjectionSupplierTypeInjection'                => 'datainjection',
        'PluginDatainjectionDeviceMemoryTypeInjection'            => 'datainjection',
        'PluginDatainjectionInterfaceTypeInjection'               => 'datainjection',
        'PluginDatainjectionPhonePowerSupplyTypeInjection'        => 'datainjection',
        'PluginDatainjectionFilesystemTypeInjection'              => 'datainjection',
        'PluginDatainjectionComputerModelInjection'               => 'datainjection',
        'PluginDatainjectionMonitorModelInjection'                => 'datainjection',
        'PluginDatainjectionPhoneModelInjection'                  => 'datainjection',
        'PluginDatainjectionPrinterModelInjection'                => 'datainjection',
        'PluginDatainjectionPeripheralModelInjection'             => 'datainjection',
        'PluginDatainjectionNetworkEquipmentModelInjection'       => 'datainjection',
        //'PluginDatainjectionNetworkEquipmentFirmwareInjection'    => 'datainjection',
        'PluginDatainjectionVirtualMachineTypeInjection'          => 'datainjection',
        'PluginDatainjectionVirtualMachineSystemInjection'        => 'datainjection',
        'PluginDatainjectionVirtualMachineStateInjection'         => 'datainjection',
        'PluginDatainjectionDocumentTypeInjection'                => 'datainjection',
        'PluginDatainjectionAutoUpdateSystemInjection'            => 'datainjection',
        'PluginDatainjectionOperatingSystemInjection'             => 'datainjection',
        'PluginDatainjectionOperatingSystemVersionInjection'      => 'datainjection',
        'PluginDatainjectionOperatingSystemServicePackInjection'  => 'datainjection',
        'PluginDatainjectionOperatingSystemKernelInjection'       => 'datainjection',
        'PluginDatainjectionOperatingSystemKernelVersionInjection' => 'datainjection',
        'PluginDatainjectionOperatingSystemEditionInjection'      => 'datainjection',
        'PluginDatainjectionItem_OperatingSystemInjection'        => 'datainjection',
        'PluginDatainjectionNetworkInterfaceInjection'            => 'datainjection',
        'PluginDatainjectionDomainInjection'                      => 'datainjection',
        'PluginDatainjectionNetworkInjection'                     => 'datainjection',
        'PluginDatainjectionDeviceCaseInjection'                  => 'datainjection',
        'PluginDatainjectionDeviceCaseTypeInjection'              => 'datainjection',
        'PluginDatainjectionDeviceControlInjection'               => 'datainjection',
        'PluginDatainjectionDeviceProcessorInjection'             => 'datainjection',
        'PluginDatainjectionDeviceMemoryInjection'                => 'datainjection',
        'PluginDatainjectionDeviceHardDriveInjection'             => 'datainjection',
        'PluginDatainjectionDeviceMotherboardInjection'           => 'datainjection',
        'PluginDatainjectionDeviceDriveInjection'                 => 'datainjection',
        'PluginDatainjectionDeviceNetworkCardInjection'           => 'datainjection',
        'PluginDatainjectionApplianceInjection'                   => 'datainjection',
        'PluginDatainjectionCertificateInjection'                 => 'datainjection',
    ];
    //Add plugins
    Plugin::doHook('plugin_datainjection_populate');

    // Register injectable assets dynamically
    plugin_datainjection_registerInjectableAssets();

    plugin_datainjection_creationInjectableAssets();
}


/**
 * Register injection classes for each injectable asset definition
 */
function plugin_datainjection_registerInjectableAssets(): void
{
    /** @var array $INJECTABLE_TYPES */
    /** @var array $CFG_GLPI */
    global $INJECTABLE_TYPES, $CFG_GLPI;

    if (!isset($CFG_GLPI['injectable_types']) || empty($CFG_GLPI['injectable_types'])) {
        return;
    }

    // For each injectable asset definition, create and register a distinct class
    foreach ($CFG_GLPI['injectable_types'] as $definition_id => $itemtype) {
        // Get the asset definition to extract the system name

        $definition = AssetDefinition::getById($definition_id);
        if ($definition->getAssetClassName() === $itemtype) {
            // Use the system name to create a nice class name
            $system_name = ucfirst($definition->fields['system_name']);//strtolower()
            $injection_class = 'PluginDatainjection' . $system_name . 'AssetInjection';

            // Only create if not already registered
            if (!isset($INJECTABLE_TYPES[$injection_class])) {
                $INJECTABLE_TYPES[$injection_class] = 'datainjection';
            }
        }
    }
}

/**
 * Register injection classes for each injectable asset definition
 */
function plugin_datainjection_creationInjectableAssets(): void
{
    /** @var array $CFG_GLPI */
    global $CFG_GLPI;

    if (!isset($CFG_GLPI['injectable_types']) || empty($CFG_GLPI['injectable_types'])) {
        return;
    }


    // For each injectable asset definition, create and register a distinct class
    foreach ($CFG_GLPI['injectable_types'] as $definition_id => $itemtype) {
        $definition = AssetDefinition::getById($definition_id);
        // Get the asset definition to extract the system name
        if ($definition->getAssetClassName() === $itemtype) {
            // Use the system name to create a nice class name
            $system_name = ucfirst($definition->fields['system_name']);//strtolower()
            $injection_class = 'PluginDatainjection' . $system_name . 'AssetInjection';

            plugin_datainjection_createAssetInjectionClass($injection_class, $definition_id);
        }
    }
}

/**
 * Dynamically create an asset injection class
 */
function plugin_datainjection_createAssetInjectionClass(string $class_name, int $definition_id): void
{

    // Check if class already exists
    if (class_exists($class_name)) {
        return;
    }

    // Create the class dynamically (not final, so it can be extended)
    $code = <<<PHP

use GlpiPlugin\\Datainjection\\Glpi\\Asset\\AssetInjection;

final class $class_name extends AssetInjection implements PluginDatainjectionInjectionInterface
{
    protected static int \$fixed_asset_definition_id = $definition_id;

    public static function getAssetDefinitionID(): int
    {
        return self::\$fixed_asset_definition_id;
    }
}
PHP;

    eval($code);

}


function plugin_datainjection_migratetypes_datainjection($types)
{

    $types[996] = 'NetworkPort';
    $types[999] = 'NetworkPort';
    return $types;
}


function plugin_datainjection_checkDirectories()
{
    return !(!file_exists(PLUGIN_DATAINJECTION_UPLOAD_DIR) || !is_writable(PLUGIN_DATAINJECTION_UPLOAD_DIR));
}

function plugin_datainjection_geturl(): string
{
    /** @var array $CFG_GLPI */
    global $CFG_GLPI;
    return sprintf('%s/plugins/datainjection/', $CFG_GLPI['root_doc']);
}
