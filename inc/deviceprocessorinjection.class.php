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

class PluginDatainjectionDeviceProcessorInjection extends DeviceProcessor
                                               implements PluginDatainjectionInjectionInterface {


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array("Computer");
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab           = Search::getOptions(get_parent_class($this));

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array();

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = array("multiline_text" => array(16),
                                        "dropdown"       => array(23));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
   **/
   function processAfterInsertOrUpdate($values, $add=true, $rights=array()) {

      if (isset($values['Computer']['id'])) {
         $class   = "Item_".get_parent_class($this);
         $item    = new $class();
         $foreign = getForeignKeyFieldForTable(getTableForItemType(get_parent_class($this)));

         $where   = "`$foreign`='".$values[get_parent_class($this)]['id']."'
                      AND `itemtype`='Computer'
                      AND `items_id`='".$values['Computer']['id']."'";

         if (!countElementsInTable($item->getTable(), $where)) {

            if (isset($values[get_parent_class($this)]['frequency'])
                && ($values[get_parent_class($this)]['frequency'] > 0)) {
               $tmp['frequency'] = $values[get_parent_class($this)]['frequency'];
            } else {
               $tmp['frequency'] = 0;
            }

            $tmp[$foreign]   = $values[get_parent_class($this)]['id'];
            $tmp['items_id'] = $values['Computer']['id'];
            $tmp['itemtype'] = 'Computer';
            $item->add($tmp);
         }
      }
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
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      $fields = array();
      if (!isset($values['frequency_default'])) {
         if (isset($values[get_parent_class($this)]['frequency'])) {
            $fields['frequency_default'] = $values[get_parent_class($this)]['frequency'];
         } else {
            $fields['frequency_default'] = 0;
         }
      }
      return $fields;
   }

}
?>