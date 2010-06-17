<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

/// Location class
class PluginDatainjectionNetworkportInjection extends NetworkPort
   implements PluginDatainjectionInjectionInterface {

   function __construct() {
      $this->table = getTableForItemType('NetworkPort');
   }

   function isPrimaryType() {
      return false;
   }

   function connectedTo() {
      return array('NetworkEquipment','Computer','Peripheral','Phone');
   }

   function getOptions() {
      global $LANG;
      $tab = parent::getSearchOptions();
      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();

      //To manage vlans : relies on a CommonDBRelation object !
      $tab[100]['name']          = $LANG['setup'][90];
      $tab[100]['field']         = 'name';
      $tab[100]['table']         = getTableForItemType('Vlan');
      $tab[100]['linkfield']     = getForeignKeyFieldForTable($tab[100]['table']);
      $tab[100]['displaytype']   = 'relation';
      $tab[100]['relationclass'] = 'NetworkPort_Vlan';

      //Remove some options because some fields cannot be imported
      $notimportable = array(20, 21);
      $ignore_fields = array_merge($blacklist,$notimportable);

      //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
      //imported using the commonlib
      $add_linkfield = array('comment' => 'comment', 'notepad' => 'notepad');

      //Add default displaytype (text)
      foreach ($tab as $id => $tmp) {
         if (!is_array($tmp) || in_array($id,$ignore_fields)) {
            unset($tab[$id]);
         }
         else {
            if (in_array($tmp['field'],$add_linkfield)) {
               $tab[$id]['linkfield'] = $add_linkfield[$tmp['field']];
            }
            if (!in_array($id,$ignore_fields)) {
               if (!isset($tmp['linkfield'])) {
                  $tab[$id]['injectable'] = PluginDatainjectionCommonInjectionLib::FIELD_VIRTUAL;
               }
               else {
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

      return $tab;
   }

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param values fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    */
   function addOrUpdateObject($values=array(), $options=array()) {
      global $LANG;
      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

   function checkPresent($fields_toinject = array(), $options = array()) {
      return $this->getUnicityRequest($fields_toinject, $options['checks']);
   }

   function checkParameters ($fields_toinject, $options) {
      $fields_tocheck = array();
      switch ($options['checks']['port_unicity']) {
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER :
            $fields_tocheck = array('logical_number');
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_MAC :
            $fields_tocheck = array('logical_number','mac');
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME :
            $fields_tocheck = array('logical_number','name');
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC :
            $fields_tocheck = array('logical_number','mac','name');
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_MACADDRESS :
            $fields_tocheck = array('mac');
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_NAME :
            $fields_tocheck = array('name');
            break;
      }
      $check_status = true;
      foreach($fields_tocheck as $field) {
         if (!isset($fields_toinject[$field])
               || $fields_toinject[$field] == PluginDatainjectionCommonInjectionLib::EMPTY_VALUE) {
            $check_status = false;
         }
      }
      return $check_status;
   }

      /**
    * Build where sql request to look for a network port
    * @param model the model
    * @param fields the fields to insert into DB
    *
    * @return the sql where clause
    */
   function getUnicityRequest($fields_toinject = array(), $options = array()) {
      $where = "";
      logDebug($options);
      switch ($options['port_unicity']) {
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER :
            $where .= " AND `logical_number`='" . (isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '') . "'";
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_MAC :
            $where .= " AND `logical_number`='" . (isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '') . "'";
            $where .= " AND `name`='" . (isset ($fields_toinject["name"])
                                                            ? $fields_toinject["name"] : '') . "'";
            $where .= " AND `mac`='" . (isset ($fields_toinject["mac"])
                                                            ? $fields_toinject["mac"] : '') . "'";
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME :
            $where .= " AND `logical_number`='" . (isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '') . "'";
            $where .= " AND `name`='" . (isset ($fields_toinject["name"])
                                                   ? $fields_toinject["name"] : '') . "'";
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC :
            $where .= " AND `logical_number`='" . (isset ($fields_toinject["logical_number"])
                                                   ? $fields_toinject["logical_number"] : '') . "'";
            $where .= " AND `name`='" . (isset ($fields_toinject["name"])
                                                   ? $fields_toinject["name"] : '') . "'";
            $where .= " AND `mac`='" . (isset ($fields_toinject["mac"])
                                                   ? $fields_toinject["mac"] : '') . "'";
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_MACADDRESS :
            $where .= " AND `mac`='" . (isset ($fields_toinject["mac"])
                                                   ? $fields_toinject["mac"] : '') . "'";
            break;
         case PluginDatainjectionModel::UNICITY_NETPORT_NAME :
            $where .= " AND `name`='" . (isset ($fields_toinject["name"])
                                                   ? $fields_toinject["name"] : '') . "'";
            break;
      }
      return $where;
   }

}

?>