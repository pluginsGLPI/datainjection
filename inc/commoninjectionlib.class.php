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

   //List of fields which can agregate more than one value (type multiline_text)
   private $severalvalues_fields = array();

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
   const SUCCESS                        = 10; //Injection OK
   const FAILED                         = 11; //Error during injection
   const WARNING                        = 12; //Injection ok but partial

   //Field check return constants
   const ALREADY_EXISTS                 = 21;
   const TYPE_MISMATCH                  = 22;
   const MANDATORY                      = 23;
   const ITEM_NOT_FOUND                 = 24;

   //Injection Message
   const ERROR_IMPORT_ALREADY_IMPORTED  = 30;
   const ERROR_CANNOT_IMPORT            = 31;
   const ERROR_CANNOT_UPDATE            = 32;
   const WARNING_NOTFOUND               = 33;
   const WARNING_USED                   = 34;
   const WARNING_SEVERAL_VALUES_FOUND   = 35;
   const WARNING_ALREADY_LINKED         = 36;
   const ERROR_FIELDSIZE_EXCEEDED       = 37;
   const WARNING_PARTIALLY_IMPORTED     = 38;

   //Empty values
   const EMPTY_VALUE                    = '';
   const DROPDOWN_EMPTY_VALUE           = 0;

   //Format constants
   const FLOAT_TYPE_COMMA               = 0; //xxxx,xx
   const FLOAT_TYPE_DOT                 = 1; //xxxx.xx
   const FLOAT_TYPE_DOT_AND_COM         = 2; //xx,xxx.xx

   //Date management constants
   const DATE_TYPE_DDMMYYYY             = "dd-mm-yyyy";
   const DATE_TYPE_MMDDYYYY             = "mm-dd-yyyy";
   const DATE_TYPE_YYYYMMDD             = "yyyy-mm-dd";

   //Port unicity constants
   const UNICITY_NETPORT_LOGICAL_NUMBER            = 0;
   const UNICITY_NETPORT_NAME                      = 1;
   const UNICITY_NETPORT_MACADDRESS                = 2;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME       = 3;
   const UNICITY_NETPORT_LOGICAL_NUMBER_MAC        = 4;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC   = 5;

   //Field status must evolve when ticket #2216 will be resolved
   const FIELD_INJECTABLE               = 1;
   const FIELD_NOT_INJECTABLE           = 0;
   const FIELD_VIRTUAL                  = 2;

   /**
    * Set default values for injection parameters
    * @return nothing
    */
   function setDefaultValues() {
      $this->checks = array('ip'                       => false,
                            'mac'                      => false,
                            'integer'                  => false,
                            'yes'                      => false,
                            'bool'                     => false,
                            'date'                     => false,
                            'float'                    => false,
                            'string'                   => false,
                            'right_r'                  => false,
                            'right_rw'                 => false,
                            'interface'                => false,
                            'auth_method'              => false,
                            'port_unicity'             => false);

      //Rights options
      $this->rights = array('add_dropdown'             => false,
                            'overwrite_notempty_fields'=> false,
                            'can_add'                  => false,
                            'can_update'               => false,
                            'can_delete'               => false);

      //Field format options
      $this->formats = array('date_format'             => self::DATE_TYPE_YYYYMMDD,
                             'float_format'            => self::FLOAT_TYPE_COMMA);
   }

   /**
    * Constructor : store all needed options into the library
    * @param injectionClass class which represents the itemtype to injection
    *                         (in 0.80, will be directly the itemtype class)
    * @param values values to injection into GLPI
    * @param injection_options options that can be used during the injection (maybe an empty array)
    * @return nothing
    */
   function __construct($injectionClass, $values = array(), $injection_options = array()) {
      $this->setDefaultValues();

      if (isset($injection_options['checks'])) {
         foreach ($injection_options['checks'] as $key => $value) {
            $this->checks[$key] = $value;
         }
      }

      if (isset($injection_options['checks'])) {
         foreach ($injection_options['checks'] as $key => $value) {
            $this->checks[$key] = $value;
         }
      }
      if (isset($injection_options['rights'])) {
         foreach ($injection_options['rights'] as $key => $value) {
            $this->rights[$key] = $value;
         }
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
      } else {
         $this->formats = array('date_format' => self::DATE_TYPE_YYYYMMDD,
                                'float_format' => self::FLOAT_TYPE_DOT);
      }

      //Store values to inject
      $this->values = $values;

      //Store injectClass & primary_type
      $this->injectionClass = $injectionClass;
      $this->primary_type = self::getItemtypeByInjectionClass($injectionClass);

      //If entity is given stores it, then use root entity
      if (isset($injection_options['entities_id'])) {
         $this->entity = $injection_options['entities_id'];
      } else {
         $this->entity = 0;
      }
   }

   /**
    * Check and add fields for itemtype which depend on other itemtypes
    * (for example SoftwareLicense needs to be linked to a Software)
    * @param injectionClass class to use for injection
    */
   function areTypeMandatoryFieldsOK($injectionClass) {
      $itemtype = self::getItemtypeByInjectionClass($injectionClass);

      //Add more values needed for mandatory fields management
      if (method_exists($injectionClass,'getValueForAdditionalMandatoryFields')) {
         $this->values = $injectionClass->getValueForAdditionalMandatoryFields($this->values);
      }

      //Add new mandatory fields to check, needed by other itemtypes to inject
      if (method_exists($injectionClass,'addSpecificMandatoryFields')) {
         $fields = $injectionClass->addSpecificMandatoryFields();
         foreach ($fields as $field) {
            $this->mandatory_fields[$itemtype] = $field;
         }
      }

      $status_check = true;
      foreach ($this->mandatory_fields[$itemtype] as $field => $value) {
         //Get value associated with the mandatory field
         $value = $this->getValueByItemtypeAndName($itemtype,$field);

         //Get search option associated with the mandatory field
         $option = self::findSearchOption($injectionClass->getOptions(),$field);

         //If field not defined, or if value is the dropdown's default value
         //If no value found or value is 0 and field is a dropdown,
         //then mandatory field management failed
         //if (!$value) {
         if ($value == false
               || ($value == self::DROPDOWN_EMPTY_VALUE
                     && self::isFieldADropdown($option['displaytype']))) {
            $status_check = false;
            $this->results[self::ACTION_CHECK][] = array(self::MANDATORY,$option['name']);
         }
      }
      return $status_check;
   }

   /**
    * Check if a field type represents a dropdown or not
    * @param field_type the type of field
    * @return true if it's a dropdown type, false if not
    */
   static function isFieldADropdown($field_type) {
      if (!in_array($field_type, array('text','multiline_text','date'))) {
         return true;
      } else {
         return false;
      }
   }
   static function getItemtypeInstanceByInjection($injectionClassName) {
      $injection = self::getItemtypeByInjectionClass(new $injectionClassName);
      return new $injection;
   }

   static function getItemtypeByInjection($injectionClassName) {
      return self::getItemtypeByInjectionClass(new $injectionClassName);
   }

   static function getItemtypeByInjectionClass($injectionClass) {
      return getItemTypeForTable($injectionClass->getTable());
   }

   static function getInjectionClassInstance($itemtype) {
      if (!isPluginItemType($itemtype)) {
         $injectionClass = 'PluginDatainjection'.ucfirst($itemtype).'Injection';
      } else {
         $injectionClass = ucfirst($itemtype).'Injection';
      }
      return new $injectionClass();
   }

   static function getBlacklistedOptions() {
      //2 : id, 19 : date_mod
      return array(2, 19);
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
         if (isset($option['injectable'])
               && $option['injectable'] == self::FIELD_INJECTABLE
                  && isset($option['linkfield'])
                     && $option['linkfield'] == $lookfor) {
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
      } else {
         return true;
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
    * @param data to be added (true) or updaes(false)
    * @return nothing
    */
   private function manageFieldValues() {
      $blacklisted_fields = array('id');

      foreach ($this->values as $itemtype => $data) {
         $injectionClass = self::getInjectionClassInstance($itemtype);
         $searchOptions = $injectionClass->getOptions($this->primary_type);
         foreach ($data as $field => $value) {
            if (!in_array($field,$blacklisted_fields)) {
               $searchOption = self::findSearchOption($searchOptions,$field);
               $this->getFieldValue($injectionClass,
                                    $itemtype,
                                    $searchOption,
                                    $field,
                                    $value);
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
    * @param add is insertion (true) or update (false)
    * @return nothing
    */
   private function getFieldValue($injectionClass, $itemtype, $searchOption, $field, $value,$add=true) {
      if(isset($searchOption['storevaluein'])) {
         $linkfield = $searchOption['storevaluein'];
      } else {
         $linkfield = $searchOption['linkfield'];
      }

      switch ($searchOption['displaytype']) {
         case 'decimal':
         case 'text':
            $this->setValueForItemtype($itemtype,$linkfield,$value);
            break;
         case 'dropdown':
         case 'relation':
            $tmptype = getItemTypeForTable($searchOption['table']);
            $item = new $tmptype();
            if ($item instanceof CommonDropdown) {
               if ($item->canCreate() && $this->rights['add_dropdown']) {
                  $canadd = true;
               } else {
                  $canadd = false;
               }
               $id = $item->importExternal($value,
                                           $this->entity,
                                           $this->addExternalDropdownParameters($itemtype),
                                           '',
                                           $canadd);
            } else {
               $id = self::findSingle($item,
                                      $searchOption,
                                      $this->entity,
                                      $value);
            }
            // Use EMPTY_VALUE for Mandatory field check
            $this->setValueForItemtype($itemtype, $linkfield, ($id>0 ? $id : self::EMPTY_VALUE));
            if ($id <= 0) {
                  $this->results['status'] = self::WARNING;
                  $this->results[self::ACTION_CHECK]['status']   = self::WARNING;
                  $this->results[self::ACTION_CHECK][] = array(self::WARNING_NOTFOUND, $searchOption['name']."='$value'");
            }
            break;
         case 'template':
            $id = self::getTemplateIDByName($itemtype, $value);
            if ($id) {
               //Template id is stored into the item's id : when adding the object
               //glpi will understand that it needs to take fields from the template
               $this->setValueForItemtype($itemtype,'_oldID',$id);
            }
         case 'contact':
            if ($value != self::DROPDOWN_EMPTY_VALUE) {
               $id = self::findContact($value,$this->entity);
            } else {
               $id = self::DROPDOWN_EMPTY_VALUE;
            }
            $this->setValueForItemtype($itemtype,$linkfield,$id);
            break;
         case 'user':
            if ($value != self::DROPDOWN_EMPTY_VALUE) {
               $id =  self::findUser($value, $this->entity);
            } else {
               $id =  self::DROPDOWN_EMPTY_VALUE;
            }
            $this->setValueForItemtype($itemtype,$linkfield,$id);
            break;
/*
         case 'multiline_text':
            $message = '';
            if ($value != self::EMPTY_VALUE) {
               //If update : do not overwrite the existing field in DB, append at the end !
               //if ($add == self::IMPORT_UPDATE) {
                  $message = $this->values[$itemtype][$linkfield]."\n";
               //}

               $message .= $value;
               $this->setValueForItemtype($itemtype,$linkfield,$message);
            }
            break;
*/
         default:
            if (method_exists($injectionClass,'getSpecificFieldValue')) {
               $id = $injectionClass->getSpecificFieldValue($itemtype,
                                                            $searchOption,
                                                            $field,
                                                            $this->values);
               $this->setValueForItemtype($itemtype,$linkfield,$id);
            } else {
               $this->setValueForItemtype($itemtype,$linkfield,$value);
            }
            break;
         }
      }

   /**
    * Add additional parameters needed for dropdown import
    * @param itemtype dropdrown's itemtype
    * @return an array with additional options to be added
    */
   private function addExternalDropdownParameters($itemtype) {

      $external = array();
      $values = $this->getValuesForItemtype($itemtype);
      $toadd = array('manufacturers_id' => 'manufacturer');
      foreach ($toadd as $field => $addvalue) {
         if (isset($values[$field])) {
            switch ($addvalue) {
               case 'manufacturer':
                  if (intval($values[$field])>0) {
                     $external[$addvalue]
                        = mysql_real_escape_string(Dropdown::getDropdownName('glpi_manufacturers',$values[$field]));
                     break;
                  }
               default:
                  $external[$addvalue] = $values[$field];
            }
         } else {
            $external[$addvalue] = '';
         }
      }
      return $external;
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
         if (in_array($entity,$entities)) {
            return $ID;
         } else {
            return self::DROPDOWN_EMPTY_VALUE;
         }
      } else {
         return self::DROPDOWN_EMPTY_VALUE;
      }
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
      } else {
         return self::DROPDOWN_EMPTY_VALUE;
      }
   }

   /**
    * Find id for a single type
    * @param item the ComonDBTM item representing an itemtype
    * @param searchOption searchOption related to the item
    * @param entity the current entity
    * @param value the name of the item for which id must be returned
    * @return the id of the item found
    */
   static private function findSingle($item, $searchOption, $entity, $value) {
      global $DB;

      //List of objects that should inherit from CommonDropdown...
      //TODO : not needed in 0.80
      $shouldbetropdowns = array('Budget');

      $query = "SELECT `id` FROM `".$item->getTable()."` WHERE 1";
      if ($item->maybeTemplate()) {
         $query.= " AND `is_template`='0'";
      }
      if ($item->isEntityAssign()) {
         $query.=getEntitiesRestrictRequest(" AND",$item->getTable(),'entities_id',
                                            $entity,$item->maybeRecursive());
      }
      $query.= " AND `".$searchOption['field']."`='$value'";
      $result = $DB->query($query);
      if ($DB->numrows($result)>0) {
         //check if user has right on the current entity

         return $DB->result($result,0,"id");
      } else {
         $id = self::DROPDOWN_EMPTY_VALUE;
         if (in_array(get_class($item),$shouldbetropdowns)) {
            $id = $item->add(array($searchOption['field']=>$value));
         }
         return $id;
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
      } else {
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
      } else {
         return false;
      }
   }

   private function unsetValue($itemtype,$field) {
      if ($this->getValueByItemtypeAndName($itemtype,$field)) {
         unset($this->values[$itemtype][$field]);
      }
   }

   /**
    * Set values to inject for an itemtype
    *
    * @param $itemtype
    * @param $field name
    * @param $value of the field
    * @param $fromdb boolean
    */
   private function setValueForItemtype($itemtype, $field, $value, $fromdb=false) {

      // TODO awfull hack, text ftom CSV set more than once, so check if "another" value
      if (isset($this->values[$itemtype][$field]) && $this->values[$itemtype][$field]!=$value) {
         // Data set twice (probably CSV + Additional info)
         $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($itemtype);
         $option = PluginDatainjectionCommonInjectionLib::findSearchOption($injectionClass->getOptions($itemtype), $field);

         if (isset($option['displaytype']) && $option['displaytype']=='multiline_text') {
            if ($fromdb) {
               $this->values[$itemtype][$field] = $value."\n".$this->values[$itemtype][$field];
            } else {
               $this->values[$itemtype][$field] = $this->values[$itemtype][$field]."\n".$value;
            }
         } else  if (!$fromdb) { // CSV value override DB value
            $this->values[$itemtype][$field] = $value;
         }
      } else { // First value
         $this->values[$itemtype][$field] = $value;
      }
   }

   /**
    * Get a template name by giving his ID
    * @param itemtype the objet's type
    * @param id the template's id
    * @return name of the template or false is no template found
    */
   static private function getTemplateIDByName($itemtype, $name) {
      $item = new $itemtype();
      $query = "SELECT `".getTableForItemType($itemtype)."`
                  WHERE `is_template`='1'
                     AND `template_name`='$name'";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         return $DB->result($result,0,'id');
      } else {
         return false;
      }
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

      //Browse all fields & values
      foreach ($this->values as $itemtype => $data) {

         $injectionClass = self::getInjectionClassInstance($itemtype);
         //Get search options associated with the injectionClass
         $searchOptions = $injectionClass->getOptions($this->primary_type);

         foreach ($data as $field => $value) {
            if ($value && $value == "NULL") {
               $this->values[$itemtype][$field] = self::EMPTY_VALUE;
            } else {
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
                     } else {
                        $this->results['status'] = self::WARNING;
                        $this->results[$field]   = self::WARNING_NOTFOUND;
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
      //Specific reformat action is itemtype needs it
      if (method_exists($this->injectionClass,'reformat')) {
         $this->injectionClass->reformat($this->values);
      }
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
         $searchOptions = $injectionClass->getOptions($this->primary_type);

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

   /**
    * Perform field reformat (if needed)
    * Composed of 3 passes (one is optional, and depends on the itemtype to inject)
    * @return nothing
    */
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
      if ($value == self::EMPTY_VALUE) {
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
            $new_date=preg_replace('/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/',
                                   '\1-\2-\3',
                                   $original_date);
         break;
         case self::DATE_TYPE_DDMMYYYY:
            $new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/',
                                   '\3-\2-\1',
                                   $original_date);
         break;
         case self::DATE_TYPE_MMDDYYYY:
            $new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/',
                                   '\3-\1-\2',
                                   $original_date);
         break;
      }

      if (preg_match('/[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}/',$new_date)) {
         return $new_date;
      } else {
         return $original_date;
      }
   }

   /**
    * Reformat mac adress if mac doesn't contains : or - as seperator
    * @param mac the original mac address
    * @return the mac address modified, if needed
    */
   static private function reformatMacAddress($mac)
   {
      $pattern = "/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})";
      $pattern.="([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/";
      preg_match($pattern,$mac,$results);
      if (count($results) > 0) {
         $mac="";
         $first=true;
         unset($results[0]);
         foreach($results as $result) {
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

      $continue = true;
      foreach ($this->values as $itemtype => $fields) {

         $injectionClass = self::getInjectionClassInstance($itemtype);
         //Get search options associated with the injectionClass
         $searchOptions = $injectionClass->getOptions($this->primary_type);

         foreach($fields as $field => $value) {
            if ($continue) {
               if (isset($this->mandatory_fields[$itemtype][$field])) {
                  //Get search option associated with the field
                  $option = self::findSearchOption($searchOptions,$field);
                  if ($value == self::EMPTY_VALUE
                        && $this->mandatory_fields[$itemtype][$field]) {
                     $this->results['status'] = self::FAILED;
                     $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                     $this->results[self::ACTION_CHECK][] = array(self::MANDATORY,$field);
                     $continue = false;
                  } else {
                     $check_result = $this->checkType($injectionClass,
                                                      $option,
                                                      $field,
                                                      $value,
                                                      $this->mandatory_fields[$itemtype][$field]);
                     $this->results[self::ACTION_CHECK][] = array($check_result,$field."='$value'");

                     if ($check_result != self::SUCCESS) {
                        $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                        $this->results['status'] = self::FAILED;
                        $continue = false;
                     }
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
   private function checkType($injectionClass, $option, $field_name, $data, $mandatory)
   {
      if (!empty($option)) {
         $field_type = (isset($option['checktype'])?$option['checktype']:'text');
         //If no data provided AND this mapping is not mandatory
         if (!$mandatory && ($data == null || $data == "NULL" || $data == self::EMPTY_VALUE)) {
            return self::SUCCESS;
         }
         else {
            switch ($field_type) {
               case 'text' :
                  if (sizeof($data) > 255) {
                     return self::ERROR_FIELDSIZE_EXCEEDED;
                  } else {
                     return self::SUCCESS;
                  }
               case 'decimal' :
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
               case 'bool':
                  return (($data == 0 || $data == 1)?self::SUCCESS:self::TYPE_MISMATCH);
               case 'date':
                  //TODO : check date !!
                  return $data;
               default :
                  //Not a standard check ? Try checks specific to the injection class
                  //Will return SUCCESS if it's not a specific check
                  if (method_exists($injectionClass,'checkType')) {
                     return $injectionClass->checkType($field_name, $data, $mandatory);
                  } else {
                     return self::SUCCESS;
                  }

            }
         }
      } else {
         return self::SUCCESS;
      }
   }


   //--------------------------------------------------//
   //------ Pre and post injection methods -----------//
   //------------------------------------------------//

   /**
    * Add fields needed for all type injection
    * @return nothing
    */
   private function addNecessaryFields() {
     $this->setValueForItemtype($this->primary_type,'entities_id',$this->entity);
   }

   /**
    * Add fields needed to inject and itemtype
    * @param injectionClass class which represents the object to inject
    * @param itemtype the itemtype to inject
    * @return nothing
    */
   private function addNeededFields($injectionClass, $itemtype) {

      //Add itemtype
      $this->setValueForItemtype($itemtype,'itemtype',$this->primary_type);

      //Add primary item's id
      $id = $this->getValueByItemtypeAndName($this->primary_type,'id');
      $this->setValueForItemtype($itemtype,'items_id',$id);

      //Add entities_id if itemtype can be assigned to an entity
      if ($injectionClass->isEntityAssign()) {
         $entities_id = $this->getValueByItemtypeAndName($this->primary_type,'entities_id');
         $this->setValueForItemtype($itemtype,'entities_id',$entities_id);
      }

      //Add is_recursive if itemtype can be assigned recursive
      if ($injectionClass->isRecursive()) {
         $recursive = $this->getValueByItemtypeAndName($this->primary_type,'is_recursive');
         $this->setValueForItemtype($itemtype,'is_recursive',$recursive);
      }

      if (method_exists($injectionClass,'addSpecificNeededFields')) {
         $specific_fields = $injectionClass->addSpecificNeededFields($this->primary_type,
                                                                     $this->values);
         foreach ($specific_fields as $field => $value) {
            $this->setValueForItemtype($itemtype,$field,$value);
         }
      }
   }


   //--------------------------------------------------//
   //-------- Add /Update/Delete methods -------------//
   //------------------------------------------------//

   /**
    * Process of inject data into GLPI
    * @return an array which contains the injection results
    */
   public function processAddOrUpdate() {
      $process = false;
      $add = true;
      logDebug("processAddOrUpdate(), start with", $this->values);

      // Initial value, will be change when problem
      $this->results['status'] = self::SUCCESS;
      $this->results[self::ACTION_CHECK]['status'] = self::SUCCESS;

      //Manage fields belonging to relations between tables
      $this->manageRelations();

      //Get real value for fields (ie dropdown, etc)
      $this->manageFieldValues();

      //Check if the type to inject requires additional fields
      //(for example to link it with another type)
      if (!$this->areTypeMandatoryFieldsOK($this->injectionClass)) {
         $process = false;
         $this->results['status'] = self::FAILED;
         $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
      } else {
         //Check is data to be inject still exists in DB (update) or not (add)
         $this->dataAlreadyInDB($this->injectionClass, $this->primary_type);

         $process = true;
         //No item found in DB
         if($this->getValueByItemtypeAndName($this->primary_type,'id') == self::ITEM_NOT_FOUND) {
            //Can add item ?
            if ($this->rights['can_add']) {
               $add = true;
               $this->unsetValue($this->primary_type,'id');
               $this->results['type'] = self::IMPORT_ADD;
            } else {
                  $process = false;
                  $this->results['status'] = self::ERROR_CANNOT_IMPORT;
                  $this->results['type'] = self::IMPORT_ADD;
            }
         } else { //Item found in DB
            if ($this->rights['can_update']) {
               $add = false;
               $this->results['type'] = self::IMPORT_UPDATE;
            } else {
                  $process = false;
                  $this->results['status'] = self::ERROR_CANNOT_UPDATE;
                  $this->results['type'] = self::IMPORT_UPDATE;
                  $this->results[$this->primary_type] =
                                          $this->getValueByItemtypeAndName($this->primary_type,'id');
            }
         }
      }

      if ($process) {

         //First : reformat data
         $this->reformat();

         //Second : check data
         $this->check();
         if ($this->results['status'] != self::FAILED) {

            //Third : inject data
            $item = $this->getItemInstance();

            //Add important fields for injection
            $this->addNecessaryFields();

            //Add aditional infos
            $this->addOptionalInfos();

            //Manage template only when adding an item
            if ($this->results['type'] == self::IMPORT_ADD) {
               //If needed, manage templates
               $this->addTemplateFields($this->primary_type);
            }

            $values = $this->getValuesForItemtype($this->primary_type);
            $newID  = $this->effectiveAddOrUpdate($this->injectionClass,$add,$item,$values);
            if (!$newID) {
               $this->results['status'] = self::WARNING;
            } else {
               //If type needs it : process more data after type import
               $this->processAfterInsertOrUpdate();
               //$this->results['status'] = self::SUCCESS;
               $this->results[get_class($item)] = $newID;

               //Process other types
               foreach ($this->values as $itemtype => $data) {
                  //Do not process primary_type
                  if ($itemtype != get_class($item)) {
                     $injectionClass = self::getInjectionClassInstance($itemtype);
                     $item = new $itemtype();

                     $this->addNeededFields($injectionClass, $itemtype);
                     $this->dataAlreadyInDB($injectionClass,$itemtype);

                     if($this->getValueByItemtypeAndName($itemtype,'id') == self::ITEM_NOT_FOUND) {
                        $add = true;
                        $this->unsetValue($itemtype,'id');
                     } else {
                        $add = false;
                     }
                     $values = $this->getValuesForItemtype($itemtype);
                     $tmpID = $this->effectiveAddOrUpdate($injectionClass,$add,$item,$values);
                  }
               }
            }
         }
      }
      return $this->results;
   }

   /**
    * Perform data injection into GLPI DB
    * @param injectionClass class which represents the object to inject
    * @param add true to insert an object, false to update an existing object
    * @param item the CommonDBTM object representing the itemtype to inject
    * @param values the values to inject
    * @return the id of the object added or updated
    */
   private function effectiveAddOrUpdate($injectionClass, $add=true, $item, $values) {
      //Insert data using the standard add() method
      $toinject = array();
      $options = $injectionClass->getOptions();
      foreach ($values as $key => $value) {
         $option = self::findSearchOption($options, $key);
         if ($option['checktype'] != self::FIELD_VIRTUAL) {
            $toinject[$key] = $value;
         }
      }

      if ($item instanceof CommonDropdown && $add) {
         $newID = $item->import($toinject);
      } else {
         if ($add) {
            if ($newID = $item->add($toinject)) {
               $this->setValueForItemtype(get_class($item),'id',$newID);
               self::logAddOrUpdate($item, $add);
            }
         } else {
            if ($item->update($toinject)) {
               $newID = $toinject['id'];
               self::logAddOrUpdate($item, $add);
            }
         }
      }
      return $newID;
   }

   /**
    * Delete data into GLPI
    * @return nothing
    */
   function deleteObject() {
      $itemtype = $this->getItemtype();
      $item = new $itemtype();
      if ($this->getValueByItemtypeAndName($itemtype,'id')) {
         $this->results['type'] = self::IMPORT_DELETE;

         if ($item->delete($this->values[$itemtype])) {
            $this->results['status'] = self::SUCCESS;
         } else {
            $this->results['status'] = self::FAILED;
         }
         $this->results[$itemtype] = $this->getValueByItemtypeAndName($itemtype,'id');
      }
   }

   /**
    * Add optional informations filled by the user
    * @return nothing
    */
   private function addOptionalInfos() {
      foreach ($this->optional_infos as $itemtype => $data) {
         foreach ($data as $field => $value) {
            //Exception for template management
            //We've got the template id, not let's add the template name
            if($field == 'templates_id') {
               $item = new $itemtype();
               $item->getFromDB($value);
               $this->setValueForItemtype($itemtype,'template_name',$item->fields['template_name']);
               $this->setValueForItemtype($itemtype,'_oldID',$value);
            } else {
               $this->setValueForItemtype($itemtype,$field,$value);
            }
         }
      }
   }

   /**
    * Manage fields tagged as relations
    * @return nothing
    */
   private function manageRelations() {
      foreach ($this->values as $itemtype => $data) {
         $injectionClass = self::getInjectionClassInstance($itemtype);
         //Get search options associated with the injectionClass
         $searchOptions = $injectionClass->getOptions($this->primary_type);

         foreach ($searchOptions as $id => $option) {
            //If it's a relation
            if ($option['displaytype'] == 'relation'
                  && isset($this->values[$itemtype][$option['linkfield']])) {
               //Get the relation object associated with the field
               //Add a new array for the relation object
               $value = $this->getValueByItemtypeAndName($itemtype,$option['linkfield']);
               $this->getFieldValue(null, $option['relationclass'],
                                    $option,$option['linkfield'],$value,true);

               //Remove the old option
               $this->unsetValue($itemtype,$option['linkfield']);
            }
         }
      }
   }

   /**
    * Function to check if the datas to inject already exists in DB
    * @param class which represents type to inject
    * @param itemtype the itemtype to inject
    * @return nothing
    */
   private function dataAlreadyInDB($injectionClass, $itemtype) {
      global $DB;
      $where = "";
      $continue = true;

      $searchOptions = $injectionClass->getOptions($this->primary_type);

      //Add sql request checks specific to this itemtype
      $options['checks']       = $this->checks;
      $options['itemtype']     = $this->primary_type;

      //If injectionClass has a method to check if needed parameters are present
      $values = $this->getValuesForItemtype($itemtype);
      if (method_exists($injectionClass,'checkParameters')) {
         $continue = $injectionClass->checkParameters($values,$options);
      }
      //Needed parameters are not present : not found
      if (!$continue) {
         $this->values[$itemtype]['id'] = self::ITEM_NOT_FOUND;
      } else {
         $sql  = "SELECT * FROM `" . $injectionClass->getTable()."`";

         $item = new $itemtype;
         //Type is a relation : check it this relation still exists
         if ($item instanceof CommonDBRelation) {
            //Define the side of the relation to use
            if (method_exists($item,'relationSide')) {
               $side = $injectionClass->relationSide();
            } else {
               $side = true;
            }

            if($side) {
               $source_id = $item->items_id_2;
               $destination_id = $item->items_id_1;
               $source_itemtype = $item->itemtype_2;
               $destination_itemtype = $item->itemtype_1;
            } else {
               $source_id = $item->items_id_1;
               $destination_id = $item->items_id_2;
               $source_itemtype = $item->itemtype_1;
               $destination_itemtype = $item->itemtype_2;
            }

            $where .= " AND `$source_id`='";
            $where .= $this->getValueByItemtypeAndName($itemtype,$source_id)."'";

            if ($item->isField('itemtype')) {
               $where .= " AND `$source_itemtype`='";
               $where .= $this->getValueByItemtypeAndName($itemtype,$source_itemtype)."'";
            }
            $where .= " AND `".$destination_id."`='";
            $where .= $this->getValueByItemtypeAndName($itemtype,$destination_id)."'";
            $sql   .= " WHERE 1 ".$where;
         } else {
            //Type is not a relation

            //Type can be deleted
            if ($injectionClass->maybeDeleted()) {
               $where .= " AND `is_deleted`='0' ";
            }

            //Type can be a template
            if ($injectionClass->maybeTemplate()) {
               $where .= " AND `is_template`='0' ";
            }

            //Type can be assigned to an entity
            if($injectionClass->isEntityAssign()) {

               //Type can be recursive
               if ($injectionClass->maybeRecursive()) {
                  $where_entity = getEntitiesRestrictRequest(" AND",
                                                             $injectionClass->getTable(),
                                                             "entities_id",
                                                             $this->getValueByItemtypeAndName($itemtype,
                                                                                              'entities_id'),
                                                             true);
               } else {
                  //Type cannot be recursive
                  $where_entity = " AND `entities_id`='" .
                                   $this->getValueByItemtypeAndName($itemtype,'entities_id')."'";
               }
            } else { //If no entity assignment for this itemtype
               $where_entity = "";
            }

            //Add mandatory fields to the query only if it's the primary_type to be injected
            if ($itemtype == $this->primary_type) {
               foreach ($this->mandatory_fields[$itemtype] as $field => $is_mandatory) {
                  if ($is_mandatory) {
                     $option = self::findSearchOption($searchOptions,$field);
                     $where .= " AND `" . $field . "`='";
                     $where .= $this->getValueByItemtypeAndName($itemtype,$field) . "'";
                  }
               }
            } else {
               //Table contains an itemtype field
               if ($injectionClass->isField('itemtype')) {
                  $where.= " AND `itemtype`='";
                  $where.= $this->getValueByItemtypeAndName($itemtype,'itemtype')."'";
               }
               //Table contains an items_id field
               if ($injectionClass->isField('items_id')) {
                  $where.= " AND `items_id`='";
                  $where.= $this->getValueByItemtypeAndName($itemtype,'items_id')."'";
               }
            }

            //Add additional parameters specific to this itemtype (or function checkPresent exists)
            if (method_exists($injectionClass,'checkPresent')) {
               $where .= $injectionClass->checkPresent($this->values, $options);
            }
            $sql .= " WHERE 1 " . $where_entity . " " . $where;
         }
         $result = $DB->query($sql);
         if ($DB->numrows($result) > 0) {
            $db_fields = $DB->fetch_assoc($result);
            foreach ($db_fields as $key => $value) {
               $this->setValueForItemtype($itemtype,$key,$value,true);
            }
            $this->setValueForItemtype($itemtype,'id',$DB->result($result,0,'id'));
         } else {
            $this->setValueForItemtype($itemtype,'id',self::ITEM_NOT_FOUND);
         }
      }
   }

   /**
    * Add fields coming for a template to the values to be injected
    * @param itemtype the itemtype to inject
    * @return nothing
    */
   private function addTemplateFields($itemtype) {
      //If data inserted is not a template
      if (!$this->getValueByItemtypeAndName($itemtype,'is_template')) {
         $template = new $itemtype();
         $template_id = $this->getValueByItemtypeAndName($itemtype,'_oldID');
         if ($template->getFromDB($template_id)) {
            unset ($template->fields["id"]);
            unset ($template->fields["date_mod"]);
            unset ($template->fields["is_template"]);
            unset ($template->fields["entities_id"]);
            foreach ($template->fields as $key => $value) {
               if ($value != self::EMPTY_VALUE && (!isset ($this->values[$itemtype][$key])
                     || $this->values[$itemtype][$key] == self::EMPTY_VALUE
                        || $this->values[$itemtype][$key] == self::DROPDOWN_EMPTY_VALUE))
                  $this->setValueForItemtype($itemtype,$key,$value);
            }
            $name = autoName($this->values[$itemtype]['name'], "name", true,
                                       $itemtype,$this->values[$itemtype]['entities_id']);
            $this->setValueForItemtype($itemtype,'name',$name);
            $otherserial = autoName($this->values[$itemtype]['otherserial'], "otherserial", true,
                                       $itemtype,$this->values[$itemtype]['entities_id']);
            $this->setValueForItemtype($itemtype,'otherserial',$otherserial);
         }
      }
   }

   /**
    * Log event into the history
    * @param device_type the type of the item to inject
    * @param device_id the id of the inserted item
    * @param the action_type the type of action(add or update)
    * @return nothing
    */
   static function logAddOrUpdate($item, $add=true) {
      global $LANG;

      if ($item->dohistory) {
         $changes[0] = 0;

         if ($add) {
            $changes[2] = $LANG['datainjection']['result'][8] . " " .
                             $LANG['datainjection']['history'][1];
         } else {
            $changes[2] = $LANG['datainjection']['result'][9] . " " .
                             $LANG['datainjection']['history'][1];
         }
         $changes[1] = "";
         Log::history ($item->fields['id'],get_class($item),$changes);
      }
   }

   /**
    * Get label associated with an injection action
    * @param action code as defined in the head of this file
    * @return label associated with the code
    */
   static function getActionLabel($action) {
      global $LANG;

      $actions = array(self::IMPORT_ADD      => $LANG['datainjection']['result'][8],
                       self::IMPORT_UPDATE   => $LANG['datainjection']['result'][9],
                       self::IMPORT_DELETE   => $LANG['datainjection']['result'][9]);
      if (isset($actions[$action])) {
         return  $actions[$action];
      } else {
         return "";
      }

   }

   /**
    * Get label associated with an injection result
    * @param action code as defined in the head of this file
    * @return label associated with the code
    */
   static function getLogLabel($type) {
      global $LANG;

      $labels = array(self::SUCCESS,
                      self::WARNING,
                      self::ERROR_CANNOT_IMPORT,
                      self::ERROR_CANNOT_UPDATE,
                      self::ERROR_IMPORT_ALREADY_IMPORTED,
                      self::TYPE_MISMATCH,
                      self::MANDATORY,
                      self::FAILED,
                      self::WARNING_NOTFOUND,
                      self::WARNING_USED,
                      self::WARNING_SEVERAL_VALUES_FOUND,
                      self::WARNING_ALREADY_LINKED);
      if (in_array($type,$labels)) {
         return  $LANG['datainjection']['result'][$type];
      } else {
         return "";
      }
   }

   /**
    * Manage search options
    */
   static function addToSearchOptions($type_searchOptions = array(), $options = array(),$injectionClass) {
      self::addTemplateSearchOptions($injectionClass,$type_searchOptions);

      //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
      //imported using the commonlib
      $add_linkfield = array('comment' => 'comment', 'notepad' => 'notepad');
      foreach ($type_searchOptions as $id => $tmp) {
         if (!is_array($tmp) || in_array($id,$options['ignore_fields'])) {
            unset($type_searchOptions[$id]);
         } else {
            if (in_array($tmp['field'],$add_linkfield)) {
               $type_searchOptions[$id]['linkfield'] = $add_linkfield[$tmp['field']];
            }
            if (!in_array($id,$options['ignore_fields'])) {
               if (!isset($tmp['linkfield'])) {
                  $type_searchOptions[$id]['injectable'] = self::FIELD_VIRTUAL;
               } else {
                  $type_searchOptions[$id]['injectable'] = self::FIELD_INJECTABLE;
               }

               if (isset($tmp['linkfield']) && !isset($tmp['displaytype'])) {
                  $type_searchOptions[$id]['displaytype'] = 'text';
               }
               if (isset($tmp['linkfield']) && !isset($tmp['checktype'])) {
                  $type_searchOptions[$id]['checktype'] = 'text';
               }
            }
         }
      }

      foreach (array('displaytype','checktype') as $paramtype) {
         if (isset($options[$paramtype])) {
            foreach ($options[$paramtype] as $type => $tabsID) {
               foreach ($tabsID as $tabID) {
                  $type_searchOptions[$tabID][$paramtype] = $type;
               }
            }
         }
      }

      return $type_searchOptions;
   }

   /**
    * Add necessary search options for template management
    * @param injectionClass the injection class to use
    * @param tab the options tab, as an array (passed as a reference)
    * @return nothing
    */
   static function addTemplateSearchOptions($injectionClass,&$tab) {
      global $LANG;
      $itemtype = self::getItemtypeByInjectionClass($injectionClass) ;
      $item = new $itemtype;

      if ($item->maybeTemplate()) {
         $tab[300]['table'] = $item->getTable();
         $tab[300]['field'] = 'is_template';
         $tab[300]['linkfield'] = 'is_template';
         $tab[300]['name'] = $LANG["rulesengine"][0] . " " . $LANG["common"][13] . " ?";
         $tab[300]['type'] = 'integer';
         $tab[300]['injectable'] = 1;
         $tab[300]['checktype'] = 'integer';
         $tab[300]['displaytype'] = 'bool';

         $tab[301]['table'] = $item->getTable();
         $tab[301]['field'] = 'template_name';
         $tab[301]['name'] = $LANG["common"][13];
         $tab[301]['injectable'] = 1;
         $tab[301]['checktype'] = 'text';
         $tab[301]['displaytype'] = 'template';
         $tab[301]['linkfield'] = 'templates_id';
      }
   }

   /**
    * If itemtype injection needs to process things after data is written in DB
    * @return nothing
    */
   private function processAfterInsertOrUpdate() {
      //If itemtype implements special process after type injection
      if (method_exists($this->injectionClass,'processAfterInsertOrUpdate')) {
         //Invoke it
         $this->injectionClass->processAfterInsertOrUpdate($this->values);
      }
   }
}

?>