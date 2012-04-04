<?php

/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the order plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined("PLUGIN_DATAINJECTION_UPLOAD_DIR")) {
   define("PLUGIN_DATAINJECTION_UPLOAD_DIR", GLPI_PLUGIN_DOC_DIR."/datainjection/");
}

function plugin_init_datainjection() {
   global $PLUGIN_HOOKS, $CFG_GLPI, $LANG, $INJECTABLE_TYPES;

   $plugin = new Plugin();
   $PLUGIN_HOOKS['change_profile']['datainjection'] = 'plugin_datainjection_changeprofile';

   $PLUGIN_HOOKS['migratetypes']['datainjection'] = 'plugin_datainjection_migratetypes_datainjection';

   if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {
      if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {
      if (!plugin_datainjection_checkDirectories()) {
         logDebug("[Datainjection plugin] ".PLUGIN_DATAINJECTION_UPLOAD_DIR.
                     " ".$LANG['datainjection']['install'][1]);
         return false;
      }
      
      $PLUGIN_HOOKS['headings']['datainjection']        = 'plugin_get_headings_datainjection';
      $PLUGIN_HOOKS['headings_action']['datainjection'] = 'plugin_headings_actions_datainjection';

      $image_import  = "<img src='".$CFG_GLPI["root_doc"]."/pics/actualiser.png' title='";
      $image_import .= $LANG['datainjection']['importStep'][1];
      $image_import .= "' alt='".$LANG['datainjection']['importStep'][1]."'>";

      if (plugin_datainjection_haveRight("model", "r")) {
         $PLUGIN_HOOKS['menu_entry']['datainjection'] = true;
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['title']
                                                   = $LANG['datainjection']['model'][0];
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['page']
                                                   = '/plugins/datainjection/front/model.php';
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['links']['search']
                                                   = '/plugins/datainjection/front/model.php';
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['links']['add']
                                                   = '/plugins/datainjection/front/model.form.php';

         $image_model  = "<img src='".$CFG_GLPI["root_doc"]."/pics/rdv.png' title='";
         $image_model .= $LANG['datainjection']['profiles'][1];
         $image_model .= "' alt='".$LANG['datainjection']['profiles'][1]."'>";
         $PLUGIN_HOOKS['submenu_entry']['datainjection'][$image_model] = 'front/model.php';
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['links'][$image_import]
                                                   = '/plugins/datainjection/index.php';
      }

      $PLUGIN_HOOKS['submenu_entry']['datainjection']['add'] = 'index.php';

      $PLUGIN_HOOKS['pre_item_delete']['datainjection'] = 'plugin_pre_item_delete_datainjection';

      // Css file
      $PLUGIN_HOOKS['add_css']['datainjection'] = 'css/datainjection.css';

      // Javascript file
      $PLUGIN_HOOKS['add_javascript']['datainjection'] = 'javascript/datainjection.js';

      // Inbtegration with Webservices plugin
      $PLUGIN_HOOKS['webservices']['datainjection'] = 'plugin_datainjection_registerMethods';

      $INJECTABLE_TYPES = array();
   }
}


function plugin_version_datainjection() {
   global $LANG;

   return array (
      'name'           => $LANG['datainjection']['name'][1],
      'minGlpiVersion' => '0.83',
      'author'         => 'Walid Nouh & Remi Collet',
      'homepage'       => 'https://forge.indepnet.net/projects/datainjection',
      'license'        => 'GPLv2+',
      'version'        => '2.2.0'
   );
}


function plugin_datainjection_haveRight($module, $right) {

   $matches = array(""  => array ("", "r", "w"), // ne doit pas arriver normalement
                    "r" => array ("r", "w"),
                    "w" => array ("w"),
                    "1" => array ("1"),
                    "0" => array ("0", "1")); // ne doit pas arriver non plus

   if (isset ($_SESSION["glpi_plugin_datainjection_profile"][$module])
       && in_array($_SESSION["glpi_plugin_datainjection_profile"][$module], $matches[$right])) {
      return true;
   }
   return false;
}


function plugin_datainjection_check_prerequisites() {
  global $LANG;
  if (!plugin_datainjection_checkDirectories()) {
      echo PLUGIN_DATAINJECTION_UPLOAD_DIR. " ".$LANG['datainjection']['install'][1];
      return false;
   }
   if (version_compare(GLPI_VERSION,'0.83','lt') || version_compare(GLPI_VERSION,'0.84','ge')) {
      echo "This plugin requires GLPI >= 0.83 and < 0.83";
      return false;
   }
   return true;
}


function plugin_datainjection_check_config($verbose=false) {
   return true;
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

   $INJECTABLE_TYPES = array('PluginDatainjectionCartridgeItemInjection'        => 'datainjection',
                             'PluginDatainjectionBudgetInjection'               => 'datainjection',
                             'PluginDatainjectionComputerInjection'             => 'datainjection',
                             'PluginDatainjectionComputer_ItemInjection'        => 'datainjection',
                             'PluginDatainjectionConsumableItemInjection'       => 'datainjection',
                             'PluginDatainjectionContactInjection'              => 'datainjection',
                             'PluginDatainjectionContact_SupplierInjection'     => 'datainjection',
                             'PluginDatainjectionContractInjection'             => 'datainjection',
                             'PluginDatainjectionContract_ItemInjection'        => 'datainjection',
                                //'PluginDatainjectionDocumentInjection'        => 'datainjection',
                             'PluginDatainjectionEntityInjection'               => 'datainjection',
                             'PluginDatainjectionEntityDataInjection'           => 'datainjection',
                             'PluginDatainjectionGroupInjection'                => 'datainjection',
                             'PluginDatainjectionGroup_UserInjection'           => 'datainjection',
                             'PluginDatainjectionInfocomInjection'              => 'datainjection',
                             'PluginDatainjectionLocationInjection'             => 'datainjection',
                             'PluginDatainjectionStateInjection'                => 'datainjection',
                             'PluginDatainjectionManufacturerInjection'         => 'datainjection',
                             'PluginDatainjectionMonitorInjection'              => 'datainjection',
                             'PluginDatainjectionNetworkequipmentInjection'     => 'datainjection',
                             'PluginDatainjectionPeripheralInjection'           => 'datainjection',
                             'PluginDatainjectionPhoneInjection'                => 'datainjection',
                             'PluginDatainjectionPrinterInjection'              => 'datainjection',
                             'PluginDatainjectionProfileInjection'              => 'datainjection',
                             'PluginDatainjectionProfile_UserInjection'         => 'datainjection',
                             'PluginDatainjectionSoftwareInjection'             => 'datainjection',
                             'PluginDatainjectionSoftwareLicenseInjection'      => 'datainjection',
                             'PluginDatainjectionSoftwareVersionInjection'      => 'datainjection',
                             'PluginDatainjectionSupplierInjection'             => 'datainjection',
                             'PluginDatainjectionUserInjection'                 => 'datainjection',
                             'PluginDatainjectionNetworkportInjection'          => 'datainjection',
                             'PluginDatainjectionVlanInjection'                 => 'datainjection',
                             'PluginDatainjectionNetworkport_VlanInjection'     => 'datainjection',
                             'PluginDatainjectionNetpointInjection'             => 'datainjection',
                             'PluginDatainjectionKnowbaseItemCategoryInjection' => 'datainjection',
                             'PluginDatainjectionKnowbaseItemInjection'         => 'datainjection',
                             'PluginDatainjectionITILCategoryInjection'         => 'datainjection',
                             'PluginDatainjectionTaskCategoryInjection'         => 'datainjection',
                             'PluginDatainjectionSolutionTypeInjection'         => 'datainjection',
                             'PluginDatainjectionRequestTypeInjection'          => 'datainjection',
                             'PluginDatainjectionSolutionTemplateInjection'     => 'datainjection',
                             'PluginDatainjectionComputerTypeInjection'         => 'datainjection',
                             'PluginDatainjectionMonitorTypeInjection'          => 'datainjection',
                             'PluginDatainjectionNetworkEquipmentTypeInjection' => 'datainjection',
                             'PluginDatainjectionPeripheralTypeInjection'       => 'datainjection',
                             'PluginDatainjectionPrinterTypeInjection'          => 'datainjection',
                             'PluginDatainjectionPhoneTypeInjection'            => 'datainjection',
                             'PluginDatainjectionSoftwareLicenseTypeInjection'  => 'datainjection',
                             'PluginDatainjectionContractTypeInjection'         => 'datainjection',
                             'PluginDatainjectionContactTypeInjection'          => 'datainjection',
                             'PluginDatainjectionSupplierTypeInjection'         => 'datainjection',
                             'PluginDatainjectionDeviceMemoryTypeInjection'     => 'datainjection',
                             'PluginDatainjectionDeviceMemoryTypeInjection'     => 'datainjection',
                             'PluginDatainjectionInterfaceTypeInjection'        => 'datainjection',
                             'PluginDatainjectionPhonePowerSupplyTypeInjection' => 'datainjection',
                             'PluginDatainjectionFilesystemTypeInjection'       => 'datainjection',
                             'PluginDatainjectionComputerModelInjection'        => 'datainjection',
                             'PluginDatainjectionMonitorModelInjection'         => 'datainjection',
                             'PluginDatainjectionPhoneModelInjection'           => 'datainjection',
                             'PluginDatainjectionPrinterModelInjection'         => 'datainjection',
                             'PluginDatainjectionPeripheralModelInjection'      => 'datainjection',
                             'PluginDatainjectionNetworkEquipmentModelInjection'=> 'datainjection',
                             'PluginDatainjectionNetworkEquipmentFirmwareInjection'=> 'datainjection',
                             'PluginDatainjectionVirtualMachineTypeInjection'   => 'datainjection',
                             'PluginDatainjectionVirtualMachineSystemInjection' => 'datainjection',
                             'PluginDatainjectionVirtualMachineStateInjection'  => 'datainjection',
                             'PluginDatainjectionDocumentTypeInjection'         => 'datainjection',
                             'PluginDatainjectionAutoUpdateSystemInjection'     => 'datainjection',
                             'PluginDatainjectionOperatingSystemInjection'      => 'datainjection',
                             'PluginDatainjectionOperatingSystemVersionInjection' => 'datainjection',
                             'PluginDatainjectionOperatingSystemServicePackInjection' => 'datainjection',
                             'PluginDatainjectionNetworkInterfaceInjection'     => 'datainjection',
                             'PluginDatainjectionDomainInjection'               => 'datainjection',
                             'PluginDatainjectionNetworkInjection'              => 'datainjection',
                             'PluginDatainjectionDeviceCaseInjection'           => 'datainjection',
                             'PluginDatainjectionDeviceProcessorInjection'      => 'datainjection',
                             'PluginDatainjectionDeviceMemoryInjection'         => 'datainjection',
                             'PluginDatainjectionDeviceHardDriveInjection'      => 'datainjection',
                             'PluginDatainjectionDeviceMotherboardInjection'    => 'datainjection',
                             'PluginDatainjectionDeviceDriveInjection'          => 'datainjection',
                             'PluginDatainjectionDeviceNetworkCardInjection'    => 'datainjection'
                             );
   //Add pluginsq
   Plugin::doHook('plugin_datainjection_populate');
}


function plugin_datainjection_migratetypes_datainjection($types) {
   $types[996] = 'NetworkPort';
   $types[999] = 'NetworkPort';
   return $types;
}

function plugin_datainjection_checkDirectories() {
   if (!file_exists(PLUGIN_DATAINJECTION_UPLOAD_DIR)
     || !is_writable(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
         return false;
   } else {
      return true;
   }
}
?>