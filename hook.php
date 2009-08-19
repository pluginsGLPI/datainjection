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

function plugin_datainjection_install() {
   global $DB;

   $status = plugin_datainjection_needUpdateOrInstall();
   if ($status == -1)
      return true;
      
   if (!$status) {
      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_models` (
                          `ID` int(11) NOT NULL auto_increment,
                          `name` varchar(255) NOT NULL,
                          `comments` text,
                          `date_mod` datetime NOT NULL default '0000-00-00 00:00:00',
                          `type` int(11) NOT NULL default '1',
                          `device_type` int(11) NOT NULL default '1',
                          `FK_entities` int(11) NOT NULL default '-1',
                          `behavior_add` int(1) NOT NULL default '1',
                          `behavior_update` int(1) NOT NULL default '0',
                          `can_add_dropdown` int(1) NOT NULL default '0',
                          `can_overwrite_if_not_empty` int(1) NOT NULL default '1',
                          `private` int(1) NOT NULL default '1',
                          `recursive` int(1) NOT NULL default '0',
                          `perform_network_connection` int(1) NOT NULL default '0',
                          `FK_users` int(11) NOT NULL,
                          `date_format` varchar(11) NOT NULL default 'yyyy-mm-dd',
                          `float_format` INT( 1 ) NOT NULL DEFAULT '0',
                          `port_unicity` INT( 1 ) NOT NULL DEFAULT '0',
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";

      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_models_csv` (
                          `ID` int(11) NOT NULL auto_increment,
                          `model_id` int(11) NOT NULL,
                          `device_type` int(11) NOT NULL default '1',
                          `delimiter` varchar(1) NOT NULL default ';',
                          `header_present` int(1) NOT NULL default '1',
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";

      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_mappings` (
                           `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                           `model_id` INT( 11 ) NOT NULL ,
                           `type` INT( 11 ) NOT NULL DEFAULT '1',
                           `rank` INT( 11 ) NOT NULL ,
                           `name` VARCHAR( 255 ) NOT NULL ,
                           `value` VARCHAR( 255 ) NOT NULL ,
                           `mandatory` INT( 1 ) NOT NULL DEFAULT '0'
                           ) ENGINE = MYISAM CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_infos` (
                           `ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                           `model_id` INT( 11 ) NOT NULL ,
                           `type` int(11) NOT NULL default '9',
                           `value` VARCHAR( 255 ) NOT NULL ,
                           `mandatory` INT( 1 ) NOT NULL DEFAULT '0'
                           ) ENGINE = MYISAM CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_filetype` (
                          `ID` int(11) NOT NULL auto_increment,
                          `name` varchar(255) NOT NULL,
                          `value` int(11) NOT NULL,
                          `backend_class_name` varchar(255) NOT NULL,
                          `model_class_name` varchar(255) NOT NULL,
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARSET=utf8 COLLATE=utf8_unicode_ci;
                        ";
      $DB->query($query) or die($DB->error());

      $query = "REPLACE INTO `glpi_plugin_datainjection_filetype` (`ID`, `name`, `value`, `backend_class_name`, `model_class_name`) VALUES
                     (1, 'CSV', 1, 'BackendCSV', 'DataInjectionModelCSV');";
      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_profiles` (
                          `ID` int(11) NOT NULL auto_increment,
                          `name` varchar(255) default NULL,
                          `is_default` int(6) NOT NULL default '0',
                          `model` char(1) default NULL,
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die($DB->error());

      if (!is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
         @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR) or die("Can't create folder " . PLUGIN_DATAINJECTION_UPLOAD_DIR);

         plugin_datainjection_createfirstaccess($_SESSION["glpiactiveprofile"]["ID"]);

      }
   }
   else
   {
      //When updating, check if the upload folder is already present
      if (!is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
         @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR) or die("Can't create folder " . PLUGIN_DATAINJECTION_UPLOAD_DIR);
      }

      //Old temporary directory, needs to be removed !
      if (is_dir(GLPI_PLUGIN_DOC_DIR."/data_injection/")) {
         deleteDir(GLPI_PLUGIN_DOC_DIR."/data_injection/");
      }
      
      if (!FieldExists("glpi_plugin_data_injection_models","recursive")) {
         // Update
         plugin_datainjection_update131_14();
      }
      if (!FieldExists("glpi_plugin_data_injection_models","port_unicity")) {
         plugin_datainjection_update14_15();
      }

      if (!TableExists("glpi_plugin_datainjection_models")) {
         plugin_datainjection_update15_170();
      }

   }

   plugin_datainjection_changeprofile();
   return true;
}

function plugin_datainjection_uninstall() {
   global $DB;

   $tables = array("glpi_plugin_datainjection_models",
               "glpi_plugin_datainjection_models_csv",
               "glpi_plugin_datainjection_models_csv",
               "glpi_plugin_datainjection_mappings",
               "glpi_plugin_datainjection_infos",
               "glpi_plugin_datainjection_filetype",
               "glpi_plugin_datainjection_profiles");

   foreach ($tables as $table)
   if (TableExists($table))
   $DB->query("DROP TABLE `$table`;") or die($DB->error());

   if (is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
      deleteDir(PLUGIN_DATAINJECTION_UPLOAD_DIR);
   }
   return true;
}



function plugin_pre_item_delete_datainjection($input) {
   if (isset ($input["_item_type_"]))
   switch ($input["_item_type_"]) {
      case PROFILE_TYPE :
         // Manipulate data if needed
         $DataInjectionProfile = new DataInjectionProfile;
         $DataInjectionProfile->cleanProfiles($input["ID"]);
         break;
   }
   return $input;
}

function plugin_datainjection_changeprofile() {
   $plugin = new Plugin;

   if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {
      $prof = new DataInjectionProfile();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['ID']))
      $_SESSION["glpi_plugin_datainjection_profile"] = $prof->fields;
      else
      unset ($_SESSION["glpi_plugin_datainjection_profile"]);
   }
}

function plugin_headings_actions_datainjection($type) {
   switch ($type) {
      case PROFILE_TYPE :
         return array (
            1 => "plugin_headings_datainjection",

         );
         break;
   }
   return false;
}

function plugin_get_headings_datainjection($type, $ID, $withtemplate) {
   global $LANG;

   switch ($type) {
      case PROFILE_TYPE :
         $prof = new Profile();
         if ($ID>0 && $prof->getFromDB($ID) && $prof->fields['interface']=='central') {
            return array(
               1 => $LANG["datainjection"]["name"][1]
            );
         } else {
            return array();
         }
         break;
   }
   return false;
}

function plugin_headings_datainjection($type, $ID, $withtemplate = 0) {
   global $CFG_GLPI;

   switch ($type) {
      case PROFILE_TYPE :
         $profile = new DataInjectionProfile;
         if (!$profile->getFromDB($ID))
         plugin_datainjection_createaccess($ID);

         $profile->showForm($CFG_GLPI["root_doc"] . "/plugins/datainjection/front/plugin_datainjection.profile.php", $ID);
         break;
      default :
         break;
   }
}
?>
