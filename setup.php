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

define ('PLUGIN_DATAINJECTION_VERSION', '2.13.4');

// Minimal GLPI version, inclusive
define("PLUGIN_DATAINJECTION_MIN_GLPI", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_DATAINJECTION_MAX_GLPI", "10.0.99");

if (!defined("PLUGIN_DATAINJECTION_UPLOAD_DIR")) {
    define("PLUGIN_DATAINJECTION_UPLOAD_DIR", GLPI_PLUGIN_DOC_DIR."/datainjection/");
}

function plugin_init_datainjection() {

   global $PLUGIN_HOOKS, $CFG_GLPI, $INJECTABLE_TYPES;

   $PLUGIN_HOOKS['csrf_compliant']['datainjection'] = true;
   $PLUGIN_HOOKS['migratetypes']['datainjection'] = 'plugin_datainjection_migratetypes_datainjection';

   $plugin = new Plugin();
   if ($plugin->isActivated("datainjection")) {

      Plugin::registerClass(
          'PluginDatainjectionProfile',
          ['addtabon' => ['Profile']
          ]
      );

      //If directory doesn't exists, create it
      if (!plugin_datainjection_checkDirectories()) {
          @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR)
          or die(
              sprintf(
                  __('%1$s %2$s'), __("Can't create folder", 'datainjection'),
                  PLUGIN_DATAINJECTION_UPLOAD_DIR
              )
          );
      }
      if (Session::haveRight('plugin_datainjection_use', READ)) {
          $PLUGIN_HOOKS["menu_toadd"]['datainjection'] = ['tools'  => 'PluginDatainjectionMenu'];
      }

      $PLUGIN_HOOKS['pre_item_purge']['datainjection']
          = ['Profile' => ['PluginDatainjectionProfile', 'purgeProfiles']];

         // Css file
      if (strpos($_SERVER['REQUEST_URI'] ?? '', Plugin::getPhpDir('datainjection', false)) !== false) {
         $PLUGIN_HOOKS['add_css']['datainjection'] = 'css/datainjection.css';
      }

      // Javascript file
      $PLUGIN_HOOKS['add_javascript']['datainjection'] = 'js/datainjection.js';

      // Inbtegration with Webservices plugin
      $PLUGIN_HOOKS['webservices']['datainjection'] = 'plugin_datainjection_registerMethods';
      $INJECTABLE_TYPES = [];

   }
}


function plugin_version_datainjection() {

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
         ]
      ]
   ];
}


/**
 * Return all types that can be injected using datainjection
 *
 * @return an array of injection class => plugin
 */
function getTypesToInject() {

   global $INJECTABLE_TYPES,$PLUGIN_HOOKS;

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
                        'PluginDatainjectionComputer_ItemInjection'               => 'datainjection',
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
                        'PluginDatainjectionItem_SoftwareVersionInjection'        => 'datainjection',
                        'PluginDatainjectionItem_SoftwareLicenseInjection'        => 'datainjection',
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
                        'PluginDatainjectionOperatingSystemKernelVersionInjection'=> 'datainjection',
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
                        'PluginDatainjectionCertificateInjection'                 => 'datainjection'
   ];
   //Add plugins
   Plugin::doHook('plugin_datainjection_populate');
}


function plugin_datainjection_migratetypes_datainjection($types) {

   $types[996] = 'NetworkPort';
   $types[999] = 'NetworkPort';
   return $types;
}


function plugin_datainjection_checkDirectories() {

   if (!file_exists(PLUGIN_DATAINJECTION_UPLOAD_DIR) || !is_writable(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
      return false;
   }
   return true;
}
