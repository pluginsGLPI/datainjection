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

class PluginDatainjectionItem_SoftwareLicenseInjection extends Item_SoftwareLicense implements PluginDatainjectionInjectionInterface
{
    public static function getTypeName($nb = 0)
    {
        return __('Computer');
    }


    public static function getTable($classname = null)
    {
        $parenttype = get_parent_class();
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
        return ['SoftwareLicense', 'Software'];
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
    public function getOptions($primary_type = '')
    {
        $tab[110]['table']        = 'glpi_computers';
        $tab[110]['field']        = 'name';
        $tab[110]['linkfield']    = 'name';
        $tab[110]['name']         = sprintf(__('%1$s - %2$s'), self::getTypeName(), __('Name'));
        $tab[110]['injectable']   = true;
        $tab[110]['displaytype']  = 'dropdown';
        $tab[110]['checktype']    = 'text';
        $tab[110]['storevaluein'] = 'computers_id';

        $tab[111]['table']        = 'glpi_computers';
        $tab[111]['field']        = 'serial';
        $tab[111]['linkfield']    = 'serial';
        $tab[111]['name']         = sprintf(
            __('%1$s - %2$s'),
            self::getTypeName(),
            __('Serial number')
        );
        $tab[111]['injectable']   = true;
        $tab[111]['displaytype']  = 'dropdown';
        $tab[111]['checktype']    = 'text';
        $tab[112]['storevaluein'] = 'computers_id';

        $tab[112]['table']        = 'glpi_computers';
        $tab[112]['field']        = 'otherserial';
        $tab[112]['linkfield']    = 'otherserial';
        $tab[112]['name']         = sprintf(
            __('%1$s - %2$s'),
            self::getTypeName(),
            __('Inventory number')
        );
        $tab[112]['injectable']   = true;
        $tab[112]['displaytype']  = 'dropdown';
        $tab[112]['checktype']    = 'text';
        $tab[112]['storevaluein'] = 'computers_id';

        return $tab;
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
            'softwarelicenses_id' => 1
        ];
    }


    /**
    * @param $primary_type
    * @param $values
   **/
    public function addSpecificNeededFields($primary_type, $values)
    {
        $fields = [];

        if (isset($values['SoftwareLicense'])) {
            $fields['softwarelicenses_id'] = $values['SoftwareLicense']['id'];
        }

        if (isset($values['Item_SoftwareLicense'])) {
            $fields['itemtype'] = "Computer";
            $fields['items_id'] = $values['Item_SoftwareLicense']['computers_id'];
        }

        return $fields;
    }
}
