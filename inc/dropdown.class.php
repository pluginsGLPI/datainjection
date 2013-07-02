<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
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

class PluginDatainjectionDropdown {

   static function dropdownDateFormat($format) {

      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_DDMMYYYY]
                                                            = __('dd-mm-yyyy', 'datainjection');
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_MMDDYYYY]
                                                            = __('mm-dd-yyyy', 'datainjection');
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD]
                                                            = __('yyyy-mm-dd', 'datainjection');

      Dropdown::showFromArray('date_format', $date_format, array('value' => $format));
   }


   static function floatFormats() {

      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT]
                                                            = __('1 234.56', 'datainjection');
      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_COMMA]
                                                            = __('1 234,56', 'datainjection');
      $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT_AND_COM]
                                                            = __('1,234.56', 'datainjection');

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

      $values[PluginDatainjectionBackend::ENCODING_AUTO]      = __('Automatic detection', 'datainjection');
      $values[PluginDatainjectionBackend::ENCODING_UFT8]      = __('UTF-8', 'datainjection');
      $values[PluginDatainjectionBackend::ENCODING_ISO8859_1] = __('ISO8859-1', 'datainjection');

      Dropdown::showFromArray('file_encoding', $values,
                              array('value' => PluginDatainjectionBackend::ENCODING_AUTO));
   }


   static function portUnicityValues() {

      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER]
                                             = __('Port number');
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_NAME]
                                             = __('Name');
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS]
                                             = __('Mac address');
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME]
                                             = __('Port number')."+".__('Name');
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_MAC]
                                             = __('Port number')."+".__('Mac address');
      $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC]
                                             = __('Port number')."+".__('Name')."+".
                                               __('Mac address');
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