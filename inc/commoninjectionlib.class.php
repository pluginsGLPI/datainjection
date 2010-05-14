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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file: Common library to import data into GLPI
// This library *SHOULD* be included in GLPI's core in version 0.80
// ----------------------------------------------------------------------

class PluginDatainjectionCommonInjectionLib {

   //Injection results
   private $results = array();

   //Values to inject
   private $values = array();

   //Fields mandatory for injection
   private $mandatory_fields = array();

   //Injection class to use
   private $injectionClass;

   //Store checks to perform on values
   private $checks = array();

   //Store rights
   private $rights = array();

   //Store specific fields formats
   private $formats = array();

   //Entity in which data will be inserted
   private $entity = 0;

   const ACTION_CHECK                   = 0;
   const ACTION_INJECT                  = 1;

   //Type of action to perform
   const IMPORT_ADD                     = 0;
   const IMPORT_UPDATE                  = 1;
   const IMPORT_DELETE                  = 2;

   //Action return constants
   const SUCCESS                        = 1;
   const FAILED                         = 0;
   const WARNING                        = 2;

   const ITEM_NOT_FOUND                 = -1;

   //Field check return constants
   const UNKNOWN_ID                     = 0;
   const ALREADY_EXISTS                 = 1;
   const TYPE_MISMATCH                  = 2;
   const MANDATORY                      = 3;

   // Injection Message
   const ERROR_IMPORT_ALREADY_IMPORTED  = 11;
   const ERROR_CANNOT_IMPORT            = 12;
   const ERROR_CANNOT_UPDATE            = 13;
   const WARNING_NOTFOUND               = 14;
   const WARNING_USED                   = 15;
   const WARNING_NOTEMPTY               = 16;
   const WARNING_ALLEMPTY               = 17;
   const WARNING_SEVERAL_VALUES_FOUND   = 18;
   const WARNING_ALREADY_LINKED         = 19;
   const IMPORT_IMPOSSIBLE              = 20;
   const ERROR_FIELDSIZE_EXCEEDED       = 21;
   const WARNING_PARTIALLY_IMPORTED     = 22;

   //Empty values
   const EMPTY_VALUE                    = '';
   const DROPDOWN_DEFAULT_VALUE         = 0;

   //Format constants
   const FLOAT_TYPE_COMMA               = 0; //xxxx,xx
   const FLOAT_TYPE_DOT                 = 1; //xxxx.xx
   const FLOAT_TYPE_DOT_AND_COM         = 2; //xx,xxx.xx

   //Date management constants
   const DATE_TYPE_DDMMYYYY             = "dd-mm-yyyy";
   const DATE_TYPE_MMDDYYYY             = "mm-dd-yyyy";
   const DATE_TYPE_YYYYMMDD             = "yyyy-mm-dd";

   /**
    * Constructor : store all needed options into the library
    * @param injectionClass class which represents the itemtype to injection
    *                         (in 0.80, will be directly the itemtype class)
    * @param values values to injection into GLPI
    * @param injection_options options that can be used during the injection (maybe an empty array)
    * @return nothing
    */
   function __construct($injectionClass, $values = array(), $injection_options = array()) {

      if (isset($injection_options['checks'])) {
         $this->checks = $injection_options['checks'];
      }
      if (isset($injection_options['rights'])) {
         $this->rights = $injection_options['rights'];
      }
      if (isset($injection_options['mandatory_fields'])) {
         $this->mandatory_fields = $injection_options['mandatory_fields'];
      }

      //Split $injection_options array and store data into the rights internal arrays
      if (isset($injection_options['formats'])) {
         $this->formats = $injection_options['formats'];
      }
      else {
         $this->formats = array('date_format' => self::DATE_TYPE_YYYYMMDD,
                                'float_format' => FLOAT_TYPE_DOT);
      }

      $this->values = $values;
      $this->injectionClass = $injectionClass;

      //If entity is given stores it, then use root entity
      if (isset($injection_options['entities_id'])) {
         $this->entity = $injection_options['entities_id'];
      }
      else {
         $this->entity = 0;
      }
   }

   /**
    * Find and return the right search option
    * @param options the search options array
    * @param lookfor the search option we're looking for
    * @return the search option matching lookfor parameter
    */
   static function findSearchOption($options, $lookfor) {
      $found = false;
      foreach ($options as $option) {
         if (isset($option['linkfield']) && $option['linkfield'] == $lookfor) {
            $found = $option;
         }
      }
      return $found;
   }

   //--------------------------------------------------//
   //----------- Injection options getters -----------//
   //------------------------------------------------//

   /**
    * Get date format used for injection
    * @return date format used
    */
   private function getDateFormat() {
      return $this->formats['date_format'];
   }

   /**
    * Get date format used for injection
    * @return date format used
    */
   private function getFloatFormat() {
      return $this->formats['float_format'];
   }

   /**
    * Is allowed to add values to dropdowns ?
    * @return boolean
    */
   private function canAddDropdownValue() {
      if (isset($this->rights['add_dropdown'])) {
         return $this->rights['add_dropdown'];
      }
      else {
         return true;
      }
   }

   /**
    * Should a field type be checked ?
    * @return the value as given in the constructor or false (no check to perform for this type)
    */
   private function getCheckFieldType($type) {
      if (isset($this->checks[$type])) {
         return $this->checks[$type];
      }
      else {
         return false;
      }
   }

   /**
    * Get itemtype associated to the injectionClass
    * @return an itemtype
    */
   private function getItemtype() {
      $classname = get_class($this->injectionClass);
      return PluginDatainjectionInjectionCommon::getItemtypeByInjection($classname);
   }

   /**
    * Get itemtype associated to the injectionClass
    * @return an itemtype
    */
   private function getItemInstance() {
      $classname = get_class($this->injectionClass);
      return PluginDatainjectionInjectionCommon::getItemtypeInstanceByInjection($classname);
   }

   /**
    * Return injection results
    * @return an array which contains the reformat/check/injection logs
    */
   public function getInjectionResults() {
      return $this->results;
   }

   private function getFieldValue($field) {

   }
   //--------------------------------------------------//
   //----------- Reformat methods --------------------//
   //------------------------------------------------//
   //Several pass are needed to reformat data
   //because dictionnaries need to be process in a
   //specific order

   /**
    * First pass of data reformat : check values like NULL or values coming from dropdown tables
    * @return nothing
    */
   private function reformatFirstPass() {
      //By default check is ok : if it's not the case, the value will be modified later
      $results[self::ACTION_CHECK]['status'] = self::SUCCESS;

      //Get search options associated with the injectionClass
      $searchOptions = $this->injectionClass->getOptions();

      //Browse all fields & values
      foreach ($this->values as $field => $value) {
         if ($value == "NULL") {
               $this->values[$field]=self::EMPTY_VALUE;
         }
         else {
            //Get search option associated with the field
            $option = self::findSearchOption($searchOptions,$field);

            //If field is a dropdown, then use the standard import() table
            if($option['checktype'] == 'dropdown') {
               $dropdownItemName = getItemTypeForTable($option['table']);
               $dropdownClass = new $dropdownItemName();

               if ($dropdownClass instanceof CommonTreeDropdown) {
                  $value = self::reformatSpecialChars($value);
               }
               if ($dropdownClass->canCreate() && $this->canAddDropdownValue()) {
                  $dropdownID = $dropdownClass->import($value);
                  if ($dropdownID) {
                     $this->values[$field] = Dropdown::getDropdownName($option['table'],$dropdownID);
                  }
                  else {
                     $results[self::ACTION_CHECK]['status'] = self::WARNING;
                     $results[self::ACTION_CHECK][$field] = self::WARNING_NOTFOUND;
                  }
               }
            }
         }
      }
   }

   /**
    * Second pass of reformat : check if the itemtype needs specific reformat (like Software)
    * @return nothing
    */
   private function reformatSecondPass() {
      $this->injectionClass->reformat($this->values);
   }

   /**
    * Third pass of reformat : datas, mac address & floats
    *
    */
   private function reformatThirdPass() {
      global $CFG_GLPI;
      //Get search options associated with the injectionClass
      $searchOptions = $this->injectionClass->getOptions();

      foreach ($this->values as $field => $value) {
         //Get search option associated with the field
         $option = self::findSearchOption($searchOptions,$field);

         // Check some types
         switch ($option['checktype'])
         {
            case "date":
               //If the value is a date, try to reformat it if it's not the good type
               //(dd-mm-yyyy instead of yyyy-mm-dd)
               $this->values[$field] = self::reformatDate($value,$this->getDateFormat());
               break;
            case "mac":
               $this->values[$field] = self::reformatMacAddress($value);
               break;
            case "float":
                  $this->values[$field] = self::reformatFloat($value, $this->getFloatFormat());
               break;
            default:
            break;
         }
      }
   }

   private function reformat() {
      $this->reformatFirstPass();
      $this->reformatSecondPass();
      $this->reformatThirdPass();
   }

   /**
    * Replace < and > by their html code
    * @value to inject
    * @value modified
    */
   static function reformatSpecialChars($value)
   {
      return str_replace(array('<','>'),array("&lt;","&gt;"),$value);
   }

   /**
    * Reformat float value. Input could be :
    * xxxx.xx
    * xx,xxx.xx
    * xxxx,xx
    * @value value : the float to reformat
    * @format the float format
    * @return the float modified as expected in GLPI
    */
   static private function reformatFloat($value,$format)
   {
      if ($value==self::EMPTY_VALUE) {
         return $value;
      }
      //TODO : replace str_replace by a regex
      switch ($format)
      {
         case self::FLOAT_TYPE_COMMA:
         $value = str_replace(array(" ", ","), array("","."), $value);
         break;
         case self::FLOAT_TYPE_DOT:
         $value = str_replace(" ","", $value);
         break;
         case self::FLOAT_TYPE_DOT_AND_COM:
         $value=str_replace(",","", $value);
         break;
      }
      return $value;
   }

   /**
    * Reformat date from dd-mm-yyyy to yyyy-mm-dd
    * @param original_date the original date
    * @return the date reformated, if needed
    */
   static private function reformatDate($original_date,$date_format)
   {
      switch ($date_format)
      {
         case self::DATE_TYPE_YYYYMMDD:
            $new_date=preg_replace('/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/','\1-\2-\3',
                                   $original_date);
         break;
         case self::DATE_TYPE_DDMMYYYY:
            $new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\2-\1',
                                   $original_date);
         break;
         case self::DATE_TYPE_MMDDYYYY:
            $new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\1-\2',
                                   $original_date);
         break;
      }

      if (preg_match('/[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}/',$new_date))
         return $new_date;
      else
         return $original_date;
   }

   /**
    * Reformat mac adress if mac doesn't contains : or - as seperator
    * @param mac the original mac address
    * @return the mac address modified, if needed
    */
   static private function reformatMacAddress($mac)
   {
      $pattern = "/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})";
      $patter .="([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/";
      preg_match($pattern,$mac,$results);
      if (count($results) > 0)
      {
         $mac="";
         $first=true;
         unset($results[0]);
         foreach($results as $result)
         {
            $mac.=(!$first?":":"").$result;
            $first=false;
         }
      }
      return $mac;
   }

   //--------------------------------------------------//
   //----------- Check methods -----------------------//
   //------------------------------------------------//

   /**
    * Check all data to be imported
    * @return nothing
    */
   private function check() {
      //Get search options associated with the injectionClass
      $searchOptions = $this->injectionClass->getOptions();

      foreach ($this->values as $type => $fields) {
         foreach($fields as $field => $value) {
            //Get search option associated with the field
            $option = self::findSearchOption($searchOptions,$field);
            if ($value == self::EMPTY_VALUE && $this->mandatory_fields[$type][$field]) {
               $this->results[self::ACTION_CHECK]['status'] = self::ERROR;
               $this->results[self::ACTION_CHECK][$field] = self::MANDATORY;
            }
            else {
               $check_result = $this->checkType($field,$value,$this->mandatory_fields[$type][$field]);
               $this->results[self::ACTION_CHECK][$field] = $check_result;
               if ($check_result != self::SUCCESS) {
                  $this->results[self::ACTION_CHECK]['status'] = self::ERROR;
               }
            }
         }
      }
   }
   /**
    * Check one data
    *
    * @param the type of data waited
    * @data the data to import
    *
    * @return true if the data is the correct type
    */

   private function checkType($field_name, $data, $mandatory)
   {
      $searchOptions = $this->injectionClass->getOptions();
      $option = self::findSearchOption($searchOptions,$field_name);
      if (!empty($options)) {
         $field_type = (isset($option['checktype'])?$option['checktype']:'text');

         //If no data provided AND this mapping is not mandatory
         if (!$mandatory && ($data == null || $data == "NULL" || $data == EMPTY_VALUE)) {
            return self::SUCCESS;
         }
         else {
            switch ($field_type) {
               case 'text' :
                  if (sizeof($data) > 255) {
                     return self::ERROR_FIELDSIZE_EXCEEDED;
                  }
                  else {
                     return self::SUCCESS;
                  }
               case 'integer' :
                  return (is_numeric($data)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'float':
                  return (PluginDatainjectionCheck::isTrueFloat($data)?self::SUCCESS:
                                                                        self::TYPE_MISMATCH);
               case 'date' :
                  preg_match("/([0-9]{4})[\-]([0-9]{2})[\-]([0-9]{2})/",$data,$regs);
                  return ((count($regs) > 0)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'ip':
                  preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/",
                             $data,$regs);
                  return ((count($regs) > 0)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'mac':
                  preg_match("/([0-9a-fA-F]{2}([:-]|$)){6}$/",$data,$regs);
                  return ((count($regs) > 0)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'itemtype':
                  return (class_exists($data)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'yesno':
                  return (($data == 0 || $data == 1)?self::SUCCESS:self::TYPE_MISMATCH);
               default :
                  //Not a standard check ? Try checks specific to the injection class
                  //Will return SUCCESS if it's not a specific check
                  return $injectionClass->checkType($field_name, $data, $mandatory);
            }
         }
      }
      else {
         return self::SUCCESS;
      }
   }


   //--------------------------------------------------//
   //------ Pre and post injection methods -----------//
   //------------------------------------------------//
   function addNecessaryFields() {
     $itemtype = $this->getItemtype();
     $this->values[$itemtype]['entities_id'] = $this->entity;
   }

   //--------------------------------------------------//
   //-------- Add /Update/Delete methods -------------//
   //------------------------------------------------//

   /**
    * Add object into GLPI
    * @return the injection results
    */
   function addObject() {

      //First : reformat data
      $this->reformat();

      //Second : check data
      $this->check();
      if ($this->results[self::ACTION_CHECK] != self::FAILED) {
         //Third : inject data
         $item = $this->getItemInstance();

         //Add important fields for injection
         $this->addNecessaryFields();

         $tmp = $this->values[get_class($item)];
         logDebug($tmp);
         //Insert data using the standard add() method
         if ($item instanceof CommonDropdown) {
            $newID = $item->import($tmp);
         }
         else {
            $newID = $item->add($tmp);
         }
         $this->results[self::ACTION_INJECT]['status'] = self::SUCCESS;
         $this->results[self::ACTION_INJECT]['type'] = self::IMPORT_ADD;
         $this->results[self::ACTION_INJECT][get_class($item)] = $newID;
      }
      return $this->results;
   }

   /**
    * Update data into GLPI
    */
   function updateObject() {

      //First : reformat data
      $this->reformat();

     //Second : check data
      $this->check();
      if ($this->results[self::ACTION_CHECK] != self::FAILED) {
         //Third : inject data
         $item = $this->getItemInstance();

         //Add important fields for injection
         $this->addNecessaryFields();

         //Insert data using the standard update() method
         $tmp = $this->values[get_class($item)];
         if ($item instanceof CommonDropdown) {
            $newID = $item->import($tmp);
         }
         else {
            $newID = $item->update($tmp);
         }
         $this->results[self::ACTION_INJECT]['status'] = self::SUCCESS;
         $this->results[self::ACTION_INJECT]['type'] = self::IMPORT_UPDATE;
         $this->results[self::ACTION_INJECT][get_class($item)] =
                                                              $this->values[get_class($item)]['id'];
      }
   }


   /**
    * Delete data into GLPI
    */
   function deleteObject() {
      $itemtype = $this->getItemtype();
      $item = new $itemtype();
      if (isset($this->values[$itemtype]['id'])) {
         $this->results[self::ACTION_INJECT]['type'] = self::IMPORT_DELETE;

         if ($item->delete($this->values[$itemtype])) {
            $this->results[self::ACTION_INJECT]['status'] = self::SUCCESS;
         }
         else {
            $this->results[self::ACTION_INJECT]['status'] = self::FAILED;
         }
         $this->results[self::ACTION_INJECT][$itemtype] = $this->values[$itemtype]['id'];
      }
   }
}
?>