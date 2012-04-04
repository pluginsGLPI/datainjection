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
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionDeviceMotherboardInjection extends DeviceMotherboard
                                               implements PluginDatainjectionInjectionInterface {

   function __construct() {
      //Needed for getSearchOptions !
      $this->table = getTableForItemType(get_parent_class($this));
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array("Computer");
   }


   function getOptions($primary_type = '') {

      $tab                      = Search::getOptions(get_parent_class($this));
      $options['ignore_fields'] = array();
      $options['displaytype']   = array("multiline_text" => array(16),
                                        "dropdown"       => array(23));

      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

      return $tab;
   }


   function processAfterInsertOrUpdate($values, $add = true, $rights = array()) {
      if (isset($values['Computer']['id'])) {
         $computer_device   = new Computer_Device(get_parent_class($this));

         if (!countElementsInTable($computer_device->getTable(), 
                                   "`devicemotherboards_id`='".$values[get_parent_class($this)]['id']."' 
                                       AND `computers_id`='".$values['Computer']['id']."'")) {
            $tmp['devicemotherboards_id'] = $values[get_parent_class($this)]['id'];
            $tmp['computers_id']          = $values['Computer']['id'];
            $tmp['itemtype']              = get_parent_class($this);
            $computer_device->add($tmp);
         }
      }
   }
   
   /**
    * Standard method to add an object into glpi
 
    *
    * @param values fields to add into glpi
    * @param options options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

}

?>