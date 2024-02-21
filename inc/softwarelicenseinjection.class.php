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

/// SoftwareLicense class
class PluginDatainjectionSoftwareLicenseInjection extends SoftwareLicense implements PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null)
    {

        $parenttype = get_parent_class();
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {

        return true;
    }


    public function connectedTo()
    {

        return ['Software'];
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
    public function getOptions($primary_type = '')
    {

        $tab                 = Search::getOptions(get_parent_class($this));

        $tab[8]['checktype'] = 'date';

        if ($primary_type == 'SoftwareLicense') {
            $tab[100]['name']          = _n('Software', 'Software', 1);
            $tab[100]['field']         = 'name';
            $tab[100]['table']         = getTableForItemType('Software');
            $tab[100]['linkfield']     = 'softwares_id';
            $tab[100]['displaytype']   = 'dropdown';
            $tab[100]['checktype']     = 'text';
            $tab[100]['injectable']    = true;
        }

       //Remove some options because some fields cannot be imported
        $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
        $notimportable = [];

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);

        $key = array_search(2, $options['ignore_fields']);
        unset($options['ignore_fields'][$key]);

        $options['displaytype']   = ["dropdown"       => [5, 6, 7, 110],
            "date"           => [8],
            "multiline_text" => [16],
            "software" => [100]
        ];

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
    }


    /**
    * @param $info      array
    * @param $option    array
   **/
    public function showAdditionalInformation($info = [], $option = [])
    {

        $name = "info[" . $option['linkfield'] . "]";

        switch ($option['displaytype']) {
            case 'computer':
                Computer::dropdown(
                    ['name'        => $name,
                        'entity'      => $_SESSION['glpiactive_entity'],
                        'entity_sons' => false
                    ]
                );
                break;

            case 'software':
                Software::dropdown(
                    ['name'        => $name,
                        'entity'      => $_SESSION['glpiactive_entity'],
                        'entity_sons' => false
                    ]
                );
                break;

            default:
                break;
        }
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
    * @param $fields_toinject    array
   **/
    public function getValueForAdditionalMandatoryFields($fields_toinject = [])
    {
        /** @var DBmysql $DB */
        global $DB;

        if (!isset($fields_toinject['SoftwareLicense']['softwares_id'])) {
            return $fields_toinject;
        }

        $query = "SELECT `id`
                FROM `glpi_softwares`
                WHERE `name` = '" . $fields_toinject['SoftwareLicense']['softwares_id'] . "'" .
                    getEntitiesRestrictRequest(
                        " AND",
                        "glpi_softwares",
                        "entities_id",
                        $fields_toinject['SoftwareLicense']['entities_id'],
                        true
                    );
        /** @phpstan-ignore-next-line */
        $result = $DB->query($query); // phpcs:ignore

        if ($DB->numrows($result) > 0) {
            $id = $DB->result($result, 0, 'id');
            //Add softwares_id to the array
            $fields_toinject['SoftwareLicense']['softwares_id'] = $id;
        } else {
            //Remove software id
            unset($fields_toinject['SoftwareLicense']['softwares_id']);
        }

        return $fields_toinject;
    }


    /**
    * @param $primary_type
    * @param $values
   **/
    public function addSpecificNeededFields($primary_type, $values)
    {

        $fields = [];
        if ($primary_type == 'Software') {
            $fields['softwares_id'] = $values[$primary_type]['id'];
        }
        return $fields;
    }


    /**
    * @param $fields_toinject    array
    * @param $options            array
   **/
    public function checkPresent($fields_toinject = [], $options = [])
    {

        if ($options['itemtype'] != 'SoftwareLicense') {
            return (" AND `softwares_id` = '" . $fields_toinject['Software']['id'] . "'
                   AND `name` = '" . $fields_toinject['SoftwareLicense']['name'] . "'");
        }
        return "";
    }
}
