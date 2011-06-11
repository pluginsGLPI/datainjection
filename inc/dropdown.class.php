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

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

class PluginDatainjectionDropdown {

   static function dropdownDateFormat($format) {
      global $LANG;

      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_DDMMYYYY]
                                                            = $LANG['datainjection']['model'][22];
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_MMDDYYYY]
                                                            = $LANG['datainjection']['model'][23];
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD]
                                                            = $LANG['datainjection']['model'][24];

      Dropdown::showFromArray('date_format', $date_format, array('value' => $format));
   }


   static function floatFormats() {
      global $LANG;

      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT]
                                                            = $LANG['datainjection']['model'][25];
      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_COMMA]
                                                            = $LANG['datainjection']['model'][26];
      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT_AND_COM]
                                                            = $LANG['datainjection']['model'][27];

      return $float_format;
   }


   static function getFloatFormat($format) {

      $formats = PluginDatainjectionDropdown::floatFormats();
      if (isset($formats[$format])) {
         return $formats[$format];
      }
      return "";
   }


   static function dropdownFloatFormat($format) {

      Dropdown::showFromArray('float_format', PluginDatainjectionDropdown::floatFormats(),
                              array('value' => $format));
   }


   static function dropdownFileTypes($value) {

      $values['csv'] = "CSV";
      Dropdown::showFromArray('filetype', $values, array('value' => $value));
   }


   static function dropdownFileEncoding() {
      global $LANG;

      $values[PluginDatainjectionBackend::ENCODING_AUTO]      = $LANG['datainjection']['fileStep'][10];
      $values[PluginDatainjectionBackend::ENCODING_UFT8]      = $LANG['datainjection']['fileStep'][11];
      $values[PluginDatainjectionBackend::ENCODING_ISO8859_1] = $LANG['datainjection']['fileStep'][12];

      Dropdown::showFromArray('file_encoding', $values,
                              array('value' => PluginDatainjectionBackend::ENCODING_AUTO));
   }


   static function portUnicityValues() {
      global $LANG;

      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER]
                                             = $LANG["networking"][21];
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_NAME]
                                             = $LANG["common"][16];
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS]
                                             = $LANG["device_iface"][2];
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME]
                                             = $LANG["networking"][21]."+".$LANG["common"][16];
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_MAC]
                                             = $LANG["networking"][21]."+".$LANG["device_iface"][2];
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC]
                                             = $LANG["networking"][21]."+".$LANG["common"][16]."+".
                                               $LANG["device_iface"][2];
      return $values;
   }


   static function dropdownPortUnicity($value) {

      $values = PluginDatainjectionDropdown::portUnicityValues();
      Dropdown::showFromArray('port_unicity', $values, array('value' => $value));
   }


   static function getPortUnitictyValues($value) {

      $values = PluginDatainjectionDropdown::portUnicityValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      return "";
   }

}
?>
