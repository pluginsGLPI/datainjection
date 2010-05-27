<?php

/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

   This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined("PLUGIN_DATAINJECTION_UPLOAD_DIR")){
   define("PLUGIN_DATAINJECTION_UPLOAD_DIR",GLPI_PLUGIN_DOC_DIR."/datainjection/");
}

function plugin_init_datainjection() {
   global $PLUGIN_HOOKS, $CFG_GLPI, $LANG, $INJECTABLE_TYPES;

   $plugin = new Plugin;
   $PLUGIN_HOOKS['change_profile']['datainjection'] = 'plugin_datainjection_changeprofile';

   if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {

      $PLUGIN_HOOKS['headings']['datainjection'] = 'plugin_get_headings_datainjection';
      $PLUGIN_HOOKS['headings_action']['datainjection'] = 'plugin_headings_actions_datainjection';

      if (plugin_datainjection_haveRight("model", "r")) {
         $PLUGIN_HOOKS['menu_entry']['datainjection'] = true;
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['links']['search']  =
                                                            '/plugins/datainjection/front/model.php';
         $PLUGIN_HOOKS['submenu_entry']['datainjection']['options']['model']['links']['add'] =
                                                      '/plugins/datainjection/front/model.form.php';
      }
      $PLUGIN_HOOKS['submenu_entry']['datainjection']['add'] = 'index.php';

      $PLUGIN_HOOKS['pre_item_delete']['datainjection'] = 'plugin_pre_item_delete_datainjection';

      // Css file
      $PLUGIN_HOOKS['add_css']['datainjection'] = 'css/datainjection.css';

      // Javascript file
      $PLUGIN_HOOKS['add_javascript']['datainjection'] = 'javascript/datainjection.js';

      // Inbtegration with Webservices plugin
      $PLUGIN_HOOKS['webservices']['datainjection'] = 'plugin_datainjection_registerMethods';

      $classes = array ('Model',
                        'Modelcsv',
                        'Backend',
                        'Backendcsv',
                        'BackendInterface',
                        'Infos',
                        'InfosCollection',
                        'Mapping',
                        'MappingCollection',
                        'Profile',
                        'Data',
                        'Check',
                        'InjectionInterface',
                        'InjectionCommon',
                        'Result',
                        'CommonInjectionLib',
                        'Webservice',
                        'Result');
      foreach ($classes as $value) {
         Plugin::registerClass('PluginDatainjection'.$value);
      }

      getTypesToInject();
      foreach ($INJECTABLE_TYPES as $type => $plugname) {
         Plugin::registerClass($type);
      }
   }
}

function plugin_version_datainjection() {
   global $LANG;

   return array (
      'name' => $LANG["datainjection"]["name"][1],
      'minGlpiVersion' => '0.78',
      'author'=>'Walid Nouh & Remi Collet',
      'homepage'=>'https://forge.indepnet.net/projects/show/datainjection',
      'version' => '2.0.0'
   );
}

function plugin_datainjection_haveRight($module, $right) {
   $matches = array ("" => array ("", "r","w"), // ne doit pas arriver normalement
   "r" => array ("r","w"),
      "w" => array ("w"),
      "1" => array ("1"),
      "0" => array ("0","1"), // ne doit pas arriver non plus
   );
   if (isset ($_SESSION["glpi_plugin_datainjection_profile"][$module])
         && in_array($_SESSION["glpi_plugin_datainjection_profile"][$module],
                     $matches[$right])) {
      return true;
   }
   else {
      return false;
   }
}
function plugin_datainjection_check_prerequisites() {
   if (GLPI_VERSION >= 0.78) {
      return true;
   } else {
      echo "This plugin requires GLPI 0.78.x";
   }
}

function plugin_datainjection_check_config($verbose=false) {
   return true;
}

function getTypesToInject() {
   global $INJECTABLE_TYPES,$PLUGIN_HOOKS;
   $INJECTABLE_TYPES = array(//'PluginDatainjectionCartridgeItemInjection'      =>'datainjection',
                                //'PluginDatainjectionCartridgeInjection'          =>'datainjection',
                                'PluginDatainjectionComputerInjection'           =>'datainjection',
                                //'PluginDatainjectionConsumableInjection'         =>'datainjection',
                                //'PluginDatainjectionConsumableItemInjection'     =>'datainjection',
                                //'PluginDatainjectionContactInjection'            =>'datainjection',
                                //'PluginDatainjectionContractInjection'           =>'datainjection',
                                //'PluginDatainjectionDocumentInjection'           =>'datainjection',
                                //'PluginDatainjectionEntityInjection'             =>'datainjection',
                                //'PluginDatainjectionGroupInjection'              =>'datainjection',
                                'PluginDatainjectionInfocomInjection'            =>'datainjection',
                                'PluginDatainjectionLocationInjection'           =>'datainjection',
                                'PluginDatainjectionStateInjection'              =>'datainjection',
                                'PluginDatainjectionManufacturerInjection'       =>'datainjection',
                                'PluginDatainjectionMonitorInjection'            =>'datainjection',
                                'PluginDatainjectionNetworkequipmentInjection'   =>'datainjection',
                                //'PluginDatainjectionPeripheralInjection'         =>'datainjection',
                                //'PluginDatainjectionPhoneInjection'              =>'datainjection',
                                'PluginDatainjectionPrinterInjection'            =>'datainjection',
                                //'PluginDatainjectionProfileInjection'            =>'datainjection',
                                //'PluginDatainjectionSoftwareInjection'           =>'datainjection',
                                //'PluginDatainjectionSoftwareLicenseInjection'    =>'datainjection',
                                //'PluginDatainjectionSupplierInjection'           =>'datainjection',
                                //'PluginDatainjectionUserInjection'               =>'datainjection',
                                'PluginDatainjectionNetworkportInjection'        =>'datainjection',
                                'PluginDatainjectionVlanInjection'               =>'datainjection',
                                'PluginDatainjectionNetworkport_VlanInjection'   =>'datainjection',
                                'PluginDatainjectionNetpointInjection'           =>'datainjection',
                                //'PluginDatainjectionContract_ItemInjection'      =>'datainjection'
                                );
   //Add plugins
   doHook('plugin_datainjection_populate');

}
?>
