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

class PluginDatainjectionEngine {
   //Model informations
   private $model;

   //Current entity
   private $entity;

   function __construct($model, $entity = 0) {
      //Instanciate model
      $this->model = $model;

      //Load model and mappings informations
      $this->getModel()->loadMappings();
      $this->getModel()->loadInfos();

      $this->entity = $entity;
   }

   /**
    * Inject one line of datas
    * @param line one line of data to import
    */
   function injectLine($line) {
      //Store all fields to injection, sorted by itemtype
      $fields_toinject = array();
      $mandatory_fields = array();

      //Get the injectionclass associated to the itemtype
      $itemtype = $this->getModel()->getItemtype();
      $iteminjection = PluginDatainjectionInjectionCommon::getInstance($itemtype);

      //First of all : transform $line which is an array of values to inject into another array
      //which looks like this :
      //array(itemtype=>array(field=>value,field2=>value2))
      //Note : ignore values which are not mapped with a glpi's field
      for ($i = 0; $i < count($line); $i++) {
         $mapping = $this->getModel()->getMappingByRank($i);
         //If field is mapped with a value in glpi
         if ($mapping->getValue() != PluginDatainjectionMapping::NOT_MAPPED) {
            $fields_toinject[$mapping->getItemtype()][$mapping->getValue()] = $line[$i];
            $mandatory_fields[$mapping->getItemtype()][$mapping->getValue()] =
                                                                           $mapping->isMandatory();
         }
      }

      //--------------- Set all needed options ------------------//
      //Check options
      $checks = array('ip'                       =>true,
                      'mac'                      =>true,
                      'integer'                  =>true,
                      'yes'                      =>true,
                      'yesno'                    =>true,
                      'date'                     =>$this->getModel()->getDateFormat(),
                      'float'                    =>$this->getModel()->getFloatFormat(),
                      'string'                   =>true,
                      'right_r'                  =>true,
                      'right_rw'                 =>true,
                      'interface'                =>true,
                      'auth_method'              =>true);

      //Rights options
      $rights = array('add_dropdown'             =>$this->getModel()->getCanAddDropdown(),
                      'overwrite_notempty_fields'=>$this->getModel()->getCanOverwriteIfNotEmpty());

      //Field format options
      $formats = array('date_format'             =>$this->getModel()->getDateFormat(),
                       'float_format'            =>$this->getModel()->getFloatFormat());

      //Check options : by default check all types
      $options = array('checks'                  =>$checks,
                       'entities_id'             =>$this->getEntity(),
                       'rights'                  => $rights,
                       'formats'                 =>$formats,
                       'mandatory_fields'        =>$mandatory_fields);

      //Do we need to add or update the objet ?
      $action = 'add';
      //TODO : implement method to check if it's an add/update
      if ($action == 'add') {
         $results = $iteminjection->addObject($fields_toinject,$options);
      }
      else {
         $results = $iteminjection->updateObject($fields_toinject,$options);
      }

      /*
      $line = reformatDatasBeforeCheck($this->getModel(), $line, $this->getEntity(), $result);

      //If values check is not successful
      if (!checkLine($this->getModel(), $line, $result)) {
         //Add a message to indicate that import was impossible
         $result->addInjectionMessage(IMPORT_IMPOSSIBLE);
         return $result;
      }
      if ($result->getCheckStatus() != CHECK_OK)
         $result->addInjectionMessage(PARTIALY_IMPORTED);

      //Array to store the fields to write to db
      $db_fields = array ();
      $db_fields[COMMON_FIELDS] = array ();
      $db_fields[$this->getModel()->getDeviceType()] = array ();

      for ($i = 0; $i < count($line); $i++) {
         $mapping = $this->getModel()->getMappingByRank($i);
         if ($mapping != null && $mapping->getValue() != NOT_MAPPED) {
            $mapping_definition = getMappingDefinitionByTypeAndName($mapping->getItemtype(), $mapping->getValue());
            if (!isset ($db_fields[$mapping->getItemtype()]))
               $db_fields[$mapping->getItemtype()] = array ();

            $field = getFieldValue($result, $this->getModel()->getDeviceType(), $mapping, $mapping_definition, $line[$i], $this->getEntity(), $db_fields[$mapping->getItemtype()], $this->getModel()->getCanAddDropdown(), lookIfSeveralMappingsForField($this->getModel(), $mapping_definition, $mapping->getValue(), $line), getFieldCommentsIfExists($this->getModel(), $line, $mapping));
            if (!empty ($field)) {
               $db_fields[$mapping->getItemtype()] = $field;
            }
         }
      }

      //Add informations filleds by the user
      $db_fields = addInfosFields($db_fields, $infos);

      $process = true;
      //----------------------------------------------------//
      //-------------Process primary type------------------//
      //--------------------------------------------------//

      //First, try to insert or update primary object
      $fields = $db_fields[$this->getModel()->getDeviceType()];

      $obj = getInstance($this->getModel()->getDeviceType());

      //Add some fields to the common fields BEFORE inserting the primary type (in order to save some fields)
      $db_fields[COMMON_FIELDS] = preAddCommonFields($db_fields[COMMON_FIELDS], $this->getModel()->getDeviceType(), $fields, $this->getEntity());

      //If necessary, add default fields which are mandatory to create the object
      addNecessaryFields($this->getModel(), $mapping, $mapping_definition, $this->getEntity(), $this->getModel()->getDeviceType(), $fields, $db_fields[COMMON_FIELDS]);

      //Check if the line already exists in database
      $fields_from_db = dataAlreadyInDB($this->getModel()->getDeviceType(), $fields, $mapping_definition, $this->getModel());

      $ID = $fields_from_db["ID"];

      if ($ID == ITEM_NOT_FOUND) {
         if ($this->getModel()->getBehaviorAdd()) {
            //Manage templates
            updateWithTemplate($fields, $this->getModel()->getDeviceType());
            $ID = $obj->add($fields);

            if ($ID) {
               //Add some default values (getempty() or getFromDB($ID) could be better)
               addCommonFields($db_fields[COMMON_FIELDS], $this->getModel()->getDeviceType(), array (
                  "location" => 0
               ), $this->getEntity(), $ID);

               //Add the ID to the fields, so it can be reused after
               addCommonFields($db_fields[COMMON_FIELDS], $this->getModel()->getDeviceType(), $fields, $this->getEntity(), $ID);

               //Log in history
               logAddOrUpdate($this->getModel()->getDeviceType(), $ID, INJECTION_ADD);

               //Set status and messages
               $result->setInjectedId($ID);
               $result->setInjectionType(INJECTION_ADD);
            } else {
               // Add failed (SQL error ?)
               $process = false;

               $result->setInjectedId(NOT_IMPORTED);
               $result->setInjectionType(INJECTION_ADD);
               $result->addInjectionMessage(ERROR_CANNOT_IMPORT);
            } // ID
         } else {
            //Object doesn't exists, but adding is not allowed by the model
            $process = false;

            $result->setInjectedId(NOT_IMPORTED);
            $result->setInjectionType(INJECTION_ADD);
            $result->addInjectionMessage(ERROR_CANNOT_IMPORT);
         }
      } else
         if ($this->getModel()->getBehaviorUpdate()) {
            filterFields($fields, $fields_from_db, $this->getModel()->getCanOverwriteIfNotEmpty(), $this->getModel()->getDeviceType());
            $fields["ID"] = $ID;

            // Save fields from DB (mainly for location if not provided in CSV)
            addCommonFields($db_fields[COMMON_FIELDS], $this->getModel()->getDeviceType(), $fields_from_db, $this->getEntity(), $ID);

            // Save fields from CSV
            addCommonFields($db_fields[COMMON_FIELDS], $this->getModel()->getDeviceType(), $fields, $this->getEntity(), $ID);

            if (count($fields) > 1) {
               logAddOrUpdate($this->getModel()->getDeviceType(), $ID, INJECTION_UPDATE);
               $obj->update($fields);
            }
            $result->setInjectedId($ID);
            $result->setInjectionType(INJECTION_UPDATE);
         } else {
            //Object exists but update is not allowed by the model
            $process = false;

            $result->setInjectedId($ID);
            $result->setInjectionType(INJECTION_UPDATE);
            $result->addInjectionMessage(ERROR_IMPORT_ALREADY_IMPORTED);
            $result->addInjectionMessage(ERROR_CANNOT_UPDATE);
         }
      if ($process) {
         //Post processing, if some actions need to be done
         processBeforeEnd($result, $this->getModel(), $this->getModel()->getDeviceType(), $fields, $db_fields[COMMON_FIELDS]);

         //----------------------------------------------------//
         //-------------Process other types-------------------//
         //--------------------------------------------------//

         //Insert others objects in database
         foreach ($db_fields as $type => $fields) {
            //Browse the db_fields array : inject all the types execpt COMMON_FIELDS and the primary type
            if ($type != COMMON_FIELDS && $type != $this->getModel()->getDeviceType()) {
               if (!isAllEmpty($fields)) {

                  //May return null if the type is virtual (only for postprocessing in processBeforeEnd)
                  $obj = getInstance($type);
                  if ($obj) {
                     // Add some fields to the common fields BEFORE inserting the secondary type (in order to save some fields)
                     $db_fields[COMMON_FIELDS] = preAddCommonFields($db_fields[COMMON_FIELDS], $type, $fields, $this->getEntity());

                     // If necessary, add default fields which are mandatory to create the object
                     addNecessaryFields($this->getModel(), $mapping, $mapping_definition, $this->getEntity(), $type, $fields, $db_fields[COMMON_FIELDS]);

                     //Check if the line already exists in database
                     $fields_from_db = dataAlreadyInDB($type, $fields, $mapping_definition, $this->getModel());

                     $ID = $fields_from_db["ID"];
                     if ($ID == ITEM_NOT_FOUND) {
                        //Not in DB -> add
                        $ID = $obj->add($fields);
                        //Add the ID to the fields, so it can be reused after
                        addCommonFields($db_fields[COMMON_FIELDS], $type, $fields, $this->getEntity(), $ID);
                     } else {
                        $fields["ID"] = $ID;
                        filterFields($fields, $fields_from_db, $this->getModel()->getCanOverwriteIfNotEmpty(), $type);
                        //Item aleady in DB -> update
                        addCommonFields($db_fields[COMMON_FIELDS], $type, $fields, $this->entity, $ID);

                        $obj->update($fields);
                     }
                  } else
                     addNecessaryFields($this->getModel(), $mapping, $mapping_definition, $this->getEntity(), $type, $fields, $db_fields[COMMON_FIELDS]);

                  //Post processing, if some actions need to be done
                  processBeforeEnd($result, $this->getModel(), $type, $fields, $db_fields[COMMON_FIELDS]);
               }
            } // Type check
         } // Each type
      }
      */

      return $results;
   }

   //--------- Getters -------------------------//
   function getModel() {
      return $this->model;
   }

   function getEntity() {
      return $this->entity;
   }
}
?>