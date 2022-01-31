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
 * @copyright Copyright (C) 2007-2022 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionSoftwareInjection extends Software
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

      $tab                    = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[3]['linkfield']    = 'locations_id';

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = [4, 5, 31, 72, 91, 92, 93, 160, 161, 162, 163, 164, 165, 166, 170];

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $options['displaytype']   = ["dropdown"       => [3, 23, 49, 62, 71],
                                      "bool"           => [61, 86],
                                      "user"           => [24, 70],
                                      "multiline_text" => [16, 90]];

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


    /**
    * Play software dictionnary
    *
    * @param $values
   **/
   function processDictionnariesIfNeeded(&$values) {

      $params['entities_id'] = $_SESSION['glpiactive_entity'];
      $params['name']        = $values['Software']['name'];
      if (isset($values['Software']['manufacturers_id'])) {
         $params['manufacturer'] = $values['Software']['manufacturers_id'];
      } else {
         $params['manufacturer'] = '';
      }
      $rulecollection = new RuleDictionnarySoftwareCollection();
      $res_rule       = $rulecollection->processAllRules($params, [], []);

      if (!isset($res_rule['_no_rule_matches'])) {
         //Software dictionnary explicitly refuse import
         if (isset($res_rule['_ignore_import']) && $res_rule['_ignore_import']) {
            return false;
         }
         if (isset($res_rule['is_helpdesk_visible'])) {
            $values['Software']['is_helpdesk_visible'] = $res_rule['is_helpdesk_visible'];
         }
         if (isset($res_rule['version'])) {
            $values['SoftwareVersion']['name'] = $res_rule['version'];
         }
         if (isset($res_rule['name'])) {
            $values['Software']['name'] = $res_rule['name'];
         }
         if (isset($res_rule['supplier'])) {
            if (isset($values['supplier'])) {
               $values['Software']['manufacturers_id']
                 = Dropdown::getDropdownName('glpi_suppliers', $res_rule['supplier']);
            }
         }
      }
      return true;
   }

    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values = [], $options = []) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

}
