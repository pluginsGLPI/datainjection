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

function plugin_init_datainjection() {
   global $PLUGIN_HOOKS, $CFG_GLPI, $LANG;
   include_once("config/config.php");
   $plugin = new Plugin;
   $PLUGIN_HOOKS['change_profile']['datainjection'] = 'plugin_datainjection_changeprofile';

   if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {

      $PLUGIN_HOOKS['headings']['datainjection'] = 'plugin_get_headings_datainjection';
      $PLUGIN_HOOKS['headings_action']['datainjection'] = 'plugin_headings_actions_datainjection';

      if (plugin_datainjection_haveRight("model", "r"))
         $PLUGIN_HOOKS['menu_entry']['datainjection'] = true;

      $PLUGIN_HOOKS['pre_item_delete']['datainjection'] = 'plugin_pre_item_delete_datainjection';

      // Css file
      $PLUGIN_HOOKS['add_css']['datainjection'] = 'css/datainjection.css';

      // Javascript file
      $PLUGIN_HOOKS['add_javascript']['datainjection'] = 'javascript/datainjection.js';

      // Import webservice
      $PLUGIN_HOOKS['webservices']['datainjection'] = 'plugin_datainjection_registerMethods';

      //Need to load mappings when all the other files are loaded...
      //TODO : check with it cannot be included at the same at as the other files...
      $classes = array ('Model', 'ModelCSV', 'Backend', 'BackendCSV', 'Infos', 'InfosCollections',
                        'Mapping','MappingCollection', 'Profile');
      foreach ($classes as $value) {
         Plugin::registerClass('PluginDataInjection'.$value);
      }

      /*
      include_once("inc/plugin_datainjection.mapping.constant.php");
      registerPluginType('datainjection', 'PLUGIN_DATA_INJECTION_MODEL', 1450, array(
            'classname'  => 'PluginDatainjectionModel',
            'tablename'  => 'glpi_plugin_datainjection_models',
            'typename'   => $LANG['common'][22],
            'formpage'   => '',
            'searchpage' => '',
            'specif_entities_tables' => true,
            'recursive_type' => true
            ));
     */
      //loadDeviceSpecificTypes();
      //addDeviceSpecificMappings();
      //addDeviceSpecificInfos();
   }
}

function plugin_version_datainjection() {
   global $LANG;

   return array (
      'name' => $LANG["datainjection"]["name"][1],
      'minGlpiVersion' => '0.78',
      'author'=>'Dévi Balpe & Walid Nouh & Remi Collet',
      'homepage'=>'https://forge.indepnet.net/projects/show/datainjection',
      'version' => '2.0.0'
   );
}

function plugin_datainjection_haveRight($module, $right) {
   $matches = array (
      "" => array (
         "",
         "r",
         "w"
      ), // ne doit pas arriver normalement
   "r" => array (
         "r",
         "w"
      ),
      "w" => array (
         "w"
      ),
      "1" => array (
         "1"
      ),
      "0" => array (
         "0",
         "1"
      ), // ne doit pas arriver non plus


   );
   if (isset ($_SESSION["glpi_plugin_datainjection_profile"][$module]) && in_array($_SESSION["glpi_plugin_datainjection_profile"][$module], $matches[$right]))
   return true;
   else
   return false;
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
?>
