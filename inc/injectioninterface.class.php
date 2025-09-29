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

/**
 * Interface to implement for each type to inject
**/
interface PluginDatainjectionInjectionInterface
{
    /**
    * Tells datainjection is the type is a primary type or not
    *
    * @return boolean a boolean
   **/
    public function isPrimaryType();


    /**
    * Indicates to with other types it can be connected
    *
    * @return array of GLPI types
   **/
    public function connectedTo();


    /**
    * Function which calls getSearchOptions and add more parameters specific to display
    *
    * @param string $primary_type    (default '')
    *
    * @return array of search options, as defined in each commondbtm object
   **/
    public function getOptions($primary_type = '');


    /**
    * Manage display of additional informations
    *
    * @param array $info   array    which contains the additional information values
    *
    * This method is optionnal ! Implement it if the itemtype to display special information
   **/
    //function showAdditionalInformation($info=[]);


    /**
    * Standard method to add an object into glpi
    *
    * @param array $values    array fields to add into glpi
    * @param array $options   array options used during creation
    *
    * @return array of IDs of newly created objects:
    * for example array(Computer=>1, Networkport=>10)
   **/
    public function addOrUpdateObject($values = [], $options = []);

    /**
    * Check values to inject
    * This method is optionnal ! Implement it if the itemtype need special checks
    *
    * @param array $fields_name  field to add to glpi
    * @param $data         value to add
    * @param $mandatory    is this value mandatory or not ?
    *
    * @return array which indicates check status & errors
   **/
    //function checkType($field_name, $data, $mandatory);


    /**
    * Reformat data if itemtypes needs it
    * This method is optionnal ! Implement it if the itemtype need special reformat
    *
    * @param array $values array
   **/
    //function reformat(&$values=[]);


    /**
    * Add itemtype specific checks to see if object is already in DB or not
    *
    * @param array $fields_toinject    array   the fields to be injected into GLPI DB
    * @param array $options            array   more informations needed
    *
   **/
    //function checkPresent($fields_toinject=[], $options=[]);


    /**
    * Get value for a field (for example specific dropdowns for an itemtype)
    * This method is optionnal ! Implement it if the itemtype need special field values
    *
    * @param string $itemtype
    * @param array $searchOption
    * @pram  $field
    * @param array $value
   **/
    //function getSpecificFieldValue($itemtype, $searchOption, $field, $value);


    /**
    * Add specific values to the object to inject
    * This method is optionnal ! Implement it if the itemtype need special reformat
    *
    * @param string $primary_type     the primary_type to inject
    * @param array $fields_toinject  all the fields that need to be injected
   **/
    //function addSpecificNeededFields($primary_type, $fields_toinject);
}
