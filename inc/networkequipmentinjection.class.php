<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionNetworkEquipmentInjection extends NetworkEquipment
                                                   implements PluginDatainjectionInjectionInterface
{


   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {

      return true;
   }


   function connectedTo() {

      return [];
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab                       = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[3]['linkfield']       = 'locations_id';

      //Virtual type : need to be processed at the end !
      $tab[200]['table']         = 'glpi_networkequipments';
      $tab[200]['field']         = 'nb_ports';
      $tab[200]['name']          = __('Number of ports', 'datainjection');
      $tab[200]['checktype']     = 'integer';
      $tab[200]['displaytype']   = 'virtual';
      $tab[200]['linkfield']     = 'nb_ports';
      $tab[200]['injectable']    = PluginDatainjectionCommonInjectionLib::FIELD_VIRTUAL;

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = [
         41, 43, 44, 45, 46, 48, 61, 63, 64, 91, 92, 93
      ];

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $options['displaytype']   = ["dropdown"       => [3, 4, 11, 23, 31, 32, 33,
                                                                40, 49, 71],
                                      "bool"           => [86],
                                      "user"           => [24, 70],
                                      "multiline_text" => [16, 90]];

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


    /**
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
    */
   function processAfterInsertOrUpdate($values, $add = true, $rights = []) {

      if (isset($values['NetworkEquipment']['nb_ports'])) {
         for ($i=1; $i<=$values['NetworkEquipment']['nb_ports']; $i++) {
            $input   = [];
            $netport = new NetworkPort();
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
