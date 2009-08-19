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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

function plugin_datainjection_update131_14() {
   global $DB;
   $sql = "ALTER TABLE `glpi_plugin_data_injection_models` ADD `float_format` INT( 1 ) NOT NULL DEFAULT '0';";
   $DB->query($sql);

   //Template recursivity : need standardize names in order to use privatePublicSwitch
   $sql = " ALTER TABLE `glpi_plugin_data_injection_models` CHANGE `user_id` `FK_users` INT( 11 ) NOT NULL";
   $DB->query($sql);

   $sql = " ALTER TABLE `glpi_plugin_data_injection_models` CHANGE `public` `private` INT( 1 ) NOT NULL  DEFAULT '0'";
   $DB->query($sql);



   $sql="UPDATE `glpi_plugin_data_injection_models` SET FK_entities=-1 AND private=1 WHERE private=0";
   $DB->query($sql);

   $sql="UPDATE `glpi_plugin_data_injection_models` SET private=0 WHERE private=1 AND FK_entities>0";
   $DB->query($sql);

   $sql = "ALTER TABLE `glpi_plugin_data_injection_models` ADD `recursive` INT( 1 ) NOT NULL DEFAULT '0';";
   $DB->query($sql);

   $sql="UPDATE `glpi_plugin_data_injection_profiles` SET create_model=use_model WHERE create_model IS NULL";
   $DB->query($sql);

   $sql="ALTER TABLE `glpi_plugin_data_injection_profiles` DROP `use_model`";
   $DB->query($sql);

   $sql=" ALTER TABLE `glpi_plugin_data_injection_profiles` CHANGE `create_model` `model` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL";
   $DB->query($sql);
}

function plugin_datainjection_update14_15()
{
   global $DB;
   $query = "ALTER TABLE `glpi_plugin_data_injection_models` ADD `port_unicity` INT( 1 ) NOT NULL DEFAULT '0';";
   $DB->query($query);
}

function plugin_datainjection_update15_170() {
   global $DB;
   $tables = array("glpi_plugin_data_injection_models"=>"glpi_plugin_datainjection_models",
               "glpi_plugin_data_injection_models_csv"=>"glpi_plugin_datainjection_models_csv",
               "glpi_plugin_data_injection_models_csv"=>"glpi_plugin_datainjection_models_csv",
               "glpi_plugin_data_injection_mappings"=>"glpi_plugin_datainjection_mappings",
               "glpi_plugin_data_injection_infos"=>"glpi_plugin_datainjection_infos",
               "glpi_plugin_data_injection_filetype"=>"glpi_plugin_datainjection_filetype",
               "glpi_plugin_data_injection_profiles"=>"glpi_plugin_datainjection_profiles");
   foreach ($tables as $oldname => $newname) {
      $query = "RENAME TABLE `$oldname` TO `$newname`";
      $DB->query($query);
   }

}
function plugin_datainjection_createfirstaccess($ID) {
   global $DB;

   include_once(GLPI_ROOT."/inc/profile.class.php");
   $DataInjectionProfile = new DataInjectionProfile();
   if (!$DataInjectionProfile->getFromDB($ID)) {

      $Profile = new Profile();
      $Profile->getFromDB($ID);
      $name = $Profile->fields["name"];

      $query = "INSERT INTO `glpi_plugin_datainjection_profiles` ( `ID`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0','w');";
      $DB->query($query);
   }
}

function plugin_datainjection_createaccess($ID) {
   global $DB;

   $Profile = new Profile();
   $Profile->GetfromDB($ID);
   $name = $Profile->fields["name"];

   $query = "INSERT INTO `glpi_plugin_datainjection_profiles` ( `ID`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0',NULL);";

   $DB->query($query);
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

function plugin_datainjection_haveTypeRight($module,$right)
{
   return plugin_datainjection_haveRight("model", $right);
}

function plugin_datainjection_checkRight($module, $right) {
   global $CFG_GLPI;

   if (!plugin_datainjection_haveRight($module, $right)) {
      // Gestion timeout session
      if (!isset ($_SESSION["glpiID"])) {
         glpi_header($CFG_GLPI["root_doc"] . "/index.php");
         exit ();
      }

      displayRightError();
   }
}

function plugin_datainjection_loadHook($hook_name, $params = array ()) {
   global $PLUGIN_HOOKS;

   if (!empty ($params)) {
      $type = $params["type"];
      //If a plugin type is defined
      doOneHook($PLUGIN_HOOKS['plugin_types'][$type], 'datainjection_' . $hook_name);
   } else
   if (isset ($PLUGIN_HOOKS['plugin_types'])) {
      //Browse all plugins
      foreach ($PLUGIN_HOOKS['plugin_types'] as $type => $name) {
         doOneHook($name, 'datainjection_' . $hook_name);
      }
   }
}

function plugin_datainjection_needUpdateOrInstall()
{
   $plugin = new Plugin;
   //Plugin is already installed -> nothing to do !
   if ($plugin->isInstalled("datainjection")) {
      return -1;
   }
   //Never installed -> launch installation
   elseif (!$plugin->isInstalled("data_injection")) {
      //Plugin list may have been cleaned, so check if old tables still exists
      if (TableExists("glpi_plugin_data_injection_models")) {
         return 1;
      }
      else {
         return 0;
      }
   }
   //Plugin is installed in an older version -> update
   else
   {
      return 1;
   }
}
?>
