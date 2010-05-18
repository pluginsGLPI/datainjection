<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Interface to implement for each type to inject
 */
interface PluginDatainjectionInjectionInterface {

   /**
    * Tells datainjection is the type is a primary type or not
    * @param none
    * @return a boolean
    */
   function isPrimaryType();

   /**
    * Indicates to with other types it can be connected
    * @param none
    * @return an array of GLPI types
    */
   function connectedTo();

   /**
    * Function which calls getSearchOptions and add more parameters specific to display
    * @param none
    * @return an array of search options, as defined in each commondbtm object
    */
   function getOptions();

   /**
    * Manage display of additional informations
    * @param info an array which contains the additional information values
    */
   function showAdditionalInformation($info = array());

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param values fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    */
   function addObject($values=array(), $options=array());

   /**
    * Standard method to update an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param fields fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of updated objects : for example array(Computer=>1, Networkport=>10)
    */
   function updateObject($values=array(), $options=array());


   /**
    * Standard method to delete an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param fields fields to add into glpi
    * @param options options used during creation
    */
   function deleteObject($values=array(), $options=array());

   /**
    * Check values to inject
    * @param fields_name field to add to glpi
    * @param data value to add
    * @param mandatory is this value mandatory or not ?
    * @return an array which indicates check status & errors
    */
   function checkType($field_name, $data, $mandatory);

   /**
    * Reformat data if itemtypes needs it
    */
   function reformat(&$values = array());

   /**
    * Add itemtype specific checks to see if object is already in DB or not
    * @param fields_toinject the fields to be injected into GLPI DB
    * @param options more informations needed
    * @return nothing
    */
   function checkPresent($fields_toinject = array(), $options = array());

   /**
    * Get value for a field (for example specific dropdowns for an itemtype)
    */
   function getSpecificFieldValue($itemtype, $searchOption, $field, $value);

}
?>