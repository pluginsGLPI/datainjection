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

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionEntityInjection extends Entity
                                         implements PluginDatainjectionInjectionInterface
{


   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {

      return true;
   }


   function connectedTo() {

      return ['Document'];
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab           = Search::getOptions(get_parent_class($this));

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = [14, 26, 27, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41,
                           42, 43, 44, 45, 47, 48, 49,50, 51, 52, 53, 54, 55, 91, 92, 93];

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = ["multiline_text" => [3, 16, 17, 24],
                                      "dropdown"       => [9]];

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   public static function getRootEntityName() {
      $root = new Entity();
      $root->getFromDb(0);

      return $root->fields['name'];
   }


    /**
    * @param $input     array
    * @param $add                (true by default)
    * @param $rights    array
   **/
   function customimport($input = [], $add = true, $rights = []) {
      if (!isset($input['completename']) || empty($input['completename'])) {
         return -1;
      }

      $em = new Entity();

      // Search for exisiting entity
      $search = $input['completename'];

      // Check if search start by root entity
      $root = self::getRootEntityName();
      if (strpos($search, $root) !== 0) {
         $search = "$root > $search";
      }

      $results = $em->find(['completename' => $search]);

      if (count($results)) {
         $ent = array_pop($results);
         return $this->updateExistingEntity($ent['id'], $input);
      } else {
         return $this->importEntity($input);
      }
   }

   public function importEntity($input) {
      $em = new Entity();

      // Import a full tree from completename
      $names  = explode('>', $input['completename']);
      $i      = count($names);
      $parent = 0;
      $level  = 0;

      // Remove root entity if specified
      if (strcmp(trim($names[0]), trim(self::getRootEntityName())) === 0) {
         unset($names[0]);
      }

      foreach ($names as $name) {
         $name = trim($name);

         $i--;
         $level++;
         if (empty($name)) {
            // Skip empty name (completename starting/endind with >, double >, ...)
            continue;
         }
         $tmp['name'] = $name;

         if (!$i) {
            // Other fields (comment, ...) only for last node of the tree
            foreach ($input as $key => $val) {
               if ($key != 'completename') {
                  $tmp[$key] = $val;
               }
            }
         }
         $tmp['level']       = $level;
         $tmp['entities_id'] = $parent;

         // Does the entity alread exists ?
         $results = getAllDataFromTable(
            'glpi_entities',
            ['name' => $name, 'entities_id' => $parent]
         );

         // Entity doesn't exists => create it
         if (empty($results)) {
            $parent = $em->import($tmp);
         } else {
            // Entity already exists, use the ID as parent
            $ent    = array_pop($results);
            $parent = $ent['id'];
         }
      }

      return $parent;
   }

   public function updateExistingEntity($id, $input) {
      $em = new Entity();

      // Update entity
      $input['id'] = $id;
      unset($input['completename']);
      unset($input['entities_id']);
      $em->update($input);

      return $id;
   }


    /**
    * @param $injectionClass
    * @param $values
    * @param $options
   **/
   function customDataAlreadyInDB($injectionClass, $values, $options) {

      if (!isset($values['completename'])) {
         return false;
      }
      $results = getAllDataFromTable(
         'glpi_entities',
         ['completename' => $values['completename']]
      );

      if (empty($results)) {
         return false;
      }

      $ent = array_pop($results);
      return $ent['id'];
   }

}
