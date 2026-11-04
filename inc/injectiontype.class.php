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
use Glpi\Exception\Http\HttpException;

use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\preg_replace;

class PluginDatainjectionInjectionType
{
    public const NO_VALUE = 'none';

    public const NON_MANDATORY_FIELDS = [
        'groups_id',  // Groups removed because they have become multiple fields for assets
    ];


    /**
    * Return all injectable types
    *
    * @param boolean $only_primary    return only primary types (false by default)
    *
    * @return array which contains array(itemtype => itemtype name)
   **/
    public static function getItemtypes($only_primary = false)
    {
        /** @var array $INJECTABLE_TYPES */
        global $INJECTABLE_TYPES;

        getTypesToInject();

        $plugin = new Plugin();
        $values = [];
        foreach ($INJECTABLE_TYPES as $type => $from) {
            if (is_a($type, CommonDBTM::class, true)) {
                $injectionClass = new $type();

                if (
                    !$only_primary
                    || (method_exists($injectionClass, 'isPrimaryType') && $injectionClass->isPrimaryType())
                ) {
                    $instance = new $type();
                    //If user has no right to create an object of this type, do not display type in the list
                    if (!$instance->canCreate()) {
                        continue;
                    }

                    $typename = get_parent_class($type);
                    $name     = '';
                    if ($from != 'datainjection') {
                        $plugin->getFromDBbyDir($from);
                        $name = $plugin->getName() . ': ';
                    }

                    $name .= call_user_func([$type, 'getTypeName']);
                    $values[$typename] = $name;
                }
            }
        }

        asort($values);
        return $values;
    }


    /**
    * Display a list of all importable types using datainjection plugin
    *
    * @param string $value           the selected value (default '')
    * @param boolean $only_primary    (false by default)
   **/
    public static function dropdown($value = '', $only_primary = false)
    {

        return Dropdown::showFromArray(
            'itemtype',
            self::getItemtypes($only_primary),
            ['value' => $value],
        );
    }


    /**
    * Get all types linked with a primary type
    *
    * @param string $primary_type
    *
    * @return array itemtype => name, with the empty value on top
   **/
    public static function getLinkedTypes($primary_type)
    {
        /** @var array[mixed] $INJECTABLE_TYPES */
        global $INJECTABLE_TYPES;

        getTypesToInject(); // populate $INJECTABLE_TYPES

        //Add primary_type to the list of availables types
        if (!is_a($primary_type, CommonDBTM::class, true)) {
            throw new HttpException(500, 'Class ' . $primary_type . ' is not a valid class');
        }

        $values = [$primary_type => $primary_type::getTypeName()];

        foreach ($INJECTABLE_TYPES as $type => $plugin) {
            if (is_a($type, CommonDBTM::class, true)) {
                $injectionClass = new $type();
                if (method_exists($injectionClass, 'connectedTo')) {
                    $connected_to   = $injectionClass->connectedTo();
                    if (in_array($primary_type, $connected_to)) {
                        $typename          = getItemTypeForTable($injectionClass->getTable());
                        $values[$typename] = call_user_func([$type, 'getTypeName']);
                    }
                }
            }
        }

        asort($values);
        //Add null value on top of the list
        return [self::NO_VALUE => Dropdown::EMPTY_VALUE] + $values;
    }


    /**
    * Get the fields that can be mapped for an itemtype, and the value to select
    *
    * @param array $options   itemtype, primary_type, called_by and mapping_or_info (array, or JSON if need_decode)
    *
    * @return array{fields: array, value: string}
   **/
    public static function getFieldsChoices($options = [])
    {
        $p['itemtype']        = self::NO_VALUE;
        $p['primary_type']    = '';
        $p['mapping_or_info'] = [];
        $p['called_by']       = '';
        $p['need_decode']     = true;

        foreach ($options as $key => $value) {
            $p[$key] = $value;
        }

        if ($p['need_decode']) {
            $mapping_or_info = json_decode(
                $options['mapping_or_info'],
                true,
            );
        } else {
            $mapping_or_info = $options['mapping_or_info'];
        }

        $fields = [];
        $used   = [];

        //By default field has no default value
        $mapping_value = self::NO_VALUE;

        if ($p['itemtype'] != self::NO_VALUE) {
            //If a value is still present for this mapping
            if ($mapping_or_info['value'] != self::NO_VALUE) {
                $mapping_value = $mapping_or_info['value'];
            }

            $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($p['itemtype']);

            foreach ($injectionClass->getOptions($p['primary_type']) as $option) {
                //If it's a real option (not a group label) and if field is not blacklisted
                //and if a linkfield is defined (meaning that the field can be updated)
                if (
                    is_array($option)
                    && isset($option['injectable'])
                    && ($option['injectable'] == PluginDatainjectionCommonInjectionLib::FIELD_INJECTABLE)
                ) {
                    $fields[$option['linkfield']] = $option['name'];

                    if (
                        ($mapping_value == self::NO_VALUE)
                        && ($p['called_by'] == 'PluginDatainjectionMapping')
                        && self::isEqual($option, $mapping_or_info)
                    ) {
                        $mapping_value = $option['linkfield'];
                    }
                }
            }

            $used = self::getUsedMappingsOrInfos($p);
        }

        $fields = array_diff_key($fields, $used);
        asort($fields);

        if (!isset($fields[$mapping_value])) {
            $mapping_value = self::NO_VALUE;
        }

        return [
            //Add null value on top of the list
            'fields' => [self::NO_VALUE => Dropdown::EMPTY_VALUE] + $fields,
            'value'  => $mapping_value,
        ];
    }


    /**
    * Indicates if the link field / mandatory checkbox is available for a mapping or an info
    *
    * @param string $called_by     class of the mapping or info
    * @param string $primary_type  primary type of the model
    * @param string $itemtype      selected table
    * @param string $value         selected field
   **/
    public static function canBeMandatory($called_by, $primary_type, $itemtype, $value)
    {
        return $called_by == PluginDatainjectionInfo::class || (
            $primary_type == $itemtype
            && !in_array($value, self::NON_MANDATORY_FIELDS)
        );
    }


    /**
    * Get the fields targeted by the other kind of rows of a model: the infos ones for a mapping,
    * the mappings ones for an info. A field should not be both mapped from the file and asked as info.
    *
    * @param string $called_by  class of the mapping or info
    * @param int    $models_id
    *
    * @return string[] "itemtype|field" keys
   **/
    public static function getOtherTargetedFields($called_by, $models_id)
    {
        /** @var array<string, string[]> $cache */
        static $cache = [];

        $table = $called_by == PluginDatainjectionInfo::class
            ? PluginDatainjectionMapping::getTable()
            : PluginDatainjectionInfo::getTable();
        $key = $table . '_' . $models_id;

        if (!isset($cache[$key])) {
            $cache[$key] = [];
            $rows = getAllDataFromTable($table, [
                'models_id' => $models_id,
                'NOT'       => ['value' => self::NO_VALUE],
            ]);
            foreach ($rows as $row) {
                $cache[$key][] = $row['itemtype'] . '|' . $row['value'];
            }
        }

        return $cache[$key];
    }


    /**
    * Get the data needed to display a mapping or info row: table and field dropdowns,
    * mandatory checkbox, and the parameters used to refresh the fields client side
    *
    * @param PluginDatainjectionMapping|PluginDatainjectionInfo $mapping_or_info
    * @param string $primary_type
    *
    * @return array
   **/
    public static function getRow($mapping_or_info, $primary_type)
    {
        $id       = $mapping_or_info->fields['id'];
        $itemtype = $mapping_or_info->fields['itemtype'] ?: self::NO_VALUE;

        $choices = self::getFieldsChoices([
            'itemtype'        => $itemtype,
            'primary_type'    => $primary_type,
            'mapping_or_info' => $mapping_or_info->fields,
            'called_by'       => $mapping_or_info::class,
            'need_decode'     => false,
        ]);

        $other_targets = self::getOtherTargetedFields($mapping_or_info::class, $mapping_or_info->fields['models_id']);

        return [
            'has_conflict'   => in_array($itemtype . '|' . $choices['value'], $other_targets, true),
            'conflict_label' => sprintf(
                __('This field is also used in %s', 'datainjection'),
                $mapping_or_info::class == PluginDatainjectionInfo::class
                    ? __('Mappings', 'datainjection')
                    : __('Additional Information', 'datainjection'),
            ),
            'id'            => $id,
            'itemtype_html' => Dropdown::showFromArray(
                "data[" . $id . "][itemtype]",
                self::getLinkedTypes($primary_type),
                ['value' => $itemtype, 'width' => '100%', 'display' => false],
            ),
            'field_html'    => Dropdown::showFromArray(
                "data[" . $id . "][value]",
                $choices['fields'],
                ['value' => $choices['value'], 'width' => '100%', 'display' => false],
            ),
            'is_mapped'        => $choices['value'] != self::NO_VALUE,
            'is_mandatory'     => (bool) $mapping_or_info->fields['is_mandatory'],
            'can_be_mandatory' => self::canBeMandatory($mapping_or_info::class, $primary_type, $itemtype, $choices['value']),
            'params'           => [
                'primary_type'    => $primary_type,
                'called_by'       => $mapping_or_info::class,
                // Use hex code for all special chars to prevent problems when adding/stripping slashes
                'mapping_or_info' => json_encode(
                    $mapping_or_info->fields,
                    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP,
                ),
                'non_mandatory'   => self::NON_MANDATORY_FIELDS,
                'other_targets'   => $other_targets,
            ],
        ];
    }


    /**
    * Incidates if the name given corresponds to the current searchOption
    *
    * @param array $option    array the current searchOption (field definition)
    * @param array $mapping
    *
    * @return boolean the value matches the searchOption or not
   **/
    public static function isEqual($option, $mapping)
    {

        $name = strtolower((string) $mapping['name']);
        if (self::testBasicEqual(strtolower((string) $mapping['name']), $option)) {
            return true;
        }

        //Manage mappings begining with N° or n°
        $new_name = preg_replace("/[n|N]°/", __('Lifelong'), $name);
        if (self::testBasicEqual(strtolower($new_name), $option)) {
            return true;
        }

        //Field may match is it was in plural...
        $plural_name = $name . 's';
        return (bool) self::testBasicEqual(strtolower($plural_name), $option);
    }


    /**
    * @param string $name
    * @param array $option    array
   **/
    public static function testBasicEqual($name, $option = [])
    {
        //Basic tests
        return (strtolower((string) $option['field']) == $name)
        || (strtolower((string) $option['name']) == $name)
        || (strtolower((string) $option['linkfield']) == $name);
    }


    /**
    * @param array $options   array
   **/
    public static function getUsedMappingsOrInfos($options = [])
    {
        /** @var DBmysql $DB */
        global $DB;

        $p['itemtype']        = self::NO_VALUE;
        $p['primary_type']    = '';
        $p['mapping_or_info'] = [];
        $p['called_by']       = '';
        $p['need_decode']     = true;

        foreach ($options as $key => $value) {
            $p[$key] = $value;
        }

        if ($p['need_decode']) {
            $mapping_or_info = json_decode(
                $options['mapping_or_info'],
                true,
            );
        } else {
            $mapping_or_info = $options['mapping_or_info'];
        }

        $used  = [];
        $table = (($p['called_by'] == 'PluginDatainjectionMapping') ? "glpi_plugin_datainjection_mappings"
                                                              : "glpi_plugin_datainjection_infos");

        $datas = getAllDataFromTable($table, ['models_id' => $mapping_or_info['models_id']]);

        $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($p['itemtype']);
        $options        = $injectionClass->getOptions();

        foreach ($datas as $data) {
            if ($data['value'] != self::NO_VALUE) {
                foreach ($options as $option) {
                    if (
                        isset($option['table'])
                        && ($option['table'] == getItemTypeForTable($data['itemtype']))
                        && ($option['linkfield'] == $data['value'])
                        && ($option['displaytype'] != 'multiline_text')
                        && ($mapping_or_info['value'] != $data['value'])
                    ) {
                        $used[$option['linkfield']] = $option['linkfield'];
                        break;
                    }
                }
            }
        }

        return $used;
    }
}
