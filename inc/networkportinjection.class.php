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


   function __construct() {
      $this->table = getTableForItemType(get_parent_class($this));
   }


   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      global $CFG_GLPI;
      return $CFG_GLPI["networkport_types"];
   }


   function getOptions($primary_type = '') {

      $tab = Search::getOptions(get_parent_class($this));
      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));

      //To manage vlans : relies on a CommonDBRelation object !
      $tab[100]['name']          = __('VLAN');
      $tab[100]['field']         = 'name';
      $tab[100]['table']         = getTableForItemType('Vlan');
      $tab[100]['linkfield']     = getForeignKeyFieldForTable($tab[100]['table']);
      $tab[100]['displaytype']   = 'relation';
      $tab[100]['relationclass'] = 'NetworkPort_Vlan';
      $tab[100]['storevaluein']  = $tab[100]['linkfield'];

      $tab[4]['checktype'] = 'mac';
      $tab[5]['checktype'] = 'ip';
      $tab[6]['checktype'] = 'ip';
      $tab[7]['checktype'] = 'ip';
      $tab[8]['checktype'] = 'ip';

      //Remove some options because some fields cannot be imported
      $notimportable = array(20, 21);
      $ignore_fields = array_merge($blacklist, $notimportable);

      //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
      //imported using the commonlib
      $add_linkfield = array('comment' => 'comment',
                             'notepad' => 'notepad');

      //Add default displaytype (text)
      foreach ($tab as $id => $tmp) {
         if (!is_array($tmp) || in_array($id,$ignore_fields)) {
            unset($tab[$id]);

         } else {
            if (in_array($tmp['field'],$add_linkfield)) {
               $tab[$id]['linkfield'] = $add_linkfield[$tmp['field']];
            }

            if (!in_array($id,$ignore_fields)) {
               if (!isset($tmp['linkfield'])) {
                  $tab[$id]['injectable'] = PluginDatainjectionCommonInjectionLib::FIELD_VIRTUAL;
               } else {
                  $tab[$id]['injectable'] = PluginDatainjectionCommonInjectionLib::FIELD_INJECTABLE;
               }

               if (isset($tmp['linkfield']) && !isset($tmp['displaytype'])) {
                  $tab[$id]['displaytype'] = 'text';
               }

               if (isset($tmp['linkfield']) && !isset($tmp['checktype'])) {
                  $tab[$id]['checktype'] = 'text';
               }
            }
         }
      }
      
      //To manage vlans : relies on a CommonDBRelation object !
      $tab[51]['name']          = __('Connected to : device name', 'datainjection');
      $tab[51]['field']         = 'netname';
      $tab[51]['table']         = getTableForItemType('NetworkPort');
      $tab[51]['linkfield']     = "netname";
      $tab[51]['injectable']    = true;
      $tab[51]['displaytype']   = 'text';
      $tab[51]['checktype']     = 'text';

      $tab[52]['name']          = __('Connected to : port number', 'datainjection');
      $tab[52]['field']         = 'netport';
      $tab[52]['table']         = getTableForItemType('NetworkPort');
      $tab[52]['linkfield']     = "netport";
      $tab[52]['injectable']    = true;
      $tab[52]['displaytype']   = 'text';
      $tab[52]['checktype']     = 'text';

      $tab[53]['name']          = __('Connected to : port MAC address', 'datainjection');
      $tab[53]['field']         = 'netmac';
      $tab[53]['table']         = getTableForItemType('NetworkPort');
      $tab[53]['linkfield']     = "netmac";
      $tab[53]['injectable']    = true;
      $tab[53]['displaytype']   = 'text';
      $tab[53]['checktype']     = 'text';

      return $tab;
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


   function checkPresent($fields_toinject=array(), $options=array()) {
      return $this->getUnicityRequest($fields_toinject['NetworkPort'], $options['checks']);
   }


   function checkParameters ($fields_toinject, $options) {

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
             || $fields_toinject[$field] == PluginDatainjectionCommonInjectionLib::EMPTY_VALUE) {
            $check_status = false;
         }
      }

      return $check_status;
   }


      /**
    * Build where sql request to look for a network port
    *
    * @param model the model
    * @param fields the fields to insert into DB
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
                        AND `name` = '".(isset ($fields_toinject["name"])
                                         ? $fields_toinject["name"] : '')."'
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
    * @param values the values to inject
    * @return true if check ok, false if not ok
    */
   function lastCheck($values = array()) {

      if ((!isset($values['NetworkPort']['name']) || empty($values['NetworkPort']['name']))
          && (!isset($values['NetworkPort']['mac']) || empty($values['NetworkPort']['mac']))
          && (!isset($values['NetworkPort']['ip']) || empty($values['NetworkPort']['ip']))) {
         return false;
      }
      return true;
   }
   
   function processAfterInsertOrUpdate($values, $add = true, $rights = array()) {
      global $DB;
      
      //Should the port be connected to another one ?
      $params = array();
      
      $use_name = (isset ($values['NetworkPort']["netname"]) 
                     || !empty ($values['NetworkPort']["netname"]));
      $use_logical_number = (isset ($values['NetworkPort']["netport"]) 
                     || !empty ($values['NetworkPort']["netport"]));
      $use_mac = (isset ($values['NetworkPort']["netmac"]) 
                     || !empty ($values['NetworkPort']["netmac"]));
   
      if (!$use_name && !$use_logical_number && !$use_mac) {
         return false;
      }
   
      // Find port in database
      $sql = "SELECT `glpi_networkports`.`id` 
              FROM `glpi_networkports`, `glpi_networkequipments` 
              WHERE `glpi_networkports`.`itemtype`='NetworkEquipment' 
                 AND `glpi_networkports`.`items_id` = `glpi_networkequipments`.`id`
                    AND `glpi_networkequipments`.`is_template`='0' 
                       AND `glpi_networkequipments`.`entities_id`='" . 
                         $values['NetworkPort']["entities_id"]."'";
      if ($use_name) {
         $sql .= " AND `glpi_networkequipments`.`name`='" . $values['NetworkPort']["netname"] . "'";
      }
      if ($use_logical_number) {
         $sql .= " AND `glpi_networkports`.`logical_number`='" . $values['NetworkPort']["netport"] . "'";
      }
      if ($use_mac){
         $sql .= " AND `glpi_networkports`.`mac`='" . $values['NetworkPort']["netmac"] . "'";
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