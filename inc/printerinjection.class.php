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



class PluginDatainjectionPrinterInjection extends Printer implements PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null)
    {

        $parenttype = get_parent_class(self::class);
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {

        return true;
    }


    public function connectedTo()
    {

        return ['Computer', 'Document'];
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

        $tab                 = Search::getOptions(get_parent_class($this));

        //Specific to location
        $tab[3]['linkfield'] = 'locations_id';

        //Remove some options because some fields cannot be imported
        $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
        $notimportable = [91, 92, 93];

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);

        $options['displaytype']   = ["dropdown"       => [3, 4, 23, 31, 32, 33, 40, 49, 71],
            "bool"           => [42, 43, 44, 45, 46, 86],
            "user"           => [24, 70],
            "multiline_text" => [16, 90],
        ];

        $options['checktype']     = ["bool" => [42, 43, 44, 45, 46, 86]];

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
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


    /**
    * @param string $primary_type
    * @param array $values
   **/
    public function addSpecificNeededFields($primary_type, $values)
    {

        $fields = [];
        if (isset($values[$primary_type]['is_global'])) {
            $fields['is_global'] = empty($values[$primary_type]['is_global']) ? 0 : $values[$primary_type]['is_global'];
        }
        return $fields;
    }


    /**
    * Play printers dictionnary
    *
    * @param array $values
   **/
    public function processDictionnariesIfNeeded(&$values)
    {

        $matchings = ['name'         => 'name',
            'manufacturer' => 'manufacturers_id',
            'comment'      => 'comment',
        ];
        foreach ($matchings as $name => $value) {
            $params[$name] = $values['Printer'][$value] ?? '';
        }

        $rulecollection = new RuleDictionnaryPrinterCollection();
        $res_rule       = $rulecollection->processAllRules($params, [], []);

        if (!isset($res_rule['_no_rule_matches'])) {
            //Printers dictionnary explicitly refuse import
            if (isset($res_rule['_ignore_import']) && $res_rule['_ignore_import']) {
                return false;
            }
            if (isset($res_rule['is_global'])) {
                $values['Printer']['is_global'] = $res_rule['is_global'];
            }

            if (isset($res_rule['name'])) {
                $values['Printer']['name'] = $res_rule['name'];
            }

            if (isset($res_rule['supplier']) && isset($values['supplier'])) {
                $values['Printer']['manufacturers_id']
                = Dropdown::getDropdownName('glpi_suppliers', $res_rule['supplier']);
            }
        }
        return true;
    }
}
