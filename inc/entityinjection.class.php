<?php
/*
 * @version $Id$
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
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionEntityInjection extends Entity
                                         implements PluginDatainjectionInjectionInterface{


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array('Document');
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab           = Search::getOptions(get_parent_class($this));

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(14, 26, 27, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41,
                             42, 43, 44, 45, 47, 48, 49,50, 51, 52, 53, 54, 55, 91, 92, 93);

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = array("multiline_text" => array(3, 16, 17, 24),
                                        "dropdown"       => array(9));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   /**
    * @param $input     array
    * @param $add                (true by default)
    * @param $rights    array
   **/
   function customimport($input=array(), $add=true, $rights=array()) {

      if (!isset($input['completename']) || empty($input['completename'])) {
         return -1;
      }

      // Import a full tree from completename
      $names  = explode('>',$input['completename']);
      $fk     = $this->getForeignKeyField();
      $i      = count($names);
      $parent = 0;
      $entity = new Entity();
      $level  = 0;

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

         //Does the entity alread exists ?
         $results = getAllDatasFromTable('glpi_entities',
                                         "`name`='$name' AND `entities_id`='$parent'");
         //Entity doesn't exists => create it
         if (empty($results)) {
            $parent = CommonDropdown::import($tmp);
         } else {
            //Entity already exists, use the ID as parent
            $ent    = array_pop($results);
            $parent = $ent['id'];
         }
      }
      return $parent;
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
      $results = getAllDatasFromTable('glpi_entities',
                                      "`completename`='".$values['completename']."'");

      if (empty($results)) {
         return false;
      }

      $ent    = array_pop($results);
      return $ent['id'];
   }

}
?>