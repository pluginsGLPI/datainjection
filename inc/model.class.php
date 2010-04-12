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

   function __construct()
   {
      $this->mappings = new PluginPluginDatainjectionMappingCollection;
      $this->infos = new PluginDatainjectionInfosCollection;
      $this->init();
      $this->may_be_private=true;
      $this->may_be_recursive=true;
      $this->entity_assign=true;
   }

   function init()
   {

   }

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
      return $this->fields["type"];
   }

   function getModelComments()
   {
      return $this->fields["comments"];
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
      return $this->fields["ID"];
   }

   function getDeviceType()
   {
      return $this->fields["device_type"];
   }

   function getEntity()
   {
      return $this->fields["FK_entities"];
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
      return $this->fields["FK_users"];
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
      return $this->fields["recursive"];
   }

   function getPrivate()
   {
      return $this->fields["private"];
   }

   function getPortUnicity()
   {
      return $this->fields["port_unicity"];
   }

   //---- Save -----//
   function setModelType($type)
   {
      $this->fields["type"] = $type;
   }

   function setName($name)
   {
      $this->fields["name"] = $name;
   }

   function setComments($comments)
   {
      $this->fields["comments"] = $comments;
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
      $this->fields["ID"] = $ID;
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
      $this->fields["device_type"] = $device_type;
   }

   function setEntity($entity)
   {
      $this->fields["FK_entities"] = $entity;
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
      $this->fields["private"] = $private;
   }

   function setUserID($user)
   {
      $this->fields["FK_users"] = $user;
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
      $this->fields["recursive"] = $recursive;
   }

   function setFields($fields,$entity,$user_id)
   {
      //$this->setEntity($entity);
      $this->setEntity($fields["FK_entities"]);

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
      $sql = "SELECT * FROM glpi_plugin_datainjection_models WHERE" .
      " (private=" . MODEL_PUBLIC . getEntitiesRestrictRequest(" AND",
                                                               "glpi_plugin_datainjection_models",
                                                               "entities_id", $entity,true) . ")
       OR (private=" . MODEL_PRIVATE . " AND users_id=$user_id)" .
      " ORDER BY entities_id, " . ($order == "name" ? "name" : $order);
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

   static function getInstanceByType($type) {
      global $DB;
      $sql = "SELECT `model_class_name`
              FROM `glpi_plugin_datainjection_filetype`
              WHERE `value`='$type'";
      $res = $DB->query($sql);
      if ($DB->numrows($res) > 0) {
         $backend_infos = $DB->fetch_array($res);
         return new $backend_infos["model_classname"];
      } else
         return null;
   }

   static function getInstanceByID($model_id) {
      $model = new PluginDatainjectionModel;
      $model->getFromDB($model_id);
      $model = PluginDatainjectionModel::getInstanceByType($model->getModelType());
      $model->getFromDB($model_id);
      return $model;
   }


}
?>