<?php
/*
 * @version $Id: HEADER 14683 2011-06-11 06:26:34Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */
class PluginDatainjectionResult {

   const IMPORT_OK     = 1;
   const TYPE_CHECK_OK = 1;

   //Overall status of the process
   //private $status;

   //Status of the data check
   private $check_status;

   //Status of the data injection
   private $injection_status;

   //Messages of the data check
   private $check_message;

   //Message of the data injection
   private $injection_message;

   //Type of injection (add or update)
   private $injection_type;

   //ID of the item added or updated
   private $injected_id;

   //ID of the line processed
   private $line_id;


   function __construct() {

//    $this->status = -1;
      $this->check_status      = PluginDatainjectionCommonInjectionLib::SUCCESS;
      $this->injection_status  = PluginDatainjectionCommonInjectionLib::SUCCESS;
      $this->check_message     = array();
      $this->injection_message = array();
      $this->injection_type    = array();
      $this->injected_id       = -1;
      $this->line_id           = -1;
   }


   //Getters
   function getLineID() {
      return $this->line_id;
   }


   function getStatus($check) {

      // from checkline
      if ($check){
         return ($this->check_status == PluginDatainjectionCommonInjectionLib::SUCCESS);
      }
      // from report
      return ($this->check_status == PluginDatainjectionCommonInjectionLib::SUCCESS
              && $this->injection_status == PluginDatainjectionCommonInjectionLib::SUCCESS);
   }


   function getCheckStatus() {
      return $this->check_status;
   }


   function getInjectionStatus() {
      return $this->injection_status;
   }


   function getCheckMessage() {

      if ($this->check_status == PluginDatainjectionCommonInjectionLib::SUCCESS) {
         return $this->getLabel(self::TYPE_CHECK_OK);
      }

      $output = "";
      foreach ($this->check_message as $field => $res) {
         $output .= ($output?"\n":" ").$this->getLabel($res)." ($field)";
      }

      return $output;
   }


   function getInjectionMessage() {

      if (count($this->injection_message)) {
         $output = "";
         foreach ($this->injection_message as $field => $res) {
            $output .= ($output?"\n":" ").$this->getLabel($res);
            if ($field && !intval($field)) {
               $output .= " ($field)";
            }
         }

      } else {
         $output = $this->getLabel(PluginDatainjectionCommonInjectionLib::SUCCESS);
      }

      return $output;
   }


   function getInjectionType() {
      return $this->injection_type;
   }


   function getInjectedId() {
      return $this->injected_id;
   }


   //Setters

   function setInjectionType($type) {
      $this->injection_type = $type;
   }


   function setInjectedId($ID) {
      $this->injected_id = $ID;
   }


   function setLineId($ID) {
      $this->line_id = $ID;
   }


   /**
    * Add a message for Check pass
    *
    * @param $message : number
    * @param $field : name (only if error)
    *
    * @return boolean : OK
   **/
   function addCheckMessage($message, $field="") {

      switch ($message) {
         case PluginDatainjectionCommonInjectionLib::SUCCESS :
            $this->check_status = PluginDatainjectionCommonInjectionLib::SUCCESS;
            break;

         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_LINK_FIELD_MISSING :
         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_WRONG_TYPE :
         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_FIELD_MANDATORY :
            $this->check_status          = PluginDatainjectionCommonInjectionLib::FAILED;
            $this->injection_status      = PluginDatainjectionCommonInjectionLib::FAILED;
            $this->check_message[$field] = $message;
            break;
      }

      return ($this->check_status == PluginDatainjectionCommonInjectionLib::SUCCESS);
   }


   /**
    * Add a message for Injection pass
    *
    * @param $message : number
    * @param $field : name (optional)
    *
    * @return boolean : OK
   **/
   function addInjectionMessage($message, $field=false) {

      switch ($message) {
         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_ALREADY_IMPORTED :
         case PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_IMPORT :
         case PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_UPDATE :
            $this->injection_status = NOT_IMPORTED;
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_ALLEMPTY :
         case PluginDatainjectionCommonInjectionLib::WARNING_NOTEMPTY :
         case PluginDatainjectionCommonInjectionLib::WARNING_NOTFOUND :
         case PluginDatainjectionCommonInjectionLib::WARNING_USED :
         case PluginDatainjectionCommonInjectionLib::WARNING_ALREADY_LINKED :
         case PluginDatainjectionCommonInjectionLib::WARNING_SEVERAL_VALUES_FOUND:
            $this->injection_status
                              = PluginDatainjectionCommonInjectionLib::WARNING_PARTIALLY_IMPORTED;
            break;
      }

      if ($field) {
         $this->injection_message[$field] = $message;
      } else {
         $this->injection_message[] = $message;
      }

      return ($this->injection_status == PluginDatainjectionCommonInjectionLib::SUCCESS);
   }


   private function getLabel($type) {
      global $LANG;

      $message = "";

      switch ($type) {
         case PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_IMPORT :
            $message = $LANG['datainjection']['result'][5];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_NOTEMPTY :
         case PluginDatainjectionCommonInjectionLib::ERROR_CANNOT_UPDATE :
            $message = $LANG['datainjection']['result'][6];
            break;

         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_ALREADY_IMPORTED :
            $message = $LANG['datainjection']['result'][3];
            break;

         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_WRONG_TYPE :
            $message = $LANG['datainjection']['result'][1];
            break;

         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_FIELD_MANDATORY :
            $message = $LANG['datainjection']['result'][4];
            break;

         case PluginDatainjectionCommonInjectionLib::ERROR_IMPORT_LINK_FIELD_MISSING :
            $message = $LANG['datainjection']['result'][4];
            break;

         case PluginDatainjectionCommonInjectionLib::SUCCESS :
            $message = $LANG['datainjection']['result'][2];
            break;

         case PluginDatainjectionCommonInjectionLib::IMPORT_OK :
            $message = $LANG['datainjection']['result'][7];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_NOTFOUND :
            $message = $LANG['datainjection']['result'][15];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_USED :
            $message = $LANG['datainjection']['result'][16];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_ALLEMPTY :
            $message = $LANG['datainjection']['result'][17];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_SEVERAL_VALUES_FOUND :
            $message = $LANG['datainjection']['result'][19];
            break;

         case PluginDatainjectionCommonInjectionLib::WARNING_ALREADY_LINKED:
            $message = $LANG['datainjection']['result'][20];
            break;

         case PluginDatainjectionCommonInjectionLib::IMPORT_IMPOSSIBLE:
            $message = $LANG['datainjection']['result'][21];
            break;
      }

      return $message;
   }


   static function sort($results) {

      $results_sorted[0] = array();
      $results_sorted[1] = array();

      foreach ($results as $result) {
         if ($result->getStatus(false)) {
            $results_sorted[1][] = $result;
         } else {
            $results_sorted[0][] = $result;
         }
      }

      return $results_sorted;
   }

}
?>
