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
    public static function dateFormats()
    {

        $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_DDMMYYYY]
                                                          = __s('dd-mm-yyyy', 'datainjection');
        $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_MMDDYYYY]
                                                          = __s('mm-dd-yyyy', 'datainjection');
        $date_format[PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD]
                                                          = __s('yyyy-mm-dd', 'datainjection');

        return $date_format;
    }


    public static function getDateFormat($date)
    {

        $dates = self::dateFormats();
        return $dates[$date] ?? "";
    }


    public static function floatFormats()
    {

        $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT]
                                                          = __s('1 234.56', 'datainjection');
        $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_COMMA]
                                                          = __s('1 234,56', 'datainjection');
        $float_format[PluginDatainjectionCommonInjectionLib::FLOAT_TYPE_DOT_AND_COM]
                                                          = __s('1,234.56', 'datainjection');

        return $float_format;
    }


    /**
    * @param string $format
   **/
    public static function getFloatFormat($format)
    {

        $formats = self::floatFormats();
        return $formats[$format] ?? "";
    }


    public static function statusLabels()
    {

        $states[0]                                            = Dropdown::EMPTY_VALUE;
        //$states[PluginDatainjectionModel::INITIAL_STEP] = __s('Creation of the model on going', 'datainjection');
        $states[PluginDatainjectionModel::FILE_STEP]          = __s('File to inject', 'datainjection');
        $states[PluginDatainjectionModel::MAPPING_STEP]       = __s('Mappings', 'datainjection');
        $states[PluginDatainjectionModel::OTHERS_STEP]        = __s(
            'Additional Information',
            'datainjection',
        );
        $states[PluginDatainjectionModel::READY_TO_USE_STEP]  = __s(
            'Model available for use',
            'datainjection',
        );
        return $states;
    }


    /**
    * Return current status of the model
    *
    * @return string
   **/
    public static function getStatusLabel($step)
    {

        $states = self::statusLabels();
        return $states[$step] ?? "";
    }

    public static function getStatusColor($step)
    {
        switch ($step) {
            case PluginDatainjectionModel::MAPPING_STEP:
            case PluginDatainjectionModel::OTHERS_STEP:
                return "#ffb832";
            case PluginDatainjectionModel::READY_TO_USE_STEP:
                return "#2ec41f";
            default:
                return "#ff4e4e";
        }
    }


    public static function getFileEncodingValue()
    {

        $values[PluginDatainjectionBackend::ENCODING_AUTO]      = __s('Automatic detection', 'datainjection');
        $values[PluginDatainjectionBackend::ENCODING_UFT8]      = __s('UTF-8', 'datainjection');
        $values[PluginDatainjectionBackend::ENCODING_ISO8859_1] = __s('ISO8859-1', 'datainjection');

        return $values;
    }


    public static function portUnicityValues()
    {

        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER]
                                           = __s('Port number');
        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_NAME]
                                           = __s('Name');
        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS]
                                           = __s('Mac address');
        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME]
                                           = __s('Port number') . "+" . __s('Name');
        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_MAC]
                                           = __s('Port number') . "+" . __s('Mac address');
        $values[PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC]
                                           = __s('Port number') . "+" . __s('Name') . "+" .
                                             __s('Mac address');
        return $values;
    }


    /**
    * @param array $value
   **/
    public static function getPortUnicityValues($value)
    {

        $values = self::portUnicityValues();
        return $values[$value] ?? "";
    }
}
