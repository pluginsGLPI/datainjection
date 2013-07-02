<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Interface to implement for each type to inject
**/
interface PluginDatainjectionInjectionInterface {

   /**
    * Tells datainjection is the type is a primary type or not
    *
    * @param none
    *
    * @return a boolean
   */
   function isPrimaryType();


   /**
    * Indicates to with other types it can be connected
    *
    * @param none
    *
    * @return an array of GLPI types
   **/
   function connectedTo();


   /**
    * Function which calls getSearchOptions and add more parameters specific to display
    *
    * @param none
    *
    * @return an array of search options, as defined in each commondbtm object
   **/
   function getOptions($primary_type = '');


   /**
    * Manage display of additional informations
    *
    * @param info an array which contains the additional information values
    *
    * This method is optionnal ! Implement it if the itemtype to display special information
   **/
   //function showAdditionalInformation($info = array());


   /**
    * Standard method to add an object into glpi
 
    *
    * @param values fields to add into glpi
    * @param options options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array());

   /**
    * Standard method to delete an object into glpi
 
    *
    * @param fields fields to add into glpi
    * @param options options used during creation
   **/
   //function deleteObject($values=array(), $options=array());


   /**
    * Check values to inject
    * This method is optionnal ! Implement it if the itemtype need special checks
    *
    * @param fields_name field to add to glpi
    * @param data value to add
    * @param mandatory is this value mandatory or not ?
    *
    * @return an array which indicates check status & errors
   **/
   //function checkType($field_name, $data, $mandatory);


   /**
    * Reformat data if itemtypes needs it
    * This method is optionnal ! Implement it if the itemtype need special reformat
   **/
   //function reformat(&$values = array());


   /**
    * Add itemtype specific checks to see if object is already in DB or not
    *
    * @param fields_toinject the fields to be injected into GLPI DB
    * @param options more informations needed
    *
    * @return nothing
   **/
   //function checkPresent($fields_toinject = array(), $options = array());


   /**
    * Get value for a field (for example specific dropdowns for an itemtype)
    * This method is optionnal ! Implement it if the itemtype need special field values
   **/
   //function getSpecificFieldValue($itemtype, $searchOption, $field, $value);


   /**
    * Add specific values to the object to inject
    *
    * @param primary_type the primary_type to inject
    * @param fields_toinject all the fields that need to be injected
    * This method is optionnal ! Implement it if the itemtype need special reformat
    *
   **/
   //function addSpecificNeededFields($primary_type, $fields_toinject);
}
?>