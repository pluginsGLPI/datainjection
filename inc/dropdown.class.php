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
        return ['dd-mm-yyyy' => __('dd-mm-yyyy', 'datainjection'), 'mm-dd-yyyy' => __('mm-dd-yyyy', 'datainjection'), 'yyyy-mm-dd' => __('yyyy-mm-dd', 'datainjection')];
    }


    public static function getDateFormat($date)
    {

        $dates = self::dateFormats();
        return $dates[$date] ?? "";
    }


    public static function floatFormats()
    {
        return [1 => __('1 234.56', 'datainjection'), 0 => __('1 234,56', 'datainjection'), __('1,234.56', 'datainjection')];
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
        return [Dropdown::EMPTY_VALUE, 2 => __('File to inject', 'datainjection'), 3 => __('Mappings', 'datainjection'), 4 => __(
            'Additional Information',
            'datainjection',
        ), 5 => __(
            'Model available for use',
            'datainjection',
        )];
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
        return match ($step) {
            PluginDatainjectionModel::MAPPING_STEP, PluginDatainjectionModel::OTHERS_STEP => "#ffb832",
            PluginDatainjectionModel::READY_TO_USE_STEP => "#2ec41f",
            default => "#ff4e4e",
        };
    }


    public static function getFileEncodingValue()
    {
        return [2 => __('Automatic detection', 'datainjection'), __('UTF-8', 'datainjection'), 0 => __('ISO8859-1', 'datainjection')];
    }


    public static function portUnicityValues()
    {
        return [__('Port number'), __('Name'), __('Mac address'), __('Port number') . "+" . __('Name'), __('Port number') . "+" . __('Mac address'), __('Port number') . "+" . __('Name') . "+" .
          __('Mac address')];
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
