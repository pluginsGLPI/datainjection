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

class PluginDatainjectionGroup_UserInjection extends Group_User
                                             implements PluginDatainjectionInjectionInterface
{


   static function getTypeName($nb = 0) {

      return sprintf(__('%1$s - %2$s'), __('Group'), __('User'));
   }


   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();

   }

   function isPrimaryType() {

      return false;
   }


   function connectedTo() {

      //return [];
      return ['Group'];
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab                    = Search::getOptions(get_parent_class($this));

      $tab[3]['checktype']    = 'bool';
      $tab[3]['displaytype']  = 'bool';

      $tab[4]['checktype']    = 'text';
      $tab[4]['displaytype']  = 'dropdown';

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = [4];

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = ["bool"    => [3, 6, 7],
                                      "dropdown" => [4]];

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

      if ($primary_type == "User") {
         $fields['users_id'] = $values['User']['id'];
      } else if ($primary_type == "Group") {
         $fields['users_id']  = $values['Group_User']['users_id'];
         $fields['groups_id'] = $values['Group']['id'];
      }

      return $fields;
   }

}
