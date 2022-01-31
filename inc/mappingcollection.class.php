<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2022 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

class PluginDatainjectionMappingCollection
{

   private $mappingCollection;

   function __construct() {

      $this->mappingCollection = [];
   }


    //---- Getter ----//

    /**
    * Load all the mappings for a specified model
    *
    * @param model_ids the model ID
   **/
   function load($models_id) {

      global $DB;

      $sql = "SELECT *
              FROM `glpi_plugin_datainjection_mappings`
              WHERE `models_id` = '$models_id'
              ORDER BY `rank` ASC";

      $this->mappingCollection = [];

      foreach ($data = $DB->request($sql) as $data) {
         // Addslashes to conform to value return by PluginDatainjectionBackendcsv::parseLine
         $data["name"]              = addslashes($data["name"]);
         $mapping                   = new PluginDatainjectionMapping();
         $mapping->fields           = $data;
         $this->mappingCollection[] = $mapping;
      }
   }


    /**
    * Return all the mappings for this model
    *
    * @return the list of all the mappings for this model
   **/
   function getAllMappings() {

      return $this->mappingCollection;
   }


    /**
    * Get a PluginDatainjectionMapping by giving the mapping name
    *
    * @param name
    *
    * @return the PluginDatainjectionMapping object associated or null
   **/
   function getMappingByName($name) {

      return $this->getMappingsByField("name", $name);
   }


    /**
    * Get a PluginDatainjectionMapping by giving the mapping rank
    *
    * @param rank
    *
    * @return the PluginDatainjectionMapping object associated or null
   **/
   function getMappingByRank($rank) {

      return $this->getMappingsByField("rank", $rank);
   }


    /**
    * Find a mapping by looking for a specific field
    *
    * @param field the field to look for
    * @param the value of the field
    *
    * @return the PluginDatainjectionMapping object associated or null
   **/
   function getMappingsByField($field, $value) {

      foreach ($this->mappingCollection as $mapping) {
         if ($mapping->equal($field, $value)) {
            return $mapping;
         }
      }
      return null;
   }


    //---- Save ----//

    /**
    * Save in database the model and all his associated mappings
   **/
   function saveAllMappings() {

      foreach ($this->mappingCollection as $mapping) {
         if (isset($mapping->fields["id"])) {
            $mapping->update($mapping->fields);
         } else {
            $mapping->fields["id"] = $mapping->add($mapping->fields);
         }
      }
   }


    //---- Delete ----//

   function deleteMappingsFromDB($model_id) {

      $mapping = new PluginDatainjectionMapping();
      $mapping->deleteByCriteria(['models_id' => $model_id]);
   }


    //---- Add ----//

    /**
    * Add a new mapping to this model (don't write in to DB)
    *
    * @param mapping the new PluginDatainjectionMapping to add
   **/
   function addNewMapping($mapping) {

      $this->mappingCollection[] = $mapping;
   }


    /**
    * Replace all the mappings for a model
    *
    * @param mappins the array of PluginDatainjectionMapping objects
   **/
   function replaceMappings($mappings) {

      $this->mappingCollection = $mappings;
   }


    /**
    * Check if at least one mapping is defined, and if one mandatory field
   */
   static function checkMappings($models_id) {

   }


   function getMandatoryMappings() {

      $mandatories = [];
      foreach ($this->mappingCollection as $mapping) {
         if ($mapping->isMandatory()) {
            $mandatories[] = $mapping;
         }
      }
      return $mandatories;
   }


   function getNumberOfMappings() {

      return count($this->mappingCollection);
   }

}
