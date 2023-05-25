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

class PluginDatainjectionDropdown
{

   static function dateFormats() {

      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_DDMMYYYY]
                                                          = __('dd-mm-yyyy', 'datainjection');
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_MMDDYYYY]
                                                          = __('mm-dd-yyyy', 'datainjection');
      $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD]
                                                          = __('yyyy-mm-dd', 'datainjection');

      return $date_format;
   }


   static function getDateFormat($date) {

      $dates = self::dateFormats();
      if (isset($dates[$date])) {
         return $dates[$date];
      }
      return "";
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


    /**
    * @param $format
   **/
   static function getFloatFormat($format) {

      $formats = self::floatFormats();
      if (isset($formats[$format])) {
         return $formats[$format];
      }
      return "";
   }


   static function statusLabels() {

      $states[0]                                            = Dropdown::EMPTY_VALUE;
      //$states[PluginDatainjectionModel::INITIAL_STEP] = __('Creation of the model on going', 'datainjection');
      $states[PluginDatainjectionModel::FILE_STEP]          = __('File to inject', 'datainjection');
      $states[PluginDatainjectionModel::MAPPING_STEP]       = __('Mappings', 'datainjection');
      $states[PluginDatainjectionModel::OTHERS_STEP]        = __(
          'Additional Information',
          'datainjection'
      );
      $states[PluginDatainjectionModel::READY_TO_USE_STEP]  = __(
          'Model available for use',
          'datainjection'
      );
      return $states;
   }


    /**
    * Return current status of the model
    *
    * @return nothing
   **/
   static function getStatusLabel($step) {

      $states = self::statusLabels();
      if (isset($states[$step])) {
         return $states[$step];
      }
      return "";
   }


   static function dropdownFileEncoding() {

      $values[PluginDatainjectionBackend::ENCODING_AUTO]      = __(
          'Automatic detection',
          'datainjection'
      );
      $values[PluginDatainjectionBackend::ENCODING_UFT8]      = __('UTF-8', 'datainjection');
      $values[PluginDatainjectionBackend::ENCODING_ISO8859_1] = __('ISO8859-1', 'datainjection');

      Dropdown::showFromArray(
          'file_encoding', $values,
          ['value' => PluginDatainjectionBackend::ENCODING_AUTO]
      );
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


    /**
    * @param $value
   **/
   static function getPortUnicityValues($value) {

      $values = self::portUnicityValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      return "";
   }

}
