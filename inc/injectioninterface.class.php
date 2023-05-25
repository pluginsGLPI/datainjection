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
    * @return a boolean
   **/
    function isPrimaryType();


    /**
    * Indicates to with other types it can be connected
    *
    * @return an array of GLPI types
   **/
    function connectedTo();


    /**
    * Function which calls getSearchOptions and add more parameters specific to display
    *
    * @param $primary_type    (default '')
    *
    * @return an array of search options, as defined in each commondbtm object
   **/
    function getOptions($primary_type = '');


    /**
    * Manage display of additional informations
    *
    * @param $info   array    which contains the additional information values
    *
    * This method is optionnal ! Implement it if the itemtype to display special information
   **/
    //function showAdditionalInformation($info=[]);


    /**
    * Standard method to add an object into glpi
    *
    * @param $values    array fields to add into glpi
    * @param $options   array options used during creation
    *
    * @return an array of IDs of newly created objects:
    * for example array(Computer=>1, Networkport=>10)
   **/
    function addOrUpdateObject($values = [], $options = []);



    /**
    * Check values to inject
    * This method is optionnal ! Implement it if the itemtype need special checks
    *
    * @param $fields_name  field to add to glpi
    * @param $data         value to add
    * @param $mandatory    is this value mandatory or not ?
    *
    * @return an array which indicates check status & errors
   **/
    //function checkType($field_name, $data, $mandatory);


    /**
    * Reformat data if itemtypes needs it
    * This method is optionnal ! Implement it if the itemtype need special reformat
    *
    * @param $values array
   **/
    //function reformat(&$values=[]);


    /**
    * Add itemtype specific checks to see if object is already in DB or not
    *
    * @param $fields_toinject    array   the fields to be injected into GLPI DB
    * @param $options            array   more informations needed
    *
    * @return nothing
   **/
    //function checkPresent($fields_toinject=[], $options=[]);


    /**
    * Get value for a field (for example specific dropdowns for an itemtype)
    * This method is optionnal ! Implement it if the itemtype need special field values
    *
    * @param $itemtype
    * @param $searchOption
    * @pram  $field
    * @param $value
   **/
    //function getSpecificFieldValue($itemtype, $searchOption, $field, $value);


    /**
    * Add specific values to the object to inject
    * This method is optionnal ! Implement it if the itemtype need special reformat
    *
    * @param primary_type     the primary_type to inject
    * @param fields_toinject  all the fields that need to be injected
   **/
    //function addSpecificNeededFields($primary_type, $fields_toinject);
}
