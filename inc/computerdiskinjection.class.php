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
 @author    Wuerth Phoenix
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @copyright Copyright (C) 2017 Wuerth Phoenix, http://www.wuerth-phoenix.com
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionComputerDiskInjection extends ComputerDisk
                                                implements PluginDatainjectionInjectionInterface {


   static function getTable() {

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

         $where   = "`name`='".$values[get_parent_class($this)]['name']."'
                      AND `computers_id`='".$values['Computer']['id']."'";

         $tmp['computers_id'] = $values['Computer']['id'];
         $tmp['name'] =  $values[get_parent_class($this)]['name'];
         if (isset($values[get_parent_class($this)]['totalsize'])
             && ($values[get_parent_class($this)]['totalsize'] > 0)) {
            $tmp['totalsize'] = $values[get_parent_class($this)]['totalsize'];
         } else {
            $tmp['totalsize'] = $values[get_parent_class($this)]['totalsize'];
         }
         if (isset($values[get_parent_class($this)]['freesize'])
             && ($values[get_parent_class($this)]['freesize'] > 0)) {
            $tmp['freesize'] = $values[get_parent_class($this)]['freesize'];
         } else {
            $tmp['freesize'] = $values[get_parent_class($this)]['freesize'];
         }

         if (!countElementsInTable($item->getTable(), $where)) {
            $item->add($tmp);
         } else {
            $datas = getAllDatasFromTable($item->getTable(), $where);
            foreach ($datas as $data) {
               //update only first item
               if (isset($tmp['id'])) {
                  continue;
               }
               $tmp['id'] = $data['id'];
               $item->update($tmp);
            }
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

}
?>
