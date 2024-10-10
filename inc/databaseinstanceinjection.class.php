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

use Glpi\Inventory\Asset\Computer;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}


class PluginDatainjectionDatabaseinstanceInjection extends DatabaseInstance implements PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null)
    {

        $parenttype = get_parent_class(__CLASS__);
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {

        return true;
    }


    public function connectedTo()
    {

        return [Computer::class];
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
    public function getOptions($primary_type = '')
    {

        $tab                 = Search::getOptions(get_parent_class($this));

        $tab[251]['name']          = __('Itemtype');
        $tab[251]['field']         = 'itemtype';
        $tab[251]['table']         = DatabaseInstance::getTable();
        $tab[251]['linkfield']     = "itemtype";
        $tab[251]['injectable']    = true;
        $tab[251]['displaytype']   = 'text';
        $tab[251]['checktype']     = 'text';

        $tab[252]['name']          = __('Item');
        $tab[252]['field']         = 'name';
        $tab[252]['table']         = DatabaseInstance::getTable();
        $tab[252]['linkfield']     = "items_name";
        $tab[252]['injectable']    = true;
        $tab[252]['displaytype']   = 'text';
        $tab[252]['checktype']     = 'text';

        $tab[253]['name']          = __('Path');
        $tab[253]['field']         = 'path';
        $tab[253]['table']         = DatabaseInstance::getTable();
        $tab[253]['linkfield']     = "path";
        $tab[253]['injectable']    = true;
        $tab[253]['displaytype']   = 'text';
        $tab[253]['checktype']     = 'text';

       //Remove some options because some fields cannot be imported
        $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
        $notimportable            = [];
        $options['ignore_fields'] = array_merge($blacklist, $notimportable);


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
    * @param $values
    * @param $add                (true by default)
    * @param $rights    array
    **/
    public function processAfterInsertOrUpdate($values, $add = true, $rights = [])
    {
        /** @var DBmysql $DB */
        global $DB;

        //Should the port be connected to another one ?
        $use_itemtype    = (isset($values['DatabaseInstance']["itemtype"])
                            || !empty($values['DatabaseInstance']["itemtype"]));
        $use_items_name   = (isset($values['DatabaseInstance']["items_name"])
                            || !empty($values['DatabaseInstance']["items_name"]));

        if (!$use_itemtype || !$use_items_name) {
            return false;
        }

        $itemtype = new $values['DatabaseInstance']["itemtype"]();
        if ($itemtype->getFromDBByCrit(['name' => $values['DatabaseInstance']["items_name"], 'entities_id' => $values['DatabaseInstance']["entities_id"]])) {
            $dbinstance = new DatabaseInstance();
            $success = $dbinstance->update(
                [
                    'id'          => $values['DatabaseInstance']['id'],
                    'entities_id' => $values['DatabaseInstance']['entities_id'],
                    'items_id'    => $itemtype->getID()
                ]
            );
        }
    }
}
