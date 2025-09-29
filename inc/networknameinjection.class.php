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



class PluginDatainjectionNetworkNameInjection extends NetworkName implements PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null)
    {

        $parenttype = get_parent_class(self::class);
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {

        return false;
    }


    public function connectedTo()
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        return $CFG_GLPI["networkport_types"];
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

        $tab           = Search::getOptions(get_parent_class($this));

        //Remove some options because some fields cannot be imported
        $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
        $notimportable = [20, 21];

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);
        $options['displaytype']   = [
            "dropdown" => [12],
            "text"     => [13],
        ];

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

        $fields['items_id']  = $values['NetworkPort']['id'];
        $fields['itemtype']      = "NetworkPort";

        return $fields;
    }


    /**
    * @param array $values
    * @param boolean $add                   (true by default)
    * @param array|null $rights    array
    */
    public function processAfterInsertOrUpdate($values, $add = true, $rights = [])
    {
        /** @var DBmysql $DB */
        global $DB;

        //Manage ip adresses
        if (isset($values['NetworkName']['ipaddresses_id']) && !countElementsInTable(
            "glpi_ipaddresses",
            [
                'items_id' => $values['NetworkName']['id'],
                'itemtype' => 'NetworkName',
                'name'     => $values['NetworkName']['ipaddresses_id'],
            ],
        )) {
            $ip                  = new IPAddress($values['NetworkName']['ipaddresses_id']);
            $tmp['items_id']     = $values['NetworkName']['id'];
            $tmp['itemtype']     = "NetworkName";
            $tmp['name']         =  $ip->getTextual();
            $tmp['is_dynamic']   = 0;
            if (!$ip->getFromDBByCrit($tmp)) {
                $ip->add($tmp);
            }
        }
    }
}
