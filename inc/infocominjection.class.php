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
class PluginDatainjectionInfocomInjection extends Infocom
   implements PluginDatainjectionInjectionInterface {

   function __construct() {
      //Needed for getSearchOptions !
      $this->table = getTableForItemType('Infocom');
   }

   function isPrimaryType() {
      return false;
   }

   function connectedTo() {
      global $CFG_GLPI;
      return $CFG_GLPI["infocom_types"];
   }

   function getOptions() {
      global $LANG;
      $tab = parent::getSearchOptions();

      //Remove some options because some fields cannot be imported
      $remove = array(2, 3, 20, 21, 80, 86);
      foreach ($remove as $tmp) {
         unset($tab[$tmp]);
      }

      //Add displaytype value
      $fields_definition = array("date"               => array(4, 5),
                                 "dropdown"           => array(6, 9, 19),
                                 "dropdown_integer"   => array(6),
                                 "decimal"            => array(8,13,17),
                                 "sink_type"          => array(15),
                                 "alert"              => array(22),
                                 "multiline_text"     => array(16));
      foreach ($fields_definition as $type => $tabsID) {
         foreach ($tabsID as $tabID) {
            $tab[$tabID]['displaytype'] = $type;
         }
      }

      //Warranty_duration
      $tab[6]['minvalue'] = 0;
      $tab[6]['maxvalue'] = 120;
      $tab[6]['step']     = 1;
      $tab[6]['-1']       = $LANG['financial'][2];

      $tab[8]['minvalue'] = 0;
      $tab[8]['maxvalue'] = 120;
      $tab[8]['step']     = 1;

      $tab[14]['minvalue'] = 0;
      $tab[14]['maxvalue'] = 15;
      $tab[14]['step']     = 1;

      $tab[17]['size']    = 14;
      $tab[17]['default'] = 0;

      //Add default displaytype (text)
      foreach ($tab as $id => $tmp) {
         if (isset($tmp['linkfield']) && !isset($tmp['displaytype'])) {
            $tab[$id]['displaytype'] = 'text';
         }
         if (isset($tmp['linkfield']) && !isset($tmp['checktype'])) {
            $tab[$id]['checktype'] = 'text';
         }
      }

      return $tab;
   }

   function showAdditionalInformation($info = array(),$option = array()) {
      $name = "info[".$option['linkfield']."]";
      switch ($option['displaytype']) {
         case 'sink_type' :
            Infocom::dropdownAmortType($name);
         break;
         case 'alert' :
            Infocom::dropdownAlert($name);
         break;
         default:
            break;
      }
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

   function checkPresent($options = array()) {
      return " AND itemtype=" . $options['itemtype'] . " AND items_id=" . $options["id"];
   }
}

?>