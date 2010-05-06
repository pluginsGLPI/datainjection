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

/// Computer class
class PluginDatainjectionComputerInjection extends Computer
   implements PluginDatainjectionInjectionInterface {

   function isPrimaryType() {
      return true;
   }

   function connectedTo() {
      return array();
   }

   function getOptions() {
      global $LANG;
      $tab = parent::getSearchOptions();

      //Remove some options because some fields cannot be imported
      $remove = array(2, 80, 100, 7, 10, 11, 12, 13, 15, 34, 35, 36, 39, 91, 92,93);
      foreach ($remove as $tmp) {
         unset($tab[$tmp]);
      }

      //Add displaytype value
      $dropdown = array("dropdown"       => array(3, 4, 40, 31, 45, 46, 41, 71, 32, 33, 23),
                        "yesno"          => array(42),
                        "dropdown_users" => array(70, 24),
                        "multiline_text" => array(16, 90));
      foreach ($dropdown as $type => $tabsID) {
         foreach ($tabsID as $tabID) {
            $tab[$tabID]['displaytype'] = $type;
         }
      }

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

   /**
    * Manage display of additional informations
    * @param info an array which contains the additional information values
    */
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

}

?>