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
      $tab = parent::getSearchOptions();

      //Add displaytype value
      $dropdown = array("dropdown"      => array(4,40,31, 45, 46, 41, 71, 32, 33, 23),
                        "yesno"          => array(42),
                        "dropdown_users" => array(70, 24),
                        "multiline_text" => array(16,90));
      foreach ($dropdown as $type => $tabsID) {
         foreach ($tabsID as $tabID) {
            $tab[$tabID]['displaytype'] = $type;
         }
      }

      foreach ($tab as $id => $tmp) {
         if (isset($tmp['linkfield']) && !isset($tmp['displaytype'])) {
            $tab[$id]['displaytype'] = 'text';
         }
      }
      return $tab;
   }

   function showAdditionalInformation($info = array()) {

   }

}

?>