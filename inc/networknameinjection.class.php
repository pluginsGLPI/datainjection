<?php
/*
 * @version $Id: networkport_vlaninjection.class.php 749 2013-07-04 15:39:16Z tsmr $
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

class PluginDatainjectionNetworkNameInjection extends NetworkName
                                                   implements PluginDatainjectionInjectionInterface {


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      global $CFG_GLPI;
      return $CFG_GLPI["networkport_types"];
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab           = Search::getOptions(get_parent_class($this));

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(20, 21);

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = array("dropdown" => array(12));

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
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type,$values) {

      $fields['items_id']  = $values['NetworkPort']['id'];
      $fields['itemtype']      = "NetworkPort";

      return $fields;
   }


   /**
    * @param $values
    * @param $add                   (true by default)
    * @param $rights    array
    */
   function processAfterInsertOrUpdate($values, $add=true, $rights=array()) {
      global $DB;

      //Manage ip adresses
      if (isset($values['NetworkName']['ipaddresses_id'])) {
          if (!countElementsInTable("glpi_ipaddresses",
                                    "`items_id`='".$values['NetworkName']['id']."'
                                       AND `itemtype`='NetworkName'
                                       AND `name`='".$values['NetworkName']['ipaddresses_id']."'")) {

            $ip                  = new IPAddress();
            $tmp['items_id']     = $values['NetworkName']['id'];
            $tmp['itemtype']     = "NetworkName";
            $tmp['name']         = $values['NetworkName']['ipaddresses_id'];
            $tmp['is_dynamic']   = 0;
            $ip->add($tmp);
         }
      }
   }

}
?>