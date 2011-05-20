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


   function getOptions($primary_type = '') {
      global $LANG;

      $tab = Search::getOptions('Infocom');

      $tab[4]['checktype'] = 'date';
      $tab[5]['checktype'] = 'date';

      //Warranty_duration
      $tab[6]['minvalue']  = 0;
      $tab[6]['maxvalue']  = 120;
      $tab[6]['step']      = 1;
      $tab[6]['-1']        = $LANG['financial'][2];
      $tab[6]['checktype'] = 'integer';

      $tab[8]['minvalue']  = 0;
      $tab[8]['maxvalue']  = 120;
      $tab[8]['step']      = 1;
      $tab[8]['checktype'] = 'integer';

      $tab[13]['checktype'] = 'float';

      $tab[14]['minvalue']  = 0;
      $tab[14]['maxvalue']  = 15;
      $tab[14]['step']      = 1;
      $tab[14]['checktype'] = 'integer';

      $tab[17]['size']      = 14;
      $tab[17]['default']   = 0;
      $tab[17]['checktype'] = 'integer';

      //Remove some options because some fields cannot be imported
      $notimportable = array(2, 3, 20, 21, 80, 86);

      //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
      //imported using the commonlib
      $add_linkfield = array('comment' => 'comment',
                             'notepad' => 'notepad');

      //Add default displaytype (text)
      foreach ($tab as $id => $tmp) {
         if (!is_array($tmp) || in_array($id,$notimportable)) {
            unset($tab[$id]);

         } else {
            if (in_array($tmp['field'],$add_linkfield)) {
               $tab[$id]['linkfield'] = $add_linkfield[$tmp['field']];
            }

            if (!in_array($id,$notimportable)) {
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
      $fields_definition = array("date"             => array(4, 5),
                                 "dropdown"         => array(6, 9, 19),
                                 "dropdown_integer" => array(6, 14),
                                 "decimal"          => array(8, 13, 17),
                                 "sink_type"        => array(15),
                                 "alert"            => array(22),
                                 "multiline_text"   => array(16));

      foreach ($fields_definition as $type => $tabsID) {
         foreach ($tabsID as $tabID) {
            $tab[$tabID]['displaytype'] = $type;
         }
      }

      return $tab;
   }


   function showAdditionalInformation($info=array(), $option=array()) {

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