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



class PluginDatainjectionItem_SoftwareVersionInjection extends Item_SoftwareVersion implements PluginDatainjectionInjectionInterface
{
    public static function getTypeName($nb = 0)
    {
        return __('Computer');
    }


    public static function getTable($classname = null)
    {
        $parenttype = get_parent_class(self::class);
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {
        return false;
    }

    public function relationSide()
    {
        return false;
    }


    public function connectedTo()
    {
        return ['Software', 'SoftwareVersion'];
    }

    public function isNullable($field)
    {
        return true; // By default, all fields can be null
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
    public function getOptions($primary_type = '')
    {
        return [110 => ['table' => 'glpi_computers', 'field' => 'name', 'linkfield' => 'name', 'name' => sprintf(__('%1$s - %2$s'), self::getTypeName(), __('Name')), 'injectable' => true, 'displaytype' => 'dropdown', 'checktype' => 'text', 'storevaluein' => 'computers_id'], 111 => ['table' => 'glpi_computers', 'field' => 'serial', 'linkfield' => 'serial', 'name' => sprintf(
            __('%1$s - %2$s'),
            self::getTypeName(),
            __('Serial number'),
        ), 'injectable' => true, 'displaytype' => 'dropdown', 'checktype' => 'text'], 112 => ['storevaluein' => 'computers_id', 'table' => 'glpi_computers', 'field' => 'otherserial', 'linkfield' => 'otherserial', 'name' => sprintf(
            __('%1$s - %2$s'),
            self::getTypeName(),
            __('Inventory number'),
        ), 'injectable' => true, 'displaytype' => 'dropdown', 'checktype' => 'text']];
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
    public function addOrUpdateObject($values = [], $options = [])
    {

        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }


    public function addSpecificMandatoryFields()
    {
        return [
            'computers_id'        => 1,
            'softwareversions_id' => 1,
        ];
    }


    /**
    * @param string $primary_type
    * @param array $values
   **/

    public function addSpecificNeededFields($primary_type, $values)
    {
        $fields = [];

        if (isset($values['SoftwareVersion'])) {
            $fields['softwareversions_id'] = $values['SoftwareVersion']['id'];
        }

        if (isset($values['Item_SoftwareVersion'])) {
            $fields['itemtype'] = "Computer";
            $fields['items_id'] = $values['Item_SoftwareVersion']['computers_id'];
        }

        return $fields;
    }
}
