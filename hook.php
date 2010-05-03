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
function plugin_datainjection_registerMethods() {
   global $WEBSERVICES_METHOD;

   // Not authenticated method
   $WEBSERVICES_METHOD['datainjection.getModel']  = array('PluginDatainjectionModel',
                                                          'methodGetModel');
   $WEBSERVICES_METHOD['datainjection.listModels']  = array('PluginDatainjectionModel',
                                                          'methodListModels');
    // Authenticated method
   $WEBSERVICES_METHOD['datainjection.setModel']  = array('PluginDatainjectionModel',
                                                          'methodSetModel');
}

function plugin_pre_item_delete_datainjection($input) {
   if (isset ($input["_item_type_"]))
   switch ($input["_item_type_"]) {
      case PROFILE_TYPE :
         // Manipulate data if needed
         $PluginDatainjectionProfile = new PluginDatainjectionProfile;
         $PluginDatainjectionProfile->cleanProfiles($input["ID"]);
         break;
   }
   return $input;
}

function plugin_datainjection_changeprofile() {
   $plugin = new Plugin;

   if ($plugin->isInstalled("datainjection") && $plugin->isActivated("datainjection")) {
      $prof = new PluginDatainjectionProfile();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['id']))
      $_SESSION["glpi_plugin_datainjection_profile"] = $prof->fields;
      else
      unset ($_SESSION["glpi_plugin_datainjection_profile"]);
   }
}

function plugin_headings_actions_datainjection($itemtype) {
   switch ($itemtype) {
      case PROFILE_TYPE :
         return array (
            1 => "plugin_headings_datainjection",

         );
         break;
   }
   return false;
}

function plugin_get_headings_datainjection($item, $withtemplate) {
   global $LANG;

   switch (get_class($item)) {
      case PROFILE_TYPE :
         $prof = new Profile();
         if ($item->fields['id']>0
               && $prof->getFromDB($item->fields['id'])
                  && $prof->fields['interface']=='central') {
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
         $profile = new PluginDatainjectionProfile;
         if (!$profile->getFromDB($ID))
         plugin_datainjection_createaccess($ID);

         $profile->showForm($CFG_GLPI["root_doc"] . "/plugins/datainjection/front/plugin_datainjection.profile.php", $ID);
         break;
      default :
         break;
   }
}

function plugin_datainjection_install() {
   global $DB;
   $status = 1;
   //$status = plugin_datainjection_needUpdateOrInstall();
   //if ($status == -1)
   //   return true;

   //if (!$status) {
      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_models` (
                          `id` int(11) NOT NULL auto_increment,
                          `name` varchar(255) NOT NULL,
                          `comment` text NULL,
                          `date_mod` datetime NOT NULL default '0000-00-00 00:00:00',
                          `filetype` varchar(255) NOT NULL default 'csv',
                          `itemtype` varchar(255) NOT NULL default '',
                          `entities_id` int(11) NOT NULL default '-1',
                          `behavior_add` tinyint(1) NOT NULL default '1',
                          `behavior_update` tinyint(1) NOT NULL default '0',
                          `can_add_dropdown` tinyint(1) NOT NULL default '0',
                          `can_overwrite_if_not_empty` int(1) NOT NULL default '1',
                          `is_private` tinyint(1) NOT NULL default '1',
                          `is_recursive` tinyint(1) NOT NULL default '0',
                          `perform_network_connection` tinyint(1) NOT NULL default '0',
                          `users_id` int(11) NOT NULL,
                          `date_format` varchar(11) NOT NULL default 'yyyy-mm-dd',
                          `float_format` tinyint( 1 ) NOT NULL DEFAULT '0',
                          `port_unicity` tinyint( 1 ) NOT NULL DEFAULT '0',
                          `step` tinyint( 1 ) NOT NULL DEFAULT '0',
                          PRIMARY KEY  (`id`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";

      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_modelcsvs` (
                          `id` int(11) NOT NULL auto_increment,
                          `models_id` int(11) NOT NULL,
                          `itemtype` varchar(255) NOT NULL default '',
                          `delimiter` varchar(1) NOT NULL default ';',
                          `is_header_present` tinyint(1) NOT NULL default '1',
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";

      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_mappings` (
                           `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                           `models_id` INT( 11 ) NOT NULL ,
                           `itemtype` varchar(255) NOT NULL default '',
                           `rank` INT( 11 ) NOT NULL ,
                           `name` VARCHAR( 255 ) NOT NULL ,
                           `value` VARCHAR( 255 ) NOT NULL ,
                           `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'
                           ) ENGINE = MYISAM CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_infos` (
                           `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                           `models_id` INT( 11 ) NOT NULL ,
                           `itemtype` varchar(255) NOT NULL default '',
                           `value` VARCHAR( 255 ) NOT NULL ,
                           `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'
                           ) ENGINE = MYISAM CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
      $DB->query($query) or die($DB->error());

      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_profiles` (
                          `id` int(11) NOT NULL auto_increment,
                          `name` varchar(255) default NULL,
                          `is_default` TINYINT(1) NOT NULL default '0',
                          `model` char(1) default NULL,
                          PRIMARY KEY  (`ID`)
                        ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die($DB->error());

      if (!is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
         @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR) or die("Can't create folder " . PLUGIN_DATAINJECTION_UPLOAD_DIR);

         plugin_datainjection_createfirstaccess($_SESSION["glpiactiveprofile"]["id"]);

      }
   //}
   /*
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

   }*/

   //plugin_datainjection_changeprofile();
   return true;
}

function plugin_datainjection_createfirstaccess($ID) {
   global $DB;

   include_once(GLPI_ROOT."/plugins/datainjection/inc/profile.class.php");
   $PluginDatainjectionProfile = new PluginDatainjectionProfile();
   if (!$PluginDatainjectionProfile->getFromDB($ID)) {

      $Profile = new Profile();
      $Profile->getFromDB($ID);
      $name = $Profile->fields["name"];

      $query = "INSERT INTO `glpi_plugin_datainjection_profiles`
                     ( `id`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0','w');";
      $DB->query($query);
   }
}
function plugin_datainjection_uninstall() {
   global $DB;

   $tables = array("glpi_plugin_datainjection_models",
                   "glpi_plugin_datainjection_modelscsv",
                   "glpi_plugin_datainjection_modelscsv",
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

   plugin_init_datainjection();
   return true;
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

function plugin_datainjection_update170_20() {
   global $DB;
   $query = "ALTER TABLE `glpi_plugin_datainjection_models`
             CHANGE `filetypes_id` `filetype` VARCHAR( 255 ) NOT NULL DEFAULT 'csv'";
   $query = "DROP TABLE `glpi_plugin_datainjection_filetype`";
   $query = "RENAME TABLE `glpi_plugin_datainjection_models_csv`
             TO `glpi_plugin_datainjection_modelcsvs`  ;";
   $query = "ALTER TABLE `glpi_plugin_datainjection_models`
             ADD `step` TINYINT( 1 ) NOT NULL DEFAULT '0'";
   $qery = "ALTER TABLE `glpi_plugin_datainjection_mappings`
             CHANGE `mandatory` `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'";
   $query = "ALTER TABLE `glpi_plugin_datainjection_infos`
            CHANGE `mandatory` `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'";
}
function plugin_datainjection_createaccess($ID) {
   global $DB;

   $Profile = new Profile();
   $Profile->GetfromDB($ID);
   $name = $Profile->fields["name"];

   $query = "INSERT INTO `glpi_plugin_datainjection_profiles` ( `ID`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0',NULL);";

   $DB->query($query);
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

function plugin_datainjection_giveItem($type,$ID,$data,$num) {
   global $DB, $CFG_GLPI, $LANG;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$ID]["table"];
   $field = $searchopt[$ID]["field"];

   switch ($table.'.'.$field) {
      case "glpi_plugin_datainjection_models.port_unicity" :
         return PluginDatainjectionDropdown::getPortUnitictyValues($data['ITEM_'.$num]);
      case "glpi_plugin_datainjection_models.float_format":
         return PluginDatainjectionDropdown::getFloatFormat($data['ITEM_'.$num]);
   }
   return "";
}

?>
