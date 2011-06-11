<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Remi Collet
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionSoftwareInjection extends Software
                                           implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType('Software');
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array();
   }


   function getOptions($primary_type = '') {

      $tab = parent::getSearchOptions();

      //Specific to location
      $tab[3]['linkfield']  = 'locations_id';
      $tab[86]['linkfield'] = 'is_recursive';

      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      //Remove some options because some fields cannot be imported
      $notimportable = array(7, 72, 5, 31, 170, 160, 161, 162, 163, 164, 165, 166);
      $options['ignore_fields'] = array_merge($blacklist,$notimportable);
      $options['displaytype']   = array("dropdown"       => array(3, 4, 62, 23, 71),
                                        "bool"           => array(61,86),
                                        "user"           => array(70, 24),
                                        "multiline_text" => array(16, 90));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * Play software's dictionnary
   **/
   function reformat(&$values = array()) {

      foreach ($values as $field => $value) {
         $supplier       = (isset($values['supplier'])?$values['supplier']:'');
         $rulecollection = new  RuleDictionnarySoftwareCollection;
         $res_rule       = $rulecollection->processAllRules(array("name"         => $value,
                                                                  "manufacturer" => $supplier),
                                                            array(), array());

         if (isset($res_rule['name'])) {
            $values['name'] = $res_rule['name'];
         }

         if (isset($res_rule['supplier'])) {
            if (isset($values['supplier'])) {
               $values['supplier'] = Dropdown::getDropdownName('glpi_suppliers',
                                                               $res_rule['supplier']);
            }
         }
      }
   }


   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
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