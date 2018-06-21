<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2017 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/pluginsGLPI/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionMonitorInjection extends Monitor
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

      return ['Computer', 'Document'];
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab                 = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[3]['linkfield'] = 'locations_id';

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = [91, 92, 93];

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $options['displaytype']   = ["dropdown"       => [3, 4, 23, 31, 40, 49, 46, 71],
                                      "user"           => [24, 70],
                                      "float"          => [11],
                                      "bool"           => [41, 42, 43, 44, 45, 46, 47, 48],
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
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      $fields = [];
      if (isset($values[$primary_type]['is_global'])) {
         if (empty($values[$primary_type]['is_global'])) {
            $fields['is_global'] = 0;
         } else {
            $fields['is_global'] = $values[$primary_type]['is_global'];
         }
      }
      return $fields;
   }

}
