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
 * @author    Wuerth Phoenix
 * @copyright Copyright (C) 2017-2023 Wuerth Phoenix, http://www.wuerth-phoenix.com
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionItem_DiskInjection extends Item_Disk 
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

      $tab[101]['table']         = $this->getTable();
      $tab[101]['field']         = 'totalsize';
      $tab[101]['linkfield']     = 'totalsize';
      $tab[101]['name']          = __('Global size');
      $tab[101]['datatype']      = 'string';
      $tab[101]['injectable']    = true;

      $tab[102]['table']         = $this->getTable();
      $tab[102]['field']         = 'freesize';
      $tab[102]['linkfield']     = 'freesize';
      $tab[102]['name']          = __('Free size');
      $tab[102]['datatype']      = 'string';
      $tab[102]['injectable']    = true;

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array();

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = array( "text"       => array(101),
                                         "text"       => array(102));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
   **/
   
   function processAfterInsertOrUpdate($values, $add=true, $rights=array()) {

      if (isset($values['Computer']['id'])) {
         $class   = get_parent_class($this);
         $item    = new $class();

		 $where   = [
    	    'itemtype' => 'Computer',
            'name'     => $values[$class]['name'],
            'items_id' => $values['Computer']['id'],
         ];
		 
         $tmp['items_id'] = $values['Computer']['id'];
         $tmp['itemtype'] = 'Computer';

   	   $tmp = $values[$class];
		   unset($tmp['id']);	

         if (!countElementsInTable($item->getTable(), $where)) {
            $item->add($tmp);
         } else {
			$datas = getAllDataFromTable($item->getTable(), $where);
            foreach ($datas as $data) {
               $tmp['id'] = $data['id'];
               $item->update($tmp);
            }
         }
      }
   }

   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {
      //error_log("THIS IS NEVER EXECUTED BECAUSE A COMPUTER IS ADDED, NOT A DISK!!"  );
   }

}
?>
