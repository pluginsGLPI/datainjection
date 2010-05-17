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
// Original Author of file: Remi Collet
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
      return parent::getSearchOptions();
   }

   function showAdditionalInformation($info = array()) {

   }


   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param values fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    */
   function addObject($values=array(), $options=array()) {
      global $LANG;
      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->addObject();
      return $lib->getInjectionResults();
   }


   /**
    * Standard method to update an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param fields fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of updated objects : for example array(Computer=>1, Networkport=>10)
    */
   function updateObject($values=array(), $options=array()) {
      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->updateObject();
      return $lib->getInjectionResults();

   }


   /**
    * Standard method to delete an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param fields fields to add into glpi
    * @param options options used during creation
    */
   function deleteObject($values=array(), $options=array()) {
      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->deleteObject();
      return $lib->getInjectionResults();
   }

   function checkType($field_name, $data, $mandatory) {
      return PluginDatainjectionCommonInjectionLib::SUCCESS;
   }

   function reformat(&$values = array()) {

   }

   function checkPresent($fields_toinject = array(), $options = array()) {
      $where  = " AND `itemtype`='" . $options['itemtype']."'";
      $where .= " AND `items_id`='" . $fields_toinject['items_id']."'";
      $where .= getPortUnicityRequest($fields_toinject, $options);
      return $where;
   }

      /**
    * Build where sql request to look for a network port
    * @param model the model
    * @param fields the fields to insert into DB
    *
    * @return the sql where clause
    */
   function getPortUnicityRequest($fields_toinject = array(), $options = array()) {
      $where = "";

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