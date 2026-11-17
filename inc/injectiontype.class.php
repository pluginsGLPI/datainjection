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
use function Safe\ob_get_clean;
use function Safe\ob_start;
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
    * @param mixed $mapping_or_info
    * @param array $options            array
    * @param bool  $load_fields        load the fields dropdown through AJAX on page load
   **/
    public static function dropdownLinkedTypes($mapping_or_info, $options = [], bool $load_fields = true)
    {
        [$p, $values] = self::getLinkedTypesParams($mapping_or_info, $options);

        $mappings_id = $mapping_or_info->fields['id'];

        $rand = Dropdown::showFromArray(
            "data[" . $mappings_id . "][itemtype]",
            $values,
            ['value' => $p['itemtype']],
        );

        $p['itemtype'] = '__VALUE__';
        $di_base_url   = plugin_datainjection_geturl();
        $url_field     = $di_base_url . "ajax/dropdownChooseField.php";
        $toobserve     = "dropdown_data[" . $mapping_or_info->getID() . ('][itemtype]' . $rand);
        $toupdate      = "span_field_" . $mappings_id;
        if ($load_fields) {
            Ajax::updateItem($toupdate, $url_field, $p, $toobserve);
        }

        Ajax::updateItemOnSelectEvent($toobserve, $toupdate, $url_field, $p);
        return $rand;
    }


    /**
    * Get the HTML of the fields dropdown and of the mandatory checkbox of a mapping or an info,
    * as the AJAX calls of dropdownLinkedTypes() and dropdownFields() load them
    *
    * @param mixed $mapping_or_info
    * @param array $options            array
    *
    * @return array{field: string, mandatory: string}
   **/
    public static function getFieldsHtml($mapping_or_info, $options = []): array
    {
        [$p, $values] = self::getLinkedTypesParams($mapping_or_info, $options);

        //Value the browser selects in the itemtype dropdown
        $p['itemtype'] = self::getSelectedValue($values, $p['itemtype']);

        ob_start();
        $p['value'] = self::dropdownFields($p, false);
        $field_html = ob_get_clean();

        ob_start();
        self::showMandatoryCheckbox($p + ['need_decode' => true]);
        $mandatory_html = ob_get_clean();

        return [
            'field'     => $field_html,
            'mandatory' => $mandatory_html,
        ];
    }


    /**
     * Value selected by the browser in a dropdown: the given value, or the first one if it is not in the list
     *
     * @param int|string $value
     * @return int|string
     **/
    private static function getSelectedValue(array $values, $value)
    {
        return isset($values[$value]) ? $value : array_key_first($values);
    }


    /**
    * Get the parameters and the values of the linked types dropdown
    *
    * @param mixed $mapping_or_info
    * @param array $options            array
    *
    * @return array{0: array, 1: array}
   **/
    private static function getLinkedTypesParams($mapping_or_info, $options = []): array
    {
        /** @var array[mixed] $INJECTABLE_TYPES */
        global $INJECTABLE_TYPES;

        getTypesToInject(); // populate $INJECTABLE_TYPES

        $p['primary_type']    = '';
        $p['itemtype']        = self::NO_VALUE;
        // Use hex code for all special chars to prevent problems when adding/stripping slashes
        $p['mapping_or_info'] = json_encode(
            $mapping_or_info->fields,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP,
        );
        $p['called_by']       = $mapping_or_info::class;
        $p['fields_update']   = true;
        foreach ($options as $key => $value) {
            $p[$key] = $value;
        }

        $values      = [];

        if (
            ($p['itemtype'] == self::NO_VALUE)
            && ($mapping_or_info->fields['itemtype'] != self::NO_VALUE)
        ) {
            $p['itemtype'] = $mapping_or_info->fields['itemtype'];
        }

        //Add null value
        $values[self::NO_VALUE] = __('-------Choose a table-------', 'datainjection');

        //Add primary_type to the list of availables types
        if (!is_a($p['primary_type'], CommonDBTM::class, true)) {
            throw new HttpException(500, 'Class ' . $p['primary_type'] . ' is not a valid class');
        }

        //The linked types are the same for all the rows of a model: computed once per request
        static $values_by_primary_type = [];
        if (isset($values_by_primary_type[$p['primary_type']])) {
            return [$p, $values_by_primary_type[$p['primary_type']]];
        }

        $type                       = new $p['primary_type']();
        $values[$p['primary_type']] = $type->getTypeName();

        foreach ($INJECTABLE_TYPES as $type => $plugin) {
            if (is_a($type, CommonDBTM::class, true)) {
                $injectionClass = new $type();
                if (method_exists($injectionClass, 'connectedTo')) {
                    $connected_to   = $injectionClass->connectedTo();
                    if (in_array($p['primary_type'], $connected_to)) {
                        $typename          = getItemTypeForTable($injectionClass->getTable());
                        $values[$typename] = call_user_func([$type, 'getTypeName']);
                    }
                }
            }
        }

        asort($values);
        $values_by_primary_type[$p['primary_type']] = $values;

        return [$p, $values];
    }


    /**
    * @param array $options          array
    * @param bool  $load_mandatory   load the mandatory checkbox through AJAX on page load
    *
    * @return int|string the value selected in the fields dropdown
   **/
    public static function dropdownFields($options = [], bool $load_mandatory = true)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $used                = [];
        $p['itemtype']        = self::NO_VALUE;
        $p['primary_type']    = '';
        $p['mapping_or_info'] = [];
        $p['called_by']       = '';
        $p['need_decode']     = true;
        $p['fields_update']   = true;

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
        $fields[self::NO_VALUE] = __('-------Choose a field-------', 'datainjection');

        //By default field has no default value
        $mapping_value = self::NO_VALUE;

        if ($p['itemtype'] != self::NO_VALUE) {
            //If a value is still present for this mapping
            if ($mapping_or_info['value'] != self::NO_VALUE) {
                $mapping_value = $mapping_or_info['value'];
            }

            //Search options are the same for all the rows of a model: computed once per request
            static $search_options_cache = [];
            $cache_key = $p['itemtype'] . '|' . $p['primary_type'];
            if (!isset($search_options_cache[$cache_key])) {
                $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($p['itemtype']);
                $search_options_cache[$cache_key] = $injectionClass->getOptions($p['primary_type']);
            }

            $search_options = $search_options_cache[$cache_key];
            foreach ($search_options as $option) {
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

            $used = self::getUsedMappingsOrInfos($p, $search_options);
        }

        asort($fields);

        $rand = Dropdown::showFromArray(
            "data[" . $mapping_or_info['id'] . "][value]",
            $fields,
            ['value' => $mapping_value,
                'used'  => $used,
            ],
        );

        $p['value'] = '__VALUE__';
        $url = plugin_datainjection_geturl() . "ajax/dropdownMandatory.php";
        if ($load_mandatory) {
            Ajax::updateItem(
                "span_mandatory_" . $mapping_or_info['id'],
                $url,
                $p,
                "dropdown_data[" . $mapping_or_info['id'] . ('][value]' . $rand),
            );
        }

        Ajax::updateItemOnSelectEvent(
            "dropdown_data[" . $mapping_or_info['id'] . ('][value]' . $rand),
            "span_mandatory_" . $mapping_or_info['id'],
            $url,
            $p,
        );

        return self::getSelectedValue($fields, $mapping_value);
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
    public static function showMandatoryCheckbox($options = [])
    {

        // Received data has been slashed.

        if ($options['need_decode']) {
            // JSON data has been slashed twice, stripslashes has to be done a second time.
            $mapping_or_info = json_decode(
                $options['mapping_or_info'],
                true,
            );
        } else {
            $mapping_or_info = $options['mapping_or_info'];
        }

        //TODO : to improve
        $checked = '';
        if ($mapping_or_info['is_mandatory']) {
            $checked = 'checked';
        }

        if (
            $options['called_by'] == 'PluginDatainjectionInfo' || (
                $options['primary_type'] == $options['itemtype']
                && !in_array($options['value'], self::NON_MANDATORY_FIELDS)
            )
        ) {
            echo "<input type='checkbox' name='data[" . htmlspecialchars((string) $mapping_or_info['id']) . sprintf("][is_mandatory]' %s>", $checked);
        }
    }


    /**
    * @param array      $options          array
    * @param array|null $search_options   search options of the injection class, computed if not given
   **/
    public static function getUsedMappingsOrInfos($options = [], ?array $search_options = null)
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

        //The fields used by the model are the same for all its rows: computed once per request
        static $used_by_model = [];

        $table = (($p['called_by'] == 'PluginDatainjectionMapping') ? "glpi_plugin_datainjection_mappings"
                                                              : "glpi_plugin_datainjection_infos");
        $cache_key = implode('|', [$table, $mapping_or_info['models_id'], $p['itemtype'], $p['primary_type'], $search_options === null]);

        if (!isset($used_by_model[$cache_key])) {
            $datas = getAllDataFromTable($table, ['models_id' => $mapping_or_info['models_id']]);

            if ($search_options === null) {
                $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($p['itemtype']);
                $search_options = $injectionClass->getOptions();
            }

            $options = $search_options;

            $used_by_model[$cache_key] = [];
            foreach ($datas as $data) {
                if ($data['value'] != self::NO_VALUE) {
                    foreach ($options as $option) {
                        if (
                            isset($option['table'])
                            && ($option['table'] == getItemTypeForTable($data['itemtype']))
                            && ($option['linkfield'] == $data['value'])
                            && ($option['displaytype'] != 'multiline_text')
                        ) {
                            $used_by_model[$cache_key][$option['linkfield']] = $option['linkfield'];
                            break;
                        }
                    }
                }
            }
        }

        //The field of the current row stays available
        return array_filter(
            $used_by_model[$cache_key],
            fn($linkfield): bool => $mapping_or_info['value'] != $linkfield,
        );
    }
}
