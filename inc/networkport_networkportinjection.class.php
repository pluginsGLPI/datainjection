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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionNetworkport_NetworkPortInjection extends NetworkPort_NetworkPort
                                              implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType(get_parent_class($this));
   }

   static function getTypeName() {
      global $LANG;

      return $LANG['datainjection']['port'][1];
   }


   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      global $CFG_GLPI;
      return $CFG_GLPI["networkport_types"];
   }


   function getOptions($primary_type = '') {
      global $LANG;

      //To manage vlans : relies on a CommonDBRelation object !
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['field']         = 'name';
      $tab[1]['table']         = getTableForItemType('NetworkPort');
      $tab[1]['linkfield']     = "name";
      $tab[1]['injectable']    = true;

      $tab[2]['name']          = $LANG['networking'][14];
      $tab[2]['field']         = 'ip';
      $tab[2]['table']         = getTableForItemType('NetworkPort');
      $tab[2]['linkfield']     = "ip";
      $tab[2]['checktype']     = "ip";
      $tab[2]['injectable']    = true;

      $tab[3]['name']          = $LANG['networking'][15];
      $tab[3]['field']         = 'mac';
      $tab[3]['table']         = getTableForItemType('NetworkPort');
      $tab[3]['linkfield']     = "mac";
      $tab[3]['injectable']    = true;

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

}

?>