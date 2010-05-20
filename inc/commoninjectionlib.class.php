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

   private $optional_infos = array();

   //Injection class to use
   private $injectionClass;

   //Primary type to inject
   private $primary_type;

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
      if (isset($injection_options['optional_data'])) {
         $this->optional_infos = $injection_options['optional_data'];
      }

      //Split $injection_options array and store data into the rights internal arrays
      if (isset($injection_options['formats'])) {
         $this->formats = $injection_options['formats'];
      }
      else {
         $this->formats = array('date_format' => self::DATE_TYPE_YYYYMMDD,
                                'float_format' => FLOAT_TYPE_DOT);
      }

      //Store values to inject
      $this->values = $values;

      //Store injectClass & primary_type
      $this->injectionClass = $injectionClass;
      $this->primary_type = self::getItemtypeByInjectionClass($injectionClass);

      //If entity is given stores it, then use root entity
      if (isset($injection_options['entities_id'])) {
         $this->entity = $injection_options['entities_id'];
      }
      else {
         $this->entity = 0;
      }
   }

   static function getItemtypeInstanceByInjection($injectionClassName) {
      $pattern = "/PluginDatainjection(.*)Injection/";
      if (preg_match($pattern,$injectionClassName,$results)) {
         return new $results[1];
      }
      return false;
   }

   static function getItemtypeByInjection($injectionClassName) {
      $pattern = "/PluginDatainjection(.*)Injection/";
      if (preg_match($pattern,$injectionClassName,$results)) {
         return $results[1];
      }
      return false;
   }

   static function getItemtypeByInjectionClass($injectionClass) {
      return self::getItemtypeByInjection(get_class($injectionClass));
   }

   static function getInjectionClassInstance($itemtype) {
      $injectionClass = 'PluginDatainjection'.ucfirst($itemtype).'Injection';
      return new $injectionClass();
   }

   /**
    * Find and return the right search option
    * @param options the search options array
    * @param lookfor the search option we're looking for
    * @return the search option matching lookfor parameter or false it not found
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
      return self::getItemtypeByInjection($classname);
   }

   /**
    * Get itemtype associated to the injectionClass
    * @return an itemtype
    */
   private function getItemInstance() {
      $classname = get_class($this->injectionClass);
      return self::getItemtypeInstanceByInjection($classname);
   }

   /**
    * Return injection results
    * @return an array which contains the reformat/check/injection logs
    */
   public function getInjectionResults() {
      return $this->results;
   }

   /**
    * Get ID associate to the value from the CSV file is needed (for example for dropdown tables)
    * @return nothing
    */
   private function manageFieldValues() {
      $blacklisted_fields = array('id');
      $searchOptions = $this->injectionClass->getOptions();

      foreach ($this->values as $itemtype => $data) {
         foreach ($data as $field => $value) {
            if (!in_array($field,$blacklisted_fields)) {
               $searchOption = self::findSearchOption($searchOptions,$field);
               $this->getFieldValue($itemtype, $searchOption,$field,$value);
            }
         }
         //TODO : This is ugly, need to find why it adds an empty value to the array
         foreach ($this->values[$itemtype] as $field => $value) {
            if ($field == '') {
               unset($this->values[$itemtype][$field]);
            }
         }
      }
   }

   /**
    * Get the ID associated with a value from the CSV file
    * @param itemtype itemtype of the values to inject
    * @param searchOption option associated with the field to check
    * @param field the field to check
    * @param value the value coming from the CSV file
    * @return nothing
    */
   private function getFieldValue($itemtype, $searchOption, $field, $value) {
      $linkfield = $searchOption['linkfield'];
      if (!isset($searchOption['displaytype'])) {
         $searchOption['displaytype'] = 'text';
      }

      switch ($searchOption['displaytype']) {
         case 'text':
            $this->setValueForItemtype($itemtype,$linkfield,$value);
            break;
         case 'dropdown':
            $tmptype = getItemTypeForTable($searchOption['table']);
            $item = new $tmptype;
            if ($item instanceof CommonDropdown) {
               $id = $item->importExternal($value,
                                           $this->entity,
                                           array(),
                                           '',
                                           $this->rights['add_dropdown']);
            }
            else {
               $id = self::findSingle($item,
                                      $searchOption,
                                      $value);
            }
            $this->setValueForItemtype($itemtype,$linkfield,$id);
            break;
         case 'contact':
            if ($value != self::DROPDOWN_DEFAULT_VALUE) {
               $id = self::findContact($value,$this->entity);
            }
            else {
               $id = self::DROPDOWN_DEFAULT_VALUE;
            }
            $this->setValueForItemtype($itemtype,$linkfield,$id);
            break;
         case 'user':
            if ($value != self::DROPDOWN_DEFAULT_VALUE) {
               $id =  self::findUser($value, $this->entity);
            }
            else {
               $id =  self::DROPDOWN_DEFAULT_VALUE;
            }
            $this->setValueForItemtype($itemtype,$linkfield,$id);
            break;
         case 'multiline_text':
            if ($value != self::EMPTY_VALUE) {
               if (!isset($this->values[$field])) {
                  $message = '';
               }
               $message .= $searchOption['name'] . "=" . $value . "\n";
               $this->setValueForItemtype($itemtype,$linkfield,$message);
            }
            break;
         default:
            $value = $this->injectionClass->getSpecificFieldValue($itemtype,
                                                                  $searchOption,
                                                                  $field,
                                                                  $value);
            if ($value) {
               $this->setValueForItemtype($itemtype,$linkfield,$value);
            }
            break;
         }
      }

   /**
    * Find a user. Look for login OR firstname + lastname OR lastname + firstname
    * @param value the user to look for
    * @param entity the entity where the user should have right
    * @return the user ID if found or ''
    */
   static private function findUser($value,$entity)
   {
      global $DB;

      $sql = "SELECT `id` FROM `glpi_users`
              WHERE LOWER(`name`)=\"".strtolower($value)."\"
                 OR (CONCAT(LOWER(`realname`),' ',LOWER(`firstname`))=\"".strtolower($value)."\"
                  OR CONCAT(LOWER(`firstname`),' ',LOWER(`realname`))=\"".strtolower($value)."\")";
      $result = $DB->query($sql);
      if ($DB->numrows($result)>0)
      {
         //check if user has right on the current entity
         $ID = $DB->result($result,0,"id");
         $entities = getUserEntities($ID,true);
         if (in_array($entity,$entities))
            return $ID;
         else
            return 0;
      }
      else
         return 0;
   }


   /**
    * Find a user. Look for login OR firstname + lastname OR lastname + firstname
    * @param value the user to look for
    * @param entity the entity where the user should have right
    * @return the user ID if found or ''
    */
   static private function findContact($value,$entity)
   {
      global $DB;
      $sql = "SELECT id FROM `glpi_contacts`
              WHERE `entities_id`='".$entity."'
               AND (LOWER(`name`)=\"".strtolower($value)."\"
                  OR (CONCAT(LOWER(`name`),' ',LOWER(`firstname`))=\"".strtolower($value)."\"
                     OR CONCAT(LOWER(`firstname`),' ',LOWER(`name`))=\"".strtolower($value)."\"))";
      $result = $DB->query($sql);
      if ($DB->numrows($result)>0)
      {
         //check if user has right on the current entity
         return $DB->result($result,0,"id");
      }
      else {
         return self::EMPTY_VALUE;
      }
   }

   static private function findSingle($item, $searchOption, $value) {
      global $DB;
      $query = "SELECT `id` FROM `".$item->getTable()."` WHERE 1";
      if ($item->maybeTemplate()) {
         $query.= " `is_template`='0'";
      }
      if ($item->isEntityAssign()) {
         $query.=getEntitiesRestrictRequest(" AND",$item->getTable(),'entities_id',
                                            $value,$item->maybeRecursive());
      }
      $query.= " AND `".$searchOption['field']."`='$value'";
      $result = $DB->query($query);
      if ($DB->numrows($result)>0)
      {
         //check if user has right on the current entity
         return $DB->result($result,0,"id");
      }
      else {
         return self::DROPDOWN_DEFAULT_VALUE;
      }
   }

   /**
    * Get values to inject for an itemtype
    * @param the itemtype
    * @return an array with all values for this itemtype
    */
   function getValuesForItemtype($itemtype) {
      if (isset($this->values[$itemtype])) {
         return $this->values[$itemtype];
      }
      else {
         return false;
      }
   }

   /**
    * Get values to inject for an itemtype
    * @param the itemtype
    * @return an array with all values for this itemtype
    */
   private function getValueByItemtypeAndName($itemtype,$field) {
      $values = $this->getValuesForItemtype($itemtype);
      if ($values) {
         return (isset($values[$field])?$values[$field]:false);
      }
      else {
         return false;
      }
   }

   private function unsetValue($itemtype,$field) {
      if ($this->getValueByItemtypeAndName($itemtype,$field)) {
         unset($this->values[$itemtype][$field]);
      }
   }

   /**
    * Get values to inject for an itemtype
    * @param the itemtype
    * @return an array with all values for this itemtype
    */
   private function setValueForItemtype($itemtype,$field, $value) {
      $this->values[$itemtype][$field] = $value;
   }

   //--------------------------------------------------//
   //----------- Reformat methods --------------------//
   //------------------------------------------------//
   //Several pass are needed to reformat data
   //because dictionnaries need to be process in a specific order

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
      foreach ($this->values as $itemtype => $data) {
         foreach ($data as $field => $value) {
            if ($value == "NULL") {
                  $this->values[$itemtype][$field] = self::EMPTY_VALUE;
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
                        $this->values[$itemtype][$field] = Dropdown::getDropdownName($option['table'],
                                                                                     $dropdownID);
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

      foreach ($this->values as $itemtype => $data) {

         //Get injectionClass associated with the itemtype
         $injectionClass = self::getInjectionClassInstance($itemtype);

         //Get search options associated with the injectionClass
         $searchOptions = $injectionClass->getOptions();

         foreach ($data as $field => $value) {
            //Get search option associated with the field
            $option = self::findSearchOption($searchOptions,$field);

            // Check some types
            switch ($option['checktype'])
            {
               case "date":
                  //If the value is a date, try to reformat it if it's not the good type
                  //(dd-mm-yyyy instead of yyyy-mm-dd)
                  $date = self::reformatDate($value,$this->getDateFormat());
                  $this->setValueForItemtype($itemtype,$field,$date);
                  break;
               case "mac":
                  $this->setValueForItemtype($itemtype,$field,self::reformatMacAddress($value));
                  break;
               case "float":
                  $float = self::reformatFloat($value,$this->getFloatFormat());
                  $this->setValueForItemtype($itemtype,$field,$float);
                  break;
               default:
               break;
            }
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

      foreach ($this->values as $itemtype => $fields) {
         foreach($fields as $field => $value) {
            if (isset($this->mandatory_fields[$itemtype][$field])) {
               //Get search option associated with the field
               $option = self::findSearchOption($searchOptions,$field);
               if ($value == self::EMPTY_VALUE
                     && $this->mandatory_fields[$itemtype][$field]) {
                  $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                  $this->results[self::ACTION_CHECK][$field] = self::MANDATORY;
               }
               else {
                  $check_result = $this->checkType($field,
                                                   $value,
                                                   $this->mandatory_fields[$itemtype][$field]);
                  $this->results[self::ACTION_CHECK][$field] = $check_result;
                  if ($check_result != self::SUCCESS) {
                     $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                  }
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
   private function addNecessaryFields() {
     $this->setValueForItemtype($this->primary_type,'entities_id',$this->entity);
   }

   private function addNeededFields($itemtype) {
      //Add default fields
      $fields = array('entities_id' =>'entities_id',
                      'items_id'    =>'id',
                      'is_recursive'=>'is_recursive');
      foreach ($fields as $new => $field) {
         $val = $this->getValueByItemtypeAndName($this->primary_type,$field);
         if ($val) {
            $this->setValueForItemtype($itemtype,$new,$val);
         }
      }
      $this->setValueForItemtype($itemtype,'itemtype',$this->primary_type);

      $itemClass = self::getInjectionClassInstance($itemtype);
      $specific_fields = $itemClass->addSpecificNeededFields($this->primary_type,$this->values);
      foreach ($specific_fields as $field => $value) {
         $this->setValueForItemtype($itemtype,$field,$value);
      }
   }


   //--------------------------------------------------//
   //-------- Add /Update/Delete methods -------------//
   //------------------------------------------------//
   private function processAddOrUpdate() {
      $process = false;

      //Check is data to be inject still exists in DB (update) or not (add)
      $this->dataAlreadyInDB($this->injectionClass);

      //No item found in DB
      if($this->getValueByItemtypeAndName($this->primary_type,'id')
            == self::ITEM_NOT_FOUND) {
         //Can add item ?
         if ($this->rights['can_add']) {
            $process = true;
            $add = true;
            $this->unsetValue($this->primary_type,'id');
         }
         else {
               $this->results[self::ACTION_INJECT]['status'] =
                                         self::ERROR_CANNOT_IMPORT;
               $this->results[self::ACTION_INJECT]['type'] =
                                         self::IMPORT_ADD;
         }
      }
      //Item found in DB
      else {
         if ($this->rights['can_update']) {
            $process = true;
            $add = false;
         }
         else {
               $this->results[self::ACTION_INJECT]['status'] =
                                         self::ERROR_CANNOT_UPDATE;
               $this->results[self::ACTION_INJECT]['type'] =
                                         self::IMPORT_UPDATE;
         }
      }
      if ($process) {
         //Get real value for fields (ie dropdown, etc)
         $this->manageFieldValues();

         //First : reformat data
         $this->reformat();

         //Second : check data
         $this->check();
         if ($this->results[self::ACTION_CHECK] != self::FAILED) {

            //Third : inject data
            $item = $this->getItemInstance();

            //Add important fields for injection
            $this->addNecessaryFields();

            $this->addOptionalInfos();

            $values = $this->getValuesForItemtype($this->primary_type);
            $newID = $this->effectiveAddOrUpdate($add,$item,$values);

            if (!$newID) {
               $this->results[self::ACTION_INJECT]['status'] = self::FAILED;
               $this->results[self::ACTION_INJECT]['type'] = ($add?self::IMPORT_ADD
                                                                     :self::IMPORT_UPDATE);
            }
            else {
               $this->results[self::ACTION_INJECT]['status'] = self::SUCCESS;
               $this->results[self::ACTION_INJECT]['type'] = ($add?self::IMPORT_ADD
                                                                     :self::IMPORT_UPDATE);
               $this->results[self::ACTION_INJECT][get_class($item)] = $newID;

               //Process other types
               foreach ($this->values as $itemtype => $data) {
                  //Do not process primary_type
                  if ($itemtype != get_class($item)) {
                    $injectionClass = self::getInjectionClassInstance($itemtype);
                    $item = new $itemtype();
                    $this->dataAlreadyInDB($injectionClass);
                    if($this->getValueByItemtypeAndName($itemtype,'id') == self::ITEM_NOT_FOUND) {
                       $add = true;
                       $this->unsetValue($itemtype,'id');
                    }
                    else {
                       $add = false;
                    }
                    $this->addNeededFields($itemtype);
                    $values = $this->getValuesForItemtype($itemtype);
                    $tmpID = $this->effectiveAddOrUpdate($add,$item,$values);
                  }
               }
            }
         }
      }
      return $this->results;
   }

   private function effectiveAddOrUpdate($add=true, $item, $values) {

      //Insert data using the standard add() method
      if ($item instanceof CommonDropdown) {
         $newID = $item->import($values);
      }
      else {
         if ($add) {
            logDebug($values);
            $newID = $item->add($values);
            $this->setValueForItemtype(get_class($item),'id',$newID);
         }
         else {
            logDebug($values,$this->values[$this->primary_type]);
            $newID = $item->update($values);
         }
      }
      return $newID;
   }
   /**
    * Add object into GLPI
    * @return the injection results
    */
   function addObject() {
      $this->processAddOrUpdate();
   }

   /**
    * Update data into GLPI
    */
   function updateObject() {
      $this->processAddOrUpdate();
   }


   /**
    * Delete data into GLPI
    */
   function deleteObject() {
      $itemtype = $this->getItemtype();
      $item = new $itemtype();
      if ($this->getValueByItemtypeAndName($itemtype,'id')) {
         $this->results[self::ACTION_INJECT]['type'] = self::IMPORT_DELETE;

         if ($item->delete($this->values[$itemtype])) {
            $this->results[self::ACTION_INJECT]['status'] = self::SUCCESS;
         }
         else {
            $this->results[self::ACTION_INJECT]['status'] = self::FAILED;
         }
         $this->results[self::ACTION_INJECT][$itemtype]
                                                = $this->getValueByItemtypeAndName($itemtype,'id');
      }
   }

   private function addOptionalInfos() {
      foreach ($this->optional_infos as $itemtype => $data) {
         foreach ($data as $field => $value) {
            $this->setValueForItemtype($itemtype,$field,$value);
         }
      }
   }


   /**
    * Function to check if the datas to inject already exists in DB
    * @param pe the type of datas to inject
    */
   private function dataAlreadyInDB($injectionClass) {
      global $DB;
      $where = "";
      $itemtype = self::getItemtypeByInjectionClass($injectionClass);

      if ($injectionClass->maybeDeleted()) {
         $where .= " AND `is_deleted`='0' ";
      }
      if ($injectionClass->maybeTemplate()) {
         $where .= " AND `is_template`='0' ";
      }
      if ($injectionClass->maybeRecursive()) {
         $where_entity = getEntitiesRestrictRequest(" AND",
                                                    $injectionClass->getTable(),
                                                    "entities_id",
                                                    $this->getValueByItemtypeAndName($itemtype,
                                                                                     'entities_id'),
                                                    true);
      } elseif($injectionClass->isEntityAssign()) {
         $where_entity = " AND `entities_id`='" .
                           $this->getValueByItemtypeAndName($itemtype,'entities_id')."'";
      }
      else {
         $where_entity = "";
      }

      //Add mandatory fields to the query only if it's the primary_type to be injected
      if ($itemtype == $this->primary_type) {
         $searchOptions = $injectionClass->getOptions();
         foreach ($this->mandatory_fields[$itemtype] as $field => $is_mandatory) {
            if ($is_mandatory) {
               $option = self::findSearchOption($searchOptions,
                                                                                 $field);
               $where .= " AND `" . $field . "`='";
               $where .= $this->getValueByItemtypeAndName($itemtype,$field) . "'";
            }
         }
      }
      else {
         $where.= " AND `itemtype`='".$this->getValueByItemtypeAndName($itemtype,'itemtype')."'";
         $where.= " AND `items_id`='".$this->getValueByItemtypeAndName($itemtype,'items_id')."'";
      }

      //Add sql request checks specific to this itemtype
      $options['model'] = $this->checks;
      $options['itemtype'] = $itemtype;
      $where .= $injectionClass->checkPresent($this->getValuesForItemtype($itemtype), $options);

      $sql  = "SELECT `id` FROM `" . $injectionClass->getTable()."`";
      $sql .= " WHERE 1 " . $where_entity . " " . $where;

      $result = $DB->query($sql);
      if ($DB->numrows($result) > 0) {
         $this->values[$itemtype]['id'] = $DB->result($result,0,'id');
      }
      else {
         $this->values[$itemtype]['id'] = self::ITEM_NOT_FOUND;
      }
   }

}
?>