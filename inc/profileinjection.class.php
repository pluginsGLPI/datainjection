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

class PluginDatainjectionProfileInjection extends Profile
                                          implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType(get_parent_class($this));
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array();
   }


   function getOptions($primary_type = '') {
      return Search::getOptions(get_parent_class($this));
   }


   function checkType($field_name, $data, $mandatory) {

      switch($field_name) {
         case 'right_rw' :
            return (in_array($data, array('r', 'w'))
                    ?PluginDatainjectionCommonInjectionLib::SUCCESS
                    :PluginDatainjectionCommonInjectionLib::TYPE_MISMATCH);

         case 'right_r' :
            return (($data=='r')?PluginDatainjectionCommonInjectionLib::SUCCESS
                                :PluginDatainjectionCommonInjectionLib::TYPE_MISMATCH);

         case 'right_w' :
            return (($data=='w')?PluginDatainjectionCommonInjectionLib::SUCCESS
                                :PluginDatainjectionCommonInjectionLib::TYPE_MISMATCH);

         case 'interface':
            return (in_array($data, array('helpdesk', 'central'))
                    ?PluginDatainjectionCommonInjectionLib::SUCCESS
                    :PluginDatainjectionCommonInjectionLib::TYPE_MISMATCH);

          default :
            return PluginDatainjectionCommonInjectionLib::SUCCESS;
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