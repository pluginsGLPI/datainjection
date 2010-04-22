<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */
class PluginDatainjectionModel extends CommonDBTM {

   private $mappings;
   private $backend;
   protected $infos;
   public $dohistory = true;

   const DATE_TYPE_DDMMYYYY = "dd-mm-yyyy";
   const DATE_TYPE_MMDDYYYY = "mm-dd-yyyy";
   const DATE_TYPE_YYYYMMDD = "yyyy-mm-dd";

   const FLOAT_TYPE_DOT          = 0;
   const FLOAT_TYPE_COMMA        = 1;
   const FLOAT_TYPE_DOT_AND_COM  = 2;

   const UNICITY_NETPORT_LOGICAL_NUMBER = 0;
   const UNICITY_NETPORT_NAME = 1;
   const UNICITY_NETPORT_MACADDRESS = 2;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME = 3;
   const UNICITY_NETPORT_LOGICAL_NUMBER_MAC = 4;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC = 5;

   const MODEL_PRIVATE = 1;
   const MODEL_PUBLIC = 0;

   function __construct()
   {
      //$this->mappings = new PluginPluginDatainjectionMappingCollection;
      //$this->infos = new PluginDatainjectionInfosCollection;
      //$this->init();
      //$this->may_be_private=true;
      //$this->may_be_recursive=true;
      //$this->entity_assign=true;
   }

   function canCreate() {
      return plugin_datainjection_haveRight('model','w');
   }

   function canView() {
      return plugin_datainjection_haveRight('model','r');
   }

   function init()
   {

   }
/*
   //To be implemented
   function saveSpecificFields()
   {

   }

   function deleteSpecificFields()
   {
   }

   function updateSpecificFields()
   {

   }

   function loadSpecificfields()
   {

   }

   //---- Load -----//


   function loadAll($model_id)
   {
      if ($this->getFromDB($model_id))
      {
         $this->loadSpecificfields();
         $this->loadMappings($model_id);
         $this->loadInfos($model_id);
         return true;
      }
      else
         return false;
   }
   function loadMappings($model_id)
   {
      $this->mappings->getAllMappingsByModelID($model_id);
   }

   function loadInfos($model_id)
   {
      $this->infos->getAllInfosByModelID($model_id);
   }

   //---- Add -----//
   function addMappingToModel($mapping)
   {
      $this->mappings->addNewMapping($mapping);
   }

   function addInfosToModel($infos)
   {
      $this->infos->addNewInfos($infos);
   }

   //---- Save -----//
   function saveModel()
   {
      //Save or add model
      if (!isset($this->fields["ID"]))
         $this->fields["ID"] = $this->add($this->fields);
      else
         $this->update($this->fields);

      $this->saveSpecificFields();

      //Save or add mappings
      $this->mappings->saveAllMappings($this->fields["ID"]);
      $this->infos->saveAllInfos($this->fields["ID"]);
   }

   function deleteModel()
   {
      if($this->mappings->deleteMappingsFromDB($this->fields["ID"]) && $this->infos->deleteInfosFromDB($this->fields["ID"]))
         {
         $this->deleteSpecificFields();
         if($this->deleteFromDB($this->fields["ID"]))
            return true;
         else
            return false;
         }
      else
         return false;
   }

   function updateModel()
   {
      $this->update($this->fields);
      $this->updateSpecificFields();
      $this->mappings->deleteMappingsFromDB($this->fields["ID"]);
      $this->mappings->saveAllMappings($this->fields["ID"]);
      $this->infos->deleteInfosFromDB($this->fields["ID"]);
      $this->infos->saveAllInfos($this->fields["ID"]);
   }
*/
   //---- Getters -----//
   function getMappings()
   {
      return $this->mappings->getAllMappings();
   }

   function getInfos()
   {
      return $this->infos->getAllInfos();
   }

   function getBackend()
   {
      return $this->backend;
   }

   function getMappingByName($name)
   {
      return $this->mappings->getMappingByName($name);
   }

   function getMappingByRank($rank)
   {
      return $this->mappings->getMappingByRank($rank);
   }

   function getMappingByValue($value)
   {
      return $this->mappings->getMappingsByField("value",$value);
   }

   function getModelInfos()
   {
      return $this->fields;
   }

   function getModelName()
   {
      return $this->fields["name"];
   }

   function getModelType()
   {
      return $this->fields["filetype"];
   }

   function getModelComments()
   {
      return $this->fields["comment"];
   }

   function getBehaviorAdd()
   {
      return $this->fields["behavior_add"];
   }

   function getBehaviorUpdate()
   {
      return $this->fields["behavior_update"];
   }

   function getModelID()
   {
      return $this->fields["id"];
   }

   function getDeviceType()
   {
      return $this->fields["itemtype"];
   }

   function getEntity()
   {
      return $this->fields["entities_id"];
   }

   function getCanAddDropdown()
   {
      return $this->fields["can_add_dropdown"];
   }

   function getCanOverwriteIfNotEmpty()
   {
      return $this->fields["can_overwrite_if_not_empty"];
   }

   function getUserID()
   {
      return $this->fields["users_id"];
   }

   function getPerformNetworkConnection()
   {
      return $this->fields["perform_network_connection"];
   }

   function getDateFormat()
   {
      return $this->fields["date_format"];
   }

   function getFloatFormat()
   {
      return $this->fields["float_format"];
   }

   function getRecursive()
   {
      return $this->fields["is_recursive"];
   }

   function getPrivate()
   {
      return $this->fields["is_private"];
   }

   function getPortUnicity()
   {
      return $this->fields["port_unicity"];
   }

   //---- Save -----//
   function setModelType($type)
   {
      $this->fields["filetype"] = $type;
   }

   function setName($name)
   {
      $this->fields["name"] = $name;
   }

   function setComments($comments)
   {
      $this->fields["comment"] = $comments;
   }

   function setBehaviorAdd($add)
   {
      $this->fields["behavior_add"] = $add;
   }

   function setBehaviorUpdate($update)
   {
      $this->fields["behavior_update"] = $update;
   }

   function setModelID($ID)
   {
      $this->fields["id"] = $ID;
   }

   function setMappings($mappings)
   {
      $this->mappings = $mappings;
   }

   function setInfos($infos)
   {
      $this->infos = $infos;
   }

   function setPluginDatainjectionBackend($backend)
   {
      $this->backend = $backend;
   }

   function setDeviceType($device_type)
   {
      $this->fields["itemtype"] = $device_type;
   }

   function setEntity($entity)
   {
      $this->fields["entities_id"] = $entity;
   }

   function setCanAddDropdown($canadd)
   {
      $this->fields["can_add_dropdown"] = $canadd;
   }

   function setCanOverwriteIfNotEmpty($canoverwrite)
   {
      $this->fields["can_overwrite_if_not_empty"] = $canoverwrite;
   }

   function setPrivate($private)
   {
      $this->fields["is_private"] = $private;
   }

   function setUserID($user)
   {
      $this->fields["users_id"] = $user;
   }

   function setDateFormat($df)
   {
      $this->fields["date_format"] = $df;
   }

   function setFloatFormat($ff)
   {
      $this->fields["float_format"] = $ff;
   }

   function setPerformNetworkConnection($perform)
   {
      $this->fields["perform_network_connection"] = $perform;
   }

   function setRecursive($recursive)
   {
      $this->fields["is_recursive"] = $recursive;
   }
/*
   function setFields($fields,$entity,$user_id)
   {
      //$this->setEntity($entity);
      $this->setEntity($fields["entities_id"]);

      $this->setUserID($user_id);

      if(isset($fields["dropdown_device_type"]))
         $this->setDeviceType($fields["dropdown_device_type"]);

      if(isset($fields["dropdown_type"]))
         $this->setModelType($fields["dropdown_type"]);

      if(isset($fields["dropdown_create"]))
         $this->setBehaviorAdd($fields["dropdown_create"]);

      if(isset($fields["dropdown_update"]))
         $this->setBehaviorUpdate($fields["dropdown_update"]);

      if(isset($fields["dropdown_canadd"]))
         $this->setCanAddDropdown($fields["dropdown_canadd"]);

      if(isset($fields["can_overwrite_if_not_empty"]))
         $this->setCanOverwriteIfNotEmpty($fields["can_overwrite_if_not_empty"]);

      if(isset($fields["private"]))
         $this->setPrivate($fields["private"]);

      if(isset($fields["perform_network_connection"]))
         $this->setPerformNetworkConnection($fields["perform_network_connection"]);

      if(isset($fields["date_format"]))
         $this->setDateFormat($fields["date_format"]);

      if(isset($fields["float_format"]))
         $this->setFloatFormat($fields["float_format"]);

      if(isset($fields["recursive"]))
         $this->setRecursive($fields["recursive"]);

      if(isset($fields["port_unicity"]))
         $this->setPortUnicity($fields["port_unicity"]);

   }
*/

   function setPortUnicity($unicity)
   {
      $this->fields["port_unicity"]=$unicity;
   }

   //Webservices functions
   static function methodGetModel($params,$protocol) {

      if (isset ($params['help'])) {
         return array('help' => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      $model = new PluginPluginDatainjectionModel;
      if ($model->getFromDB($params['id'])) {
         return $model->fields;
      }
      else {
         return array();
      }
   }

   static function methodListModels($params,$protocol) {
      if (isset ($params['help'])) {
         return array('help' => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      return getAllDatasFromTable('glpi_plugin_datainjection_models');
   }

   static function getModels($user_id, $order = "name", $entity = -1, $can_write = false) {
      global $DB;

      $models = array ();
      $sql = "SELECT * FROM `glpi_plugin_datainjection_models` WHERE" .
      " (`private`=" . PluginDatainjectionModel::MODEL_PUBLIC . getEntitiesRestrictRequest(" AND",
                                                               "glpi_plugin_datainjection_models",
                                                               "entities_id", $entity,true) . ")
       OR (private=" . PluginDatainjectionModel::MODEL_PRIVATE . " AND `users_id`=$user_id)" .
      " ORDER BY `entities_id`, " . ($order == "`name`" ? "`name`" : $order);
      $result = $DB->query($sql);

      while ($data = $DB->fetch_array($result)) {
         $model = new PluginDatainjectionModel();
         if ($model->can($data["id"], ($can_write ? "w" : "r"))) {
            $model->fields = $data;
            $models[] = $model;
         }
      }

      return $models;
   }

   static function getInstanceByID($model_id) {
      $model = new PluginDatainjectionModel;
      $model->getFromDB($model_id);
      $model = PluginDatainjectionModel::getInstance($model->getModelType());
      $model->getFromDB($model_id);
      return $model;
   }

   //Standard functions
   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG["datainjection"]["model"][0];

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[2]['table']        = $this->getTable();
      $tab[2]['field']        = 'id';
      $tab[2]['linkfield']    = '';
      $tab[2]['name']         = $LANG['common'][2];

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'behavior_add';
      $tab[3]['linkfield'] = '';
      $tab[3]['name']      = $LANG["datainjection"]["model"][6];
      $tab[3]['datatype'] = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'behavior_update';
      $tab[4]['linkfield'] = '';
      $tab[4]['name']      = $LANG["datainjection"]["model"][7];
      $tab[4]['datatype'] = 'bool';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'itemtype';
      $tab[5]['linkfield'] = '';
      $tab[5]['name']      = $LANG["common"][17];
      $tab[5]['datatype'] = 'itemtypename';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'can_add_dropdown';
      $tab[6]['linkfield'] = '';
      $tab[6]['name']      = $LANG["datainjection"]["model"][8];
      $tab[6]['datatype'] = 'bool';

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'date_format';
      $tab[7]['linkfield'] = 'date_format';
      $tab[7]['name']      = $LANG["datainjection"]["model"][21];
      $tab[7]['datatype'] = 'text';

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'float_format';
      $tab[8]['linkfield'] = 'float_format';
      $tab[8]['name']      = $LANG["datainjection"]["model"][28];
      $tab[8]['datatype'] = 'text';

      $tab[9]['table']     = $this->getTable();
      $tab[9]['field']     = 'perform_network_connection';
      $tab[9]['linkfield'] = 'perform_network_connection';
      $tab[9]['name']      = $LANG["datainjection"]["model"][20];
      $tab[9]['datatype'] = 'bool';

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'port_unicity';
      $tab[10]['linkfield'] = 'port_unicity';
      $tab[10]['name']      = $LANG["datainjection"]["mappings"][7];
      $tab[10]['datatype'] = 'text';

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = $LANG['common'][25];
      $tab[16]['datatype']  = 'text';


      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = $LANG['entity'][0];

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = $LANG['entity'][9];
      $tab[86]['datatype']  = 'bool';
      return $tab;
   }

   function showForm($ID, $options = array()) {
      global $DB,$LANG;
      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
      }
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";

      echo "<td>".$LANG['common'][16].": </td>";
      echo "<td>";
      autocompletionTextField($this,"name");
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][18].": </td>";
      echo "<td>";
      Dropdown::showYesNo("is_private",$this->fields['is_private']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][6].": </td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_add",$this->fields['behavior_add']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][7].": </td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_update",$this->fields['behavior_update']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][4].": </td>";
      echo "<td>";
      PluginDatainjectionInjectionType::dropdown($this->fields['itemtype']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][12].": </td>";
      echo "<td>";
      Dropdown::showYesNo("can_overwrite_if_not_empty",$this->fields['can_overwrite_if_not_empty']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][20].": </td>";
      echo "<td>";
      Dropdown::showYesNo("perform_network_connection",$this->fields['perform_network_connection']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][21].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownDateFormat($this->fields['date_format']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][28].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownFloatFormat($this->fields['float_format']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][5].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownFileTypes($this->fields['filetype']);
      echo "</td>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["mappings"][7].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownPortUnicity($this->fields['port_unicity']);
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

   function defineTabs($options=array()) {
      global $LANG;

      $ong[1] = $LANG['title'][26];
      if ($this->fields['id'] > 0) {
         $ong[2] = $LANG["datainjection"]["tabs"][0];
         $ong[3] = $LANG["datainjection"]["tabs"][1];
         $ong[4] = $LANG["datainjection"]["tabs"][2];
         $ong[12] = $LANG['title'][38];
      }
      return $ong;
   }

   function showUploadForm() {
      global $LANG;
      if ($this->can($this->fields['id'],'w')) {
         echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'".
               "enctype='multipart/form-data'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='2'>".$LANG["datainjection"]["model"][29]."</th></tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<td>" . $LANG["datainjection"]["fileStep"][3] . "</td>";
         echo "<td><input type='file' name='file' /></td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>" . $LANG["datainjection"]["fileStep"][9] . "</td>";
         echo "<td>";
         PluginDatainjectionDropdown::dropdownFileEncoding();
         echo "</td>";
         echo "</tr>";
         echo "<tr class='tab_bg_2'>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='hidden' name='id' value='".$this->fields['id']."'>";
         echo "<input type='submit' name='upload' value=\"".$LANG['buttons'][7]."\" class='submit' >";
         echo "</td></tr>";
         echo "</table></form>";
      }
   }

   /*
    * Get the backend implementation by type
    */
   static function getInstance($type)
   {
      $class = 'PluginDatainjectionModel'.$type;
      if (class_exists($class)) {
         return new $class();
      }
      else {
         return false;
      }
   }
}
?>