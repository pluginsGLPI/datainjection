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
 @author    Wuerth Phoenix
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @copyright Copyright (C) 2017-2022 Wuerth Phoenix, http://www.wuerth-phoenix.com
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionComputerVirtualMachineInjection extends ComputerVirtualMachine //MOMI from Item_Diskinjection !!
                                                implements PluginDatainjectionInjectionInterface {


   static function getTable($classname = null) { 

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array('Computer');
   }

   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab           = Search::getOptions(get_parent_class($this));
	  
	  #these have to be added here because they are defined only in rawSearchOptionsToAdd!
	  #  -- put id as tab[id] and add linkfield.
	  $tab[161] = [
         'table'              => 'glpi_virtualmachinestates',
         'field'              => 'name',
         'name'               => __('State'),
         'forcegroupby'       => true,
         'massiveaction'      => false,
         'datatype'           => 'dropdown',
         'joinparams'         => [
            'beforejoin'         => [
               'table'              => self::getTable(),
               'joinparams'         => [
                  'jointype'           => 'child'
               ]
            ]
         ],
         'linkfield'		  => 'virtualmachinestates_id'
      ];
	  
	  $tab[162] = [
         'table'              => 'glpi_virtualmachinesystems',
         'field'              => 'name',
         'name'               => VirtualMachineSystem::getTypeName(1),
         'forcegroupby'       => true,
         'massiveaction'      => false,
         'datatype'           => 'dropdown',
         'joinparams'         => [
            'beforejoin'         => [
               'table'              => self::getTable(),
               'joinparams'         => [
                  'jointype'           => 'child'
               ]
            ]
         ],
         'linkfield'		  => 'virtualmachinesystems_id'
      ];

      $tab[163] = [
         'table'              => 'glpi_virtualmachinetypes',
         'field'              => 'name',
         'name'               => VirtualMachineType::getTypeName(1),
         'datatype'           => 'dropdown',
         'forcegroupby'       => true,
         'massiveaction'      => false,
         'joinparams'         => [
            'beforejoin'         => [
               'table'              => self::getTable(),
               'joinparams'         => [
                  'jointype'           => 'child'
               ]
            ]
         ],
         'linkfield'		  => 'virtualmachinetypes_id'
      ];

      //Remove some options because some fields cannot be imported
      $blacklist =  array();#  = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array();

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
	  $options['displaytype']   = ["dropdown"       => [161, 162, 163]];

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }

   /**
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      $fields = [];
      if ($primary_type == 'Computer') {
         $fields['computers_id'] = $values[$primary_type]['id'];
      }
      return $fields;
   }

   /**
    * @param $fields_toinject    array
    * @param $options            array
    **/
   function checkPresent($fields_toinject = [], $options = []) {
      if ($options['itemtype'] != 'ComputerVirtualMachine') {
         return (" AND `computers_id` = '" . $fields_toinject['Computer']['id'] . "'
                AND `name` = '" . $fields_toinject['ComputerVirtualMachine']['name'] . "'");
      }
      return "";
   }

   /**
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
   **/
   
   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {
	   //error_log("THIS IS NEVER EXECUTED BECAUSE A COMPUTER IS ADDED, NOT A VM!!"  );
   }

}
