<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the datainjection plugin.

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
 @copyright Copyright (c) 2010-2017 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/pluginsGLPI/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

function plugin_datainjection_registerMethods() {

   global $WEBSERVICES_METHOD;

   $methods = ['getModel'      => 'methodGetModel',
               'listModels'    => 'methodListModels',
               'inject'        => 'methodInject',
               'listItemtypes' => 'methodListItemtypes'];

   foreach ($methods as $code => $method) {
      $WEBSERVICES_METHOD['datainjection.'.$code] = ['PluginDatainjectionWebservice', $method];
   }
}


function plugin_datainjection_install() {
   global $DB;

   include_once GLPI_ROOT."/plugins/datainjection/inc/profile.class.php";

   $migration = new Migration(null);

   switch (plugin_datainjection_needUpdateOrInstall()) {
      case -1 :
         // Migrations from version 2.2.0+
         plugin_datainjection_update220_230();
         plugin_datainjection_upgrade23_240($migration);
         plugin_datainjection_migration_24_250($migration);
         plugin_datainjection_migration_251_252($migration);
         plugin_datainjection_migration_264_270($migration);
         break;

      case 0 :
         // Plugin installation
          $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_models` (
                     `id` int(11) NOT NULL auto_increment,
                     `name` varchar(255) NOT NULL,
                     `comment` text NULL,
                     `date_mod` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                     `date_creation` datetime DEFAULT NULL,
                     `filetype` varchar(255) NOT NULL default 'csv',
                     `itemtype` varchar(255) NOT NULL default '',
                     `entities_id` int(11) NOT NULL default '0',
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
                     `step` int( 11 ) NOT NULL DEFAULT '0',
                     PRIMARY KEY  (`id`)
                   ) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci";
          $DB->queryOrDie($query, $DB->error());

          $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_modelcsvs` (
                     `id` int(11) NOT NULL auto_increment,
                     `models_id` int(11) NOT NULL,
                     `itemtype` varchar(255) NOT NULL default '',
                     `delimiter` varchar(1) NOT NULL default ';',
                     `is_header_present` tinyint(1) NOT NULL default '1',
                     PRIMARY KEY  (`ID`)
                   ) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci;";
          $DB->queryOrDie($query, $DB->error());

          $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_mappings` (
                     `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                     `models_id` INT( 11 ) NOT NULL ,
                     `itemtype` varchar(255) NOT NULL default '',
                     `rank` INT( 11 ) NOT NULL ,
                     `name` VARCHAR( 255 ) NOT NULL ,
                     `value` VARCHAR( 255 ) NOT NULL ,
                     `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'
                   ) ENGINE = InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
          $DB->queryOrDie($query, $DB->error());

          $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_datainjection_infos` (
                     `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                     `models_id` INT( 11 ) NOT NULL ,
                     `itemtype` varchar(255) NOT NULL default '',
                     `value` VARCHAR( 255 ) NOT NULL ,
                     `is_mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0'
                   ) ENGINE = InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
          $DB->queryOrDie($query, $DB->error());

         if (!is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
            @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR)
             or die(
                 sprintf(
                     __('%1$s %2$s'), __("Can't create folder", 'datainjection'),
                     PLUGIN_DATAINJECTION_UPLOAD_DIR
                 )
             );

            PluginDatainjectionProfile::createFirstAccess($_SESSION["glpiactiveprofile"]["id"]);
         }
        break;

      case 1 :
         // Migrations from version prior to 2.2.0

         //When updating, check if the upload folder is already present
         if (!is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
            @ mkdir(PLUGIN_DATAINJECTION_UPLOAD_DIR)
             or die(
                 sprintf(
                     __('%1$s %2$s'), __("Can't create folder", 'datainjection'),
                     PLUGIN_DATAINJECTION_UPLOAD_DIR
                 )
             );
         }

          //Old temporary directory, needs to be removed !
         if (is_dir(GLPI_PLUGIN_DOC_DIR."/data_injection/")) {
            Toolbox::deleteDir(GLPI_PLUGIN_DOC_DIR."/data_injection/");
         }

         if ($DB->tableExists("glpi_plugin_data_injection_models")
            && !$DB->fieldExists("glpi_plugin_data_injection_models", "recursive")
          ) {
            // Update
            plugin_datainjection_update131_14();
         }

         if ($DB->tableExists("glpi_plugin_data_injection_models")
            && !$DB->fieldExists("glpi_plugin_data_injection_models", "port_unicity")
          ) {

            $migration->addField('glpi_plugin_data_injection_models', 'port_unicity', 'bool');
            $migration->executeMigration();
         }

         if (!$DB->tableExists("glpi_plugin_datainjection_models")) {
            plugin_datainjection_update15_170();
         }

         if (!$DB->tableExists("glpi_plugin_datainjection_modelcsvs")) {
            plugin_datainjection_update170_20();
         }

         plugin_datainjection_update210_220();

         plugin_datainjection_update220_230();

         plugin_datainjection_upgrade23_240($migration);

         plugin_datainjection_migration_24_250($migration);

         plugin_datainjection_migration_251_252($migration);

         plugin_datainjection_migration_264_270($migration);
         break;

      default :
         break;
   }

   return true;
}


function plugin_datainjection_uninstall() {
   global $DB;

   $tables = ["glpi_plugin_datainjection_models",
              "glpi_plugin_datainjection_modelcsvs",
              "glpi_plugin_datainjection_mappings",
              "glpi_plugin_datainjection_infos",
              "glpi_plugin_datainjection_filetype",
              "glpi_plugin_datainjection_profiles"];

   foreach ($tables as $table) {
      if ($DB->tableExists($table)) {
         $DB->queryOrDie("DROP TABLE IF EXISTS `".$table."`", $DB->error());
      }
   }

   if (is_dir(PLUGIN_DATAINJECTION_UPLOAD_DIR)) {
      Toolbox::deleteDir(PLUGIN_DATAINJECTION_UPLOAD_DIR);
   }

   plugin_init_datainjection();
   return true;
}

function plugin_datainjection_migration_264_270(Migration $migration) {

   global $DB;

   $migration->setVersion('2.7.0');

   $migration->addPostQuery(
      $DB->buildUpdate(
         'glpi_plugin_datainjection_mappings',
         [
            'value' => 'licenseid',
         ],
         [
            'itemtype' => 'Item_OperatingSystem',
            'value'    => 'license_id',
         ]
      ),
      'Changing "license_id" field of "Item_OperatingSystem" to "licenseid".'
   );

   $migration->executeMigration();
}

function plugin_datainjection_migration_251_252(Migration $migration) {
   global $DB;

   $migration->setVersion('2.5.2');

   if ($DB->tableExists('glpi_plugin_datainjection_models')
       && $DB->fieldExists('glpi_plugin_datainjection_models', 'date_mod')) {
      $migration->changeField(
         'glpi_plugin_datainjection_models',
         'date_mod',
         'date_mod',
         'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
      );
      $migration->migrationOneTable('glpi_plugin_datainjection_models');
   }

   $migration->executeMigration();
}

function plugin_datainjection_migration_24_250(Migration $migration) {
   global $DB;

   $migration->setVersion('2.5.0');

   if ($DB->tableExists('glpi_plugin_datainjection_models')
       && !$DB->fieldExists('glpi_plugin_datainjection_models', 'date_creation')) {
      $migration->addField('glpi_plugin_datainjection_models', 'date_creation', 'datetime');
      $migration->addKey('glpi_plugin_datainjection_models', 'date_creation');
      $migration->migrationOneTable('glpi_plugin_datainjection_models');
   }

   //Migrate OSes infos
   //TODO use DB->update in 9.3
   $query = "UPDATE `glpi_plugin_datainjection_mappings`
             SET `itemtype`='Item_OperatingSystem'
             WHERE `itemtype`='Computer'
                AND `value` IN (
                   'license_id', 'license_number', 'operatingsystemservicepacks_id',
                   'operatingsystems_id', 'operatingsystemversions_id',
                   'operatingsystemarchitectures_id', 'operatingsystemkernels_id',
                   'operatingsystemkernelversions_id', 'operatingsystemeditions_id'
                )";
   $DB->query($query);

   $migration->executeMigration();
}

function plugin_datainjection_upgrade23_240(Migration $migration) {
   global $DB;

   $migration->setVersion('2.4.0');

   if ($DB->tableExists('glpi_plugin_datainjection_profiles')) {
      if ($DB->fieldExists('glpi_plugin_datainjection_profiles', 'ID')) {
         $migration->changeField('glpi_plugin_datainjection_profiles', 'ID', 'id', 'autoincrement');
         $migration->migrationOneTable('glpi_plugin_datainjection_profiles');
      }

       PluginDatainjectionProfile::migrateProfiles();

      //Drop profile table : no use anymore !
      $migration->dropTable('glpi_plugin_datainjection_profiles');
   }

   $migration->executeMigration();
}

function plugin_datainjection_update131_14() {

   global $DB;

   $migration = new Migration('1.4');

   $migration->addField(
      'glpi_plugin_data_injection_models', 'float_format',
      'bool'
   );

   //Template recursivity : need standardize names in order to use privatePublicSwitch
   $migration->changeField(
      'glpi_plugin_data_injection_models', 'user_id',
      'FK_users', 'integer'
   );
   $migration->changeField(
      'glpi_plugin_data_injection_models', 'public',
      'private', 'bool'
   );

   $migration->migrationOneTable('glpi_plugin_data_injection_models');

   $sql = "UPDATE `glpi_plugin_data_injection_models`
           SET `FK_entities` = '-1',
               `private` = '1'
           WHERE `private` = '0'";
   $DB->query($sql);

   $sql = "UPDATE `glpi_plugin_data_injection_models`
           SET `private` = '0'
           WHERE `private` = '1'
                AND `FK_entities` > '0'";
   $DB->query($sql);

   $migration->addField(
      'glpi_plugin_data_injection_models', 'recursive',
      'bool'
   );

   $sql = "UPDATE `glpi_plugin_data_injection_profiles`
           SET `create_model` = `use_model`
           WHERE `create_model` IS NULL";
   $DB->query($sql);

   $migration->dropField('glpi_plugin_data_injection_profiles', 'use_model');
   $migration->changeField(
      'glpi_plugin_data_injection_profiles',
      'create_model', 'model', 'char'
   );

   $migration->executeMigration();
}


function plugin_datainjection_update15_170() {

   global $DB;

   $tables = ["glpi_plugin_data_injection_models"     => "glpi_plugin_datainjection_models",
              "glpi_plugin_data_injection_models_csv" => "glpi_plugin_datainjection_models_csv",
              "glpi_plugin_data_injection_models_csv" => "glpi_plugin_datainjection_models_csv",
              "glpi_plugin_data_injection_mappings"   => "glpi_plugin_datainjection_mappings",
              "glpi_plugin_data_injection_infos"      => "glpi_plugin_datainjection_infos",
              "glpi_plugin_data_injection_filetype"   => "glpi_plugin_datainjection_filetype",
              "glpi_plugin_data_injection_profiles"   => "glpi_plugin_datainjection_profiles"];

   foreach ($tables as $oldname => $newname) {
      $query = "RENAME TABLE IF EXISTS `".$oldname."` TO `".$newname."`";
      $DB->query($query);
   }
}


function plugin_datainjection_update170_20() {
   global $DB;
   $migration = new Migration('2.0');

   $migration->changeField('glpi_plugin_datainjection_models', 'ID', 'id',
                           'autoincrement');
   $migration->changeField('glpi_plugin_datainjection_models', 'type',
                           'filetype', 'string', ['value' => 'csv']);
   $migration->addField('glpi_plugin_datainjection_models', 'step', 'integer');
   $migration->changeField('glpi_plugin_datainjection_models', 'comments',
                           'comment', 'text');
   $migration->changeField('glpi_plugin_datainjection_models', 'device_type',
                           'itemtype', 'string', ['value' => '']);
   $migration->changeField('glpi_plugin_datainjection_models', 'FK_entities',
                           'entities_id', 'integer');
   $migration->changeField('glpi_plugin_datainjection_models', 'private',
                           'is_private', 'bool');
   $migration->changeField('glpi_plugin_datainjection_models', 'FK_users',
                            'users_id', 'integer');
   $migration->changeField('glpi_plugin_datainjection_models', 'recursive',
                            'is_recursive', 'bool');

   $migration->migrationOneTable('glpi_plugin_datainjection_models');
   $query = "UPDATE `glpi_plugin_datainjection_models`
              SET `step` = '5'";
   $DB->query($query);

   $query = "UPDATE `glpi_plugin_datainjection_models`
              SET `filetype` = 'csv'";
   $DB->queryOrDie($query, "update filetype of glpi_plugin_datainjection_models");

   $migration->dropTable('glpi_plugin_datainjection_filetype');

   $migration->renameTable('glpi_plugin_datainjection_models_csv',
                            'glpi_plugin_datainjection_modelcsvs');

   $migration->changeField('glpi_plugin_datainjection_modelcsvs', 'model_id',
                            'models_id', 'integer');
   $migration->changeField('glpi_plugin_datainjection_modelcsvs', 'device_type',
                            'itemtype', 'string', ['value' => '']);
   $migration->changeField('glpi_plugin_datainjection_modelcsvs', 'header_present',
                            'is_header_present', 'bool', ['value' => 1]);

   $migration->changeField('glpi_plugin_datainjection_mappings', 'mandatory',
                            'is_mandatory', 'bool');
   $migration->changeField('glpi_plugin_datainjection_mappings', 'type',
                            'itemtype', 'string', ['value' => '']);
   $migration->changeField('glpi_plugin_datainjection_mappings', 'model_id',
                            'models_id', 'integer');

   $migration->changeField('glpi_plugin_datainjection_infos', 'type',
                            'itemtype', 'string', ['value' => '']);
   $migration->changeField('glpi_plugin_datainjection_infos', 'model_id',
                            'models_id', 'integer');
   $migration->changeField('glpi_plugin_datainjection_infos', 'mandatory',
                            'is_mandatory', 'bool');

   $glpitables = ['glpi_plugin_datainjection_models',
                  'glpi_plugin_datainjection_mappings',
                  'glpi_plugin_datainjection_modelcsvs',
                  'glpi_plugin_datainjection_infos',
                  'glpi_plugin_datainjection_profiles'];

   foreach ($glpitables as $table) {
      $migration->changeField($table, 'ID', 'id', 'autoincrement');
   }

   $migration->migrationOneTable('glpi_plugin_datainjection_mappings');
   $migration->migrationOneTable('glpi_plugin_datainjection_infos');
   $migration->migrationOneTable('glpi_plugin_datainjection_modelcsvs');

   $glpitables = ['glpi_plugin_datainjection_models',
                  'glpi_plugin_datainjection_mappings',
                  'glpi_plugin_datainjection_infos',
                  'glpi_plugin_datainjection_modelcsvs'];
   Plugin::migrateItemType([], [], $glpitables);

   $query = "UPDATE `glpi_plugin_datainjection_mappings`
             SET `itemtype` = 'none' ,
                 `value`='none'
             WHERE `itemtype` = '-1'";
   $DB->queryOrDie($query, "Datainjection mappings tables : error updating not mapped fields");

   $migration->migrationOneTable('glpi_plugin_datainjection_infos');
   $query = "UPDATE `glpi_plugin_datainjection_infos`
             SET `itemtype` = 'none', `value` = 'none'
             WHERE `itemtype` = '-1'";
   $DB->queryOrDie($query, "Datainjection infos table : error updating not mapped fields");

   $foreignkeys = [
      'assign' => [
         [
            'to' => 'users_id_assign',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'assign_group' => [
         [
            'to' => 'groups_id_assign',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'assign_ent' => [
         [
            'to' => 'suppliers_id_assign',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'auth_method' => [
         [
            'to' => 'authtype',
            'noindex' => [
               'glpi_users'
            ],
            'tables' => [
               'glpi_users'
            ]
         ]
      ],
      'author' => [
         [
            'to' => 'users_id',
            'tables' => [
               'glpi_itilfollowups',
               'glpi_knowbaseitems',
               'glpi_tickets'
            ]
         ]
      ],
      'auto_update' => [
         [
            'to' => 'autoupdatesystems_id',
            'tables' => [
               'glpi_computers'
            ]
         ]
      ],
      'budget' => [
         [
            'to' => 'budgets_id',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'buy_version' => [
         [
            'to' => 'softwareversions_id_buy',
            'tables' => [
               'glpi_softwarelicenses'
            ]
         ]
      ],
      'category' => [
         [
            'to' => 'ticketcategories_id',
            'tables' => [
               'glpi_tickets'
            ]
         ],
         [
            'to' => 'softwarecategories_id',
            'tables' => [
               'glpi_softwares'
            ]
         ]
      ],
      'categoryID' => [
         [
            'to' => 'knowbaseitemcategories_id',
            'tables' => [
               'glpi_knowbaseitems'
            ]
         ]
      ],
      'cID' => [
         [
            'to' => 'computers_id',
            'tables' => [
               'glpi_computers_softwareversions'
            ]
         ]
      ],
      'computer' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'computer_id' => [
         [
            'to' => 'computers_id',
            'tables' => [
               'glpi_registrykeys'
            ]
         ]
      ],
      'contract_type' => [
         [
            'to' => 'contracttypes_id',
            'tables' => [
               'glpi_contracts'
            ]
         ]
      ],
      'default_rubdoc_tracking' => [
         [
            'to' => 'documentcategories_id_forticket',
            'tables' => [
               'glpi_configs'
            ],
            'comments' => [
               'glpi_configs' => 'default category for documents added with a ticket'
            ]
         ]
      ],
      'device_type' => [
         [
            'to' => 'itemtype',
            'tables' => [
               'glpi_alerts',
               'glpi_contracts_items',
               'glpi_documents_items',
               'glpi_infocoms',
               'glpi_bookmarks',
               'glpi_bookmarks_users',
               'glpi_links_itemtypes',
               'glpi_networkports',
               'glpi_reservationitems',
               'glpi_tickets'
            ]
         ]
      ],
      'domain' => [
         [
            'to' => 'domains_id',
            'tables' => [
               'glpi_computers',
               'glpi_networkequipments',
               'glpi_printers'
            ]
         ]
      ],
      'end1' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_computers_items'
            ],
            'comments' => [
               'glpi_computers_items' => 'RELATION to various table, according to itemtype (ID]'
            ]
         ],
         [
            'to' => 'networkports_id_1',
            'tables' => [
               'glpi_networkports_networkports'
            ]
         ]
      ],
      'end2' => [
         [
            'to' => 'computers_id',
            'tables' => [
               'glpi_computers_items'
            ]
         ],
         [
            'to' => 'networkports_id_2',
            'tables' => [
               'glpi_networkports_networkports'
            ]
         ]
      ],
      'firmware' => [
         [
            'to' => 'networkequipmentfirmwares_id',
            'tables' => [
               'glpi_networkequipments'
            ]
         ]
      ],
      'FK_bookmark' => [
         [
            'to' => 'bookmarks_id',
            'tables' => [
               'glpi_bookmarks_users'
            ]
         ]
      ],
      'FK_computers' => [
         [
            'to' => 'computers_id',
            'tables' => [
               'glpi_computerdisks',
               'glpi_softwarelicenses'
            ]
         ]
      ],
      'FK_contact' => [
         [
            'to' => 'contacts_id',
            'tables' => [
               'glpi_contacts_suppliers'
            ]
         ]
      ],
      'FK_contract' => [
         [
            'to' => 'contracts_id',
            'tables' => [
               'glpi_contracts_suppliers',
               'glpi_contracts_items'
            ]
         ]
      ],
      'FK_device' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_alerts',
               'glpi_contracts_items',
               'glpi_documents_items',
               'glpi_infocoms'
            ]
         ]
      ],
      'FK_doc' => [
         [
            'to' => 'documents_id',
            'tables' => [
               'glpi_documents_items'
            ]
         ]
      ],
      'manufacturer' => [
         [
            'to' => 'suppliers_id',
            'tables' => [
               'glpi_contacts_suppliers',
               'glpi_contracts_suppliers',
               'glpi_infocoms'
            ]
         ],
         [
            'to' => 'manufacturers_id',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_devicecases',
               'glpi_devicecontrols',
               'glpi_devicedrives',
               'glpi_devicegraphiccards',
               'glpi_deviceharddrives',
               'glpi_devicenetworkcards',
               'glpi_devicemotherboards',
               'glpi_devicepcis',
               'glpi_devicepowersupplies',
               'glpi_deviceprocessors',
               'glpi_devicememories',
               'glpi_devicesoundcards',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares'
            ]
         ]
      ],
      'FK_entities' => [
         [
            'to' => 'entities_id',
            'tables' => [
               'glpi_bookmarks',
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_contacts',
               'glpi_contracts',
               'glpi_documents',
               'glpi_locations',
               'glpi_netpoints',
               'glpi_suppliers',
               'glpi_entitydatas',
               'glpi_groups',
               'glpi_knowbaseitems',
               'glpi_links',
               'glpi_mailcollectors',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_reminders',
               'glpi_rules',
               'glpi_softwares',
               'glpi_softwarelicenses',
               'glpi_softwareversions',
               'glpi_tickets',
               'glpi_users',
               'glpi_profiles_users'
            ],
            'default' => [
               'glpi_bookmarks' => "-1"
            ]
         ]
      ],
      'FK_filesystems' => [
         [
            'to' => 'filesystems_id',
            'tables' => [
               'glpi_computerdisks'
            ]
         ]
      ],
      'FK_glpi_cartridges_type' => [
         [
            'to' => 'cartridgeitems_id',
            'tables' => [
               'glpi_cartridges',
               'glpi_cartridges_printermodels'
            ]
         ]
      ],
      'FK_glpi_consumables_type' => [
         [
            'to' => 'consumableitems_id',
            'tables' => [
               'glpi_consumables'
            ]
         ]
      ],
      'FK_glpi_dropdown_model_printers' => [
         [
            'to' => 'printermodels_id',
            'tables' => [
               'glpi_cartridges_printermodels'
            ]
         ]
      ],
      'FK_glpi_printers' => [
         [
            'to' => 'printers_id',
            'tables' => [
               'glpi_cartridges'
            ]
         ]
      ],
      'FK_group' => [
         [
            'to' => 'groups_id',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'FK_groups' => [
         [
            'to' => 'groups_id',
            'tables' => [
               'glpi_computers',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares',
               'glpi_groups_users'
            ]
         ]
      ],
      'FK_interface' => [
         [
            'to' => 'interfacetypes_id',
            'tables' => [
               'glpi_devicegraphiccards'
            ]
         ]
      ],
      'FK_item' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_mailingsettings'
            ]
         ]
      ],
      'FK_links' => [
         [
            'to' => 'links_id',
            'tables' => [
               'glpi_links_itemtypes'
            ]
         ]
      ],
      'FK_port' => [
         [
            'to' => 'networkports_id',
            'tables' => [
               'glpi_networkports_vlans'
            ]
         ]
      ],
      'FK_profiles' => [
         [
            'to' => 'profiles_id',
            'tables' => [
               'glpi_profiles_users',
               'glpi_users'
            ]
         ]
      ],
      'FK_users' => [
         [
            'to' => 'users_id',
            'tables' => [
               'glpi_bookmarks',
               'glpi_displaypreferences',
               'glpi_documents',
               'glpi_groups',
               'glpi_reminders',
               'glpi_bookmarks_users',
               'glpi_groups_users',
               'glpi_profiles_users',
               'glpi_computers',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares'
            ]
         ]
      ],
      'FK_vlan' => [
         [
            'to' => 'vlans_id',
            'tables' => [
               'glpi_networkports_vlans'
            ]
         ]
      ],
      'glpi_id' => [
         [
            'to' => 'computers_id',
            'tables' => [
               'glpi_ocslinks'
            ]
         ]
      ],
      'id_assign' => [
         [
            'to' => 'users_id',
            'tables' => [
               'glpi_ticketplannings'
            ]
         ]
      ],
      'id_auth' => [
         [
            'to' => 'auths_id',
            'tables' => [
               'glpi_users'
            ]
         ]
      ],
      'id_device' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_reservationitems'
            ]
         ]
      ],
      'id_item' => [
         [
            'to' => 'reservationitems_id',
            'tables' => [
               'glpi_reservations'
            ]
         ]
      ],
      'id_user' => [
         [
            'to' => 'users_id',
            'tables' => [
               'glpi_consumables',
               'glpi_reservations'
            ]
         ]
      ],
      'iface' => [
         [
            'to' => 'networkinterfaces_id',
            'tables' => [
               'glpi_networkports'
            ]
         ]
      ],
      'interface' => [
         [
            'to' => 'interfacetypes_id',
            'tables' => [
               'glpi_devicecontrols',
               'glpi_deviceharddrives',
               'glpi_devicedrives'
            ]
         ]
      ],
      'location' => [
         [
            'to' => 'locations_id',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_netpoints',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_users',
               'glpi_softwares'
            ]
         ]
      ],
      'model' => [
         [
            'to' => 'computermodels_id',
            'tables' => [
               'glpi_computers'
            ]
         ],
         [
            'to' => 'monitormodels_id',
            'tables' => [
               'glpi_monitors'
            ]
         ],
         [
            'to' => 'networkequipmentmodels_id',
            'tables' => [
               'glpi_networkequipments'
            ]
         ],
         [
            'to' => 'peripheralmodels_id',
            'tables' => [
               'glpi_peripherals'
            ]
         ],
         [
            'to' => 'phonemodels_id',
            'tables' => [
               'glpi_phones'
            ]
         ],
         [
            'to' => 'printermodels_id',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'netpoint' => [
         [
            'to' => 'netpoints_id',
            'tables' => [
               'glpi_networkports'
            ]
         ]
      ],
      'network' => [
         [
            'to' => 'networks_id',
            'tables' => [
               'glpi_computers',
               'glpi_networkequipments',
               'glpi_printers'
            ]
         ]
      ],
      'on_device' => [
         [
            'to' => 'items_id',
            'tables' => [
               'glpi_networkports'
            ]
         ]
      ],
      'os' => [
         [
            'to' => 'operatingsystems_id',
            'tables' => [
               'glpi_computers'
            ]
         ]
      ],
      'os_license_id' => [
         [
            'to' => 'os_licenseid',
            'tables' => [
               'glpi_computers'
            ]
         ]
      ],
      'os_version' => [
         [
            'to' => 'operatingsystemversions_id',
            'tables' => [
               'glpi_computers'
            ]
         ]
      ],
      'parentID' => [
         [
            'to' => 'knowbaseitemcategories_id',
            'tables' => [
               'glpi_knowbaseitemcategories'
            ]
         ],
         [
            'to' => 'locations_id',
            'tables' => [
               'glpi_locations'
            ]
         ],
         [
            'to' => 'ticketcategories_id',
            'tables' => [
               'glpi_ticketcategories'
            ]
         ],
         [
            'to' => 'entities_id',
            'tables' => [
               'glpi_entities'
            ]
         ]
      ],
      'platform' => [
         [
            'to' => 'operatingsystems_id',
            'tables' => [
               'glpi_softwares'
            ]
         ]
      ],
      'power' => [
         [
            'to' => 'phonepowersupplies_id',
            'tables' => [
               'glpi_phones'
            ]
         ]
      ],
      'recipient' => [
         [
            'to' => 'users_id_recipient',
            'tables' => [
               'glpi_tickets'
            ]
         ]
      ],
      'rubrique' => [
         [
            'to' => 'documentcategories_id',
            'tables' => [
               'glpi_documents'
            ]
         ]
      ],
      'sID' => [
         [
            'to' => 'softwares_id',
            'tables' => [
               'glpi_softwarelicenses',
               'glpi_softwareversions'
            ]
         ]
      ],
      'state' => [
         [
            'to' => 'states_id',
            'tables' => [
               'glpi_computers',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwareversions'
            ]
         ]
      ],
      'tech_num' => [
         [
            'to' => 'users_id_tech',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares'
            ]
         ]
      ],
      'title' => [
         [
            'to' => 'usertitles_id',
            'tables' => [
               'glpi_users'
            ]
         ]
      ],
      'type' => [
         [
            'to' => 'cartridgeitemtypes_id',
            'tables' => [
               'glpi_cartridgeitems'
            ]
         ],
         [
            'to' => 'computertypes_id',
            'tables' => [
               'glpi_computers'
            ]
         ],
         [
            'to' => 'consumableitemtypes_id',
            'tables' => [
               'glpi_consumableitems'
            ]
         ],
         [
            'to' => 'contacttypes_id',
            'tables' => [
               'glpi_contacts'
            ]
         ],
         [
            'to' => 'devicecasetypes_id',
            'tables' => [
               'glpi_devicecases'
            ]
         ],
         [
            'to' => 'devicememorytypes_id',
            'tables' => [
               'glpi_devicememories'
            ]
         ],
         [
            'to' => 'suppliertypes_id',
            'tables' => [
               'glpi_suppliers'
            ]
         ],
         [
            'to' => 'monitortypes_id',
            'tables' => [
               'glpi_monitors'
            ]
         ],
         [
            'to' => 'networkequipmenttypes_id',
            'tables' => [
               'glpi_networkequipments'
            ]
         ],
         [
            'to' => 'peripheraltypes_id',
            'tables' => [
               'glpi_peripherals'
            ]
         ],
         [
            'to' => 'phonetypes_id',
            'tables' => [
               'glpi_phones'
            ]
         ],
         [
            'to' => 'printertypes_id',
            'tables' => [
               'glpi_printers'
            ]
         ],
         [
            'to' => 'softwarelicensetypes_id',
            'tables' => [
               'glpi_softwarelicenses'
            ]
         ],
         [
            'to' => 'usercategories_id',
            'tables' => [
               'glpi_users'
            ]
         ],
         [
            'to' => 'itemtype',
            'tables' => [
               'glpi_computers_items',
               'glpi_displaypreferences'
            ]
         ]
      ],
      'update_software' => [
         [
            'to' => 'softwares_id',
            'tables' => [
               'glpi_softwares'
            ]
         ]
      ],
      'use_version' => [
         [
            'to' => 'softwareversions_id_use',
            'tables' => [
               'glpi_softwarelicenses'
            ]
         ]
      ],
      'vID' => [
         [
            'to' => 'softwareversions_id',
            'tables' => [
               'glpi_computers_softwareversions'
            ]
         ]
      ],
      'conpta_num' => [
         [
            'to' => 'accounting_number',
            'tables' => [
               'glpi_contracts'
            ]
         ]
      ],
      'num_commande' => [
         [
            'to' => 'order_number',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'bon_livraison' => [
         [
            'to' => 'delivery_number',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'num_immo' => [
         [
            'to' => 'immo_number',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'facture' => [
         [
            'to' => 'bill',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'amort_time' => [
         [
            'to' => 'sink_time',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'amort_type' => [
         [
            'to' => 'sink_type',
            'tables' => [
               'glpi_infocoms'
            ]
         ]
      ],
      'ifmac' => [
         [
            'to' => 'mac',
            'tables' => [
               'glpi_networkequipments'
            ]
         ]
      ],
      'ifaddr' => [
         [
            'to' => 'ip',
            'tables' => [
               'glpi_networkequipments',
               'glpi_networkports'
            ]
         ]
      ],
      'ramSize' => [
         [
            'to' => 'memory_size',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'facturation' => [
         [
            'to' => 'billing',
            'tables' => [
               'glpi_contracts'
            ]
         ]
      ],
      'monday' => [
         [
            'to' => 'use_monday',
            'tables' => [
               'glpi_contracts'
            ]
         ]
      ],
      'saturday' => [
         [
            'to' => 'use_saturday',
            'tables' => [
               'glpi_contracts'
            ]
         ]
      ],
      'recursive' => [
         [
            'to' => 'is_recursive',
            'tables' => [
               'glpi_networkequipments',
               'glpi_groups',
               'glpi_contracts',
               'glpi_contacts',
               'glpi_suppliers',
               'glpi_printers',
               'glpi_softwares',
               'glpi_softwareversions',
               'glpi_softwarelicences'
            ]
         ]
      ],
      'faq' => [
         [
            'to' => 'is_faq',
            'tables' => [
               'glpi_knowbaseitems'
            ]
         ]
      ],
      'flags_micro' => [
         [
            'to' => 'have_micro',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_speaker' => [
         [
            'to' => 'have_speaker',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_subd' => [
         [
            'to' => 'have_subd',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_bnc' => [
         [
            'to' => 'have_bnc',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_dvi' => [
         [
            'to' => 'have_dvi',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_pivot' => [
         [
            'to' => 'have_pivot',
            'tables' => [
               'glpi_monitors'
            ]
         ]
      ],
      'flags_hp' => [
         [
            'to' => 'have_hp',
            'tables' => [
               'glpi_phones'
            ]
         ]
      ],
      'flags_casque' => [
         [
            'to' => 'have_headset',
            'tables' => [
               'glpi_phones'
            ]
         ]
      ],
      'flags_usb' => [
         [
            'to' => 'have_usb',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'flags_par' => [
         [
            'to' => 'have_parallel',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'flags_serial' => [
         [
            'to' => 'have_serial',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'initial_pages' => [
         [
            'to' => 'init_pages_counter',
            'tables' => [
               'glpi_printers'
            ]
         ]
      ],
      'global' => [
         [
            'to' => 'is_global',
            'tables' => [
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares'
            ]
         ]
      ],
      'template' => [
         [
            'to' => 'template_name',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_devicecases',
               'glpi_devicecontrols',
               'glpi_devicedrives',
               'glpi_devicegraphiccards',
               'glpi_deviceharddrives',
               'glpi_devicenetworkcards',
               'glpi_devicemotherboards',
               'glpi_devicepcis',
               'glpi_devicepowersupplies',
               'glpi_deviceprocessors',
               'glpi_devicememories',
               'glpi_devicesoundcards',
               'glpi_monitors',
               'glpi_networkequipments',
               'glpi_peripherals',
               'glpi_phones',
               'glpi_printers',
               'glpi_softwares'
            ]
         ]
      ],
      'comments' => [
         [
            'to' => 'comment',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_contacts',
               'glpi_contracts',
               'glpi_documents',
               'glpi_autoupdatesystems',
               'glpi_budgets',
               'glpi_cartridgeitemtypes',
               'glpi_devicecasetypes',
               'glpi_consumableitemtypes',
               'glpi_contacttypes',
               'glpi_contracttypes',
               'glpi_domains',
               'glpi_suppliertypes',
               'glpi_filesystems',
               'glpi_networkequipmentfirmwares',
               'glpi_networkinterfaces',
               'glpi_interfacetypes',
               'glpi_knowbaseitemcategories',
               'glpi_softwarelicensetypes',
               'glpi_locations',
               'glpi_manufacturers',
               'glpi_computermodels',
               'glpi_monitormodels',
               'glpi_networkequipmentmodels',
               'glpi_peripheralmodels',
               'glpi_phonemodels',
               'glpi_printermodels',
               'glpi_netpoints',
               'glpi_networks',
               'glpi_operatingsystems',
               'glpi_operatingsystemservicepacks',
               'glpi_operatingsystemversions',
               'glpi_phonepowersupplies',
               'glpi_devicememorytypes',
               'glpi_documentcategories',
               'glpi_softwarecategories',
               'glpi_states',
               'glpi_ticketcategories',
               'glpi_usertitles',
               'glpi_usercategories',
               'glpi_vlans',
               'glpi_suppliers',
               'glpi_entities',
               'glpi_groups',
               'glpi_infocoms',
               'glpi_monitors',
               'glpi_phones',
               'glpi_printers',
               'glpi_peripherals',
               'glpi_networkequipments',
               'glpi_reservationitems',
               'glpi_rules',
               'glpi_softwares',
               'glpi_softwarelicenses',
               'glpi_softwareversions',
               'glpi_computertypes',
               'glpi_monitortypes',
               'glpi_networkequipmenttypes',
               'glpi_peripheraltypes',
               'glpi_phonetypes',
               'glpi_printertypes',
               'glpi_users'
            ]
         ]
      ],
      'notes' => [
         [
            'to' => 'notepad',
            'tables' => [
               'glpi_cartridgeitems',
               'glpi_computers',
               'glpi_consumableitems',
               'glpi_contacts',
               'glpi_contracts',
               'glpi_documents',
               'glpi_suppliers',
               'glpi_entitydatas',
               'glpi_printers',
               'glpi_monitors',
               'glpi_phones',
               'glpi_peripherals',
               'glpi_networkequipments',
               'glpi_softwares'
            ]
         ]
      ]
   ];

   $foreignkeys = Plugin::doHookFunction("plugin_datainjection_migratefields", $foreignkeys);
   $query = "SELECT `itemtype`, `value`
            FROM `glpi_plugin_datainjection_mappings`
            WHERE `itemtype` NOT IN ('none')
            GROUP BY `itemtype`,`value`";

   foreach ($DB->request($query) as $data) {
      if (isset($foreignkeys[$data['value']])) {
         foreach ($foreignkeys[$data['value']] as $field_info) {
            $table = getTableForItemType($data['itemtype']);
            if (in_array($table, $field_info['tables'])) {
               $query = "UPDATE `glpi_plugin_datainjection_mappings`
                         SET `value` = '".$field_info['to']."'
                         WHERE `itemtype` = '".$data['itemtype']."'
                           AND `value` = '".$data['value']."'";
               $DB->queryOrDie($query, "Datainjection : error converting mapping fields");
               $query = "UPDATE `glpi_plugin_datainjection_infos`
                         SET `value` = '".$field_info['to']."'
                         WHERE `itemtype` = '".$data['itemtype']."'
                          AND `value` = '".$data['value']."'";
               $DB->queryOrDie($query, "Datainjection : error converting infos fields");
            }
         }
      }
   }
}

function plugin_datainjection_update210_220() {
   global $DB;

   foreach (['glpi_plugin_datainjection_mappings',
            'glpi_plugin_datainjection_infos'] as $table) {

      $move = ['TicketCategory'     => 'ITILCategory',
               'TicketSolutionType' => 'SolutionType'];
      foreach ($move as $old => $new) {
         $query = "UPDATE `".$table."`
                   SET `itemtype` = '".$new."'
                   WHERE `itemtype` = '".$old."'";
         $DB->query($query);
      }

      //emails are now dropdowns
      $query = "UPDATE `".$table."`
                SET `value` = 'useremails_id'
                WHERE `itemtype` = 'User'
                      AND `value` = 'email'";
      $DB->query($query);
   }

}
function plugin_datainjection_update220_230() {
   global $DB;

   if (countElementsInTable("glpi_plugin_datainjection_models", ['entities_id' => -1])) {
      $query = "UPDATE `glpi_plugin_datainjection_models`
                SET `is_private` = '1',
                    `entities_id` = '0',
                    `is_recursive` = '1'
               WHERE `entities_id` = '-1'";
      $DB->query($query);
   }
}


/**
 * @param $hook_name
 * @param $params       array
**/
function plugin_datainjection_loadHook($hook_name, $params = []) {

   global $PLUGIN_HOOKS;

   if (!empty($params)) {
      $type = $params["type"];
      //If a plugin type is defined
      Plugin::doOneHook(
         $PLUGIN_HOOKS['plugin_types'][$type],
         'datainjection_' . $hook_name
      );

   } else {
      if (isset($PLUGIN_HOOKS['plugin_types'])) {
         //Browse all plugins
         foreach ($PLUGIN_HOOKS['plugin_types'] as $type => $name) {
            Plugin::doOneHook($name, 'datainjection_' . $hook_name);
         }
      }
   }
}


function plugin_datainjection_needUpdateOrInstall() {
   global $DB;

    //Install plugin
   if (!$DB->tableExists('glpi_plugin_datainjection_models')) {
      return 0;
   }

   if ($DB->tableExists("glpi_plugin_datainjection_modelcsvs")) {
      return -1;
   }

    return 1;
}


/**
 * Used for filter list of models
 *
 * @param $itemtype
**/
function plugin_datainjection_addDefaultWhere($itemtype) {

   switch ($itemtype) {
      case 'PluginDatainjectionModel' :
         $models = PluginDatainjectionModel::getModels(
            Session::getLoginUserID(), 'name',
            $_SESSION['glpiactive_entity'], true
         );
         if (count($models) > 0) {
            $tab = [];
            foreach ($models as $model) {
               $tab[] = $model['id'];
            }
            if (count($tab) > 0) {
               $where = "`glpi_plugin_datainjection_models`.`id` IN ('".implode("','", $tab)."')";
            }
            return $where;
         }
         return false;
      default:
         break;
   }
}
