<?php
/*
 * @version $Id: computer_iteminjection.class.php 759 2013-07-16 09:54:20Z yllen $
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

class PluginDatainjectionNotepadInjection extends Notepad
                                                implements PluginDatainjectionInjectionInterface {


   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();

   }

   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      return ['Computer', 'NetworkEquipment', 'Printer'];
   }

   function customDataAlreadyInDB($injectionClass, $values, $options) {
      //Do not manage updating notes: only creation
      return false;
   }

   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab = Notepad::rawSearchOptionsToAdd();
      $searchoptions = [];
      foreach ($tab as $option) {
         if (is_numeric($option['id'])) {
            if ($option['table'] != 'glpi_notepads') {
               $option['linkfield'] = getForeignKeyFieldForTable($option['table']);
            } else {
               $option['linkfield'] = $option['field'];
            }
            $searchoptions[$option['id']] = $option;
         }
      }

      $options['ignore_fields'] = [201, 203, 204];
      $options['displaytype'] = [
         "multiline_text" => [200],
         "dropdown"       => [202]

      ];

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($searchoptions, $options, $this);
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   /**
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      $fields['items_id'] = $values[$primary_type]['id'];
      $fields['itemtype'] = $primary_type;
      return $fields;
   }

}
