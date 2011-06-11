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

class PluginDatainjectionNetpointInjection extends Netpoint
                                           implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType('Netpoint');
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array('Computer', 'NetworkEquipment', 'Peripheral', 'Phone');
   }


   function getOptions($primary_type = '') {

      $tab = parent::getSearchOptions();

      //Specific to location
      $tab[3]['linkfield'] = 'locations_id';

      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      //Remove some options because some fields cannot be imported
      $notimportable = array(80, 86, 91, 92, 93);
      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype'] = array("dropdown"       => array(3),
                                      "multiline_text" => array(16));

      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
      return $tab;
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


   function addSpecificNeededFields($primary_type, $fields_toinject) {

      //If netpoint is not the primary type to inject, then get the locations_id from the primary_type
      if ($primary_type != 'Netpoint') {
         if (isset($fields_toinject[$primary_type]['locations_id'])) {
            return array('locations_id', $fields_toinject[$primary_type]['locations_id']);
         }
         return array('locations_id',0);
      }
   }

}

?>