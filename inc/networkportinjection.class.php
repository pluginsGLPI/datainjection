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

class PluginDatainjectionNetworkportInjection extends NetworkPort
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

      $tab                      = Search::getOptions(get_parent_class($this));

      $tab[4]['checktype']      = 'mac';

      //To manage vlans : relies on a CommonDBRelation object !
      $tab[51]['name']          = sprintf(__('%1$s: %2$s'), __('Connected to'), __('Device name'));
      $tab[51]['field']         = 'netname';
      $tab[51]['table']         = getTableForItemType('NetworkPort');
      $tab[51]['linkfield']     = "netname";
      $tab[51]['injectable']    = true;
      $tab[51]['displaytype']   = 'text';
      $tab[51]['checktype']     = 'text';

      $tab[52]['name']          = sprintf(__('%1$s: %2$s'), __('Connected to'), __('Port number'));
      $tab[52]['field']         = 'netport';
      $tab[52]['table']         = getTableForItemType('NetworkPort');
      $tab[52]['linkfield']     = "netport";
      $tab[52]['injectable']    = true;
      $tab[52]['displaytype']   = 'text';
      $tab[52]['checktype']     = 'text';

      $tab[53]['name']          = sprintf(__('%1$s: %2$s'), __('Connected to'),
                                          __('Port MAC address', 'datainjection'));
      $tab[53]['field']         = 'netmac';
      $tab[53]['table']         = getTableForItemType('NetworkPort');
      $tab[53]['linkfield']     = "netmac";
      $tab[53]['injectable']    = true;
      $tab[53]['displaytype']   = 'text';
      $tab[53]['checktype']     = 'text';

      //To manage vlans : relies on a CommonDBRelation object !
      $tab[100]['name']          = __('VLAN');
      $tab[100]['field']         = 'name';
      $tab[100]['table']         = getTableForItemType('Vlan');
      $tab[100]['linkfield']     = getForeignKeyFieldForTable($tab[100]['table']);
      $tab[100]['displaytype']   = 'relation';
      $tab[100]['relationclass'] = 'NetworkPort_Vlan';
      $tab[100]['storevaluein']  = $tab[100]['linkfield'];

      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(20, 21);

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $options['displaytype']   = array("dropdown"           => array(9),
                                        "multiline_text"     => array(16),
                                        "instantiation_type" => array(87));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type,$values) {

      if (isset($values[$primary_type]['instantiation_type'])) {
         $fields['instantiation_type'] = $values[$primary_type]['instantiation_type'];
      } else {
         $fields['instantiation_type'] = "NetworkPortEthernet";
      }
      return $fields;
   }


   /**
    * @param $info      array
    * @param $option    array
   **/
   function showAdditionalInformation($info=array(), $option=array()) {

      $name = "info[".$option['linkfield']."]";

      switch ($option['displaytype']) {
         case 'instantiation_type' :
            $instantiations = array();
            $class          = get_parent_class($this);
            foreach ($class::getNetworkPortInstantiations() as $inst_type) {
               if (call_user_func(array($inst_type, 'canCreate'))) {
                  $instantiations[$inst_type] = call_user_func(array($inst_type, 'getTypeName'));
               }
            }
            Dropdown::showFromArray('instantiation_type', $instantiations,
                                    array('value' => 'NetworkPortEthernet'));
            break;

         default:
            break;
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
    * @param $fields_toinject    array
    * @param $options            array
   **/
   function checkPresent($fields_toinject=array(), $options=array()) {
      return $this->getUnicityRequest($fields_toinject['NetworkPort'], $options['checks']);
   }


   /**
    * @param $fields_toinject
    * @param $options
   **/
   function checkParameters($fields_toinject, $options) {

      $fields_tocheck = array();
      switch ($options['checks']['port_unicity']) {
         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER :
            $fields_tocheck = array('logical_number');
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_MAC :
            $fields_tocheck = array('logical_number', 'mac');
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME :
            $fields_tocheck = array('logical_number', 'name');
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC :
            $fields_tocheck = array('logical_number', 'mac', 'name');
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS :
            $fields_tocheck = array('mac');
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_NAME :
            $fields_tocheck = array('name');
            break;
      }

      $check_status = true;
      foreach ($fields_tocheck as $field) {
         if (!isset($fields_toinject[$field])
             || ($fields_toinject[$field] == PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {
            $check_status = false;
         }
      }

      return $check_status;
   }


   /**
    * Build where sql request to look for a network port
    *
    * @param $fields_toinject    array    the fields to insert into DB
    * @param $options            array
    *
    * @return the sql where clause
   **/
   function getUnicityRequest($fields_toinject=array(), $options=array()) {

      $where = "";

      switch ($options['port_unicity']) {
         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER :
            $where .= " AND `logical_number` = '".(isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '')."'";
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_MAC :
            $where .= " AND `logical_number` = '".(isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '')."'
                        AND `mac` = '".(isset ($fields_toinject["mac"])
                                        ? $fields_toinject["mac"] : '')."'";
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME :
            $where .= " AND `logical_number` = '".(isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '')."'
                        AND `name` = '".(isset ($fields_toinject["name"])
                                         ? $fields_toinject["name"] : '')."'";
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC :
            $where .= " AND `logical_number` = '".(isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '')."'
                        AND `name` = '".(isset ($fields_toinject["name"])
                                         ? $fields_toinject["name"] : '')."'
                        AND `mac` = '".(isset ($fields_toinject["mac"])
                                        ? $fields_toinject["mac"] : '')."'";
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS :
            $where .= " AND `mac` = '".(isset ($fields_toinject["mac"])
                                        ? $fields_toinject["mac"] : '')."'";
            break;

         case PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_NAME :
            $where .= " AND `name` = '".(isset ($fields_toinject["name"])
                                         ? $fields_toinject["name"] : '')."'";
            break;
      }

      return $where;
   }


   /**
    * Check if at least mac or ip is defined otherwise block import
    *
    * @param $values    array    the values to inject
    *
    * @return true if check ok, false if not ok
   **/
   function lastCheck($values=array()) {

      if ((!isset($values['NetworkPort']['name']) || empty($values['NetworkPort']['name']))
          && (!isset($values['NetworkPort']['mac']) || empty($values['NetworkPort']['mac']))
          && (!isset($values['NetworkPort']['instantiation_type'])
              || empty($values['NetworkPort']['instantiation_type']))) {
         return false;
      }
      return true;
   }


   /**
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
   **/
   function processAfterInsertOrUpdate($values, $add=true, $rights=array()) {
      global $DB;

      //Should the port be connected to another one ?
      $use_name            = (isset ($values['NetworkPort']["netname"])
                              || !empty ($values['NetworkPort']["netname"]));
      $use_logical_number  = (isset ($values['NetworkPort']["netport"])
                              || !empty ($values['NetworkPort']["netport"]));
      $use_mac             = (isset ($values['NetworkPort']["netmac"])
                              || !empty ($values['NetworkPort']["netmac"]));

      if (!$use_name && !$use_logical_number && !$use_mac) {
         return false;
      }

      // Find port in database
      $sql = "SELECT `glpi_networkports`.`id`
              FROM `glpi_networkports`, `glpi_networkequipments`
              WHERE `glpi_networkports`.`itemtype`='NetworkEquipment'
                    AND `glpi_networkports`.`items_id` = `glpi_networkequipments`.`id`
                    AND `glpi_networkequipments`.`is_template` = '0'
                    AND `glpi_networkequipments`.`entities_id`
                           = '".$values['NetworkPort']["entities_id"]."'";
      if ($use_name) {
         $sql .= " AND `glpi_networkequipments`.`name` = '".$values['NetworkPort']["netname"]."'";
      }
      if ($use_logical_number) {
         $sql .= " AND `glpi_networkports`.`logical_number` = '".$values['NetworkPort']["netport"]."'";
      }
      if ($use_mac){
         $sql .= " AND `glpi_networkports`.`mac` = '".$values['NetworkPort']["netmac"]."'";
      }
      $res = $DB->query($sql);

      //if at least one parameter is given
      $nb = $DB->numrows($res);
      if ($nb == 1) {
         //Get data for this port
         $netport         = $DB->fetch_array($res);
         $netport_netport = new NetworkPort_NetworkPort();
         //If this port already connected to another one ?
         if (!$netport_netport->getOppositeContact($netport['id'])) {
            //No, add a new port to port connection
            $tmp['networkports_id_1'] = $values['NetworkPort']['id'];
            $tmp['networkports_id_2'] = $netport['id'];
            $netport_netport->add($tmp);
         }
      } //TODO add injection warning if no port found or more than one
   }

}
?>