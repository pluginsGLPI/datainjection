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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionNetworkEquipmentInjection extends NetworkEquipment
                                                   implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType('NetworkEquipment');
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array();
   }


   function getOptions($primary_type = '') {
      global $LANG;

      $tab = Search::getOptions('NetworkEquipment');

      //Specific to location
      $tab[3]['linkfield']  = 'locations_id';
      $tab[12]['checktype'] = 'ip';
      $tab[13]['checktype'] = 'mac';

      //Virtual type : need to be processed at the end !
      $tab[200]['table']       = 'glpi_networking';
      $tab[200]['field']       = 'nb_ports';
      $tab[200]['name']        = $LANG['datainjection']['mappings'][1];
      $tab[200]['checktype']   = 'integer';
      $tab[200]['displaytype'] = 'virtual';
      $tab[200]['linkfield']   = 'nb_ports';
      $tab[200]['injectable']  = PluginDatainjectionCommonInjectionLib::FIELD_VIRTUAL;

      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      //Remove some options because some fields cannot be imported
      $notimportable = array(2, 91, 92, 93, 80, 100);
      $ignore_fields = array_merge($blacklist, $notimportable);

      //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
      //imported using the commonlib
      $add_linkfield = array('comment' => 'comment',
                             'notepad' => 'notepad');

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

      //Add displaytype value
      $dropdown = array("dropdown"       => array(3, 4, 40, 31, 71, 11, 32, 33, 23),
                        "bool"           => array(86),
                        "user"           => array(70, 24),
                        "multiline_text" => array(16, 90));

      foreach ($dropdown as $type => $tabsID) {
         foreach ($tabsID as $tabID) {
            $tab[$tabID]['displaytype'] = $type;
         }
      }

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


   function processAfterInsertOrUpdate($values) {

      if (isset($values['NetworkEquipment']['nb_ports'])) {
         for ($i=1 ; $i<=$values['NetworkEquipment']['nb_ports'] ; $i++) {
            $input   = array ();
            $netport = new NetworkPort;
            $add     = "";

            if ($i < 10) {
               $add = "0";
            }

            $input["logical_number"] = $i;
            $input["name"]           = $add . $i;
            $input["items_id"]       = $values['NetworkEquipment']['id'];
            $input["itemtype"]       = 'NetworkEquipment';
            $input["entities_id"]    = $values['NetworkEquipment']['entities_id'];
            $netport->add($input);
         }
      }
   }

}

?>