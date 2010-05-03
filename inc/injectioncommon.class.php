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
class PluginDatainjectionInjectionCommon {

   // Check Status
   const CHECK_OK = 0;
   const CHECK_NOTOK = 1;

   // Check message
   const TYPE_CHECK_OK = 1;
   const ERROR_IMPORT_WRONG_TYPE = 2;

   //Line of data to inject
   var $data_to_inject = array();

   //Result after line check
   var $check_results = array();

   //Result after line injection
   var $injection_results = array();

   //Model to use for injection
   var $model = false;

   static function getInstance($itemtype) {
      $injectionClass = 'PluginDatainjection'.ucfirst($itemtype).'Injection';
      return new $injectionClass();
   }
   function __construct(PluginDatainjectionModel $model, $data_to_inject=array()) {
      $this->model = $model;
      $this->data_to_inject = $data_to_inject;
   }

   function reformatFirstPass() {

   }

   function reformatSecondPass() {

   }
   function reformatThirdPass() {

   }

   function check() {
      return $this->check_results;
   }

   function reformat() {
      $this->reformatFirstPass();
      $this->reformatSecondPass();
      $this->reformatThirdtPass();
   }

   function checkSpecificTypes() {
      return false;
   }

   /**
    * Check if the data to import is the good type
    *
    * @param the type of data waited
    * @data the data to import
    *
    * @return true if the data is the correct type
    */
   static function checkType($type, $name, $data,$mandatory)
   {
      global $DATA_INJECTION_MAPPING;

      if (isset($DATA_INJECTION_MAPPING[$type][$name]))
      {
         $field_type = $DATA_INJECTION_MAPPING[$type][$name]['type'];

         //If no data provided AND this mapping is not mandatory
         if (!$mandatory && ($data == null || $data == "NULL" || $data == EMPTY_VALUE))
            return self::TYPE_CHECK_OK;

         switch($field_type)
         {
            case 'text' :
               return self::TYPE_CHECK_OK;
            break;
            case 'integer' :
               if (is_numeric($data))
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'float':
               if (PluginDatainjectionCheck::isTrueFloat($data))
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'date' :
               preg_match("/([0-9]{4})[\-]([0-9]{2})[\-]([0-9]{2})/",$data,$regs);
               if (count($regs) > 0)
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'ip':
               preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/",$data,$regs);
               if (count($regs) > 0)
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'mac':
               preg_match("/([0-9a-fA-F]{2}([:-]|$)){6}$/",$data,$regs);
               if (count($regs) > 0)
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            //TODO remove this type
            case 'glpi_type':
               $commonitem = new Commonitem;
               $commonitem->setType($data);
               if($commonitem->obj != null)
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'yesno':
               logInFile("debug",$DATA_INJECTION_MAPPING[$type][$name]['name']."=".$data."\n");
               if ($data == 0 || $data == 1)
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'right_rw':
               if (in_array($data,array('r','w')))
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'right_r':
               if ($data=='r')
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'right_w':
               if ($data=='w')
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'interface':
               if (in_array($data,array('helpdesk','central')))
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            case 'auth_method':
               if (in_array($data,array(Auth::AUTH_CAS, Auth::AUTH_DB_GLPI, Auth::AUTH_EXTERNAL,
                                        Auth::AUTH_LDAP, Auth::AUTH_MAIL, Auth::AUTH_X509)))
                  return self::TYPE_CHECK_OK;
               else
                  return self::ERROR_IMPORT_WRONG_TYPE;
            break;
            default :
               if (!$this->checkSpecificTypes()) {
                  return self::ERROR_IMPORT_WRONG_TYPE;
               }
         }
      }
      else
         return self::ERROR_IMPORT_WRONG_TYPE;
   }

   static function findSearchOption($options, $lookfor) {
      $found = false;
      foreach ($options as $option) {
         if (isset($option['linkfield']) && $option['linkfield'] == $lookfor) {
            $found = $option;
         }
      }
      return $found;
   }

   static function displayAdditionalInformation(PluginDatainjectionInfo $info) {
      $injectionClass = PluginDatainjectionInjectionCommon::getInstance($info->fields['itemtype']);
      $option = self::findSearchOption($injectionClass->getOptions(),$info->fields['value']);
      if ($option) {
         echo "<td>";
         echo $option['name'];
         echo "</td>";
         echo "<td>";
         self::showAdditionalInformation($info,$option,$injectionClass);
         echo "</td>";
      }
   }

   static function showAdditionalInformation(PluginDatainjectionInfo $info, $option = array(),
                                             $injectionClass) {
      switch ($option['displaytype']) {
         case 'text' :
            echo "<input type='text' name='".$option['linkfield']."'>";
         break;
         case 'dropdown':
            Dropdown::show(getItemTypeForTable($option['table']));
            break;
         case 'yesno':
            Dropdown::showYesNo($option['linkfield'],0);
            break;
         case 'user':
            User::dropdown(array('name'=>$option['linkfield']));
            break;
         default:
            //If type is not a standard type, must be treated by specific injection class
            $injectionClass->showAdditionalInformation($info,$option);
            break;
      }
      if ($info->isMandatory()) {
         echo "&nbsp;*";
      }
   }
}
?>