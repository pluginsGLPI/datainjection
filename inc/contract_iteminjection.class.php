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

class PluginDatainjectionContract_ItemInjection extends Contract_Item
                                                implements PluginDatainjectionInjectionInterface
{


   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {

      return false;
   }


   function relationSide() {

      return true;
   }


   function connectedTo() {

      global $CFG_GLPI;

      return $CFG_GLPI["contract_types"];
   }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type = '') {

      $tab[100]['table']         = 'glpi_contracts';
      $tab[100]['field']         = 'name';
      $tab[100]['linkfield']     = 'name';
      $tab[100]['name']          = __('Name');
      $tab[100]['injectable']    = true;
      $tab[100]['checktype']     = 'text';
      $tab[100]['displaytype']   = 'relation';
      $tab[100]['relationclass'] = 'Contract_Item';
      $tab[100]['storevaluein']  = 'contracts_id';

      $tab[101]['table']         = 'glpi_contracts';
      $tab[101]['field']         = 'num';
      $tab[101]['linkfield']     = 'num';
      $tab[101]['name']          = __('Serial number');
      $tab[101]['injectable']    = true;
      $tab[101]['checktype']     = 'text';
      $tab[101]['displaytype']   = 'relation';
      $tab[101]['relationclass'] = 'Contract_Item';
      $tab[101]['storevaluein']  = 'contracts_id';

      return $tab;
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

      $fields['items_id'] = $values[$primary_type]['id'];
      $fields['itemtype'] = $primary_type;
      return $fields;
   }

}
